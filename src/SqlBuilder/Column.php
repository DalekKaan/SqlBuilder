<?php

namespace SqlBuilder;

/**
 * Column
 */
class Column implements IColumn
{
    /**
     * Expression
     * @var string
     */
    protected string $expression;
    
    /**
     * Alias
     * @var string|null 
     */
    protected ?string $alias = null;

    /**
     * @param string $expression expression
     * @param string|null $alias alias
     */
    public function __construct(string $expression, ?string $alias = null)
    {
        $this->expression = $expression;
        $this->alias = $alias;
    }


    public function __toString() {
        $out = $this->expression;
        if ($this->alias) {
            $out .= " AS " . $this->alias;
        }
        return $out;
    }
}
