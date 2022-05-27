<?php

namespace SqlBuilder\DB\Structure;

/**
 * Database
 */
class Database
{
    /**
     * Schemes
     * @var Scheme[]
     */
    protected array $schemes = [];

    /**
     * @param Scheme[] $schemes schemes
     */
    public function __construct(array $schemes)
    {
        $this->setSchemes($schemes);
    }

    /**
     * Get schemes
     * @return Scheme[]
     */
    public function getSchemes(): array
    {
        return $this->schemes;
    }

    /**
     * Set schemes
     * @param Scheme[] $schemes
     * @return self
     */
    public function setSchemes(array $schemes): self
    {
        $this->schemes = $schemes;
        return $this;
    }

    /**
     * Add scheme
     * @param Scheme $scheme
     * @return self
     */
    public function addScheme(Scheme $scheme): self
    {
        $this->schemes[] = $scheme;
        return $this;
    }


}
