<?php

namespace NeeZiaa\Permissions;


use NeeZiaa\App;
use NeeZiaa\Database\DatabaseException;
use NeeZiaa\Utils\JSONData;

class Permission
{

    private App $app;
    private User $user;
    private ?Job $job;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->app = App::getInstance();
        $this->job = $this->app->getJob();
        $this->user = $user;
    }

    /**
     * @param string $permission
     * @param string|null $token
     * @return bool
     * @throws DatabaseException
     */
    public function can(string $permission, string $token = null): bool
    {

        if(empty($this->user)) return false;
        if($this->is_admin()) return true;

        $rank = $this->job->get_rank_by_user_id($this->user->get_user()['id']);

        if (in_array($permission, json_decode($rank['permissions']))) {
            return true;
        }
        return false;
    }

    /**
     * @param bool $assoc
     * @return bool|string|array
     */
    public function all_permissions(bool $assoc = false): bool|string|array
    {
        $items = new JSONData('items.json');

        if($assoc) return $items->get_array();
        return $items->get_raw();
    }

    /**
     * @param string|null $token
     * @return bool
     * @throws DatabaseException
     */
    public function is_admin(string $token = null): bool
    {
        $user = $this->user->get_user();
        if(!is_null($token)) $user = $this->user->get_user_by_token($token);

        // User not found
        if(empty($user)) return false;

        // Always return true if user is admin
        if($user['admin'] === 1) return true;

        return false;
    }

    /**
     * @param User|null $user
     * @return array
     * @throws DatabaseException
     */
    public function get_user_permissions(User $user = null): array
    {
        $ranks = $this->job->get_all_ranks(true);

        if(!is_null($user)) $this->user = $user;
        if(empty($this->user->get_user())) return [];

        $rank = $this->job->get_rank_by_id(
            $this->user->get_user()['rank']
        );
        if(empty($rank)) return [];
        return json_decode($rank['permissions']);
    }

}