<?php

namespace SqlBuilder\Model\QueryPart\With;

use SqlBuilder\Facade\Select;
use SqlBuilder\Model\Query\SelectQuery;
use SqlBuilder\SQLStatementInterface;

/**
 * "With" SQL statement
 */
class WithStmt implements SQLStatementInterface
{
    /**
     * Data
     * @var string|integer|SelectQuery
     */
    protected $data;

    /**
     * Alias
     * @var string 
     */
    protected string $alias;

    /**
     * @param SelectQuery|int|string $data
     * @param string $alias
     */
    public function __construct($data, string $alias)
    {
        $this->data = $data;
        $this->alias = $alias;
    }

    /**
     * @inheritDoc
     */
    public function toSQL(): string
    {
        if ($this->data instanceof SelectQuery || $this->data instanceof Select) {
            return sprintf("%s AS %s", $this->alias, $this->data);
        }
        return sprintf("%s AS %s", $this->data, $this->alias);
    }


}
