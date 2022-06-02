<?php

namespace SqlBuilder\Model\QueryPart\With;

use SqlBuilder\Model\Query\QueryInterface;
use SqlBuilder\Model\Query\SelectQuery;
use SqlBuilder\SQLStatementInterface;

/**
 * "With" SQL statement
 */
class WithStatement implements SQLStatementInterface
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
        if ($this->data instanceof QueryInterface) {
            return sprintf("%s AS %s", $this->alias, "({$this->data->toSQL()})");
        }
        return sprintf("%s AS %s", $this->data, $this->alias);
    }


}
