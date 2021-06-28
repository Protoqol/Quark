<div>
    <img alt="Quark" src="./assets/quarkFull.png"/>
</div>

<p align="center">
    <a href="https://packagist.org/packages/protoqol/quark">
	    <img alt="PHP version" src="https://img.shields.io/badge/php-%5E7.2.5-lightblue.svg"/>	
    </a>
    <a href="https://twitter.com/intent/follow?screen_name=Protoqol_XYZ">
        <img src="https://img.shields.io/twitter/follow/Protoqol_XYZ.svg?label=%40Protoqol_XYZ&style=social"
            alt="Follow Protoqol on Twitter">
    </a>
</p>

### This is still in a very early alpha stage.

---

### If you are in need of a quick, easy-to-use, sub-atomic database, Quark is for you!

---

#### TL;DR.

Quark is an easy-to-use flat-file database, meaning, a database that exists entirely inside a single file. In Quark's
case, it uses a minified JSON file for optimal performance and speed. Quark includes a simple yet powerful Query Builder
for all your querying needs.

## Installation & Quick start

<a name="install_quick_start"/>

###### 1. Install using the command below.

```bash
$ composer require protoqol/quark
```

###### 2. Initialise the Quark executable using the command below.

```bash
$ ./vendor/protoqol/quark/bin/quark install
```

This will create a file called `quark` in your root directory, with this you no longer have to reference the entire path
to use Quark. You can just use `./quark`.

> Note: using `./` simply indicates that this script needs to be executed according to its contents, meaning, you can also use `php quark` to execute it as a php script.

###### 3. Initialise quark directory structure.

This will generate the needed directory structure for Quark to function.

```bash
$ ./quark init
```

###### 4. Create a migration.

This will generate a migration file which is used to define a table.

```bash
$ ./quark create {TableName}
```

> Migrations can be found in de {root}/quark/migrations directory.

###### 5. Run migrations.

Running this command will migrate all pending migrations to the database.

```bash
$ ./quark migrate
```

###### 6. Using the data.

To use the table data, create a class (use singular pascal-cased version of table name as class name) which
extends `Protoqol\Quark\Database\QModel`.
> If you want to user a different class name you can overwrite the `public $table` property in your class with the correct table name.

This class will give you a few methods to interact with your data such as:

```php

/**
 * Returns all available data from table. 
 * Define what columns you need with the $columns parameter. Example: ['id', 'name'], this only returns 'id' & 'name' columns.
 * 
 * @param array $columns string[] Defaults to all columns.
 * 
 * @return \Protoqol\Quark\Database\QCollection
 */
public function all(array $columns = ['*']): \Protoqol\Quark\Database\QCollection

/**
 * Returns first entry from table.
 * Define what columns you need with the $columns parameter. Example: ['id', 'name'], this only returns 'id' & 'name' columns.
 * 
 * @param array $columns string[] Defaults to all columns.
 * 
 * @return \Protoqol\Quark\Database\QCollection
 */
public function first(array $columns = ['*']): \Protoqol\Quark\Database\QCollection

/**
 * Returns last entry from table.
 * Define what columns you need with the $columns parameter. Example: ['id', 'name'], this only returns 'id' & 'name' columns.
 * 
 * @param array $columns string[] Defaults to all columns.
 * 
 * @return \Protoqol\Quark\Database\QCollection
 */
public function last(array $columns = ['*']): \Protoqol\Quark\Database\QCollection

/**
 * Creates a new record in your table. Define values in $attributes parameter.
 * Example: self::create(['name' => 'Cool Name', 'is_active' => true])
 *
 * @param array $attributes 
 *                         
 * @return \Protoqol\Quark\Database\QModel
 */
public function create(array $attributes): \Protoqol\Quark\Database\QModel

```

> These methods can provide a very basic database interaction but are very limited, while Quark is being developed more features will be implemented.

###### 6. Enjoy.

For the complete documentation refer to: __{$LINK_HERE}__

---

#### Issues, bugs and feature requests can be reported [here!](https://github.com/Protoqol/Quark/issues/new/choose)

## Quark in depth

<a name="in_depth"/>

#### What _*is*_ Quark?

<a name="what"/>

_Note: Quark is not meant to replace any other database but is meant as a developer tool._

With Quark, you can easily set up a throwaway database without having to have a dedicated server running. Quark
especially shines when you want to quickly prototype something or just need a quick playground to test some other
interesting feature.

#### Why is Quark a thing?

<a name="why"/>

I personally dislike having to set up an entire project before I can even begin coding a project, and want to get into
prototyping my (then still fresh)
idea as quick as possible. At that point I do not care for setting up a database. Quark helps by bootstrapping a quick
and easy database for my every need. When my idea/prototype proves it has potential, I can refactor to a server-based
database, this also saves myself from having to drop _n_ databases to clean up. _(WIP) Quark makes that transition as
smooth as possible with the schema generator._

#### Where is Quark?

<a name="where"/>

Everywhere.

#### How secure is Quark?

<a name="secure"/>

As secure as any other file in your root directory. Without access to the filesystem there is no access possible to the
database. So Quark is as secure as you need it to be, but keep in mind, the *.qrk files need write & read access.

**Be warned, I strongly discourage using Quark to store sensitive data, and I will not take any responsibility for any
harm that is caused by or due to Quark.**

## Contributors

<a name="contributors"/>

- [Quinten Schorsij](https://github.com/QuintenJustus)

## License

Quark is licensed under the MIT License. Please see [License File](LICENSE) for more information.
