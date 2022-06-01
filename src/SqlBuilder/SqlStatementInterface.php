<?php

namespace SqlBuilder;

/**
 * SQL statement
 */
interface SqlStatementInterface
{
    /**
     * Convert to SQL
     * @return string
     */
    public function toSQL(): string;
}
