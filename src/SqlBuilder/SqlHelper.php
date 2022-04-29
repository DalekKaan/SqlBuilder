<?php

namespace SqlBuilder;

/**
 * SQL helper
 */
class SqlHelper
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
     * @return ICondition
     */
    public static function makeCondition($data): ICondition {
        if ($data instanceof ICondition) {
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
        return new SimpleCondition($data);
    }

    /**
     * Make condition from array
     * @param array $data data for condition
     * @return ICondition
     */
    protected static function makeConditionFromArray(array $data): ICondition {
        $count = count($data);
        
        switch ($count) {
            case 0:
                return new SimpleCondition("TRUE");
            case 1:
                return new ConditionStmt($data[0], '=', self::VALUE_TRUE);
            case 2:
                return new ConditionStmt($data[0], '=', $data[1]);
            case 3:
                return new ConditionStmt($data[0], $data[1], $data[2]);
            default:
                return new ConditionStmt($data[1], $data[2], $data[3], $data[0]);
        }
    }
}
