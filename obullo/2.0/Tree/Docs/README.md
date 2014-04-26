
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
	name VARCHAR(20) NOT NULL,
	lft INT NOT NULL,
	rgt INT NOT NULL
);
```

Don’t forget to add some indexes on your tables to speed up the “reading” process. You should add indexes for parent_id, lft and rght:

```php
ALTER TABLE  `categories` ADD INDEX  `lft` (  `lft` );
ALTER TABLE  `categories` ADD INDEX  `rgt` (  `rgt` );
```

### Add root category

#### $this->treeCategory->addTree(string $text);

Adds the main category to the table.

```php
$this->treeCategory->addTree('Electronics');
```
Gives

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  2  |
+-------------+----------------------+-----+-----+
```

#### $this->treeCategory->addTree(string $text, $extra = array());

Adds to extra column data to table.

```php
$this->treeCategory->addTree('Electronics', $extra = array('column' => 'value'));
```
Gives

```php
+-------------+----------------------+-----+-----+--------+
| category_id | name                 | lft | rgt | column |
+-------------+----------------------+-----+-----+--------+
|           1 | Electronics          |  1  |  2  |  value |
+-------------+----------------------+-----+-----+--------+
```

### Adding nodes

#### $this->treeCategory->addChild(int $lftValue, string $text, $extra = array());

Inserts a new node as the first child of the supplied parent node.

```php
$this->treeCategory->addChild($lft = 1, 'Televisions');
```
Gives

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  4  |
|           2 | Televisions          |  2  |  3  |
+-------------+----------------------+-----+-----+
```

Let's add a Portable Electronics node as child of 

```php
$this->treeCategory->addChild($lft = 1, 'Portable Electronics');
```

Gives

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  6  |
|           3 | Portable Electronics |  2  |  3  |
|           2 | Televisions          |  4  |  5  |
+-------------+----------------------+-----+-----+
```

#### $this->treeCategory->appendChild(int $rgtValue, string $text, $extra = array());

Same as addChild except the new node is added as the last child.

```php
$this->treeCategory->appendChild($rgt = 5, 'Lcd');
```
Gives

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  8  |
|           3 | Portable Electronics |  2  |  3  |
|           2 | Televisions          |  4  |  7  |
|           4 | Lcd				 	 |  5  |  6  |
+-------------+----------------------+-----+-----+
```

#### $this->treeCategory->addSibling(int $lftValue, string $text, $extra = array());

Inserts a new node as the first sibling of the supplied parent node.

```php
$this->treeCategory->addSibling($lft = 5, 'Tube');
```
Gives

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  10 |
|           3 | Portable Electronics |  2  |  3  |
|           2 | Televisions          |  4  |  9  |
|           5 | Tube				 |  5  |  6  |
|           4 | Lcd					 |  7  |  8  |
+-------------+----------------------+-----+-----+
```

#### $this->treeCategory->appendSibling(int $rgtValue, string $text, $extra = array());

Inserts a new node as the last sibling of the supplied parent node.

```php
$this->treeCategory->appendSibling($rgt = 8, 'Plasma');
```
Gives

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  12 |
|           3 | Portable Electronics |  2  |  3  |
|           2 | Televisions          |  4  |  11 |
|           5 | Tube				 |  5  |  6  |
|           4 | Lcd					 |  7  |  8  |
|           6 | Plasma				 |  9  |  10 |
+-------------+----------------------+-----+-----+
```

** NOTE: **
This function added "Plasma" as sibling to "Lcd". If we wanted to add "Plasma" as sibling to "Lcd" we should set the value of the second parameter "Tube's" which represents the value of "rgt".

#### $this->treeCategory->deleteChild(int $lftValue, int $rgtValue);

Deletes the given node (and any children) from the tree table.

```php
$this->treeCategory->deleteChild($lft = 5, $rgt = 6); // deletes "Tube"
```
Gives

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  10 |
|           3 | Portable Electronics |  2  |  3  |
|           2 | Televisions          |  4  |  9  |
|           4 | Lcd					 |  5  |  6  |
|           6 | Plasma				 |  7  |  8  |
+-------------+----------------------+-----+-----+
```

#### $this->treeCategory->updateNode($category_id, $data = array());

Updates your table row using the primary key ( category_id ).

```php
$this->treeCategory->updateNode($id = 2, array('name' => 'TV', 'column' => 'test'));
```
Gives

```php
+-------------+----------------------+-----+-----+--------+
| category_id | name                 | lft | rgt | column |
+-------------+----------------------+-----+-----+--------+
|           1 | Electronics          |  1  |  10 |		  |
|           3 | Portable Electronics |  2  |  3  |		  |
|           2 | TV		             |  4  |  9  | test   |
|           4 | Lcd					 |  5  |  6  |		  |
|           6 | Plasma				 |  7  |  8  |		  |
+-------------+----------------------+-----+-----+--------+
```

#### $this->treeCategory->moveAsFirstChild($source, $target);

Move as first child.

Our current table.

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  16 |
|           2 | Portable Electronics |  2  |  7  |
|           3 | Flash				 |  3  |  4  |
|           4 | Mp3 Player			 |  5  |  6  |
|           5 | Televisions          |  8  |  15 |
|           6 | Lcd					 |  9  |  10 |
|           7 | Tube				 |  11 |  12 |
|           8 | Plasma				 |  13 |  14 |
+-------------+----------------------+-----+-----+
```

We want to move "Portable Electronics" under the "Televisions" to be the first child.

```php
$source = array(    // Portable Electronics
	'lft' => 2,
	'rgt' => 7
);

$target = array(	// Televisions
	'lft' => 8,
	'rgt' => 15	
);
$this->treeCategory->moveAsFirstChild($source, $target);
```

After the move operation.

Gives

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  16 |
|           5 | Televisions          |  2  |  15 |
|           2 | Portable Electronics |  3  |  8  |
|           3 | Flash				 |  4  |  5  |
|           4 | Mp3 Player			 |  6  |  7  |
|           6 | Lcd					 |  9  |  10 |
|           7 | Tube				 |  11 |  12 |
|           8 | Plasma				 |  13 |  14 |
+-------------+----------------------+-----+-----+
```

#### $this->treeCategory->moveAsPrevSibling($source, $target);

Move as prev sibling.

Before move operation our current table.

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  16 |
|           5 | Televisions          |  2  |  15 |
|           2 | Portable Electronics |  3  |  8  |
|           3 | Flash				 |  4  |  5  |
|           4 | Mp3 Player			 |  6  |  7  |
|           6 | Lcd					 |  9  |  10 |
|           7 | Tube				 |  11 |  12 |
|           8 | Plasma				 |  13 |  14 |
+-------------+----------------------+-----+-----+
```

We want to move "Portable Electronics" as a previous sibling of "Televisions" 

```php
$source = array(    // Portable Electronics
	'lft' => 3,
	'rgt' => 8
);

$target = array(	// Televisions
	'lft' => 2,
	'rgt' => 15	
);
$this->treeCategory->moveAsPrevSibling($source, $target);
```

After the move operation.

Gives

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  16 |
|           2 | Portable Electronics |  2  |  7  |
|           3 | Flash				 |  3  |  4  |
|           4 | Mp3 Player			 |  5  |  6  |
|           5 | Televisions          |  8  |  15 |
|           6 | Lcd					 |  9  |  10 |
|           7 | Tube				 |  11 |  12 |
|           8 | Plasma				 |  13 |  14 |
+-------------+----------------------+-----+-----+
```

#### $this->treeCategory->moveAsLastChild($source, $target);

Move as last child.

Before move operation our current table.

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  16 |
|           2 | Portable Electronics |  2  |  7  |
|           3 | Flash				 |  3  |  4  |
|           4 | Mp3 Player			 |  5  |  6  |
|           5 | Televisions          |  8  |  15 |
|           6 | Lcd					 |  9  |  10 |
|           7 | Tube				 |  11 |  12 |
|           8 | Plasma				 |  13 |  14 |
+-------------+----------------------+-----+-----+
```

We want to move "Portable Electronics" under the "Televisions" as a last child.

```php
$source = array(    // Portable Electronics
	'lft' => 2,
	'rgt' => 7
);

$target = array(	// Televisions
	'lft' => 8,
	'rgt' => 15	
);
$this->treeCategory->moveAsLastChild($source, $target);
```

After the move operation.

Gives

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  16 |
|           5 | Televisions          |  2  |  15 |
|           6 | Lcd					 |  3  |  4  |
|           7 | Tube				 |  5  |  6  |
|           8 | Plasma				 |  7  |  8  |
|           2 | Portable Electronics |  9  |  14 |
|           3 | Flash				 |  10 |  11 |
|           4 | Mp3 Player			 |  12 |  13 |
+-------------+----------------------+-----+-----+
```

#### $this->treeCategory->moveAsNextSibling($source, $target);

Move as next sibling.

Before move operation our current table.

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  16 |
|           5 | Televisions          |  2  |  15 |
|           6 | Lcd					 |  3  |  4  |
|           7 | Tube				 |  5  |  6  |
|           8 | Plasma				 |  7  |  8  |
|           2 | Portable Electronics |  9  |  14 |
|           3 | Flash				 |  10 |  11 |
|           4 | Mp3 Player			 |  12 |  13 |
+-------------+----------------------+-----+-----+
```

We want to move "Portable Electronics" as a next sibling of "Televisions" 

```php
$source = array(    // Portable Electronics
	'lft' => 9,
	'rgt' => 14
);

$target = array(	// Televisions
	'lft' => 2,
	'rgt' => 15	
);
$this->treeCategory->moveAsNextSibling($source, $target);
```

After the move operation.

Gives

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  16 |
|           5 | Televisions          |  2  |  9  |
|           6 | Lcd					 |  3  |  4  |
|           7 | Tube				 |  5  |  6  |
|           8 | Plasma				 |  7  |  8  |
|           2 | Portable Electronics |  10 |  15 |
|           3 | Flash				 |  11 |  12 |
|           4 | Mp3 Player			 |  13 |  14 |
+-------------+----------------------+-----+-----+
```


#### $this->treeCategory->truncateTable();

Truncate the table data.


### Function Reference

------

#### $this->treeCategory->setEscapeChar(string $char);

You can set escape character to protect to database column identifiers. It depends on your database driver.

#### $this->treeCategory->addTree(string $text, $extra = array());

Adds the first entry to the table.

#### $this->treeCategory->addChild(int $lftValue, string $text, $extra = array());

Inserts a new node as the first child of the supplied parent node.

#### $this->treeCategory->appendChild(int $rgtValue, string $text, $extra = array());

Same as addChild except the new node is added as the last child.

#### $this->treeCategory->addSibling(int $lftValue, string $text, $extra = array());

Inserts a new node as the first sibling of the supplied parent node.

#### $this->treeCategory->appendSibling(int $rgtValue, string $text, $extra = array());

Inserts a new node as the last sibling of the supplied parent node.

#### $this->treeCategory->deleteChild(int $lftValue, int $rgtValue);

Deletes the given node (and any children) from the tree table.

#### $this->treeCategory->updateNode($category_id, $data = array());

Updates your table row using the primary key ( category_id ).

#### $this->treeCategory->truncateTable();

Truncate the table data.

#### $this->treeCategory->moveAsFirstChild($source, $target);

Set node as first child.

#### $this->treeCategory->moveAsPrevSibling($source, $target);

Set node as prev sibling.

#### $this->treeCategory->moveAsLastChild($source, $target);

Set node as last child.

#### $this->treeCategory->moveAsNextSibling($source, $target);

Set node as next sibling.