<?php
/**
 * Name of the app
 */
define("APP_NAME", "test");

/**
 * server's path on the prod server
 */
define("PROD_PATH", "/var/www/mysite.com/");

/**
 * server's path on the dev server
 */
define("DEV_PATH", $_SERVER["DOCUMENT_ROOT"] . "/" . APP_NAME . "/");

/**
 * path to temp directory
 */
define("PATH_TEMP", "/tmp/");
