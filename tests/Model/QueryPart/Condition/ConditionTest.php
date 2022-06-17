<?php

namespace DalekKaan\SqlBuilder\Model\QueryPart\Condition;

use DalekKaan\SqlBuilder\SQLStatementInterface;
use PHPUnit\Framework\TestCase;

/**
 * Condition test
 */
class ConditionTest extends TestCase
{
    /**
     * @dataProvider getDataSets
     * @param $left string|SQLStatementInterface left side
     * @param $right string|SQLStatementInterface right side
     * @param $operator string
     * @param $expected string
     * @return void
     */
    public function testToSQL($left, string $operator, $right, string $expected): void
    {
        $condition = new Condition($left, $operator, $right);
        self::assertEquals($expected, $condition->toSQL());
    }

    /**
     * Get data sets
     * @return array
     */
    public function getDataSets(): array
    {
        return [
            ['Field', '=', 1, 'Field = 1'],
            ['Field', 'IN', [1, 3, 5], 'Field IN (1, 3, 5)'],
            ['Field', 'IN', '(1, 3, 5)', 'Field IN (1, 3, 5)'],
            ['Field', 'BETWEEN', [1, 5], 'Field BETWEEN 1 AND 5'],
            ['Field', 'AND', new Condition('Field2', '=', '2'), 'Field AND (Field2 = 2)']
        ];
    }

}
