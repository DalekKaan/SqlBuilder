<?php

namespace tests\SqlBuilder;

use PHPUnit\Framework\TestCase;
use SqlBuilder\Facade\QueryFacade;
use SqlBuilder\Query;
use SqlBuilder\QueryPart\Column\Column;
use SqlBuilder\QueryPart\Condition\ConditionStmt;

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
            ->limit(50, 450)
            ->groupBy(["Field3", "Field4"]);

        $expectedSQL = 'WITH 152 AS var1, SomeValue AS var2, subQuery AS (SELECT * FROM AnotherTable) ' .
            'SELECT Field1, Field2 AS F2, Field3 AS F3 ' .
            'FROM ExampleTable ' .
            'WHERE ((Field1 > 20) AND (Field2 = 15)) ' .
            'GROUP BY Field3, Field4 ' .
            'LIMIT 50 OFFSET 450';
        $this->assertSame($expectedSQL, $facade->buildSql());
    }

    /**
     * Test create facade with empty query
     * @return void
     */
    public function testCreateSampleQuery(): void {
        $facade = new QueryFacade(new Query("ExampleTable"));
        $this->assertSame(
            "SELECT * FROM ExampleTable",
            $facade->buildSql()
        );
        
        $facade = new QueryFacade(new Query("ExampleTable", "ET"));
        $this->assertSame(
            "SELECT * FROM ExampleTable AS ET",
            $facade->buildSql()
        );
        
        $facade = QueryFacade::newQuery("ExampleTable");
        $this->assertSame(
            "SELECT * FROM ExampleTable",
            $facade->buildSql()
        );

        $facade = QueryFacade::newQuery("ExampleTable", "ET");
        $this->assertSame(
            "SELECT * FROM ExampleTable AS ET",
            $facade->buildSql()
        );
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
     * Test JOIN
     */
    public function testJoin(): void
    {
        $ordersSubqueryFacade = new QueryFacade(new Query("Orders"));
        $ordersSubqueryFacade->where("date BETWEEN '2021-01-01' AND '2021-01-31'");

        $facade = new QueryFacade(new Query("Users", "U"));
        $facade->select([
            "U.Login",
            ["D.Name", "Department"],
            ["R.Name", "Role"],
            ["O.ID", "OrderID"]
        ])
            ->leftJoin("Departments", "D", new ConditionStmt('U.DepartmentID', '=', 'D.ID'))
            ->rightJoin(new Query("Roles"), "R", 'U.RoleID = R.ID')
            ->innerJoin($ordersSubqueryFacade, "O", 'U.ID = O.UserID');

        $this->assertSame(
            "SELECT U.Login, D.Name AS Department, R.Name AS Role, O.ID AS OrderID FROM Users AS U LEFT JOIN Departments AS D ON ((U.DepartmentID = D.ID)) RIGHT JOIN (SELECT * FROM Roles) AS R ON (U.RoleID = R.ID) INNER JOIN (SELECT * FROM Orders WHERE date BETWEEN '2021-01-01' AND '2021-01-31') AS O ON (U.ID = O.UserID)",
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
