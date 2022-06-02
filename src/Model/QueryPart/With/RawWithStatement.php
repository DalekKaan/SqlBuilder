<?php

namespace DalekKaan\SqlBuilder\Model\QueryPart\With;

use DalekKaan\SqlBuilder\SQLStatementInterface;

/**
 * Raw `WITH` statement
 */
class RawWithStatement implements SQLStatementInterface
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
