<?php

namespace SqlBuilder;

/**
 * SQL statement
 */
interface SQLStatementInterface
{
    /**
     * Convert to SQL
     * @return string
     */
    public function toSQL(): string;
}
