<?php
namespace NeeZiaa\Models;

use NeeZiaa\Database\QueryBuilder;
use NeeZiaa\Main;

class TokenModel {

    /**
     * vérifie un token
     * @param string $token
     * @return boolean
     */

    public function verify(string $token) 
    {
    
        $api_tokens = (new QueryBuilder())
            ->select('token')
            ->table('api_keys')
            ->where('ip = :ip')
            ->params(['ip'=>Main::getIp()])
            ->fetch();
        
        if(in_array($token, $api_tokens)) return true; else return false;

    }

    public function create() 
    {

        $token = (new QueryBuilder)
            ->insert(['token'])
            ->table('api_keys')
            ->params(['token'=>bin2hex(random_bytes(20))])
            ->execute();

        return $token;

    }

    public function regenerate(int $id) 
    {

        $token = (new QueryBuilder)
            ->update(['token'])
            ->table('api_key')
            ->where('id = :id')
            ->params(['token'=>bin2hex(random_bytes(20)), 'id'=>$id])
            ->execute();

        return $token;

    }

}