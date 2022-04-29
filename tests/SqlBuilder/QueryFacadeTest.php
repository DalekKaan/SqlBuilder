<?php

namespace tests\SqlBuilder;

use PHPUnit\Framework\TestCase;
use SqlBuilder\Column;
use SqlBuilder\ConditionStmt;
use SqlBuilder\Query;
use SqlBuilder\QueryFacade;

class QueryFacadeTest extends TestCase
{

    /**
     * Test building SQL
     */
    public function testBuildSql(): void
    {
        $facade = new QueryFacade(new Query("ExampleTable"));

        $facade->with([
            'var1' => '152',
            'var2' => 'SomeValue',
            'subQuery' => new Query("AnotherTable"),
        ])
            ->select([
                "Field1",
                ["Field2", "F2"],
                new Column("Field3", "F3")
            ])
            ->where([
                new ConditionStmt('Field1', '>', 20),
                ['AND', 'Field2', '=', 15]
            ])
            ->limitBy([5, ["UserID", "GroupID"], 10])
            ->limit(50, 450)
            ->groupBy(["Field3", "Field4"]);

        $expectedSQL = 'WITH 152 AS var1, SomeValue AS var2, subQuery AS (SELECT * FROM AnotherTable) ' .
            'SELECT Field1, Field2 AS F2, Field3 AS F3 ' .
            'FROM ExampleTable ' .
            'WHERE ((Field1 > 20) AND (Field2 = 15)) ' .
            'GROUP BY Field3, Field4 ' .
            'LIMIT 5 OFFSET 10 BY UserID, GroupID ' .
            'LIMIT 50 OFFSET 450';
        $this->assertSame($expectedSQL, $facade->buildSql());
    }

    /**
     * Test WITH
     */
    public function testWith(): void
    {
        $facade = new QueryFacade(new Query("ExampleTable"));
        $facade->with([
            'var1' => '152',
            'var2' => 'SomeValue',
            'subQuery' => new Query("AnotherTable"),
        ]);

        $this->assertSame(
            "WITH 152 AS var1, SomeValue AS var2, subQuery AS (SELECT * FROM AnotherTable) SELECT * FROM ExampleTable",
            $facade->buildSql()
        );
    }

    /**
     * Test SELECT
     */
    public function testSelect(): void
    {
        $facade = new QueryFacade(new Query("ExampleTable"));
        $facade->select([
            "Field1",
            ["Field2", "F2"],
            new Column("Field3", "F3")
        ]);

        $this->assertSame(
            "SELECT Field1, Field2 AS F2, Field3 AS F3 FROM ExampleTable",
            $facade->buildSql()
        );
    }

    /**
     * Test WHERE
     */
    public function testWhere(): void
    {
        $facade = new QueryFacade(new Query("ExampleTable"));
        $facade->where([
            new ConditionStmt('Field1', '>', 20),
            ['AND', 'Field2', '=', 15],
            new ConditionStmt('Field3', 'IN', [1, '4', 5, 'str', true])
        ]);

        $this->assertSame(
            "SELECT * FROM ExampleTable WHERE ((Field1 > 20) AND (Field2 = 15) AND (Field3 IN (1, '4', 5, 'str', TRUE)))",
            $facade->buildSql()
        );
    }

    /**
     * Test LIMIT BY
     */
    public function testLimitBy(): void
    {
        $facade = new QueryFacade(new Query("ExampleTable"));
        $facade->limitBy([5, ["UserID", "GroupID"], 10]);

        $this->assertSame(
            "SELECT * FROM ExampleTable LIMIT 5 OFFSET 10 BY UserID, GroupID",
            $facade->buildSql()
        );
    }

    /**
     * Test LIMIT
     */
    public function testLimit(): void
    {
        $facade = new QueryFacade(new Query("ExampleTable"));
        $facade->limit(50, 450);

        $this->assertSame(
            "SELECT * FROM ExampleTable LIMIT 50 OFFSET 450",
            $facade->buildSql()
        );
    }

    /**
     * Test GROUP BY
     */
    public function testGroupBy(): void
    {
        $facade = new QueryFacade(new Query("ExampleTable"));
        $facade->groupBy(['UserID', 'GroupID']);

        $this->assertSame(
            "SELECT * FROM ExampleTable GROUP BY UserID, GroupID",
            $facade->buildSql()
        );
    }

    /**
     * Test HAVING
     */
    public function testHaving(): void
    {
        $facade = new QueryFacade(new Query("ExampleTable"));
        $facade->groupBy(['UserID', 'GroupID'])
            ->having([
                ['count(*)', '>', 15],
                ['OR', 'count(*)', '<', 10]
            ]);

        $this->assertSame(
            "SELECT * FROM ExampleTable GROUP BY UserID, GroupID HAVING ((count(*) > 15) OR (count(*) < 10))",
            $facade->buildSql()
        );
    }
}
