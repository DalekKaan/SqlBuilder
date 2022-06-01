<?php

namespace SqlBuilder\QueryPart\Join;

/**
 * Cross join
 */
class CrossJoinInterfaceStmt implements JoinInterface
{
    /**
     * Object to join
     * @var string
     */
    protected string $source;

    /**
     * Alias of joining objet
     * @var string
     */
    protected string $alias;

    /**
     * @param string $source table or sub query to join
     * @param string $alias alias of joined table
     */
    public function __construct(string $source, string $alias)
    {
        $this->source = $source;
        $this->alias = $alias;
    }


    /**
     * @inheritDoc
     */
    public function toSQL(): string
    {
        return sprintf("CROSS JOIN %s AS %s", $this->source, $this->alias);
    }

}
