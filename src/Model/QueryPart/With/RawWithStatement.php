<?php

namespace DalekKaan\SqlBuilder\Model\QueryPart\With;

/**
 * Raw `WITH` statement
 */
class RawWithStatement implements WithInterface
{
    /**
     * Raw statement
     * @var string
     */
    protected string $statement;

    /**
     * @param string $statement raw statement
     */
    public function __construct(string $statement)
    {
        $this->statement = $statement;
    }


    /**
     * @inheritDoc
     */
    public function toSQL(): string
    {
        return $this->statement;
    }
}
