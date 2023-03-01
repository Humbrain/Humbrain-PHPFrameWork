# SteoFrameWork

[![Generic badge](https://img.shields.io/badge/Version-2.0.0-green.svg?style=flat-square)](https://shields.io/)
> A simple framework with Router and ORM
___

## Instalation

___
> You can use independante to use composer
> ``composer require humbrain/php-frame-work``

### Or

> Install with all project with
> ``composer project ...``

## Usage/Examples

___
### Configuration
> You need .env and .htaccess for work corectly

`.env`
````dotenv
debug=false
HOST="localhost"
HOST_DB="test"
HOST_URL="mysql:host=${HOST};dbname=${sncf_suppeo};charset=utf8"
DB_USER="steodec"
DB_PASSWORD="Myszkowski98"
````
`.htacess`
````apacheconf
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]

<Files .env>
    Order allow,deny
    Deny from all
</Files>
````
### Create Route

````php
<?php
namespace App\controllers

use Steodec\SteoFrameWork\Controllers\AbstractControllers;

class HomeController extends AbstractControllers {

    #[Get(_name: 'HOME')]
    public function index() {
        //TODO Some thing
    }
}
````

### Create Model

````php
<?php
//PostModel.php
namespace App\entity

use Steodec\SteoFrameWork\ORM\Entity;

class PostModel extends Entity {
    protected string $TABLE_NAME = "post"
    public string $name;
    public string $first_name;
    
    public function __construct() {
        parent::__construct();
    }
        
}
````
### Use Model
````php
<?php
//PostController.php
namespace App\controllers

use Steodec\SteoFrameWork\Controllers\AbstractControllers;

class PostController extends AbstractControllers {

    #[Post(_name: 'CREATE_POST')]
    public function create() {
        $post = new PostModel()
        $post->name = "Doe"
        $post->first_name = "Jonh"
        $post->save();
    }
    
    #[Post(_name: 'UPDATE_POST')]
    public function update() {
        $post = new PostModel()
        $post->name = "Doe2"
        $post->save();
    }
}
````

### Fetch data from base

> You can fetch data from Entity with one function

````php

    $post = new PostModel();
    // Simple
    $result = $post->find("id = 1")

    // More complex
    $result = $post->find([
    "conditions" => [
        "first_name" => "john",
        "name" => "Doe",
        "logique" => "OR"
     ],
     "limit" => 1,
     "order" => "id ASC"
     ])
````

## Information

___

## Tech Stack

**Client:** HTML, Twig

**Server:** PHP 8.1, MYSQL 8.0

### Author

- [@steodec](https://www.github.com/steodec)

### Website

- [Humbrain](http://www.humbrain.com)

### Dependency

> The following packages were found in your dependencies which publish funding information:

- doctrine <br> instantiator
https://www.doctrine-project.org/sponsorship.html
https://www.patreon.com/phpdoctrine
https://tidelift.com/funding/github/packagist/doctrine%2Finstantiator

- guzzlehttp <br>
guzzle, promises, psr7
https://github.com/sponsors/GrahamCampbell
https://github.com/sponsors/Nyholm <br>
guzzle <br>
https://tidelift.com/funding/github/packagist/guzzlehttp/guzzle <br>
promises <br>
https://tidelift.com/funding/github/packagist/guzzlehttp/promises <br>
psr7 <br>
https://tidelift.com/funding/github/packagist/guzzlehttp/psr7 <br>

- myclabs <br>
deep-copy <br>
https://tidelift.com/funding/github/packagist/myclabs/deep-copy <br>

- phar-io <br>
manifest <br>
https://github.com/sponsors/theseer <br>

- phpunit <br>
php-code-coverage, php-file-iterator, php-invoker, php-text-template, php-timer, phpunit <br>
https://github.com/sponsors/sebastianbergmann <br>
phpunit <br>
https://phpunit.de/sponsors.html <br>
https://tidelift.com/funding/github/packagist/phpunit/phpunit <br>

- sebastian <br>
cli-parser, code-unit, code-unit-reverse-lookup, comparator, complexity, diff, environment, exporter, global-state,
lines-of-code, object-enumerator, object-reflector, recursion-context, resource-operations, type, version <br>
https://github.com/sponsors/sebastianbergmann <br>

- symfony <br>
deprecation-contracts, polyfill-ctype, polyfill-intl-grapheme, polyfill-intl-normalizer, polyfill-mbstring, string, 
translation-contracts <br>
https://symfony.com/sponsor <br>
https://github.com/sponsors/fabpot <br>
https://tidelift.com/funding/github/packagist/symfony/symfony <br>

- theseer <br>
tokenizer <br>
https://github.com/sponsors/theseer <br>

- twig <br>
string-extra, twig <br>
https://github.com/sponsors/fabpot <br>
https://tidelift.com/funding/github/packagist/twig/twig <br>

> Please consider following these links and sponsoring the work of package authors!
Thank you!
