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
 * Classe statique d'accès à la base de données.
 * Cette classe est instanciée et initialisée par la classe Engine
 *
 */
final class Db{
    /**
     * L'object PDO instancié par $engine
     */
    private static PDO $db;

    /**
     * Constructeur appelé par $engine en transmetant l'objet PDO pointant sur la base de données
     * @param object $db l'object PDO instancié par $engine
     */
    //public function __construct(?object &$db) {
    public function __construct (string $host = "", int $port = 3306, string $user = "", string $pwd = "", string $dbname = "") {
        try{
            self::$db = new PDO("mysql:host={$host};port={$port};dbname={$dbname}", $user, $pwd);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            self::$db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
            self::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            self::$db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
            self::$db->query('SET NAMES utf8');
        } catch (PDOException $e) {
            die('Erreur class Database._construct(): ' . $e->getMessage());
        }
    }

    /**
     * Executer une requête de sélection SQL
     * Si les paramêtres sont fournis, la requête est préparée en utilisant les paramêtres nommés ou bien les ?
     * Ex : $query="SELECT * FROM test WHERE ID = ?", $params = [1]
     * Ex : $query="SELECT * FROM test WHERE ID = :id", $params = ["id" -> 1]
     * @param string $query La requête SQL
     * @param array $params Le tableau des paramêtres
     * @return object retourne une seule ligne répondant à la requête ou null si la requête échoue
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
     * Executer une requête de sélection multiple SQL
     * Si les paramêtres sont fournis, la requête est préparée en utilisant les paramêtres nommés ou bien les ?
     * Ex : $query="SELECT * FROM test WHERE ID = ?", $params = [1]
     * Ex : $query="SELECT * FROM test WHERE ID = :id", $params = ["id" -> 1]
     * @param string $query La requête SQL
     * @param array $params Le tableau des paramêtres
     * @return array[object] retourne toutes les lignes répondants à la requête ou null si la requête échoue
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
     * Executer une requête de sélection multiple avec pagination SQL
     * Si les paramêtres sont fournis, la requête est préparée en utilisant les paramêtres nommés ou bien les ?
     * Ex : $query="SELECT * FROM test WHERE ID = ?", $params = [1]
     * Ex : $query="SELECT * FROM test WHERE ID = :id", $params = ["id" -> 1]
     * @param string $query La requête SQL
     * @param array $params Le tableau des paramêtres
     * @param int $start Index du prémier élément à lire
     * @param int $end Index du dernier élément à lire
     * @return array[object] retourne toutes les lignes répondants à la requête ou null si la requête échoue
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
     * Executer une requête d'insertion SQL
     * Si les paramêtres sont fournis, la requête est préparée en utilisant les paramêtres nommés ou bien les ?
     * Ex : $query="SELECT * FROM test WHERE ID = ?", $params = [1]
     * Ex : $query="SELECT * FROM test WHERE ID = :id", $params = ["id" -> 1]
     * @param string $query La requête SQL
     * @param array $params Le tableau des paramêtres
     * @param bool $getid True s'il faut retourné l'ID du dernier élément inséré
     * @return bool|string retourne True si la requête s'est éxécutée et False sinon mais aussi l'ID du dernier élément inséré si $getif == true
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
     * Executer une requête de mise à jour SQL
     * Si les paramêtres sont fournis, la requête est préparée en utilisant les paramêtres nommés ou bien les ?
     * Ex : $query="SELECT * FROM test WHERE ID = ?", $params = [1]
     * Ex : $query="SELECT * FROM test WHERE ID = :id", $params = ["id" -> 1]
     * @param string $query La requête SQL
     * @param array $params Le tableau des paramêtres
     * @return bool retourne True si la requête s'est éxécutée et False sinon
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
     * Executer une requête de suppression SQL
     * Si les paramêtres sont fournis, la requête est préparée en utilisant les paramêtres nommés ou bien les ?
     * Ex : $query="SELECT * FROM test WHERE ID = ?", $params = [1]
     * Ex : $query="SELECT * FROM test WHERE ID = :id", $params = ["id" -> 1]
     * @param string $query La requête SQL
     * @param array $params Le tableau des paramêtres
     * @return bool retourne True si la requête s'est éxécutée et False sinon
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
     * Execute une instruction SQL
     *
     * Permet d'envoyer à la base de données une instructions simple sans parametre
     * et sans retour de données ormis le résultat de l'excution de la fonction (true/false)
     *
     * @param string $query La requête SQL à éxécuter
     * @return bool True si la commande s'est bien éxécutée et False sinon
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
     * Executer une requête non-préparée SQL
     * @param string $query La requête SQL
     * @return bool retourne True si la requête s'est éxécutée et False sinon
     */
    public static function unprepared(string $query):bool {
        $result = null;
        $request = self::$db->query($query);
        if($request){
            $result = $request->execute();
        }
        return $result;
    }
}