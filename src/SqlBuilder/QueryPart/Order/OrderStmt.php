<?php

namespace SqlBuilder\QueryPart\Order;

/**
 * Order statement
 */
class OrderStmt
{
    /**
     * Ordering direction - asc
     */
    public const DIRECTION_ASC = "ASC";
    
    /**
     * Ordering direction - asc
     */
    public const DIRECTION_DESC = "DESC";

    /**
     * Order column
     * @var string
     */
    protected string $column;

    /**
     * Ordering direction
     * @var string 
     */
    protected string $direction = self::DIRECTION_ASC;

    /**
     * @param string $column
     * @param string $direction
     */
    public function __construct(string $column, string $direction = self::DIRECTION_ASC)
    {
        $this->column = $column;
        $this->direction = $direction;
    }
    
    public function __toString()
    {
        return sprintf("%s %s", $this->column, $this->direction);
    }


}
