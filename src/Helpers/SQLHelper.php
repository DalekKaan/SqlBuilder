<?php

namespace DalekKaan\SqlBuilder\Helpers;

use DalekKaan\SqlBuilder\Model\Query\QueryInterface;
use DalekKaan\SqlBuilder\Model\QueryPart\Condition\Condition;
use DalekKaan\SqlBuilder\Model\QueryPart\Condition\ConditionInterface;
use DalekKaan\SqlBuilder\Model\QueryPart\Condition\ConditionsGroup;
use DalekKaan\SqlBuilder\Model\QueryPart\Condition\RawCondition;
use DalekKaan\SqlBuilder\SQLStatementInterface;

/**
 * SQL helper
 */
class SQLHelper
{
    /**
     * Representation of `true` value
     */
    protected const VALUE_TRUE = 'TRUE';

    /**
     * Representation of `false` value
     */
    protected const VALUE_FALSE = 'FALSE';

    /**
     * Representation of `null` value
     */
    protected const VALUE_NULL = 'NULL';

    /**
     * Implode SQL statements with separator
     * @param string $separator separator
     * @param array $statements statements
     * @return string
     */
    public static function implodeStatements(string $separator, array $statements): string
    {
        return implode($separator, array_map(static fn(SQLStatementInterface $statement) => $statement->toSQL(), $statements));
    }

    /**
     * Present scalar data for SQL
     * @param mixed $value
     * @return string
     */
    public static function scalarToSQL($value): string
    {
        if (is_array($value)) {
            $out = "(";
            foreach ($value as $index => $item) {
                if ($index !== 0) {
                    $out .= ", ";
                }
                $out .= self::scalarToSQL($item);
            }
            $out .= ")";
            return $out;
        }

        if (is_string($value)) {
            return "'$value'";
        }

        if (is_numeric($value)) {
            return (string)$value;
        }

        if (is_bool($value)) {
            return $value ? self::VALUE_TRUE : self::VALUE_FALSE;
        }

        if ($value === null) {
            return self::VALUE_NULL;
        }
        return "";
    }

    /**
     * Make condition
     * @param mixed $data
     * @return ConditionInterface
     */
    public static function makeCondition($data): ConditionInterface
    {
        if ($data instanceof ConditionInterface) {
            return $data;
        }

        if (is_array($data)) {
            $isArrayCondition = true;
            foreach ($data as $item) {
                if (!is_scalar($item)) {
                    $isArrayCondition = false;
                    break;
                }
            }
            if ($isArrayCondition) {
                return self::makeConditionFromArray($data);
            }

            $conditionGroup = new ConditionsGroup([]);
            foreach ($data as $item) {
                $conditionGroup->addCondition(self::makeCondition($item));
            }
            return $conditionGroup;
        }
        return new RawCondition($data);
    }

    /**
     * Make condition from array
     * @param array $data data for condition
     * @return ConditionInterface
     */
    protected static function makeConditionFromArray(array $data): ConditionInterface
    {
        $count = count($data);

        switch ($count) {
            case 0:
                return new RawCondition("TRUE");
            case 1:
                return new Condition($data[0], '=', self::VALUE_TRUE);
            case 2:
                return new Condition($data[0], '=', $data[1]);
            case 3:
                return new Condition($data[0], $data[1], $data[2]);
            default:
                return new Condition($data[1], $data[2], $data[3], $data[0]);
        }
    }

    /**
     * Wrap data source
     * @param QueryInterface|string $source the string to check
     * @return string
     */
    public static function wrapDataSource($source): string {
        if ($source instanceof QueryInterface) {
            return "({$source->toSQL()})";
        }
        if ($source==="") {
            return "";
        }
        if (strpos($source, " ") !== false) {
            return "($source)";
        }
        return $source;
    }
    
}
