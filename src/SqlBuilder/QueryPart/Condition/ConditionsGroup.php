<?php

namespace SqlBuilder\QueryPart\Condition;

/**
 * Group of conditions
 */
class ConditionsGroup implements ConditionInterface
{
    /**
     * Conditions
     * @var ConditionInterface[]
     */
    protected array $conditions;

    /**
     * Logic for joining to others conditions
     * @var string
     */
    protected string $joinedBy = "AND";

    /**
     * @param ConditionInterface[] $conditions
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
     * @param ConditionInterface $condition
     * @return self
     */
    public function addCondition(ConditionInterface $condition): self
    {
        $this->conditions[] = $condition;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toSQL(): string
    {
        if (!$this->conditions) {
            return "";
        }

        $out = "(";
        foreach ($this->conditions as $index => $condition) {
            if ($index !== 0) {
                $out .= " " . $condition->getJoinedBy() . " ";
            }
            $out .= $condition->toSQL();
        }
        $out .= ")";
        return $out;
    }


}
