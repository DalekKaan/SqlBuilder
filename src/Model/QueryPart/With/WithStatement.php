<?php

namespace RibSelezen\SqlBuilder\Model\QueryPart\With;

use RibSelezen\SqlBuilder\Model\Query\QueryInterface;
use RibSelezen\SqlBuilder\Model\Query\SelectQuery;
use RibSelezen\SqlBuilder\SQLStatementInterface;

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
