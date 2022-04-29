<?php

namespace SqlBuilder\QueryPart;

/**
 * Group of conditions
 */
class ConditionsGroup implements ICondition
{
    /**
     * Conditions
     * @var ICondition[]
     */
    protected array $conditions;

    /**
     * Logic for joining to others conditions
     * @var string
     */
    protected string $joinedBy = "AND";

    /**
     * @param ICondition[] $conditions
     * @param string $joinedBy logic for joining to others conditions
     */
    public function __construct(array $conditions, string $joinedBy = "AND")
    {
        $this->conditions = $conditions;
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
     * Add condition to group
     * @param ICondition $condition
     * @return self
     */
    public function addCondition(ICondition $condition): self
    {
        $this->conditions[] = $condition;
        return $this;
    }

    public function __toString()
    {
        if (!$this->conditions) {
            return "";
        }

        $out = "(";
        foreach ($this->conditions as $index => $condition) {
            if ($index !== 0) {
                $out .= " " . $condition->getJoinedBy() . " ";
            }
            $out .= $condition;
        }
        $out .= ")";
        return $out;
    }


}
