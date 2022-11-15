<?php

namespace DalekKaan\SqlBuilder\Model;

use DalekKaan\SqlBuilder\SQLStatementInterface;

/**
 * Raw SQL statement
 */
class RawSQLStatement implements SQLStatementInterface
{
    /**
     * Raw value
     * @var string
     */
    protected string $value;

    /**
     * @param string $value value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }


    /**
     * @inheritDoc
     */
    public function toSQL(): string
    {
        return $this->value;
    }
}
