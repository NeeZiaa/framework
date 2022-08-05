<?php
namespace NeeZiaa\Database\Mysql;

use NeeZiaa\Database\Database;
use PDO;

class MysqlDatabase extends Database {

    public function getDB($host, $db, $user, $password): PDO
    {
        return (new PDO('mysql:dbname=' . $db . ';host=' . $host, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]));

    }

}