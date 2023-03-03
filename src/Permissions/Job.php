<?php

namespace NeeZiaa\Permissions;

use NeeZiaa\Database\DatabaseException;
use NeeZiaa\Database\QueryBuilder;

class Job
{

    /**
     * @throws DatabaseException
     */
    public function get_all_jobs(): array
    {
        return (new QueryBuilder())
            ->select()
            ->table('jobs')
            ->fetchAll();
    }

    /**
     * @param int $id
     * @return array
     * @throws DatabaseException
     */
    public function get_job_by_id(int $id): array
    {
        return (new QueryBuilder())
            ->select()
            ->table('jobs')
            ->where('id = :id')
            ->params(['id'=>$id])
            ->fetch();
    }

    /**
     * @param string $name
     * @return array
     * @throws DatabaseException
     */
    public function get_job_by_name(string $name): array
    {
        return (new QueryBuilder())
            ->select()
            ->table('jobs')
            ->where('name = :name')
            ->params(['name'=>$name])
            ->fetch();
    }

    /**
     * @param int $id
     * @return array
     * @throws DatabaseException
     */
    public function get_job_by_user_id(int $id): array
    {
        $user = (new User())->get_user_by_id($id);
        if(empty($user)) return [];
        return $this->get_job_by_id($user['job']);
    }

    /**
     * @param string $username
     * @return array
     * @throws DatabaseException
     */
    public function get_job_by_username(string $username): array
    {
        $user = (new User())->get_user_by_name($username);
        if(empty($user)) return [];
        return $this->get_job_by_id($user['job']);
    }

    /**
     * @throws DatabaseException
     */
    public function get_all_ranks(): array
    {
        return (new QueryBuilder())
            ->select()
            ->table('jobs_ranks')
            ->fetchAll();
    }

    /**
     * @param int $id
     * @return array
     * @throws DatabaseException
     */
    public function get_rank_by_id(int $id): array
    {
        return (new QueryBuilder())
            ->select()
            ->table('jobs_ranks')
            ->where('id = :id')
            ->params(['id'=>$id])
            ->fetch();
    }

    /**
     * @param string $name
     * @return array
     * @throws DatabaseException
     */
    public function get_rank_by_name(string $name): array
    {
        return (new QueryBuilder())
            ->select()
            ->table('jobs_ranks')
            ->where('name = :name')
            ->params(['name'=>$name])
            ->fetch();
    }

    /**
     * @param int $id
     * @return array
     * @throws DatabaseException
     */
    public function get_rank_by_user_id(int $id): array
    {
        $user = (new User())->get_user_by_name($id);
        if(empty($user)) return [];
        return $this->get_job_by_id($user['rank']);
    }

    /**
     * @param string $username
     * @return array
     * @throws DatabaseException
     */
    public function get_rank_by_username(string $username): array
    {
        $user = (new User())->get_user_by_name($username);
        if(empty($user)) return [];
        return $this->get_job_by_id($user['rank']);
    }

}