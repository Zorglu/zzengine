<?php declare (strict_types=1);

namespace zzengine\App;

use zzengine\App\Database, zzengine\App\Session, zzengine\App\Response, zzengine\App\Request, zzengine\App\Db;

require_once(__DIR__ . "/config/general_settings.php");
require_once(__DIR__ . "/config/session_settings.php");
require_once(__DIR__ . "/config/database_settings.php");

/**
 * Main class
 * This class encapsulates the handlers for:
 * Database
 * Session
 * Request
 * Response
 */
final class Engine{

    private static $instance = null;
    private static bool $prod = false;
    private static string $path = "";
    public static Session $session;
    public static Response $response;
    public static Request $request;
    public static Db $DB;


    public function __construct (&$session, &$response, &$request, &$DB = null) {
        self::$session = &$session;
        self::$response = &$response;
        self::$request = &$request;
        self::$DB = &$DB;
        self::$prod = getenv("PROD") === true;
        self::$path = self::$prod ? PROD_PATH : DEV_PATH;
        error_reporting(self::$prod ? 0 : (E_ERROR | E_WARNING | E_PARSE | E_NOTICE));
        ini_set('display_errors', (int) self::$prod);
    }

    /**
     * Make a singleton instance of the class
     * Initialize the session, response, request, DB classes
     * @return object instance of the class
     */
    public static function &create():mixed {
        $c = __CLASS__;
        if (!(self::$instance instanceof $c) || !is_null($DB)) {
            $session = new Session(SESSION_NAME, SESSION_LIFETIME);
            $response = new Response();
            $request = new Request();
            $DB = new Db(BDD_HOST, BDD_PORT, BDD_USER, BDD_PWD, BDD_NAME);
            self::$instance = new $c($session, $response, $request, $DB);
        }
        return self::$instance;
    }

    public static function isProd():bool {
        return self::$prod;
    }

    public static function getPath():string {
        return self::$path;
    }
}
