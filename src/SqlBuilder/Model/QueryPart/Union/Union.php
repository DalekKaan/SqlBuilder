<?php

namespace SqlBuilder\Model\QueryPart\Union;

use SqlBuilder\SQLStatementInterface;

/**
 * `UNION` statement
 */
class Union implements SQLStatementInterface
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