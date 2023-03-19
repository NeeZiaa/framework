<?php

namespace NeeZiaa;

use NeeZiaa\Components\ConfigLoader;
use NeeZiaa\Components\Parser;
use NeeZiaa\Database\QueryBuilder;

class User {

    private int $id = 0;
    private string $email;
    private string $token;
    private string $hashedPassword;
    private string $firstname;
    private string $lastname;
    private array $permissions;

    private static array $config;

    public function __construct()
    {
        self::$config = (new ConfigLoader('security/config'))->get()['user'];
    }

    public function getByToken(string $token)
    {
        return $user = (new QueryBuilder())
            ->select()
            ->table('users')
            ->where('token = :token')
            ->params(['token' => $token]);
    }

    public function getByID(int $id)
    {
        return $this->setPropsFromArray(
             (new QueryBuilder())
                ->select()
                ->table('users')
                ->where('id = :id')
                ->params(['id' => $id])
                ->fetch()
        );
    }

    public function getByEmail(string $email)
    {
        return $this->setPropsFromArray(
            (new QueryBuilder())
                ->select()
                ->table('users')
                ->where('email = :email')
                ->params(['email' => $email])
                ->fetch()
        );
    }

    public function setProps(string $email, string $password, string $firstname, string $lastname, array|string $permissions)
    {
        $this->email = $email;
        $this->hashedPassword = password_hash($password, self::$config['passwordHash']);
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->permissions = $permissions;
        return $this;
    }

    /**
     * @param array $user
     * @return $this
     * @throws Stream\ParserException
     */
    private function setPropsFromArray(array $user)
    {
        $this->email = $user['email'];
        $this->hashedPassword = password_hash($user['password'], constant(self::$config['passwordHash']));
        $this->firstname = $user['firstname'];
        $this->lastname = $user['lastname'];
        $this->permissions = (new Parser($user['permissions']))->getArray();

        return $this;
    }

    public function getProps()
    {
        dd($this);
        return $this;
    }

    /**
     * @return $this
     * @throws Database\DatabaseException
     * @throws Stream\ParserException
     */
    public function create() {
        (new QueryBuilder())
            ->insert(['token', 'email', 'password', 'lastname', 'firstname', 'permissions'])
            ->table('users')
            ->params([
                'token'=>$this->token,
                'email'=>$this->email,
                'password'=>$this->hashedPassword,
                'lastname'=>$this->lastname,
                'firstname'=>$this->firstname,
                'permissions'=>(new Parser($this->permissions))->getJson()
            ])
            ->execute();
        return $this;
    }



    /**
     * @throws \Exception
     */
    // TODO unique token
    private static function generateUniqueToken()
    {
        return bin2hex(
            random_bytes(self::$config['tokenLength'])
        );
    }

//    public function get

}