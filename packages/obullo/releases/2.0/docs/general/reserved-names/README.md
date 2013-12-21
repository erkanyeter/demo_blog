### Reserved Names <a name="reserved-names"></a>

------

In order to help out, Obullo uses a series of functions and names in its operation. Because of this, some names cannot be used by a developer. Following is a list of reserved names that cannot be used.

### Class Names & Methods <a name="controller-names"></a>

------

Since your controller classes will extend the main application controller you must be careful not to name your functions identically to the ones used by that class, otherwise your local functions will override them. The following is a list of reserved names. Do not name your controller functions any of these:

- _getInstance()
- _remap()
- _output()
- index()
- Error
- Exceptions
- Controller
- Config
- Db
- Database
- Database_Pdo
- Hooks
- Hmvc
- Model
- Model_Trait
- Request
- Response
- Router
- Uri
- Obullo
- Odm

#### Functions

------

- autoloader()
- config()
- getConfig()
- getComponent()
- getComponentInstance()
- getInstance()
- getStatic()
- lingo()
- packageExists()
- removeInvisibleCharacters()
- runFramework()
- tpl()
- view()

Look at your <kbd>obullo.php</kbd> file located in your <b>Obullo Package</b>.

#### Controller Variables

------

- $this->config
- $this->router
- $this->uri
- $this->output
- $this->lingo

#### Constants

------

Look at your <kbd>constants</kbd> file located in your project root.

##### Database Constants

------

Database constants located in <kbd>database_pdo</kbd> package <kbd>database_layer.php</kbd> file.