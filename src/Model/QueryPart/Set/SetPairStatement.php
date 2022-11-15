<?php

namespace DalekKaan\SqlBuilder\Model\QueryPart\Set;

use DalekKaan\SqlBuilder\SQLStatementInterface;

/**
 * Component for `SET` pair in `UPDATE` query
 */
class SetPairStatement implements SetPairInterface
{
    /**
     * Column name
     * @var string
     */
    protected string $columnName;

    /**
     * Value
     * @var SQLStatementInterface
     */
    protected SQLStatementInterface $value;

    /**
     * @param string $columnName column name
     * @param SQLStatementInterface $value vale to set
     */
    public function __construct(string $columnName, SQLStatementInterface $value)
    {
        $this->columnName = $columnName;
        $this->value = $value;
    }


    public function toSQL(): string
    {
        return sprintf("%s = %s", $this->columnName, $this->value->toSQL());
    }
}
