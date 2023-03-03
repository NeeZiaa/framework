<?php

namespace NeeZiaa\Database;

use PDO;

class MysqlDatabase extends Database 
{
    public function getPDO($host, $db, $user, $password): PDO
    {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        $pdoOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4', SESSION sql_mode = 'STRICT_ALL_TABLES'"
        ];

        return new PDO($dsn, $user, $password, $pdoOptions);
    }
}
