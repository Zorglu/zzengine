

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zozor/zzengine.svg?style=flat-square)](https://packagist.org/packages/zozor/zzengine)
[![Total Downloads](https://img.shields.io/packagist/dt/zozor/zzengine.svg?style=flat-square)](https://img.shields.io/packagist/dt/zozor/zzengine.svg)

A php light framework for easily create robust applications

zzengine covers four main actions:
-Database management (all requests are securized)
-Session management
-Request management (GET and POST) avec sécurisation
-Response management

## Installation

> [!CAUTION]
> Please note that this is a new package and, even though it is well tested, it should be considered pre-release software

You can install the package via composer:

```bash
composer require zozor/zzengine
```
## Réglages généraux
La configuration du moteur se fait au travers de 3 fichiers de configuration :
- config/general_settings.php
- config/database_settings.php
- config/session_settings.php

### config/general_settings.php
```php
define("APP_NAME", "mysite");                           // Nom de l'application
define("PROD_PATH", "/chemin sur la machine de prod/"); // chemin site sur la serveur de production
define("DEV_PATH", $_SERVER["DOCUMENT_ROOT"] . "/" . APP_NAME . "/"); // chemin du site sur la machine de developpement
define("PATH_TEMP", "/tmp/");                           // chemin du répertoire temporaire
```
### config/database_settings.php
```php
define("BDD_HOST", "localhost");        // nom/ip de la machine sur laquelle tourne le serveur Mysql/MariaDb
define("BDD_USER", "username");         // Nom de l'utilisateur accédant à la base de données
define("BDD_PWD", "password");          // Mot de passe de l'utilisateur
define("BDD_NAME", "database name");    // Nom de la base de données
define("BDD_PORT", 3306);               // Port utilisé par le serveur de base de données
```
### config/session_settings.php
```php
define("SESSION_NAME", "nameapp");      // Nom de la session
define("SESSION_LIFETIME", 86400);      // durée de vie de la session
```

# Utilisations du moteur
Engine::create() Initialise le moteur, la base se données et la session.
A noter que Engine::Create retourne un singleton. Par conséquent, vous pouvez appeler Engine::create() plusieurs fois sans
que cela renouvelle la connexion à la base de données ou de la session.

## Ecrire/Lire une variable de session
```php
declare (strict_types=1);
require __DIR__ . '/vendor/autoload.php';

use zzengine\App\Engine;

$engine = Engine::create(); // Create a singleton

$engine::$session::setValue("test", "12345"); // Créer une variable de session (test)
$test = $engine::$session::getValue("test"); // Return 12345
print $test;
$engine::$session::removeValue("test"); // Suppression de la variable de session (test)
$session_id = $engine::$session::getId(); // Retounre l'ID de session
print $session_id;
```
## Lire les requêtes GET ou POST
```php
declare (strict_types=1);
require __DIR__ . '/vendor/autoload.php';

use zzengine\App\Engine;

$engine = Engine::create(); // Create a singleton

// Lit la variable $_GET["action"] ou $_POST["action"]
// Retourne null si non présent
$action = $engine::$request::get("action"));

// Lit la variable $_POST["action"]
// Retourne null si non présent dans $_POST
$action = $engine::$request::get("action", "POST"));

// Lit un entier dans la variable $_GET["myint"]
// Retourne null si non présent dans $_GET ou n'est pas un entier
$int = $engine::$request::getInt("myint", "GET"));

$float = $engine::$request::getInt("myfloat"));
$bool = $engine::$request::getBool("myboolean"));
$regexp = $engine::$request::getRegexp("myregexp"));
$email = $engine::$request::getEmail("myemail"));
$url = $engine::$request::getUrl("myurl"));
$domain = $engine::$request::getDomain("mydomain"));
$ip = $engine::$request::getIp("myip"));
$mac_address = $engine::$request::getMac("mymac"));
```

## Ecrire une réponse structurée
```php
declare (strict_types=1);
require __DIR__ . '/vendor/autoload.php';

use zzengine\App\Engine;

$engine = Engine::create(); // Create a singleton

$myresponse = [
    "id" => 123456,
    "state" => true,
    "name"=> "My name",
    "address" => [
        [
            "street" => "Rue d'alésia",
            "number" => 209,
            "city" => "Paris 14"
        ],
        [
            "street" => "Rue de Lyon",
            "number" => 34,
            "city" => "Paris 12"
        ]
    ]
];
print $engine::$response::json($myresponse, $engine::$response::OK);
/* this result will be easily readable in javascript
{
    "code":200,
    "data":{
        "id":123456,
        "state":true,
        "name":"My name",
        "address":[
            {
                "street":"Rue d'al\u00e9sia",
                "number":209,
                "city":"Paris 14"
            },
            {
                "street":"Rue de Lyon",
                "number":34,
                "city":"Paris 12"
            }
        ]
    },
    "ts":1744271580000
}
*/
```
## Gestion d'une base de données
```php
declare (strict_types=1);
require __DIR__ . '/vendor/autoload.php';

use zzengine\App\Engine;

$engine = Engine::create(); // Create a singleton

// get one line of datas from DB table users where ID_USER = 1
$user = $engine::$DB::select("SELECT * FROM `users` WHERE `ID_USER` = ?", [1]);

// get all records from table users where VALID > 0
$users = $engine::$DB::selectAll("SELECT * FROM `users` WHERE `VALID` > ?", [0]);

// get all records from table users where VALID = 1 from 2nd line to 5nd line
$user = $engine::$DB::pagination("SELECT * FROM `users` WHERE `VALID` = ?", [1], 2, 5);

// insert in table users fields VALID and NAME with 0 et myname et return the ID of lastInsertId()
$id_user = $engine::$DB::insert("INSERT INTO `users` (`VALID`, `NAME`) = ?", [0, "myname"], true);

// insert in table users fields VALID and NAME with 0 et myname without return lastInsertId()
$done = $engine::$DB::insert("INSERT INTO `users` (`VALID`, `NAME`) = ?", [0, "myname"]);

// update from table users where user ID = 1 AND VALID > 0, update VALID to 0
$done = $engine::$DB::update("UPDATE `users` SET `VALID` = ? WHERE `ID` = ? AND `VALID` > ?", [0, 1, 0]);

// delete from table users where user ID = 1
$done = $engine::$DB::delete("DELETE FROM `users` WHERE `ID` = ?", [1]);

// do a statement on table users
$done = $engine::$DB::statement("OPTIMIZE TABLE `users`");

// do a unprepared request on table users (ATTENTION no secured query)
$done = $engine::$DB::nprepared("DELETE FROM `users` WHERE `ID` = 1");

```

## Gestion du moteur
```php
declare (strict_types=1);
require __DIR__ . '/vendor/autoload.php';

use zzengine\App\Engine;

$engine = Engine::create(); // Create a singleton

// Returns a boolean true = prod mode  false = dev mode
print $engine->isProd();

// Returns the server's path based on the production mode
print $engine->getPath();

```