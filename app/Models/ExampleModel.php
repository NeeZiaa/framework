<?php

namespace App\Models;

use NeeZiaa\Database\QueryBuilder;

class ExampleModel
{

    public static function example(): array
    {
        return (new QueryBuilder())
            ->select()
            ->table('example')
            ->fetchAll();
    }

}