<?php declare (strict_types=1);

namespace zzengine\App;
/**
 * Gestion des réponses à destination du client web
 */
final class Response{
    const ERROR = 100;
    const NOK = Response::ERROR;
    const OK = 200;
    const BAD_LOGIN = 301;

    /**
     * retourne un tableau json_encodé avec les données transmises, un code d'erreur et un timestamp
     * public function getJson($data, int $code = Response::OK): string
     * @param any $data données à afficher
     * @param int $code code d'erreur à afficher
     * @return string json encodé en string contenant les les données
     *
     */
    public static function json($data, int $code = Response::OK): string {
        return json_encode([
            "code" => $code,
            "data" => ! is_null($data) ? $data : null,
            "ts" => time() * 1000
        ]);
    }
}
