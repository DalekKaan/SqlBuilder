<?php

namespace RibSelezen\SqlBuilder\Facade;

use RibSelezen\SqlBuilder\Model\Query\QueryInterface;

abstract class AbstractQueryFacade implements QueryInterface
{
    /**
     * Query
     * @var QueryInterface
     */
    protected QueryInterface $stmt;
    
    /**
     * Build sql using Query::buildSql() method
     * @return string
     */
    public function toSql(): string
    {
        return $this->stmt->toSQL();
    }

    public function __toString(): string
    {
        return $this->toSql();
    }

}
