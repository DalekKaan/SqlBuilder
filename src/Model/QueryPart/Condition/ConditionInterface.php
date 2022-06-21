<?php

namespace DalekKaan\SqlBuilder\Model\QueryPart\Condition;

use DalekKaan\SqlBuilder\SQLStatementInterface;

/**
 * SQL condition
 */
interface ConditionInterface extends SQLStatementInterface
{
    /**
     * `AND` condition
     */
    public const AND = 'AND';
    
    /**
     * OR condition
     */
    public const OR = 'OR';
    
    /**
     * IN condition 
     */
    public const IN = 'IN';

    /**
     * `BETWEEN` condition
     */
    public const BETWEEN = 'BETWEEN';

    /**
     * get logic for join to others conditions 
     * @return string
     */
    public function getJoinedBy():string; 
}
