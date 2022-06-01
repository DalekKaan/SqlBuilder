<?php

namespace SqlBuilder\QueryPart\Column;

use SqlBuilder\SqlStatementInterface;

/**
 * SQL column
 */
interface ColumnInterface extends SqlStatementInterface
{
    /**
     * Get name of the column
     * @return string
     */
    public function getName(): string;
}
