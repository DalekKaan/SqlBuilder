<?php

namespace SqlBuilder\DB\Structure;

/**
 * Scheme
 */
class Scheme
{
    /**
     * Name
     * @var string 
     */
    protected string $name;

    /**
     * Tables
     * @var Table[]
     */
    protected array $tables = [];

    /**
     * @param string $name name
     * @param Table[] $tables tables
     */
    public function __construct(string $name, array $tables = [])
    {
        $this->name = $name;
        $this->setTables($tables);
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
     * Get tables
     * @return Table[]
     */
    public function getTables(): array
    {
        return $this->tables;
    }

    /**
     * Set tables
     * @param Table[] $tables
     */
    public function setTables(array $tables): self
    {
        $this->tables = $tables;
        return $this;
    }

    /**
     * Add table
     * @param Table $table table
     * @return self
     */
    public function addTable(Table $table): self {
        $this->tables[] = $table;
        return $this;
    }
}
