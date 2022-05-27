<?php

namespace SqlBuilder\QueryPart\Join;

use SqlBuilder\Query;

class JoinStmt implements IJoin
{

    /**
     * Join type
     * @var string
     */
    protected string $type = "LEFT";

    /**
     * Object to join
     * @var string|Query
     */
    protected $source;

    /**
     * Joining condition
     * @var string|array
     */
    protected $condition = null;

    /**
     * Alias of joining objet
     * @var string|null
     */
    protected ?string $alias = null;

    /**
     * @param string $type join type: "LEFT", "RIGHT", "INNER", etc.
     * @param string $source table or sub query to join
     * @param string|null $alias alias for joined table
     * @param string|array $condition join condition 
     */
    public function __construct(string $type, string $source, ?string $alias = null, $condition = null)
    {
        $this->type = $type;
        $this->source = $source;
        $this->alias = $alias;
        $this->condition = $condition;
    }

    public function __toString()
    {
        $out = sprintf("%s JOIN %s", $this->type, $this->source);
        if ($this->alias) {
            $out .= " AS " . $this->alias;
        }
        if ($this->condition) {
            if (is_array($this->condition)) {
                $out .= " USING (" . implode(", ", $this->condition) . ")";
            } else {
                $out .= " ON (" . $this->condition . ")";
            }
        }
        return $out;
    }

}
