<?php
namespace NeeZiaa\Database;

use NeeZiaa\App;
use NeeZiaa\Database\DatabaseException;
use PDO;
use phpDocumentor\Reflection\Types\Callable_;

class QueryBuilder
{

    private string $action;

    private string $columns;

    private mixed $markers;

    private string $update;

    private mixed $from = null;

    private array $where = [];

    private mixed $entity;

    private array $order = [];

    private mixed $limit;

    private mixed $joins;

    private mixed $db = null;

    private array $params = [];

    private mixed $records = null;

    private int $index = 0;
    /**
     * @var string[]
     */
    private array $select;


    public function __construct(?PDO $db = null){
        if(is_null($this->db)) $this->db = App::getInstance()->getDb(); else $this->db = $db;
        
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
            $this->from .= $table;
        }

        return $this;
    }

    /**
     * Définie les colonnes sélectionnées
     * @param string ...$fiels
     * @return QueryBuilder
     */

    public function select(string ...$fiels): self
    {
        $this->select = $fiels;
        $this->action = "SELECT";
        return $this;
    }


    /**
     * Définie les colonnes à insérer
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
        $this->columns = $columns;
        $this->markers = $markers;
        $this->action = "INSERT";
        return $this;
    }

    /**
     * Définie les colonnes a modifier
     * @param array $values
     * @return QueryBuilder
     */

    public function update(array $values): self
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
     * @param string $order
     * @return QueryBuilder
     */

    public function order(string $order): self
    {
        $this->order[] = $order;
        return $this;    
    }

    /**
     * @param string $table
     * @param string $condition
     * @param string $type
     * @return QueryBuilder
     */

    public function join(string $table, string $condition, string $type = "left"): self
    {
        $this->join[$type][] = [$table, $condition];
        return $this;
    }

    /**
     * Definie les conditions
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
     * @return int
     * @throws \NeeZiaa\Database\DatabaseException
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
     * @param string|null $mode
     * @return array
     * @throws \NeeZiaa\Database\DatabaseException
     */

    public function fetch(?string $mode = "FETCH_ASSOC"): array
    {
        if(is_null($this->records)){
            $mode = constant("\PDO::".strtoupper($mode));
            $this->records = $this->execute()->fetch($mode);
        }

        if(is_bool($this->records)){
            $this->records = [];
        }

        return $this->records;
    }


    /**
     * Retourne un tableau
     * @param string|null $mode
     * @return array
     * @throws \NeeZiaa\Database\DatabaseException
     */

    public function fetchAll(?string $mode = "FETCH_ASSOC"): array
    {
        if(is_null($this->records)){
            $mode = constant("\PDO::".strtoupper($mode));
            $this->records = $this->execute()->fetchAll($mode);
        }

        if(is_bool($this->records)){
            $this->records = [];
        }

        return $this->records;
    }


    /**
     * Convertie la requête sous forme de string
     * @throws \NeeZiaa\Database\DatabaseException
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

            $parts[] = '('.$this->columns.')';
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

    /**
     * @throws \NeeZiaa\Database\DatabaseException
     */
    private function buildFrom(): string
    {
        $from = [];
        if(is_array($this->from)) {
            foreach ($this->from as $key => $value) {
                if(is_string($key)){
                    $from[] = "value as $key";

                } else {
                    $from[] = $value;
                }

            }
            return join(', ', $from);

        } elseif(is_string($this->from)) {
            return $this->from;
        } else {
            throw new \NeeZiaa\Database\DatabaseException("Incorrect type");
        }

    }

    /**
     * Execute la requête
     * @throws \NeeZiaa\Database\DatabaseException
     */
    
    public function execute(): bool|\PDOStatement
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