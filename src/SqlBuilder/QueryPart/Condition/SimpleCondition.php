<?php

namespace SqlBuilder\QueryPart\Condition;

/**
 * Simple string condition
 */
class SimpleCondition implements ICondition
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
    public function __toString()
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
