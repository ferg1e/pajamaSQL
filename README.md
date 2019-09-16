# pajamaSQL

pajamaSQL is a PHP SQL database layer.
It supports MySQL, PostgreSQL and SQLite.

## Documentation

This [README](https://github.com/al-codepone/pajamaSQL/blob/master/README.md)
is currently the only documentation.

## Requirements

**PHP 5.3** or higher, [Composer](https://getcomposer.org/),
and at least one of the follow PHP vendor specific database extensions:
[Mysqli](http://www.php.net/manual/en/book.mysqli.php),
[PostgreSQL](http://www.php.net/manual/en/book.pgsql.php),
[SQLite3](http://www.php.net/manual/en/book.sqlite3.php).


## Source Code

This [project](https://github.com/al-codepone/pajamaSQL)
and its [source code](https://github.com/al-codepone/pajamaSQL/tree/master/src/pjsql)
are available on GitHub.

## Tests

All the tests are in the [test directory](https://github.com/al-codepone/pajamaSQL/tree/master/test).
Each PHP script in the top level test directory is a separate test.
You need to run `composer install` in the test directory before running any of the tests.
You also need to setup database credentials and put them in a test before running any test.

## Installation

Install using composer:

```javascript
{
    "require": {
        "pajamasql/pajamasql": "0.5.0",
    }
}
```

## MySQL

Connect to MySQL using the constructor:

```php
<?php

require 'vendor/autoload.php';

$db = new pjsql\Mysql(
    'host',
    'username',
    'password',
    'database');

echo $db->conn()->stat();
```

Execute MySQL queries with `exec()` and `query()`:

```php
$db->exec('drop table if exists tanimal');

$db->exec('create table tanimal(
    animal_id int auto_increment primary key,
    name varchar(32))');

$db->exec('insert into tanimal(name) values("tiger")');

$data = $db->query('select * from tanimal');

print_r($data);
```

`exec()` and `query()` can take query parameters:

```php
$db->exec(
    'insert into tanimal(name) values(?), (?)',
    'ss',
    'tiger',
    'eagle');

$data = $db->query(
    'select * from tanimal where animal_id = ?',
    'i',
    2);

print_r($data);
```

The second argument for `exec()` and `query()` is a parameter types string and
it works the same as the [mysqli_stmt::bind_param](https://www.php.net/manual/en/mysqli-stmt.bind-param.php) `$types` argument.

`query()` returns a 2D array. If you want a `mysqli_result` object instead then use `rquery()`:

```php
$db->exec(
    'insert into tanimal(name) values(?), (?)',
    'ss',
    'tiger',
    'eagle');

$result = $db->rquery(
    'select * from tanimal where animal_id < ?',
    'i',
    1000);

while($row = $result->fetch_object()) {
    print_r($row);
}
```

Use `prepare()`, `bexec()` and `bquery()` to run a query more than once:

```php
$stmt = $db->prepare('insert into tanimal values(null, ?)');
$db->bexec($stmt, 's', 'bird');
$db->bexec($stmt, 's', 'frog');
$db->bexec($stmt, 's', 'cat');

$ids = [1, 2, 3, 4];
$stmt = $db->prepare('select name from tanimal where animal_id = ?');

foreach($ids as $id) {
    $data = $db->bquery($stmt, 'i', $id);
    print_r($data);
}
```

`bexec()` and `bquery()` take the same arguments as `exec()` and `query()`
except the first is a prepared statement instead of a query string.

`bquery()` returns a 2D array. If you want a `mysqli_result` object instead then use `brquery()`:

```php
$animals = ['cat', 'dog', 'turtle', 'crab', 'shark'];
$stmt = $db->prepare('insert into tanimal values(null, ?)');

foreach($animals as $a) {
    $db->bexec($stmt, 's', $a);
}

$floors = [1, 5];
$stmt = $db->prepare('select name from tanimal where animal_id >= ?');

foreach($floors as $f) {
    $result = $db->brquery($stmt, 'i', $f);

    while($row = $result->fetch_assoc()) {
        print_r($row);
    }
}
```

## PostgreSQL

...