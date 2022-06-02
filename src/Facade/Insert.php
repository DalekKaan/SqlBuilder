<?php

namespace DalekKaan\SqlBuilder\Facade;

use DalekKaan\SqlBuilder\Model\Query\InsertQuery;
use DalekKaan\SqlBuilder\Model\Query\SelectQuery;

class Insert extends AbstractQueryFacade
{

    /**
     * @param string $target target
     * @param array|null $columnsNames columns of the target
     * @param array|SelectQuery|Select|string $data data to insert
     * @return static
     */
    public static function into(string $target, array $columnsNames = null, $data = null): self {
        $stmt = new InsertQuery($target);
        $stmt->setColumns($columnsNames);
        
        if ($data!== null) {
            if (is_array($data)) {
                $stmt->setValues($data);
            } elseif ($data instanceof SelectQuery) {
                $stmt->setSelect($data);
            }elseif ($data instanceof Select) {
                $stmt->setSelect($data->getStatement());
            } else {
                trigger_error("Wrong data type", E_USER_NOTICE);
            }
        }
        return new self($stmt);
    }

    /**
     * Returns query from this facade
     * @return InsertQuery
     */
    public function getStatement(): InsertQuery
    {
        return $this->stmt;
    }

    /**
     * @param InsertQuery $stmt the `INSERT` statement
     */
    public function __construct(InsertQuery $stmt)
    {
        $this->stmt = $stmt;
    }

    /**
     * Set values
     * @param array $values array of values
     * @return void
     */
    public function values(array $values): void {
        $this->stmt->setValues($values);
    }

    /**
     * Set select statement
     * @param SelectQuery $select select statement
     * @return void
     */
    public function select(SelectQuery $select): void {
        $this->stmt->setSelect($select);
    }

    /**
     * Set from select statement
     * @param SelectQuery $select select statement
     * @return void
     */
    public function from(SelectQuery $select): void {
        $this->select($select);
    }

    /**
     * Build SQL
     * @return string
     */
    public function toSql(): string {
        return $this->stmt->toSQL();
    }

    public function __toString(): string
    {
        return $this->toSql();
    }

}
