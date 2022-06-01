<?php

namespace SqlBuilder\QueryPart\Union;

use SqlBuilder\SqlStatementInterface;

/**
 * `UNION` statement
 */
class Union implements SqlStatementInterface
{
    /**
     * `SELECT` statements
     * @var string[]
     */
    protected $statements;

    /**
     * @param string[] $statements `SELECT` statements
     */
    public function __construct(array $statements)
    {
        $this->statements = $statements;
    }

    /**
     * @inheritDoc
     */
    public function toSQL(): string
    {
        return implode(" UNION ", $this->statements);
    }
}
