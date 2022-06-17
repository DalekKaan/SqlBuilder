<?php

namespace DalekKaan\SqlBuilder\Model\QueryPart\Condition;

use DalekKaan\SqlBuilder\Helpers\SQLHelper;

/**
 * `IN` condition
 */
class ConditionIn implements ConditionInterface
{
    /**
     * Left side
     * @var string
     */
    protected string $leftSide;

    /**
     * Right side
     * @var array
     */
    protected array $rightSide;

    /**
     * Logic for joining to others conditions
     * @var string
     */
    protected string $joinedBy = "AND";

    /**
     * @param string $leftSide left side
     * @param array $rightSide options
     * @param string $joinedBy joined by
     */
    public function __construct(string $leftSide, array $rightSide, string $joinedBy)
    {
        $this->leftSide = $leftSide;
        $this->rightSide = $rightSide;
        $this->joinedBy = $joinedBy;
    }


    /**
     * @inheritDoc
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
        return $this->leftSide . " IN " . SQLHelper::scalarToSQL($this->rightSide);
    }
}
