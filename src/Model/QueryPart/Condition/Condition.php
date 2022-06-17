<?php

namespace DalekKaan\SqlBuilder\Model\QueryPart\Condition;

use DalekKaan\SqlBuilder\Helpers\SQLHelper;
use DalekKaan\SqlBuilder\SQLStatementInterface;

class Condition implements ConditionInterface
{
    /**
     * Operator
     * @var string
     */
    protected string $operator;

    /**
     * Left side
     * @var string|SQLStatementInterface
     */
    protected $leftSide;

    /**
     * Right side
     * @var string|SQLStatementInterface
     */
    protected $rightSide;

    /**
     * Logic for joining to others conditions
     * @var string
     */
    protected string $joinedBy = "AND";


    /**
     * @param SQLStatementInterface|string $leftSide left side of condition
     * @param string $operator
     * @param SQLStatementInterface|string|array<SQLStatementInterface|string> $rightSide right side of condition
     * @param string $joinedBy logic for joining to others conditions
     */
    public function __construct($leftSide, string $operator, $rightSide, string $joinedBy = "AND")
    {
        if (is_array($rightSide) && strtoupper($operator) === "IN") {
            $this->rightSide = SQLHelper::scalarToSQL($rightSide);
        } elseif (is_array($rightSide) && strtoupper($operator) === "BETWEEN") {
            $this->rightSide = SQLHelper::scalarToSQL($rightSide[0]) . " AND " . SQLHelper::scalarToSQL($rightSide[1]);
        } elseif ($rightSide instanceof SQLStatementInterface) {
            $this->rightSide = SQLHelper::wrap($rightSide);
        } else {
            $this->rightSide = $rightSide;
        }

        if ($leftSide instanceof SQLStatementInterface) {
            $this->leftSide = SQLHelper::wrap($leftSide);
        } else {
            $this->leftSide = $leftSide;
        }
        
        $this->operator = $operator;
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
        return sprintf("%s %s %s", $this->leftSide, $this->operator, $this->rightSide);
    }


}
