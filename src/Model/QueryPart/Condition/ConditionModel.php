<?php

namespace DalekKaan\SqlBuilder\Model\QueryPart\Condition;

use DalekKaan\SqlBuilder\Helpers\SQLHelper;
use DalekKaan\SqlBuilder\SQLStatementInterface;

/**
 * Condition model
 */
class ConditionModel implements ConditionInterface
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
     * @param string $rightSide right side of condition
     * @param string $joinedBy logic for joining to others conditions
     */
    public function __construct(string $leftSide, string $operator, string $rightSide, string $joinedBy = "AND")
    {
        $this->leftSide = $leftSide;
        $this->operator = $operator;
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


    /**
     * @inheritDoc
     */
    public function toSQL(): string
    {
        return implode(" ", [$this->leftSide, $this->operator, $this->rightSide]);
    }


}
