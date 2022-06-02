<?php

namespace DalekKaan\SqlBuilder\Model\QueryPart\Union;

use DalekKaan\SqlBuilder\Helpers\SQLHelper;
use DalekKaan\SqlBuilder\Model\Query\QueryInterface;
use DalekKaan\SqlBuilder\SQLStatementInterface;

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
        return SQLHelper::implodeStatements(") UNION (", $this->statements);
    }
}
