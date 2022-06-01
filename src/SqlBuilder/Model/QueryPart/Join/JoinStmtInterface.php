<?php

namespace SqlBuilder\Model\QueryPart\Join;

use SqlBuilder\Helpers\SqlHelper;
use SqlBuilder\Model\Query\SelectQuery;
use SqlBuilder\Model\QueryPart\Condition\ConditionInterface;

class JoinStmtInterface implements JoinInterface
{

    /**
     * Join type
     * @var string
     */
    protected string $type = "LEFT";

    /**
     * Object to join
     * @var string|SelectQuery
     */
    protected $source;

    /**
     * Joining condition
     * @var ConditionInterface
     */
    protected ?ConditionInterface $condition = null;

    /**
     * Alias of joining objet
     * @var string|null
     */
    protected ?string $alias = null;

    /**
     * @param string $type join type: "LEFT", "RIGHT", "INNER", etc.
     * @param string $source table or sub query to join
     * @param string|null $alias alias for joined table
     * @param ConditionInterface|null $condition join condition 
     */
    public function __construct(string $type, string $source, ?string $alias = null, ?ConditionInterface $condition = null)
    {
        $this->type = $type;
        $this->source = $source;
        $this->alias = $alias;
        $this->condition = $condition;
    }

    /**
     * @inheritDoc
     */
    public function toSQL(): string
    {
        $out = sprintf("%s JOIN %s", $this->type, $this->source);
        if ($this->alias) {
            $out .= " AS " . $this->alias;
        }
        if ($this->condition) {
            if (is_array($this->condition)) {
                $out .= " USING (" . SqlHelper::implodeStatements(", ", $this->condition) . ")";
            } else {
                $out .= " ON (" . $this->condition->toSQL() . ")";
            }
        }
        return $out;
    }

}
