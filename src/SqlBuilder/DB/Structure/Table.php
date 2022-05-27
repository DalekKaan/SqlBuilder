<?php

namespace SqlBuilder\DB\Structure;

class Table
{
    /**
     * Name
     * @var string 
     */
    protected string $name;

    /**
     * Columns
     * @var Column[]
     */
    protected array $columns = [];

    /**
     * @param string $name name
     * @param Column[] $columns columns
     */
    public function __construct(string $name, array $columns = [])
    {
        $this->name = $name;
        $this->setColumns($columns);
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
     * Get columns
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Set columns
     * @param Column[] $columns columns
     * @return self
     */
    public function setColumns(array $columns): self {
        $this->columns = $columns;
        return $this;
    }

    /**
     * Add column
     * @param Column $column column
     * @return self
     */
    public function addColumn(Column $column): self {
        $this->columns[] = $column;
        return $this;
    }
}
