<?php

namespace DalekKaan\SqlBuilder\Helpers;

use PHPUnit\Framework\TestCase;
use DalekKaan\SqlBuilder\Model\QueryPart\Condition\Condition;

class SQLHelperTest extends TestCase
{
    /**
     * @dataProvider getValues
     * Test converting simple data to sql
     */
    public function testScalarToSQL($value, $expected): void
    {
        $out = SQLHelper::scalarToSQL($value);
        $this->assertEquals($expected, $out);
    }

    public function getValues(): array
    {
        return [
            [10, "10"],
            ["10", "'10'"],
            [true, "TRUE"],
            [false, "FALSE"],
            [null, "NULL"],
            [[
                10,
                "10",
                "string",
                true,
                false,
                null,
                'null',
                [
                    10,
                    "10",
                    "string",
                    true,
                    false,
                    null,
                    'null'
                ]
            ], "(10, '10', 'string', TRUE, FALSE, NULL, 'null', (10, '10', 'string', TRUE, FALSE, NULL, 'null'))"],
        ];
    }

    /**
     * Test making conditions
     * @dataProvider getConditions
     * @param $condition
     * @param $expected
     */
    public function testMakeCondition($condition, $expected): void
    {
        $this->assertEquals($expected, SQLHelper::makeCondition($condition)->toSQL());
    }

    public function getConditions(): array
    {
        return [
            [
                ["Field", "=", 15],
                "(Field = 15)"
            ],
            [
                new Condition("Field", "IN", [1, 2, '3', 4, 5, true]),
                "(Field IN (1, 2, '3', 4, 5, TRUE))"
            ],
            [
                "myCustomRawCondition = TRUE",
                "myCustomRawCondition = TRUE"
            ]
        ];
    }


}
