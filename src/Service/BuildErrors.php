<?php

namespace Riotoon\Service;

class BuildErrors
{
    private static array $errors = [];

    public static function setErrors(string $key, $value) {
        self::$errors[$key] = $value;
    }

    public static function getErrors(): ?array
    {
        return self::$errors;
    }
}