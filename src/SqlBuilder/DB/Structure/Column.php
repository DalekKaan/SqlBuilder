<?php

namespace SqlBuilder\DB\Structure;

/**
 * Table column
 */
class Column
{ 
    /**
     * Name
     * @var string 
     */
    protected string $name;
    
    /**
     * Type
     * @var string 
     */
    protected string $type;

    /**
     * @param string $name name
     * @param string $type type
     */
    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Get name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get type
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

}
