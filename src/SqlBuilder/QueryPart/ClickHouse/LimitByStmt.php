<?php

namespace SqlBuilder\QueryPart\ClickHouse;

/**
 * "Limit by" SQL statement for ClickHouse database
 * @deprecated specific databases support should be removed
 */
class LimitByStmt
{
    /**
     * Limit
     * @var int 
     */
    protected int $limit;
    /**
     * Column name
     * @var string[]
     */
    protected array $column;
    /**
     * Offset
     * @var int 
     */
    protected int $offset = 0;

    /**
     * @param int $limit
     * @param string|string[] $column
     * @param int $offset
     */
    public function __construct(int $limit, $column, int $offset = 0)
    {
        if (is_string($column)) {
            $column = [$column];
        }
        $this->limit = $limit;
        $this->column = $column;
        $this->offset = $offset;
    }
    
    public function __toString()
    {
        $columnsStr = implode(', ', $this->column);
        if ($this->offset > 0) {
            return sprintf("LIMIT %s OFFSET %s BY %s", $this->limit, $this->offset, $columnsStr);
        }
        return sprintf("LIMIT %s BY %s", $this->limit, $columnsStr);
    }


}
