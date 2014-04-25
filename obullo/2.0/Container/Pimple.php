<?php

namespace Obullo\Container;

use SplObjectStorage, ArrayAccess, InvalidArgumentException, Controller;  //  we use this classes in this class ;)

/*
 * This file is part of Pimple.
 *
 * Copyright (c) 2009 Fabien Potencier
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Container class.
 * 
 * @category  Container
 * @package   Pimple
 * @author    Ersin Guvenc ( Port to Obullo ) - <eguvenc@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/uri
 */
Class Pimple implements ArrayAccess
{
    protected $values = array();
    protected $factories;
    protected $protected;
    protected $frozen = array();
    protected $raw = array();
    protected $keys = array();

    /**
     * Instantiate the container.
     *
     * Objects and parameters can be passed as argument to the constructor.
     *
     * @param array $values The parameters or objects.
     */
    public function __construct(array $values = array())
    {
        $this->factories = new SplObjectStorage;
        $this->protected = new SplObjectStorage;

        foreach ($values as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    /**
     * Sets a parameter or an object.
     *
     * Objects must be defined as Closures.
     *
     * Allowing any PHP callable leads to difficult to debug problems
     * as function names (strings) are callable (creating a function with
     * the same name as an existing parameter would break your container).
     *
     * @param string $cid   The unique identifier for the parameter or object
     * @param mixed  $value The value of the parameter or a closure to define an object
     * 
     * @return void
     */
    public function offsetSet($cid, $value)
    {        
        if (isset($this->frozen[$cid])) {
            return;
        }
        $this->values[$cid] = $value;
        $this->keys[$cid]   = true;
    }

    /**
     * Gets a parameter or an object.
     *
     * @param string $cid The unique identifier for the parameter or object
     *
     * @return mixed The value of the parameter or an object
     *
     * @throws InvalidArgumentException if the identifier is not defined
     */
    public function offsetGet($cid)
    {
        $key = strtolower($cid);

        if ( ! isset($this->keys[$cid]) AND class_exists('Controller')) {

            $Class = ucfirst($cid);
            $ObulloPackage = 'Obullo\\'.$Class.'\\'.$Class;

            if (strpos($Class, '/') > 0) {      // If we have a folder "/" request.
                $exp       = explode('/', $Class);
                $ClassName = end($exp);
                array_pop($exp);
                $key = $ClassName;
                $ObulloPackage = 'Obullo\\'. implode('\\', $exp).'\\'. ucfirst($ClassName);
            }

            if (Controller::$instance != null) {  // let's sure controller instance available and is not null
                Controller::$instance->{$key} = new $ObulloPackage;
            }
            $this->offsetSet(
                $cid, 
                function () use ($key, $ObulloPackage) {
                    return (Controller::$instance == null) ? new $ObulloPackage : Controller::$instance->{$key};
                }
            );
        }
        if (isset($this->raw[$cid])
            || ! is_object($this->values[$cid])
            || isset($this->protected[$this->values[$cid]])
            || ! method_exists($this->values[$cid], '__invoke')
        ) {
            return $this->values[$cid];
        }
        if (isset($this->factories[$this->values[$cid]])) {
            return $this->values[$cid]($this);
        }

        $this->frozen[$cid] = true;
        $this->raw[$cid] = $this->values[$cid];

        return $this->values[$cid] = $this->values[$cid]($this);
    }

    /**
     * Checks if a parameter or an object is set.
     *
     * @param string $cid The unique identifier for the parameter or object
     *
     * @return Boolean
     */
    public function offsetExists($cid)
    {
        return isset($this->keys[$cid]);
    }

    /**
     * Unsets a parameter or an object.
     *
     * @param string $cid The unique identifier for the parameter or object
     *
     * @return void
     */
    public function offsetUnset($cid)
    {
        if (isset($this->keys[$cid])) {
            if (is_object($this->values[$cid])) {
                unset($this->factories[$this->values[$cid]], $this->protected[$this->values[$cid]]);
            }
            unset($this->values[$cid], $this->frozen[$cid], $this->raw[$cid], $this->keys[$cid]);
        }
    }

    /**
     * Marks a callable as being a factory service.
     *
     * @param callable $callable A service definition to be used as a factory
     *
     * @return callable The passed callable
     *
     * @throws InvalidArgumentException Service definition has to be a closure of an invokable object
     */
    public function factory($callable)
    {
        if ( ! is_object($callable) || ! method_exists($callable, '__invoke')) {
            throw new InvalidArgumentException('Service definition is not a Closure or invokable object.');
        }
        $this->factories->attach($callable);
        return $callable;
    }

    /**
     * Protects a callable from being interpreted as a service.
     *
     * This is useful when you want to store a callable as a parameter.
     *
     * @param callable $callable A callable to protect from being evaluated
     *
     * @return callable The passed callable
     *
     * @throws InvalidArgumentException Service definition has to be a closure of an invokable object
     */
    public function protect($callable)
    {
        if (!is_object($callable) || !method_exists($callable, '__invoke')) {
            throw new InvalidArgumentException('Callable is not a Closure or invokable object.');
        }
        $this->protected->attach($callable);
        return $callable;
    }

    /**
     * Run to provider with parameters and return to closure value
     *
     * @param string $cid    service provider id
     * @param array  $params service provider parameters
     *
     * @return mixed the closure object
     * 
     * @throws InvalidArgumentException if the identifier is not defined
     */
    public function bind($cid, $params = array())
    {
        if ( ! isset($this->keys[$cid])) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $cid));
        }
        if (isset($this->values[$cid])) {
            return $this->values['app']->{$cid} = (count($params) > 0) ? $this->values[$cid]($params) : $this->values[$cid];  // register service to App/Controller.
        }
    }

    /**
     * Gets a parameter or the closure defining an object.
     *
     * @param string $cid The unique identifier for the parameter or object
     *
     * @return mixed The value of the parameter or the closure defining an object
     *
     * @throws InvalidArgumentException if the identifier is not defined
     */
    public function raw($cid)
    {
        if (!isset($this->keys[$cid])) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $cid));
        }
        if (isset($this->raw[$cid])) {
            return $this->raw[$cid];
        }
        return $this->values[$cid];
    }

    /**
     * Extends an object definition.
     *
     * Useful when you want to extend an existing object definition,
     * without necessarily loading that object.
     *
     * @param string   $cid      The unique identifier for the object
     * @param callable $callable A service definition to extend the original
     *
     * @return callable The wrapped callable
     *
     * @throws InvalidArgumentException if the identifier is not defined or not a service definition
     */
    public function extend($cid, $callable)
    {
        if ( ! isset($this->keys[$cid])) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $cid));
        }
        if ( ! is_object($this->values[$cid]) || ! method_exists($this->values[$cid], '__invoke')) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" does not contain an object definition.', $cid));
        }
        if ( ! is_object($callable) || ! method_exists($callable, '__invoke')) {
            throw new InvalidArgumentException('Extension service definition is not a Closure or invokable object.');
        }
        $factory = $this->values[$cid];

        $extended = function ($param) use ($callable, $factory) {
            return $callable($factory($param), $param);
        };
        if (isset($this->factories[$factory])) {
            $this->factories->detach($factory);
            $this->factories->attach($extended);
        }
        return $this[$cid] = $extended;
    }

    /**
     * Returns all defined value names.
     *
     * @return array An array of value names
     */
    public function keys()
    {
        return array_keys($this->values);
    }
}

/*
|--------------------------------------------------------------------------
| Container ( IOC )
|--------------------------------------------------------------------------
*/
$c = new Pimple;


// END Container class

/* End of file Container.php */
/* Location: .Obullo/Container/Pimple.php */