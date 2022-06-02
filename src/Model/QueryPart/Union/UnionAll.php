<?php

namespace DalekKaan\SqlBuilder\Model\QueryPart\Union;

use DalekKaan\SqlBuilder\Helpers\SQLHelper;
use DalekKaan\SqlBuilder\SQLStatementInterface;

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
        return "(" . SQLHelper::implodeStatements(") UNION ALL (", $this->statements) . ")";
    }
}
