<?php

namespace DalekKaan\SqlBuilder\Model\Query;

use DalekKaan\SqlBuilder\Model\QueryPart\Condition\ConditionInterface;
use DalekKaan\SqlBuilder\Model\QueryPart\Set\SetPairInterface;
use DalekKaan\SqlBuilder\SQLStatementInterface;

class UpdateQuery implements QueryInterface
{
    /**
     * Source to update
     * @var string
     */
    protected string $source;
    /**
     * Set pairs
     * @var SetPairInterface[]
     */
    protected array $set;

    /**
     * Where conditions
     * @var ConditionInterface|null
     */
    protected ?ConditionInterface $where;

    /**
     * @param string $source
     * @param SetPairInterface[] $set
     * @param ConditionInterface|null $where
     */
    public function __construct(string $source, array $set, ?ConditionInterface $where)
    {
        $this->source = $source;
        $this->set = $set;
        $this->where = $where;
    }


    /**
     * @inheritDoc
     */
    public function toSQL(): string
    {
        $out = "UPDATE " . $this->source;
        $out .= " SET " . implode(", ", array_map(static fn(SetPairInterface $pair) => $pair->toSQL(), $this->set));
        $out .= " WHERE " . $this->where->toSQL();
        return $out;
    }
}
