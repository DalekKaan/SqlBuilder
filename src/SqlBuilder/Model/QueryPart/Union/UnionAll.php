<?php

namespace SqlBuilder\Model\QueryPart\Union;

use SqlBuilder\Helpers\SqlHelper;
use SqlBuilder\SQLStatementInterface;

/**
 * `UNION` statement
 */
class UnionAll implements SQLStatementInterface
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
        return "(" . SqlHelper::implodeStatements(") UNION ALL (", $this->statements) . ")";
    }
}
