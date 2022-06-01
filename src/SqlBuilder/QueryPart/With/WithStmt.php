<?php

namespace SqlBuilder\QueryPart\With;

use SqlBuilder\Facade\SelectFacade;
use SqlBuilder\Select;
use SqlBuilder\SqlStatementInterface;

/**
 * "With" SQL statement
 */
class WithStmt implements SqlStatementInterface
{
    /**
     * Data
     * @var string|integer|Select
     */
    protected $data;

    /**
     * Alias
     * @var string 
     */
    protected string $alias;

    /**
     * @param Select|int|string $data
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
        if ($this->data instanceof Select || $this->data instanceof SelectFacade) {
            return sprintf("%s AS %s", $this->alias, $this->data);
        }
        return sprintf("%s AS %s", $this->data, $this->alias);
    }


}
