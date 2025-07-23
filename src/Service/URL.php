<?php

namespace Riotoon\Service;

class URL
{
    /**
     * Retrieve the value of the parameter from the URL as an integer.
     * Return the integer value of the parameter
     * 
     * @param string $name The name of the parameter in the URL.
     * @param ?int $default The default value if the parameter is not set (optional).
     * @return ?int The integer value of the parameter or null if not set.
     * @throws \Exception If the parameter value is not a valid integer.
     */
    public static function getInt(string $name, ?int $default = null): ?int
    {
        //si le paramètre n'existe pas en retourne alors la valeur par defaut
        if (!isset($_GET[$name]))
            return $default;

        //si le paramètre est égal à zéro en retourne 0
        if ($_GET[$name] === '0')
            return 0;

        if (!filter_var($_GET[$name], FILTER_VALIDATE_INT)) {
            throw new \Exception("Le paramètre dans l'url '$name' n'est pas un entier");
        }

        return (int) $_GET[$name];
    }

    /**
     * Retrieve the value of the parameter from the URL as a positive integer.
     * Return the positive integer value of the parameter
     * 
     * @param string $name The name of the parameter in the URL.
     * @param ?int $default The default value if the parameter is not set (optional).
     * @return ?int The positive integer value of the parameter or null if not set.
     * @throws \Exception If the parameter value is not a positive integer.
     */
    public static function getPositiveInt(string $name, ?int $default): ?int
    {
        // Obtenez la valeur entière du paramètre à l'aide de la méthode getInt
        $param = self::getInt($name, $default);

        // Vérifiez si la valeur du paramètre n'est pas nulle et non positive
        if ($param !== null && $param <= 0) {
            throw new \Exception("Le paramètre dans l'url '$name' n'est pas un nombre positive");
        }

        return $param;
    }
}
