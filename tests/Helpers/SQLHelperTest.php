<?php

namespace DalekKaan\SqlBuilder\Helpers;

use PHPUnit\Framework\TestCase;
use DalekKaan\SqlBuilder\Model\QueryPart\Condition\ConditionModel;

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
                "Field = 10",
                "Field = 10"
            ],
            [
                new ConditionModel("Field", "=", 10),
                "(Field = 10)"
            ],
            [
                ["Field"],
                "(Field = TRUE)"
            ],
            [
                ["Field", 15],
                "(Field = 15)"
            ],
            [
                ["Field", '=', 15],
                "(Field = 15)"
            ],
            [
                ["Field" => 15],
                "(Field = 15)"
            ],
            [
                ["Field", "IN", "(15, 13, 11)"],
                "(Field IN (15, 13, 11))"
            ],
            [
                ["Field", "IN", [15, 13, 11]],
                "(Field IN (15, 13, 11))"
            ],
            [
                ["Field" => [15, 13, 11]],
                "(Field IN (15, 13, 11))"
            ],
            [
                ["Field", "BETWEEN", "10 AND 17"],
                "(Field BETWEEN 10 AND 17)"
            ],
            [
                ["Field", "BETWEEN", [10, 17]],
                "(Field BETWEEN 10 AND 17)"
            ],
            
            [
                ["Field", ["Field2", "=", 70]],
                "(Field AND (Field2 = 70))"
            ],
            [
                [["Field", "=", 50], ["Field2", "=", 65]],
                "((Field = 50) AND (Field2 = 65))"
            ],
            [
                [["Field", "=", 50], "OR", ["Field2", "=", 65]],
                "((Field = 50) OR (Field2 = 65))"
            ],
            [
                [["Field", "=", 50], ["Field2", "=", 65], ["Field3", "=", 80]],
                "((Field = 50) AND (Field2 = 65) AND (Field3 = 80))"
            ],
            [
                [["Field", "=", 50], "OR", ["Field2", "=", 65], "OR", ["Field3", "=", 80]],
                "((Field = 50) OR (Field2 = 65) OR (Field3 = 80))"
            ],
            [
                [["Field", "=", 50], "OR", ["Field2", "=", 65], ["Field3", "=", 80]],
                "((Field = 50) OR (Field2 = 65) AND (Field3 = 80))"
            ]
        ];
    }


}
