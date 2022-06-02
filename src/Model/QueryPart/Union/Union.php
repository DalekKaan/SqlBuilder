<?php

namespace RibSelezen\SqlBuilder\Model\QueryPart\Union;

use RibSelezen\SqlBuilder\Helpers\SqlHelper;
use RibSelezen\SqlBuilder\Model\Query\QueryInterface;
use RibSelezen\SqlBuilder\SQLStatementInterface;

/**
 * `UNION` statement
 */
class Union implements SQLStatementInterface
{
    /**
     * `SELECT` statements
     * @var QueryInterface[]
     */
    protected $statements;

    /**
     * @param QueryInterface[] $statements `SELECT` statements
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
        return SqlHelper::implodeStatements(") UNION (", $this->statements);
    }
}
