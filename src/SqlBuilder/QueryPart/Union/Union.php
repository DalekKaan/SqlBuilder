<?php

namespace SqlBuilder\QueryPart\Union;

/**
 * `UNION` statement
 */
class Union
{
    /**
     * `SELECT` statements
     * @var string[]
     */
    protected $statements;

    /**
     * @param string[] $statements `SELECT` statements
     */
    public function __construct(array $statements)
    {
        $this->statements = $statements;
    }

    public function __toString(): string {
        return implode(" UNION ", $this->statements);
    }
}
