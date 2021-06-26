<div>
    <img src="./assets/quarkFull.png"/>
</div>

<p align="center">
    <a href="https://travis-ci.org/Protoqol/Prequel.svg?branch=Dev">
	    <img src="https://travis-ci.com/QuintenJustus/QuarkDev.svg?token=ZdiYagasyKHEDjbRoVro&branch=master"/>	
    </a>
    <a href="https://packagist.org/packages/protoqol/prequel">
	    <img src="https://img.shields.io/badge/php-%5E7.2-lightblue.svg"/>	
    </a>
    <a href="https://twitter.com/intent/follow?screen_name=Protoqol_XYZ">
        <img src="https://img.shields.io/twitter/follow/Protoqol_XYZ.svg?label=%40Protoqol_XYZ&style=social"
            alt="follow on Twitter">
    </a>
</p>

### _If you are in need of a quick, easy-to-use, sub-atomic database, Quark is for you!_

---

#### Quark, what is it, besides a sub-atomic particle?

Quark is an easy-to-use flat file database, meaning, a database that exists entirely inside a single file. In Quark's
case, it uses a minified JSON file for optimal performance and speed. Quark includes a simple yet powerful Query Builder
for all your querying needs.

## Installation & Quick start

###### 1. Install use the following command.

```bash  
$ composer require protoqol/quark  
```  

###### 2. Initialise the Quark executable using the command below. (Optional)

```bash
$ ./vendor/protoqol/quark/bin/quark install
```

This will create a file called `quark` in your root directory, with this you no longer have to reference the entire path
to use Quark. You can just use the `./quark`. Although this is optional, it is strongly recommended. In the
documentation we will always refer to the quark executable as `./quark`

Besides creating an easy access point to Quark, this command will also generate a config file
called `.quark-env-example`. In this file you can define your own custom configuration for Quark.

###### 3. Creating your first Quark databases and tables.

Creating a database

```bash
$ ./quark create database {name}
```

Creating a table

```bash
$ ./quark create table {name}
```

###### 4. Enjoy the benefits of Quark
For the complete documentation refer to: __{$LINK_HERE}__


---

#### Issues, bugs and feature requests can be reported [here!](https://github.com/Protoqol/Quark/issues/new/choose)

## Quark in depth

#### What _*is*_ Quark?

_Note: Quark is not meant to replace any other database but is meant as a developer tool._

With Quark, you can easily set up a throwaway database without having to have a dedicated server running. Quark
especially shines when you want to quickly prototype something or just need a quick playground to test some other
interesting feature.

#### Why is Quark a thing?

I personally dislike having to set up an entire project before I can even begin coding a project, and want to get into
prototyping my (then still fresh)
idea as quick as possible. At that point I do not care for setting up a database. Quark helps by bootstrapping a quick
and easy database for my every need. When my idea/prototype proves it has potential, I can refactor to a server-based
database, this also saves myself from having to drop _n_ databases to clean up. _(WIP) Quark makes that transition as
smooth as possible with the schema generator._

#### Where is Quark?

Everywhere.

#### How secure is Quark?

As secure as any other file in your root directory. Without access to the filesystem there is no access possible to the
database.

**Be warned, I strongly discourage using Quark to store sensitive data, and I will not take any responsibility for any
harm that is caused by or due to Quark.**

## Contributors

- [Quinten Schorsij](https://github.com/QuintenJustus)

## License

Quark is licensed under the MIT License. Please see [License File](LICENSE) for more information.
