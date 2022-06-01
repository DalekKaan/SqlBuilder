<?php

namespace SqlBuilder\Model\QueryPart\Condition;

use SqlBuilder\SQLStatementInterface;

/**
 * SQL condition
 */
interface ConditionInterface extends SQLStatementInterface
{

    /**
     * get logic for join to others conditions 
     * @return string
     */
    public function getJoinedBy():string; 
}
