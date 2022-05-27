<?php

namespace SqlBuilder\QueryPart\With;

use SqlBuilder\Facade\QueryFacade;
use SqlBuilder\Query;

/**
 * "With" SQL statement
 */
class WithStmt
{
    /**
     * Data
     * @var string|integer|Query
     */
    protected $data;

    /**
     * Alias
     * @var string 
     */
    protected string $alias;

    /**
     * @param Query|int|string $data
     * @param string $alias
     */
    public function __construct($data, string $alias)
    {
        $this->data = $data;
        $this->alias = $alias;
    }
    
    public function __toString()
    {
        if ($this->data instanceof Query || $this->data instanceof QueryFacade) {
            return sprintf("%s AS %s", $this->alias, $this->data);
        }
        return sprintf("%s AS %s", $this->data, $this->alias);
    }


}
