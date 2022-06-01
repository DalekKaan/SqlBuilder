<?php

namespace SqlBuilder\Query;

use SqlBuilder\Helpers\SqlHelper;
use SqlBuilder\QueryPart\Column\Column;
use SqlBuilder\QueryPart\Column\ColumnInterface;
use SqlBuilder\QueryPart\Condition\ConditionInterface;
use SqlBuilder\QueryPart\Join\JoinInterface;
use SqlBuilder\QueryPart\Order\OrderStmt;
use SqlBuilder\QueryPart\With\WithStmt;
use SqlBuilder\SqlStatementInterface;

/**
 * `SELECT` statement
 */
class SelectQuery implements SqlStatementInterface
{
    /**
     * "WITH" statements
     * @var WithStmt[]
     */
    protected array $with = [];
    /**
     * Query columns
     * @var Column[]|null
     */
    protected ?array $columns = null;

    /**
     * From clause
     * @var string
     */
    protected string $from;

    /**
     * Alias of FROM
     * @var string|null
     */
    protected ?string $alias = null;

    /**
     * Joins
     * @var JoinInterface[]
     */
    protected array $joins = [];

    /**
     * Where conditions
     * @var ConditionInterface|null
     */
    protected ?ConditionInterface $where = null;

    /**
     * Order by columns
     * @var OrderStmt[]
     */
    protected array $orderBy = [];

    /**
     * Group by columns
     * @var string[]
     */
    protected array $groupBy = [];

    /**
     * "Having" conditions
     * @var ConditionInterface|null
     */
    protected ?ConditionInterface $having = null;

    /**
     * Limit
     * @var int|null
     */
    protected ?int $limit = null;

    /**
     * Offset
     * @var int|null
     */
    protected ?int $offset = null;

    /**
     * @param string $from table or sub query
     * @param string|null $alias source alias
     */
    public function __construct(string $from, ?string $alias = null)
    {
        $this->from = $from;
        if ($alias !== null) {
            $this->alias = trim($alias);
        }
    }

    /**
     * Add "WITH" statement
     * @param WithStmt $with
     * @return self
     */
    public function addWith(WithStmt $with): self
    {
        $this->with[] = $with;
        return $this;
    }

    /**
     * Add column
     * @param ColumnInterface $column
     * @return self
     */
    public function addColumn(ColumnInterface $column): self
    {
        if ($this->columns === null) {
            $this->columns = [];
        }
        $this->columns[] = $column;
        return $this;
    }

    /**
     * Add group by statement
     * @param ColumnInterface $column
     * @return self
     */
    public function addGroupBy(ColumnInterface $column): self
    {
        $this->groupBy[] = $column->getName();
        return $this;
    }

    /**
     * Set having condition
     * @param ConditionInterface $condition
     * @return self
     */
    public function setHawing(ConditionInterface $condition): self
    {
        $this->having = $condition;
        return $this;
    }

    /**
     * Set where condition
     * @param ConditionInterface $condition
     * @return self
     */
    public function setWhere(ConditionInterface $condition): self
    {
        $this->where = $condition;
        return $this;
    }

    /**
     * Add join condition
     * @param JoinInterface $join
     * @return self
     */
    public function addJoin(JoinInterface $join): self
    {
        $this->joins[] = $join;
        return $this;
    }

    /**
     * Add order by statement
     * @param OrderStmt $orderStatement
     * @return self
     */
    public function addOrderBy(OrderStmt $orderStatement): self
    {
        $this->orderBy[] = $orderStatement;
        return $this;
    }

    /**
     * Set limit
     * @param int $limit
     * @return self
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Set offset
     * @param int $offset
     * @return self
     */
    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Build SQL
     * @return string
     */
    public function toSQL(): string
    {
        $sqlParts = [];
        // prepare with

        if ($this->with) {
            
            $sqlParts['with'] = "WITH " . SqlHelper::implodeStatements(", ", $this->with);
        }

        // prepare columns
        if ($this->columns === null) {
            $columnsStatement = "*";
        } else {
            $columnsStatement = SqlHelper::implodeStatements(", ", $this->columns);
        }
        $sqlParts['select'] = "SELECT " . $columnsStatement;

        // prepare source
        $sqlParts['from'] = "FROM " . $this->from;
        if ($this->alias !== null) {
            $sqlParts['from'] .= " AS " . $this->alias;
        }

        // prepare joins
        if ($this->joins) {
            $joinsStatement = SqlHelper::implodeStatements(" ", $this->joins);
            $sqlParts['joins'] = $joinsStatement;
        }

        // prepare where
        if ($this->where) {
            $sqlParts['where'] = "WHERE " . $this->where->toSQL();
        }

        // prepare group by
        if ($this->groupBy) {
            $groupByStatement = implode(", ", $this->groupBy);
            $sqlParts['groupBy'] = "GROUP BY " . $groupByStatement;

            // Prepare having
            if ($this->having !== null) {
                $sqlParts['having'] = "HAVING " . $this->having->toSQL();
            }
        }

        // prepare order by
        if ($this->orderBy) {
            $orderByStatement = SqlHelper::implodeStatements(", ", $this->orderBy);
            $sqlParts['orderBy'] = "ORDER BY " . $orderByStatement;
        }

        // prepare limit
        if ($this->limit !== null) {
            $sqlParts['limit'] = "LIMIT " . $this->limit;
        }

        // prepare offset
        if ($this->offset !== null) {
            $sqlParts['offset'] = "OFFSET " . $this->offset;
        }

        return implode(" ", $sqlParts);
    }

    public function __toString()
    {
        return "({$this->toSQL()})";
    }


}
