<?php

namespace Riotoon\Service;

class BuilderError
{
    private static array $errors = [];

    public static function setErrors(string $key, $value)
    {
        self::$errors[$key] = $value;
    }

    public static function getErrors(): ?array
    {
        return self::$errors;
    }

    public static function isErrors(mixed $errors): bool {
        if(empty($errors)) {
            http_response_code(422);
            return true;
        } else
            return false;
    }
}
