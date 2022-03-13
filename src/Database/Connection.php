<?php
namespace NeeZiaa\Database;

use PDO;
use NeeZiaa\main;

class Connection {

    public static function getPDO (): PDO 
    {
        
        $env = main::env();

        return new PDO('mysql:dbname='.$env['DATABASE'].';host='.$env['HOST'], $env['USERNAME'], $env['PASSWORD'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

    }

}