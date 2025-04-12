<?php declare (strict_types=1);
/**
 * @version 1.0.0.0
 * @author Zozor
 *
 *
 */
namespace zzengine\App;
use PDO;

/**
 * Static class for database access.
 * This class is instantiated and initialized by the Engine class.
 *
 */
final class Db{
    /**
     * The PDO object instantiated by $engine
     */
    private static PDO $db;

    /**
     * Constructor called by $engine, passing the PDO object pointing to the database.
     * @param string $host
     * @param int $port
     * @param string $user
     * @param string $pwd
     * @param string $dbname
     */
    public function __construct (string $host = "", int $port = 3306, string $user = "", string $pwd = "", string $dbname = "") {
        try{
            self::$db = new PDO("mysql:host={$host};port={$port};dbname={$dbname}", $user, $pwd);
            foreach(ATTR_EMULATE_PREPARES as $key => $value){
                self::$db->setAttribute($key, $value);
            }
            self::$db->query('SET NAMES ' . BDD_CHARSET);
        } catch (PDOException $e) {
            die('Error class Database._construct(): ' . $e->getMessage());
        }
    }

    /**
     * get the PDO instance
     * @return PDO instance of the PDO instance
     */
    static public function getDb():PDO{
        return self::$db;
    }

    /**
     * Execute a SQL select query.
     * If parameters are provided, the query is prepared using named parameters or question marks.
     * Ex: $query="SELECT * FROM test WHERE ID = ?", $params = [1]
     * Ex: $query="SELECT * FROM test WHERE ID = :id", $params = ["id" -> 1]
     * @param string $query The SQL query.
     * @param array $params The parameter array.
     * @return object Returns a single row matching the query, or null if the query fails.
     */
    public static function select(string $query, array $params = []): object|null{
        $result = null;
        $request = self::$db->prepare($query);
        if($request){
            $request->execute($params);
            $result = $request->fetch();
        }
        return $result;
    }

    /**
     * Execute a SQL multiple select query.
     * If parameters are provided, the query is prepared using named parameters or question marks.
     * Ex: $query="SELECT * FROM test WHERE ID = ?", $params = [1]
     * Ex: $query="SELECT * FROM test WHERE ID = :id", $params = ["id" -> 1]
     * @param string $query The SQL query.
     * @param array $params The parameter array.
     * @return array[object] Returns all rows matching the query, or null if the query fails.
     */
    public static function selectAll(string $query, array $params = []):object|null {
        $result = null;
        $request = self::$db->prepare($query);
        if($request){
            $request->execute($params);
            $result = $request->fetchAll();
        }
        return $result;
    }

    /**
     * Execute a SQL multiple select query with pagination.
     * If parameters are provided, the query is prepared using named parameters or question marks.
     * Ex: $query="SELECT * FROM test WHERE ID = ?", $params = [1]
     * Ex: $query="SELECT * FROM test WHERE ID = :id", $params = ["id" -> 1]
     * @param string $query The SQL query.
     * @param array $params The parameter array.
     * @param int $start Index of the first element to read.
     * @param int $end Index of the last element to read.
     * @return array[object] Returns all rows matching the query, or null if the query fails.
     */
    public static function pagination(string $query, array $params = [], int $start = 0, int $end = 10):array|null{
        $result = null;
        $request = self::$db->prepare($query . " LIMIT {$start}, {$end}");
        if($request){
            $request->execute($params);
            $result = $request->fetchAll();
        }
        return $result;
    }

    /**
     * Execute a SQL insert query.
     * If parameters are provided, the query is prepared using named parameters or question marks.
     * Ex: $query="SELECT * FROM test WHERE ID = ?", $params = [1]
     * Ex: $query="SELECT * FROM test WHERE ID = :id", $params = ["id" -> 1]
     * @param string $query The SQL query.
     * @param array $params The parameter array.
     * @param bool $getid True if the ID of the last inserted element should be returned.
     * @return bool|string Returns True if the query was executed, False otherwise, and the ID of the last inserted element if $getid == true.
     */
    public static function insert(string $query, array $params = [], bool $getid = false):bool|string {
        $result = false;
        $request = self::$db->prepare($query);
        if($request){
            $result = $request->execute($params);
            if($getid){
                $result = self::$db->lastInsertId();
            }
        }
        return $result;
    }

    /**
     * Execute a SQL update query.
     * If parameters are provided, the query is prepared using named parameters or question marks.
     * Ex: $query="SELECT * FROM test WHERE ID = ?", $params = [1]
     * Ex: $query="SELECT * FROM test WHERE ID = :id", $params = ["id" -> 1]
     * @param string $query The SQL query.
     * @param array $params The parameter array.
     * @return bool Returns True if the query was executed, False otherwise.
     */
    public static function update(string $query, array $params = []):bool {
        $result = false;
        $request = self::$db->prepare($query);
        if($request){
            $result = $request->execute($params);
        }
        return $result;
    }

    /**
     * Execute a SQL delete query.
     * If parameters are provided, the query is prepared using named parameters or question marks.
     * Ex: $query="SELECT * FROM test WHERE ID = ?", $params = [1]
     * Ex: $query="SELECT * FROM test WHERE ID = :id", $params = ["id" -> 1]
     * @param string $query The SQL query.
     * @param array $params The parameter array.
     * @return bool Returns True if the query was executed, False otherwise.
     */
    public static function delete(string $query, array $params = []):bool {
        $result = false;
        $request = self::$db->prepare($query);
        if($request){
            $result = $request->execute($params);
        }
        return $result;
    }

    /**
     * Execute a SQL statement.
     *
     * Allows sending a simple instruction to the database without parameters
     * and without returning data, except for the result of the function execution (true/false).
     *
     * @param string $query The SQL query to execute.
     * @return bool True if the command was executed successfully, False otherwise.
     */
    public static function statement(string $query): bool{
        $result = false;
        $request = self::$db->prepare($query);
        if($request){
            $result = $request->execute();
        }
        return $result;
    }

    /**
     * Execute a non-prepared SQL query.
     * @param string $query The SQL query.
     * @return bool Returns True if the query was executed, False otherwise.
     */
    public static function unprepared(string $query):bool {
        $result = null;
        $request = self::$db->query($query);
        if($request){
            $result = $request->execute();
        }
        return $result;
    }

    /**
     * Prepares a statement for execution
     * @param string $query a SQL statement template
     * @return PDOStatement|bool Returns PDOStatement on success or false on failure
     */
    public static function prepared(string $query) {
        return self::$db->prepare($query);
    }

    /**
     *  Executes a prepared statement
     * @param PDOStatement $request a PDO Statement gattered with ::prepared() method
     * @param array An array of values with as many elements as there are bound parameters with prepared query
     * @return bool Returns true on success or false on failure
     */
    public static function execute(mixed $request, array $params = []):bool {
        return $request->execute($params);
    }

    /**
     * Initiates a transaction
     * @return bool Returns true on success or false on failure
     */
    public static function beginTransaction():bool {
        return self::$db->beginTransaction();
    }

    /**
     *  Commits a transaction
     * @return bool Returns true on success or false on failure
     */
    public static function commit():bool {
        return self::$db->commit();
    }

    /**
     * Rolls back a transaction
     * @return bool Returns true on success or false on failure
     */
    public static function rollBack(): bool{
        return self::$db->rollBack();
    }

}