<?php

namespace DalekKaan\SqlBuilder\Model\QueryPart\Condition;

use DalekKaan\SqlBuilder\SQLStatementInterface;

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
