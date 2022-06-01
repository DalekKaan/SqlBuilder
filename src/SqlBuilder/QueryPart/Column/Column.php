<?php

namespace SqlBuilder\QueryPart\Column;

/**
 * Column
 */
class Column implements ColumnInterface
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

    /**
     * @inheritDoc
     */
    public function toSQL(): string
    {
        $out = $this->expression;
        if ($this->alias) {
            $out .= " AS " . $this->alias;
        }
        return $out;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->alias ?? $this->expression;
    }


}
