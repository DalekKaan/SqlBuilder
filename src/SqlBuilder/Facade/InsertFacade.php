<?php

namespace SqlBuilder\Facade;

use SqlBuilder\Insert;
use SqlBuilder\Select;

class InsertFacade
{
    /**
     * The `INSERT` statement
     * @var Insert 
     */
    protected Insert $stmt;

    /**
     * @param string $target target
     * @param array|null $columnsNames columns of the target
     * @param array|Select|string $data data to insert
     * @return static
     */
    public static function into(string $target, array $columnsNames = null, $data = null): self {
        $stmt = new Insert($target);
        $stmt->setColumns($columnsNames);
        
        if ($data!== null) {
            if (is_array($data)) {
                $stmt->setValues($data);
            } elseif ($data instanceof Select) {
                $stmt->setSelect($data);
            } elseif (is_string($data)) {
                $stmt->setSelect($data);
            } else {
                trigger_error("Wrong data type", E_USER_NOTICE);
            }
        }
        return new self($stmt);
    }

    /**
     * Returns query from this facade
     * @return Insert
     */
    public function getStatement(): Insert
    {
        return $this->stmt;
    }

    /**
     * @param Insert $stmt the `INSERT` statement
     */
    public function __construct(Insert $stmt)
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
     * @param string $select select statement
     * @return void
     */
    public function select(string $select): void {
        $this->stmt->setSelect($select);
    }

    /**
     * Set from select statement
     * @param string $select select statement
     * @return void
     */
    public function from(string $select): void {
        $this->select($select);
    }

    /**
     * Build SQL
     * @return string
     */
    public function buildSQL(): string {
        return $this->stmt->buildSql();
    }
    
    public function __toString():string
    {
        return $this->stmt->__toString();
    }

}
