<?php

namespace SqlBuilder;

use SqlBuilder\QueryPart\ClickHouse\LimitByStmt;
use SqlBuilder\QueryPart\Column;
use SqlBuilder\QueryPart\IColumn;
use SqlBuilder\QueryPart\ICondition;
use SqlBuilder\QueryPart\IJoin;
use SqlBuilder\QueryPart\OrderStmt;
use SqlBuilder\QueryPart\WithStmt;

/**
 * Query
 */
class Query
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
    protected string $source;

    /**
     * Alias of FROM
     * @var string|null
     */
    protected ?string $alias = null;

    /**
     * Joins
     * @var string[]
     */
    protected array $joins = [];

    /**
     * Where conditions
     * @var string|null
     */
    protected ?string $where = null;

    /**
     * Order by columns
     * @var string[]
     */
    protected array $orderBy = [];

    /**
     * Group by columns
     * @var string[]
     */
    protected array $groupBy = [];

    /**
     * "Having" conditions
     * @var string|null
     */
    protected ?string $having = null;

    /**
     * "Limit by" statements
     * @var string|null
     */
    protected ?string $limitBy = null;

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
     * @param Query|string $from table or sub query
     * @param string|null $alias source alias
     */
    public function __construct($from, ?string $alias = null)
    {
        if (is_string($from)) {
            $from = trim($from);
        }
        $this->source = $from;
        if ($alias !== null) {
            $this->alias = trim($alias);
        }
    }

    /**
     * Add "WITH" statement
     * @param string|WithStmt $with
     * @return self
     */
    public function addWith(string $with): self
    {
        $this->with[] = $with;
        return $this;
    }

    /**
     * Add column
     * @param string|IColumn $column
     * @return self
     */
    public function addColumn(string $column): self
    {
        if ($this->columns === null) {
            $this->columns = [];
        }
        $this->columns[] = $column;
        return $this;
    }

    /**
     * Add group by statement
     * @param string|Column $column
     * @return self
     */
    public function addGroupBy(string $column): self
    {
        $this->groupBy[] = $column;
        return $this;
    }

    /**
     * Set having condition
     * @param string|ICondition $condition
     * @return self
     */
    public function setHawing(string $condition): self
    {
        $this->having = $condition;
        return $this;
    }

    /**
     * Set where condition
     * @param string|ICondition $condition
     * @return self
     */
    public function setWhere(string $condition): self
    {
        $this->where = $condition;
        return $this;
    }

    /**
     * Add join condition
     * @param string|IJoin $join
     * @return self
     */
    public function addJoin(string $join): self
    {
        $this->joins[] = $join;
        return $this;
    }

    /**
     * Add order by statement
     * @param string|OrderStmt $orderStatement
     * @return self
     */
    public function addOrderBy(string $orderStatement): self
    {
        $this->orderBy[] = $orderStatement;
        return $this;
    }

    /**
     * Set "limit by" statement
     * @param string|LimitByStmt $limitByStmt
     * @return self
     */
    public function setLimitBy(string $limitByStmt): self
    {
        $this->limitBy = $limitByStmt;
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
    public function buildSql(): string
    {
        $sqlParts = [];
        // prepare with

        if ($this->with) {
            $sqlParts['with'] = "WITH " . implode(", ", $this->with);
        }

        // prepare columns
        if ($this->columns === null) {
            $columnsStatement = "*";
        } else {
            $columnsStatement = implode(", ", $this->columns);
        }
        $sqlParts['select'] = "SELECT " . $columnsStatement;

        // prepare source
        if ($this->source instanceof self) {
            $fromStatement = "({$this->source})";
        } elseif (is_string($this->source) && strpos($this->source, " ") !== false) {
            $fromStatement = "({$this->source})";
        } else {
            $fromStatement = $this->source;
        }
        if ($this->alias !== null) {
            $fromStatement .= " AS " . $this->alias;
        }
        $sqlParts['from'] = "FROM " . $fromStatement;

        // prepare joins
        if ($this->joins) {
            $joinsStatement = implode(" ", $this->joins);
            $sqlParts['joins'] = $joinsStatement;
        }

        // prepare where
        if ($this->where) {
            $sqlParts['where'] = "WHERE " . $this->where;
        }

        // prepare group by
        if ($this->groupBy) {
            $groupByStatement = implode(", ", $this->groupBy);
            $sqlParts['groupBy'] = "GROUP BY " . $groupByStatement;

            // Prepare having
            if ($this->having !== null) {
                $sqlParts['having'] = "HAVING " . $this->having;
            }
        }

        // prepare order by
        if ($this->orderBy) {
            $orderByStatement = implode(", ", $this->orderBy);
            $sqlParts['orderBy'] = "ORDER BY " . $orderByStatement;
        }

        // prepare limit by
        if ($this->limitBy) {
            $sqlParts['limitBy'] = $this->limitBy;
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
        return "({$this->buildSql()})";
    }


}
