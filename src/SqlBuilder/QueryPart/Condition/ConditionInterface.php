<?php

namespace SqlBuilder\QueryPart\Condition;

use SqlBuilder\SqlStatementInterface;

/**
 * SQL condition
 */
interface ConditionInterface extends SqlStatementInterface
{

    /**
     * get logic for join to others conditions 
     * @return string
     */
    public function getJoinedBy():string; 
}
