<?php

namespace utils;

class Validator
{
    public static function sanitize_json_input($input)
    {
        // Remove caracteres que podem quebrar o JSON antes de enviar para a API
        return str_replace(['"', "\n", "\r"], ["'", ' ', ' '], $input);
    }

    public static function validate_api_key($key)
    {
        return (bool)preg_match('/^[a-zA-Z0-9_-]{30,}$/', $key);
    }
}