<?php

namespace SqlBuilder\Query;

use SqlBuilder\Helpers\SqlHelper;
use SqlBuilder\SqlStatementInterface;

/**
 * `INSERT` statement
 */
class InsertQuery implements SqlStatementInterface
{
    /**
     * Target
     * @var string
     */
    protected string $target;

    /**
     * Columns to insert
     * @var array|null
     */
    protected ?array $columns = null;

    /**
     * Values to insert
     * @var array
     */
    protected array $values = [];

    /**
     * Select to insert
     * @var SelectQuery|null
     */
    protected ?SelectQuery $select = null;

    /**
     * @param string $target target
     */
    public function __construct(string $target)
    {
        $this->target = $target;
    }

    /**
     * Set columns of the source to insert
     * @param array|null $columnsNames
     * @return self
     */
    public function setColumns(?array $columnsNames): self
    {
        $this->columns = $columnsNames;
        return $this;
    }

    /**
     * Add column of the source to insert
     * @param string $columnName
     * @return self
     */
    public function addColumn(string $columnName): self
    {
        if ($this->columns === null) {
            $this->columns = [];
        }
        $this->columns[] = $columnName;
        return $this;
    }

    /**
     * Set values
     * @param array $values
     * @return self
     */
    public function setValues(array $values): self
    {
        if (count($values) === count($values, COUNT_RECURSIVE)) {
            // we need at least two-dimensional arrays
            $values = [$values];
        }
        $this->values = $values;
        return $this;
    }

    /**
     * Add values
     * @param array $values
     * @return self
     */
    public function addValues(array $values): self
    {
        $this->values[] = $values;
        return $this;
    }

    /**
     * Set select query
     * @param SelectQuery $select
     * @return $this
     */
    public function setSelect(SelectQuery $select): self
    {
        $this->select = $select;
        return $this;
    }

    /**
     * To SQL
     * @return string
     */
    public function toSQL(): string
    {
        $sql = "INSERT INTO {$this->target}";
        if ($this->columns !== null) {
            $sql .= " (" . implode(", ", $this->columns) . ")";
        }
        if ($this->select !== null) {
            $sql .= " " . $this->select->toSQL();
        } elseif (count($this->values) > 0) {
            $values = array_map(static fn($row) => SqlHelper::scalarToSQL($row), $this->values);
            $sql .= " VALUES ". implode(", ", $values);
        } else {
            $sql .= " VALUES ()";
        }
        return $sql;
    }

    public function __toString(): string
    {
        return "({$this->toSQL()})";
    }

}
