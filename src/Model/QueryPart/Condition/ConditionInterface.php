<?php

namespace RibSelezen\SqlBuilder\Model\QueryPart\Condition;

use RibSelezen\SqlBuilder\SQLStatementInterface;

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
