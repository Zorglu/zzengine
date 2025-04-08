<?php declare (strict_types=1);

namespace zzengine\App;

/**
 * Traitement des requettes HTTP, GET et POST
 * Une sanitisation est effectuée afin d'éviter tout codes malveillant envoyé par ces variables
 *
 */
final class Request{

    const HTTP_GET = "GET";
    const HTTP_POST = "POST";

    private static array $request = []; //Contient la liste des requettes GET et POST

    /**
     * constructor
     *
     */
    public function __construct(){
        $this->sanitize();
    }

    /**
     * Sanitisation des requetes et rangement dans le tableau des requetes
     * Le nom, la valeur et la methode sont rangés dans le tableau
     */
    private function sanitize(): void {

        foreach($_GET as $key => $val){
            self::$request[] = (object) array("method" => Request::HTTP_GET, "name" => $key, "value" => trim(htmlspecialchars($val, ENT_QUOTES, "UTF-8")));
        }
        foreach($_POST as $key => $val){
            self::$request[] = (object) array("method" => Request::HTTP_POST, "name" => $key, "value" => trim(htmlspecialchars($val, ENT_QUOTES, "UTF-8")));
        }
    }

    /**
     *
     * Retourne la valeur du parametre fournis
     * @param string $name Nom du parametre
     * @param string $method Nom de la méthode utilisée.
     * Si la méthode est vide, alors elle n'est pas prise en compte. Sinon la méthode devra être la bonne.
     * Ex : si le parametre ID est demandé avec la methode POST mais qu'il a été transmis avec la methode GET celui ci ne sera pas valide
     * @param string $filter nom du filtre à utiliser pour la validation
     * Ex :  FILTER_VALIDATE_EMAIL pour que le parametre corresponde bien au format email
     * Ex : FILTER_VALIDATE_INT pour un entier valide
     * @param array $filter_options des Parametres complémentaires pour le filtrage
     * Ex : ["flags" => FILTER_NULL_ON_FAILURE, "options" => ["min_range" => 1] pour que le parametre fournis soit Sup à 0
     * @return mixed la valeur du parametre demandé ou null si pas valide ou non trouvé
     *
     */
    public static function get(string $name, string $method = "", int $filter = 0, array $filter_options = ["flags" => FILTER_NULL_ON_FAILURE]): mixed {
        $find = null;
        foreach(self::$request as $key => $val) {
            if($method == ""){
                if($val->name === $name){
                    $find = $val->value;
                    break;
                }
            }else{
                if($val->name === $name && $val->method === $method){
                    $find = $val->value;
                    break;
                }
            }
        }
        if($filter !== 0){
            $find = filter_var($find, $filter, $filter_options);
        }
        return $find;
    }

    /**
     * Retourne un entier du parametre fournis
     * @param string $name Nom du parametre
     * @param string $method Nom de la méthode utilisée.
     * Si la méthode est vide, alors elle n'est pas prise en compte. Sinon la méthode devra être la bonne.
     * @return mixed la valeur du parametre demandé ou null si pas valide ou non trouvé
     */
    public static function getInt(string $name, string $method = ""):mixed {
        return self::get($name, $method, FILTER_VALIDATE_INT);
    }

    /**
     * Retourne un float du parametre fournis
     * @param string $name Nom du parametre
     * @param string $method Nom de la méthode utilisée.
     * Si la méthode est vide, alors elle n'est pas prise en compte. Sinon la méthode devra être la bonne.
     * @return mixed la valeur du parametre demandé ou null si pas valide ou non trouvé
     */
    public static function getFloat(string $name, string $method = ""):mixed {
        return self::get($name, $method, FILTER_VALIDATE_FLOAT);
    }

    /**
     * Retourne un email du parametre fournis
     * @param string $name Nom du parametre
     * @param string $method Nom de la méthode utilisée.
     * Si la méthode est vide, alors elle n'est pas prise en compte. Sinon la méthode devra être la bonne.
     * @return mixed la valeur du parametre demandé ou null si pas valide ou non trouvé
     */
    public static function getEmail(string $name, string $method = ""):mixed {
        return self::get($name, $method, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Retourne un booleen du parametre fournis
     * @param string $name Nom du parametre
     * @param string $method Nom de la méthode utilisée.
     * Si la méthode est vide, alors elle n'est pas prise en compte. Sinon la méthode devra être la bonne.
     * @return mixed la valeur du parametre demandé ou null si pas valide ou non trouvé
     */
    public static function getBool(string $name, string $method = ""):mixed {
        return self::get($name, $method, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Retourne un regexp du parametre fournis
     * @param string $name Nom du parametre
     * @param string $method Nom de la méthode utilisée.
     * Si la méthode est vide, alors elle n'est pas prise en compte. Sinon la méthode devra être la bonne.
     * @return mixed la valeur du parametre demandé ou null si pas valide ou non trouvé
     */
    public static function getRegexp(string $name, string $method = ""):mixed {
        return self::get($name, $method, FILTER_VALIDATE_REGEXP);
    }

    /**
     * Retourne une URL du parametre fournis
     * @param string $name Nom du parametre
     * @param string $method Nom de la méthode utilisée.
     * Si la méthode est vide, alors elle n'est pas prise en compte. Sinon la méthode devra être la bonne.
     * @return mixed la valeur du parametre demandé ou null si pas valide ou non trouvé
     */
    public static function getUrl(string $name, string $method = ""):mixed {
        return self::get($name, $method, FILTER_VALIDATE_URL);
    }

    /**
     * Retourne un domain du parametre fournis
     * @param string $name Nom du parametre
     * @param string $method Nom de la méthode utilisée.
     * Si la méthode est vide, alors elle n'est pas prise en compte. Sinon la méthode devra être la bonne.
     * @return mixed la valeur du parametre demandé ou null si pas valide ou non trouvé
     */
    public static function getDomain(string $name, string $method = ""):mixed {
        return self::get($name, $method, FILTER_VALIDATE_DOMAIN);
    }

    /**
     * Retourne une IP du parametre fournis
     * @param string $name Nom du parametre
     * @param string $method Nom de la méthode utilisée.
     * Si la méthode est vide, alors elle n'est pas prise en compte. Sinon la méthode devra être la bonne.
     * @return mixed la valeur du parametre demandé ou null si pas valide ou non trouvé
     */
    public static function getIp(string $name, string $method = ""):mixed {
        return self::get($name, $method, FILTER_VALIDATE_IP);
    }

    /**
     * Retourne une adresse MAC du parametre fournis
     * @param string $name Nom du parametre
     * @param string $method Nom de la méthode utilisée.
     * Si la méthode est vide, alors elle n'est pas prise en compte. Sinon la méthode devra être la bonne.
     * @return mixed la valeur du parametre demandé ou null si pas valide ou non trouvé
     */
    public static function getMac(string $name, string $method = ""):mixed {
        return self::get($name, $method, FILTER_VALIDATE_MAC);
    }
}