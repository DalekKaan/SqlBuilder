<?php

namespace SqlBuilder;

/**
 * Cross join
 */
class CrossJoinStmt implements IJoin
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


    public function __toString()
    {
        return sprintf("CROSS JOIN %s AS %s", $this->source, $this->alias);
    }

}
