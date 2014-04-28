
## Tree Db Class

------

Tree class use nested set model. It is a particular technique for representing nested sets (also known as trees or hierarchies) in relational databases. The term was apparently introduced by Joe Celko; others describe the same technique without naming it or using different terms.

<a href="http://ftp.nchu.edu.tw/MySQL/tech-resources/articles/hierarchical-data.html">http://ftp.nchu.edu.tw/MySQL/tech-resources/articles/hierarchical-data.html</a>

### Initializing the Class

------

```php
$c['tree.db'];

$this->treeDb->setTablename('nested_category');
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

#### $this->treeDb->addTree(string $text);

Adds the main category to the table.

```php
$this->treeDb->addTree('Electronics');
```
Gives

```php
+-------------+----------------------+-----+-----+
| category_id | name                 | lft | rgt |
+-------------+----------------------+-----+-----+
|           1 | Electronics          |  1  |  2  |
+-------------+----------------------+-----+-----+
```

#### $this->treeDb->addTree(string $text, $extra = array());

Adds to extra column data to table.

```php
$this->treeDb->addTree('Electronics', $extra = array('column' => 'value'));
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

#### $this->treeDb->addChild(int $lftValue, string $text, $extra = array());

Inserts a new node as the first child of the supplied parent node.

```php
$this->treeDb->addChild($lft = 1, 'Televisions');
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
$this->treeDb->addChild($lft = 1, 'Portable Electronics');
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

#### $this->treeDb->appendChild(int $rgtValue, string $text, $extra = array());

Same as addChild except the new node is added as the last child.

```php
$this->treeDb->appendChild($rgt = 5, 'Lcd');
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

#### $this->treeDb->addSibling(int $lftValue, string $text, $extra = array());

Inserts a new node as the first sibling of the supplied parent node.

```php
$this->treeDb->addSibling($lft = 5, 'Tube');
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

#### $this->treeDb->appendSibling(int $rgtValue, string $text, $extra = array());

Inserts a new node as the last sibling of the supplied parent node.

```php
$this->treeDb->appendSibling($rgt = 8, 'Plasma');
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

#### $this->treeDb->deleteChild(int $lftValue, int $rgtValue);

Deletes the given node (and any children) from the tree table.

```php
$this->treeDb->deleteChild($lft = 5, $rgt = 6); // deletes "Tube"
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

#### $this->treeDb->updateNode($category_id, $data = array());

Updates your table row data using the primary key ( category_id ).

```php
$this->treeDb->updateNode($id = 2, array('name' => 'TV', 'column' => 'test'));
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

#### $this->treeDb->moveAsFirstChild($source, $target);

Move as first child.

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
$this->treeDb->moveAsFirstChild($source, $target);
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

#### $this->treeDb->moveAsPrevSibling($source, $target);

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
$this->treeDb->moveAsPrevSibling($source, $target);
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

#### $this->treeDb->moveAsLastChild($source, $target);

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
$this->treeDb->moveAsLastChild($source, $target);
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

#### $this->treeDb->moveAsNextSibling($source, $target);

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
$this->treeDb->moveAsNextSibling($source, $target);
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


#### $this->treeDb->truncateTable();

Truncate the table data.


### Querying Tree

------

#### Retrieving a Full Tree

We can retrieve the full tree through the use of a self-join that links parents with nodes on the basis that a node's lft value will always appear between its parent's lft and rgt values:

```php
$sql = "SELECT node.name
		FROM nested_category AS node,
		nested_category AS parent
		WHERE node.lft BETWEEN parent.lft AND parent.rgt
		AND parent.name = 'Electronics'
		ORDER BY node.lft";

$this->treeDb->query($sql);
```
Gives
```php
+----------------------+
| name                 |
+----------------------+
| Electronics          |
| Portable Electronics |
| Flash                |
| Mp3 Player           |
| Televisions          |
| Lcd                  |
| Tube                 |
| Plasma               |
+----------------------+
```

#### Finding all the Leaf Nodes

Finding all leaf nodes in the nested set model even simpler than the LEFT JOIN method used in the adjacency list model. If you look at the nested_category table, you may notice that the lft and rgt values for leaf nodes are consecutive numbers. To find the leaf nodes, we look for nodes where rgt = lft + 1:

```php
$sql = "SELECT name
		FROM nested_category
		WHERE rgt = lft + 1";

$this->treeDb->query($sql);
```
Gives
```php
+----------------------+
| name                 |
+----------------------+
| Flash                |
| Mp3 Player           |
| Lcd                  |
| Tube                 |
| Plasma               |
+----------------------+
```

#### Retrieving a Single Path

With the nested set model, we can retrieve a single path without having multiple self-joins:

```php
$sql = "SELECT parent.name
		FROM nested_category AS node,
		nested_category AS parent
		WHERE node.lft BETWEEN parent.lft AND parent.rgt
		AND node.name = 'FLASH'
		ORDER BY parent.lft";

$this->treeDb->query($sql);
```
Gives
```php
+----------------------+
| name                 |
+----------------------+
| Electronics          |
| Portable Electronics |
| Flash                |
+----------------------+
```

#### Finding the Depth of the Nodes

We have already looked at how to show the entire tree, but what if we want to also show the depth of each node in the tree, to better identify how each node fits in the hierarchy? This can be done by adding a COUNT function and a GROUP BY clause to our existing query for showing the entire tree:

```php
$sql = "SELECT node.name, (COUNT(parent.name) - 1) AS depth
		FROM nested_category AS node,
		nested_category AS parent
		WHERE node.lft BETWEEN parent.lft AND parent.rgt
		GROUP BY node.name
		ORDER BY node.lft";

$this->treeDb->query($sql);
```
Gives
```php
+----------------------+-------+
| name                 | depth |
+----------------------+-------+
| Electronics          |     0 |
| Portable Electronics |     1 |
| Flash                |     2 |
| Mp3 Player           |     2 |
| Televisions          |     1 |
| Tube                 |     2 |
| Lcd                  |     2 |
| Plasma               |     2 |
+----------------------+-------+
```

We can use the depth value to indent our category names with the CONCAT and REPEAT string functions:

```php
$sql = "SELECT CONCAT( REPEAT(' ', COUNT(parent.name) - 1), node.name) AS name
		FROM nested_category AS node,
		nested_category AS parent
		WHERE node.lft BETWEEN parent.lft AND parent.rgt
		GROUP BY node.name
		ORDER BY node.lft";

$this->treeDb->query($sql);
```
Gives
```php
+-----------------------+
| name                  |
+-----------------------+
| Electronics			|
|	Portable Electronics|
|		Mp3 Player	    |
|   	Flash           |
|	Televisions         |
|   	Tube            |
|   	Lcd             |
|   	Plasma          |
+-----------------------+
```

Of course, in a client-side application you will be more likely to use the depth value directly to display your hierarchy. Web developers could loop through the tree, adding ```<li></li>``` and ```<ul></ul>``` tags as the depth number increases and decreases.

#### Depth of a Sub-Tree

When we need depth information for a sub-tree, we cannot limit either the node or parent tables in our self-join because it will corrupt our results. Instead, we add a third self-join, along with a sub-query to determine the depth that will be the new starting point for our sub-tree:

```php
$sql = "SELECT node.name, (COUNT(parent.name) - (sub_tree.depth + 1)) AS depth
		FROM nested_category AS node,
			nested_category AS parent,
			nested_category AS sub_parent,
			(
				SELECT node.name, (COUNT(parent.name) - 1) AS depth
				FROM nested_category AS node,
				nested_category AS parent
				WHERE node.lft BETWEEN parent.lft AND parent.rgt
				AND node.name = 'PORTABLE ELECTRONICS'
				GROUP BY node.name
				ORDER BY node.lft
			)AS sub_tree
		WHERE node.lft BETWEEN parent.lft AND parent.rgt
			AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt
			AND sub_parent.name = sub_tree.name
		GROUP BY node.name
		ORDER BY node.lft";

$this->treeDb->query($sql);
```
Gives
```php
+----------------------+-------+
| name                 | depth |
+----------------------+-------+
| Portable Electronics |     0 |
| Flash                |     1 |
| Mp3 Player           |     1 |
+----------------------+-------+
```

This function can be used with any node name, including the root node. The depth values are always relative to the named node.

#### Find the Immediate Subordinates of a Node

Imagine you are showing a category of electronics products on a retailer web site. When a user clicks on a category, you would want to show the products of that category, as well as list its immediate sub-categories, but not the entire tree of categories beneath it. For this, we need to show the node and its immediate sub-nodes, but no further down the tree. For example, when showing the PORTABLE ELECTRONICS category, we will want to show MP3 PLAYERS, CD PLAYERS, and 2 WAY RADIOS, but not FLASH.

This can be easily accomplished by adding a HAVING clause to our previous query:

```php
$sql = "SELECT node.name, (COUNT(parent.name) - (sub_tree.depth + 1)) AS depth
		FROM nested_category AS node,
			nested_category AS parent,
			nested_category AS sub_parent,
			(
				SELECT node.name, (COUNT(parent.name) - 1) AS depth
				FROM nested_category AS node,
				nested_category AS parent
				WHERE node.lft BETWEEN parent.lft AND parent.rgt
				AND node.name = 'PORTABLE ELECTRONICS'
				GROUP BY node.name
				ORDER BY node.lft
			)AS sub_tree
		WHERE node.lft BETWEEN parent.lft AND parent.rgt
			AND node.lft BETWEEN sub_parent.lft AND sub_parent.rgt
			AND sub_parent.name = sub_tree.name
		GROUP BY node.name
		HAVING depth <= 1
		ORDER BY node.lft";

$this->treeDb->query($sql);
```
Gives
```php
+----------------------+-------+
| name                 | depth |
+----------------------+-------+
| Portable Electronics |     0 |
| Flash                |     1 |
| Mp3 Player           |     1 |
+----------------------+-------+
```

If you do not wish to show the parent node, change the HAVING depth <= 1 line to HAVING depth = 1.


### Function Reference

------

#### $this->treeDb->setEscapeChar(string $char = '`');

Allows set escape character to protect database column identifiers. It depends on your database driver.

#### $this->treeDb->setTablename(string $tablename = 'nested_category');

Set table name overridding to default value.

#### $this->treeDb->setPrimaryKey(string $primaryKey = 'category_id');

Set primary key column name overridding to default value.

#### $this->treeDb->setText(string $text = 'name');

Set text column name overridding to default value.

#### $this->treeDb->setLft(string $lft = 'lft');

Set left column name overridding to default value.

#### $this->treeDb->setRgt(string $rgt = 'rgt');

Set right column name overridding to default value.

#### $this->treeDb->addTree(string $text, $extra = array());

Adds the first entry to the table.

#### $this->treeDb->addChild(int $lftValue, string $text, $extra = array());

Inserts a new node as the first child of the supplied parent node.

#### $this->treeDb->appendChild(int $rgtValue, string $text, $extra = array());

Same as addChild except the new node is added as the last child.

#### $this->treeDb->addSibling(int $lftValue, string $text, $extra = array());

Inserts a new node as the first sibling of the supplied parent node.

#### $this->treeDb->appendSibling(int $rgtValue, string $text, $extra = array());

Inserts a new node as the last sibling of the supplied parent node.

#### $this->treeDb->deleteChild(int $lftValue, int $rgtValue);

Deletes the given node (and any children) from the tree table.

#### $this->treeDb->updateNode($category_id, $data = array());

Updates your table row using the primary key ( category_id ).

#### $this->treeDb->truncateTable();

Truncate the table data.

#### $this->treeDb->moveAsFirstChild(array $source, array $target);

Set node as first child.

#### $this->treeDb->moveAsPrevSibling(array $source, array $target);

Set node as prev sibling.

#### $this->treeDb->moveAsLastChild(array $source, array $target);

Set node as last child.

#### $this->treeDb->moveAsNextSibling(array $source, array $target);

Set node as next sibling.

