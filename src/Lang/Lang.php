<?php
namespace NeeZiaa\Lang;
use NeeZiaa\Components\ConfigLoader;

class Lang {

    private static string $currentLang;

    public static function translate(string $translate, string $lang = "english", string $domain = "") {
        return $lang = (new ConfigLoader("lang", $lang . ".yaml"))->get();
    }

    public static function register(array $translate)
    {
        return (new ConfigLoader("lang", $lang . ".yaml"))->write($translate)
    }

    public static function setLang(string $lang)
    {
        self::$currentLang = $lang;
    }

    public static function auto() {
        self::$currentLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    }

}