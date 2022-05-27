<?php

namespace SqlBuilder\QueryPart\Condition;

use SqlBuilder\Helpers\SqlHelper;

class ConditionStmt implements ICondition
{
    /**
     * Operator
     * @var string
     */
    protected string $operator;

    /**
     * Left side
     * @var string
     */
    protected string $leftSide;

    /**
     * Right side
     * @var string
     */
    protected string $rightSide;

    /**
     * Logic for joining to others conditions
     * @var string
     */
    protected string $joinedBy = "AND";


    /**
     * @param string $leftSide left side of condition
     * @param string $operator
     * @param string|array $rightSide right side of condition
     * @param string $joinedBy logic for joining to others conditions
     */
    public function __construct(string $leftSide, string $operator, $rightSide, string $joinedBy = "AND")
    {
        $this->leftSide = $leftSide;
        $this->operator = $operator;
        if (is_array($rightSide) && strtoupper(trim($operator)) === "IN") {
            $rightSide = SqlHelper::scalarToSQL($rightSide);
        }
        $this->rightSide = $rightSide;
        $this->joinedBy = $joinedBy;
    }

    /**
     * {@inheritDoc}
     */
    public function getJoinedBy(): string
    {
        return $this->joinedBy;
    }


    public function __toString()
    {
        return sprintf("(%s %s %s)", $this->leftSide, $this->operator, $this->rightSide);
    }


}
