<?php
/**
 * Name of the host hosting the database
 */
define("BDD_HOST", "localhost");

/**
 * Name of the database user
 */
define("BDD_USER", "pascal");

/**
 * Password for database user
 */
define("BDD_PWD", "123456");

/**
 * Name of the database
 */
define("BDD_NAME", "test");

/**
 * Port number used by the database
 */
define("BDD_PORT", 3306);

/**
 * Charset used by the database
 */
define("BDD_CHARSET", "utf8");

/**
 * PDO attributes settings see : https://www.php.net/manual/en/pdo.setattribute.php
 */
define("ATTR_EMULATE_PREPARES", [
    PDO::ATTR_ERRMODE =>  PDO::ERRMODE_WARNING,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . BDD_CHARSET,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_STRINGIFY_FETCHES => false
]);
