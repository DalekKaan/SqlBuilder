<?php

namespace SqlBuilder\QueryPart\Condition;

/**
 * SQL condition
 */
interface ICondition
{
    /**
     * Make SQL condition string
     */
    public function __toString();

    /**
     * get logic for join to others conditions 
     * @return string
     */
    public function getJoinedBy():string; 
}
