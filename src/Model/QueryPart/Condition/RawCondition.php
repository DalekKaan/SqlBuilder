<?php

namespace SqlBuilder\Model\QueryPart\Condition;

/**
 * Simple string condition
 */
class RawCondition implements ConditionInterface
{
    /**
     * Condition
     * @var string 
     */
    protected string $condition;

    /**
     * Logic for joining to others conditions
     * @var string
     */
    protected string $joinedBy = "AND";

    /**
     * @param string $condition condition
     * @param string $joinedBy logic for joining to others conditions
     */
    public function __construct(string $condition, string $joinedBy = "AND")
    {
        $this->condition = $condition;
        $this->joinedBy = $joinedBy;
    }

    /**
     * {@inheritDoc}
     */
    public function toSQL(): string
    {
        return $this->condition;
    }

    /**
     * {@inheritDoc}
     */
    public function getJoinedBy(): string
    {
        return $this->joinedBy;
    }
}
