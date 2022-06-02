<?php

namespace SqlBuilder\Model\QueryPart\Column;

use SqlBuilder\SQLStatementInterface;

/**
 * SQL column
 */
interface ColumnInterface extends SQLStatementInterface
{
    /**
     * Get name of the column
     * @return string
     */
    public function getName(): string;
}
