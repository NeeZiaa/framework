<?php

namespace NeeZiaa\Permissions;

use Exception;
use NeeZiaa\Database\DatabaseException;
use NeeZiaa\Database\QueryBuilder;

class User
{

    private array $user = [];
    private string|bool $token = "";

    /**
     * @return array
     * @throws DatabaseException
     */
    public function get_all_users(): array
    {
        return (new QueryBuilder())
            ->select()
            ->table('users')
            ->fetchAll();
    }

    /**
     * @param int $id
     * @return array
     * @throws DatabaseException
     */
    public function get_user_by_id(int $id): array
    {
        return $this->user = (new QueryBuilder())
            ->select()
            ->table('users')
            ->where('id = :id')
            ->params(['id' => $id])
            ->fetch();
    }

    /**
     * @param string $token
     * @return array
     * @throws DatabaseException
     */
    public function get_user_by_token(string $token): array
    {
        return $this->user = (new QueryBuilder())
            ->select()
            ->table('users')
            ->where('token = :token')
            ->params(['token' => $token])
            ->fetch();
    }


    /**
     * @param string $name
     * @return array|bool
     * @throws DatabaseException
     */
    public function get_user_by_name(string $name): array|bool
    {
        return $this->user = (new QueryBuilder())
            ->select()
            ->table('users')
            ->where('username = :username')
            ->params(['username' => $name])
            ->fetch();
    }

    /**
     * @param string $email
     * @return array|bool
     * @throws DatabaseException
     */
    public function get_user_by_email(string $email): array|bool
    {
        return $this->user = (new QueryBuilder())
            ->select()
            ->table('users')
            ->where('email = :email')
            ->params(['email' => $email])
            ->fetch();
    }

    /**
     * @return string
     */
    public function get_token(): string
    {
        if(isset(getallheaders()['Token'])) {
            $this->token = getallheaders()['Token'];
        } else {
            $this->token = "";
        }
        return $this->token;
    }

    /**
     * @return array
     */
    public function get_user(): array
    {
        return $this->user;
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $firstname
     * @param string $lastname
     * @param string|null $token
     * @return void
     * @throws DatabaseException
     * @throws Exception
     */
    public function update_infos(string $username, string $password, string $firstname, string $lastname, string $token = null)
    {

        if(is_null($token)) $this->token = $this->get_token();

        $tokens = $this->get_all_users();
        while(1){
            $new_token = bin2hex(random_bytes(30));
            if(!in_array($new_token, $tokens)) {
                break;
            }
        }

        $user = $this->get_user();

        (new QueryBuilder())
            ->update(['token', 'username', 'password', 'firstname', 'lastname'])
            ->table('users')
            ->where("id = :id")
            ->params([
                'id'=>$user['id'],
                'token'=>$new_token,
                'username'=>$username,
                'password'=>password_hash($password, PASSWORD_ARGON2ID),
                'firstname'=>$firstname,
                'lastname'=>$lastname
            ])
            ->execute();
    }

}