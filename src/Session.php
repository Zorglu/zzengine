<?php declare (strict_types=1);

namespace zzengine\App;

/**
 * Gestion des variables de session
 */
final class Session{
    private static string $id = "";

    /**
     * Initialise la class avec le nom de la session souhaité
     * @params string $session_name Nom de la session
     * @params int $life_time Durée de vie du coockie de session
     */
    public function __construct (?string $session_name, int $life_time = 86400) {
        try{
            session_name($session_name);
            session_set_cookie_params(0, '/');
            self::$id = session_start(['cookie_lifetime' => $life_time]) ? session_id() : null;
        }catch (Exception $e) {
            die('Erreur class Session._construct(): ' . $e->getMessage());
        }
    }

    /**
     * retourne l'identifiant de session acquis avec session_id()
     * @return string L'ID de session
     */
    public static function getId():string {
        return self::$id;
    }

    /**
     * Retourne la valeur d'une variable de session
     * si la session ou la variable existe
     * @param string $var_name Nom de la variable
     * @return mixed la valeur de la variable ou NULL si elle n'existe pas
     */
    public static function getValue(string $var_name):mixed {
        if (isset($_SESSION[$var_name])) {
            return $_SESSION[$var_name];
        } else {
            return null;
        }
    }

    /**
     * Range une valeur dans une variable de session
     * @param string $var_name Le nom de la variable
     * @param mixed la valeur à stocker
     * @return void
     */
    public static function setValue(string $var_name, mixed $value):void {
        $_SESSION[$var_name] = $value;
    }

    /**
     * Supprime une variable de session
     * @param string $var_name Le nom de la variable
     * @return void
     */
    public static function removeValue(string $var_name):void {
        if (isset($_SESSION[$var_name])){
            unset($_SESSION[$var_name]);
        }
    }
}
