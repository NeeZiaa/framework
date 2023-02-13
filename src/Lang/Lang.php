<?php
namespace NeeZiaa\Lang;

class Lang {

    private static string $currentLang;

    public function translate(string $translate, string $lang = null) {
        if(is_null($lang)) {

        }
    }

    public function register(array $translate)
    {

    }

    public function auto() {
        self::$currentLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    }

}