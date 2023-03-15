<?php
namespace NeeZiaa\Lang;

class Lang {

    private static string $currentLang;

    public static function translate(string $translate, string $lang = "english", string $domain = "") {
        $lang = new Parser(new ConfigLoader("admin/"));
    }

    public static function register(array $translate)
    {
        
    }

    public function auto() {
        self::$currentLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    }

}