<?php
namespace NeeZiaa\Database\Mysql;

use NeeZiaa\App;
use NeeZiaa\Database\DatabaseException;

class QueryBuilder
{

    private $action;

    private $colums;

    private $markers;

    private $update;

    private $from;

    private $where = [];

    private $entity;

    private $order = [];

    private $limit;

    private $joins;

    private $db;

    private $params = [];

    private $records;

    private $index = 0;

    /**
     * @throws \NeeZiaa\Database\DatabaseException
     */
    public function __construct(?\PDO $db = null){
        if(is_null($this->db)) $db = (App::getInstance())->getDb();
        $this->db = $db;
    }

    /**
     * Défini la table
     * @param string $table
     * @param string|null $alias
     * @return QueryBuilder
     */
    public function table(string $table, ?string $alias = null): self
    {

        if($alias) {
            $this->from[$alias] = $table;
        } else {
            $this->from[] = $table;
        }

        return $this;
    }

    /**
     * Définie les colonnes séléctionnées
     * @param string $fiels
     * @return QueryBuilder
     */

    public function select(string ...$fiels): self
    {
        $this->select = $fiels;
        $this->action = "SELECT";
        return $this;
    }


    /**
     * Définie les colonnes a insérer
     * @param array $values
     * @return QueryBuilder
     */

    public function insert(array $values): self
    {
        $columns = '';
        $markers = '';
        foreach ($values as $key ) {
            $columns .= "`{$key}`, ";
            $markers .= ":{$key}, ";
        }
        $columns = trim($columns, ', ');
        $markers = trim($markers, ', ');
        $request = 'INSERT INTO `[TABLE_NAME]` (' . $columns . ') VALUES (' . $markers . ')';
        $this->colums = $columns;
        $this->markers = $markers;
        $this->action = "INSERT";
        return $this;
    }

    /**
     * Définie les colonnes a modifier
     * @param array $values
     * @return QueryBuilder
     */

    public function update($values): self
    {
        $request = '';
        foreach ($values as $key) {
            $request .= "`{$key}` = :{$key}, ";
        }
        $this->update = trim($request, ', ');
        $this->action = "UPDATE";
        return $this;
    }

    /**
     * Définie la/les ligne(s) a supprimer
     * @param array $values
     * @return QueryBuilder
     */

    public function delete(): self
    {
        $this->action = "DELETE";
        return $this;
    }


    /**
     * Spécifie la limite
     * @param int $lenght
     * @param int $offset
     * @return QueryBuilder
     */

    public function limit(int $lenght, int $offset = 0): self
    {
        $this->limit = "$offset, $lenght";
        return $this;
    }

    /**
     * Spécifie l'ordre
     * @param string $orders
     * @return QueryBuilder
     */

    public function order(string $order): self
    {
        $this->order[] = $order;
        return $this;
    }

    /**
     * @param string $table
     * @param string $condtion
     * @param string $type
     * @return QueryBuilder
     */

    public function join(string $table, string $condition, string $type = "left"): self
    {
        $this->join[$type][] = [$table, $condition];
        return $this;
    }

    /**
     * Définie les conditions
     * @param string ...$condition
     * @return QueryBuilder
     */

    public function where(string ...$condition): self
    {
        $this->where = array_merge($this->where, $condition);
        return $this;
    }

    /**
     * Compte le nombre de lignes
     * @return QueryBuilder
     */

    public function count(): int
    {
        $this->select = ["COUNT(id)"];
        return $this->execute()->fetchColumn();

    }

    /**
     * Spécifie les paramètres
     * @param array $params
     * @return QueryBuilder
     */

    public function params(array $params): self
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    /**
     * Retourne un tableau
     * @param string $mode
     * @param QueryBuilder
     */

    public function fetch(?string $mode = "FETCH_ASSOC"): array
    {
        if(is_null($this->records)){
            $mode = constant("\PDO::".strtoupper($mode));
            $this->records = $this->execute()->fetch('\PDO::'.$mode);
        }
        return $this->records;

    }


    /**
     * Retourne un tableau
     * @param string $mode
     * @param QueryBuilder
     */

    public function fetchAll(?string $mode = "FETCH_ASSOC"): array
    {
        if(is_null($this->records)){
            $mode = constant("\PDO::".strtoupper($mode));
            $this->records = $this->execute()->fetchAll($mode);
        }
        return $this->records;
    }


    /**
     * Convertie la requête sous forme de string
     */

    public function __toString()
    {

        if($this->action === "SELECT"){

            $parts = ['SELECT'];
            if($this->select){
                $parts[] = join(', ', $this->select);
            } else {
                $parts[] = '*';
            }

            $parts[] = 'FROM';
            $parts[] = $this->buildFrom();

            if(!empty($this->joins)) {
                foreach($this->joins as $type => $joins) {
                    foreach($joins as [$table, $condition]){
                        $parts[] = strtoupper($type) . " JOIN $table ON $condition";
                    }
                }
            }

            if(!empty($this->where)){
                $parts[] = "WHERE";
                $parts[] = "(" . join(') AND (', $this->where) . ')';
            }

            if(!empty($this->order)){
                $parts[] = 'ORDER BY';
                $parts[] = join(', ', $this->order);
            }

            if(!empty($this->limit)){
                $parts[] = 'LIMIT ' . $this->limit;
            }

            return join(' ', $parts);

        } elseif($this->action === "INSERT") {

            $parts = ['INSERT INTO'];

            $parts[] = $this->buildFrom();

            //dd($this->markers, $this->colums, $this->buildFrom(), $this->params);

            $parts[] = '('.$this->colums.')';
            $parts[] = 'VALUES('.$this->markers.')';

            return join(' ', $parts);


        } elseif($this->action === "UPDATE") {

            $parts = ['UPDATE'];

            $parts[] = $this->buildFrom().' SET';

            $parts[] = $this->update;
            //dd($parts);

            if(!empty($this->where)){
                $parts[] = "WHERE";
                $parts[] = "(" . join(') AND (', $this->where) . ')';
            }

            return join(' ', $parts);


        } elseif($this->action === "DELETE") {

            $parts = ['DELETE FROM'];
            $parts[] = $this->buildFrom();

            if(!empty($this->where)){
                $parts[] = "WHERE";
                $parts[] = "(" . join(') AND (', $this->where) . ')';
            }

            return join(' ', $parts);

        } else {

            if(is_null($this->action)) {
                throw new DatabaseException("Undefined SQL statement, please define it", 1);
            } else {
                throw new DatabaseException("Unknow SQL statement", 1);
            }

        }



    }

    private function buildFrom(): string
    {
        $from = [];
        foreach ($this->from as $key => $value) {
            if(is_string($key)){
                $from[] = "value as $key";

            } else {
                $from[] = $value;
            }

        }
        return join(', ', $from);
    }

    /**
     * Execute la requête
     */

    public function execute()
    {
        $query = $this->__toString();
        if(!empty($this->params)){
            $statement = $this->db->prepare($query);
            $statement->execute($this->params);
            return $statement;
        }

        return $this->db->query($query);


    }


}