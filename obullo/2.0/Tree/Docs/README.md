
## Tree Category Class

------

Tree class use nested set model. It is a particular technique for representing nested sets (also known as trees or hierarchies) in relational databases. The term was apparently introduced by Joe Celko; others describe the same technique without naming it or using different terms.

<a href="http://ftp.nchu.edu.tw/MySQL/tech-resources/articles/hierarchical-data.html">http://ftp.nchu.edu.tw/MySQL/tech-resources/articles/hierarchical-data.html</a>

### Initializing the Class

------

```php
$c['tree.category'];
$this->treeCategory->method();
```

### Run SQL Code

Run below the sql query this will create the nested tree. 

```php
CREATE TABLE categories (
	category_id INT AUTO_INCREMENT PRIMARY KEY,
	parent_id INT NOT NULL,
	name VARCHAR(20) NOT NULL,
	lft INT NOT NULL,
	rgt INT NOT NULL
);
```

Don’t forget to add some indexes on your tables to speed up the “reading” process. You should add indexes for parent_id, lft and rght:

```php
ALTER TABLE  `categories` ADD INDEX  `lft` (  `lft` );
ALTER TABLE  `categories` ADD INDEX  `rgt` (  `rgt` );
ALTER TABLE  `categories` ADD INDEX  `parent_id` (  `parent_id` );
```

### Add root category

#### $this->treeCategory->addTree(string $text);

Adds the main category to the table.

```php
$this->treeCategory->addTree('Electronics');
```
Gives

```php
+-------------+-----------+----------------------+-----+-----+
| category_id | parent_id | name                 | lft | rgt |
+-------------+----------------------------------+-----+-----+
|           1 |         0 | Electronics          |  1  |  2  |
+-------------+-----------+----------------------+-----+-----+
```

#### $this->treeCategory->addTree(string $text, $extra = array());

Adds to extra column data to table.

```php
$this->treeCategory->addTree('Electronics', $extra = array('column' => 'value'));
```
Gives

```php
+-------------+-----------+----------------------+-----+-----+--------+
| category_id | parent_id | name                 | lft | rgt | column |
+-------------+----------------------------------+-----+-----+--------+
|           1 |         0 | Electronics          |  1  |  2  |  value |
+-------------+-----------+----------------------+-----+-----+--------+
```

### Adding nodes

#### $this->treeCategory->addChild(int $category_id, int $lftValue, string $text, $extra = array());

Inserts a new node as the first child of the supplied parent node.

```php
$this->treeCategory->addChild(1, 1, 'Televisions');
```
Gives

```php
+-------------+-----------+----------------------+-----+-----+
| category_id | parent_id | name                 | lft | rgt |
+-------------+----------------------------------+-----+-----+
|           1 |         0 | Electronics          |  1  |  4  |
|           2 |         1 | Televisions          |  2  |  3  |
+-------------+-----------+----------------------+-----+-----+
```

Let's add a Portable Electronics node as child of 

```php
$this->treeCategory->addChild(1, 1, 'Portable Electronics');
```

Gives

```php
+-------------+-----------+----------------------+-----+-----+
| category_id | parent_id | name                 | lft | rgt |
+-------------+----------------------------------+-----+-----+
|           1 |         0 | Electronics          |  1  |  6  |
|           3 |         1 | Portable Electronics |  2  |  3  |
|           2 |         1 | Televisions          |  4  |  5  |
+-------------+-----------+----------------------+-----+-----+
```

#### $this->treeCategory->appendChild(int $category_id, int $rgtValue, string $text, $extra = array());

Same as addChild except the new node is added as the last child.

```php
$this->treeCategory->appendChild(2, 5, 'Lcd');
```
Gives

```php
+-------------+-----------+----------------------+-----+-----+
| category_id | parent_id | name                 | lft | rgt |
+-------------+----------------------------------+-----+-----+
|           1 |         0 | Electronics          |  1  |  8  |
|           3 |         1 | Portable Electronics |  2  |  3  |
|           2 |         1 | Televisions          |  4  |  7  |
|           4 |         2 | Lcd				 	 |  5  |  6  |
+-------------+-----------+----------------------+-----+-----+
```

#### $this->treeCategory->addSibling(int $category_id, int $lftValue, string $text, $extra = array());

Inserts a new node as the first sibling of the supplied parent node.

```php
$this->treeCategory->addSibling(2, 5, 'Tube');
```
Gives

```php
+-------------+-----------+----------------------+-----+-----+
| category_id | parent_id | name                 | lft | rgt |
+-------------+----------------------------------+-----+-----+
|           1 |         0 | Electronics          |  1  |  10 |
|           3 |         1 | Portable Electronics |  2  |  3  |
|           2 |         1 | Televisions          |  4  |  9  |
|           5 |         2 | Tube				 |  5  |  6  |
|           4 |         2 | Lcd					 |  7  |  8  |
+-------------+-----------+----------------------+-----+-----+
```

#### $this->treeCategory->appendSibling(int $category_id, int $rgtValue, string $text, $extra = array());

Inserts a new node as the last sibling of the supplied parent node.

```php
$this->treeCategory->appendSibling(2, 8, 'Plasma');
```
Gives

```php
+-------------+-----------+----------------------+-----+-----+
| category_id | parent_id | name                 | lft | rgt |
+-------------+----------------------------------+-----+-----+
|           1 |         0 | Electronics          |  1  |  12 |
|           3 |         1 | Portable Electronics |  2  |  3  |
|           2 |         1 | Televisions          |  4  |  11 |
|           5 |         2 | Tube				 |  5  |  6  |
|           4 |         2 | Lcd					 |  7  |  8  |
|           6 |         2 | Plasma				 |  9  |  10 |
+-------------+-----------+----------------------+-----+-----+
```

** NOTE: **
This function added "Plasma" as sibling to "Lcd". If we wanted to add "Plasma" as sibling to "Lcd" we should set the value of the second parameter "Tube's" which represents the value of "rgt".

#### $this->treeCategory->deleteChild(int $lftValue, int $rgtValue);

Deletes the given node (and any children) from the tree table.

```php
$this->treeCategory->deleteChild(5, 6); // deletes "Tube"
```
Gives

```php
+-------------+-----------+----------------------+-----+-----+
| category_id | parent_id | name                 | lft | rgt |
+-------------+----------------------------------+-----+-----+
|           1 |         0 | Electronics          |  1  |  10 |
|           3 |         1 | Portable Electronics |  2  |  3  |
|           2 |         1 | Televisions          |  4  |  9  |
|           4 |         2 | Lcd					 |  5  |  6  |
|           6 |         2 | Plasma				 |  7  |  8  |
+-------------+-----------+----------------------+-----+-----+
```

#### $this->treeCategory->updateNode($category_id, $data = array());

Updates your table row using the primary key ( category_id ).

```php
$this->treeCategory->updateNode(2, array('name' => 'TV', 'column' => 'test'));
```
Gives

```php
+-------------+-----------+----------------------+-----+-----+--------+
| category_id | parent_id | name                 | lft | rgt | column |
+-------------+----------------------------------+-----+-----+--------+
|           1 |         0 | Electronics          |  1  |  10 |		  |
|           3 |         1 | Portable Electronics |  2  |  3  |		  |
|           2 |         1 | TV		             |  4  |  9  | test   |
|           4 |         2 | Lcd					 |  5  |  6  |		  |
|           6 |         2 | Plasma				 |  7  |  8  |		  |
+-------------+-----------+----------------------+-----+-----+--------+
```

#### $this->treeCategory->truncateTable();

Truncate the table data.


### Function Reference

------

#### $this->treeCategory->setEscapeChar(string $char);

You can set escape character to protect to database column identifiers. It depends on your database driver.

#### $this->treeCategory->addTree(string $text, $extra = array());

Adds the first entry to the table.

#### $this->treeCategory->addChild(int $category_id, int $lftValue, string $text, $extra = array());

Inserts a new node as the first child of the supplied parent node.

#### $this->treeCategory->appendChild(int $category_id, int $rgtValue, string $text, $extra = array());

Same as addChild except the new node is added as the last child.

#### $this->treeCategory->addSibling(int $category_id, int $lftValue, string $text, $extra = array());

Inserts a new node as the first sibling of the supplied parent node.

#### $this->treeCategory->appendSibling(int $category_id, int $rgtValue, string $text, $extra = array());

Inserts a new node as the last sibling of the supplied parent node.

#### $this->treeCategory->deleteChild(int $lftValue, int $rgtValue);

Deletes the given node (and any children) from the tree table.

#### $this->treeCategory->updateNode($category_id, $data = array());

Updates your table row using the primary key ( category_id ).

#### $this->treeCategory->truncateTable();

Truncate the table data.