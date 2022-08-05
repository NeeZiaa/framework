<?php

namespace App\Models;

use NeeZiaa\Database\Mysql\QueryBuilder;

class ExampleModel
{

    public function example(): array
    {
        return (new QueryBuilder())
            ->select()
            ->table('example')
            ->fetchAll();
    }

}