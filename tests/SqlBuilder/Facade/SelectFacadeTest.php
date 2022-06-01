<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace SqlBuilder\Facade;

use PHPUnit\Framework\TestCase;
use SqlBuilder\Model\Query\SelectQuery;
use SqlBuilder\Model\QueryPart\Column\Column;
use SqlBuilder\Model\QueryPart\Condition\Condition;

class SelectFacadeTest extends TestCase
{

    /**
     * Test building SQL
     */
    public function testBuildSql(): void
    {
        $facade = new Select(new SelectQuery("ExampleTable"));

        $facade->with([
            'var1' => '152',
            'var2' => 'SomeValue',
            'subQuery' => new SelectQuery("AnotherTable"),
        ])
            ->select([
                "Field1",
                ["Field2", "F2"],
                new Column("Field3", "F3")
            ])
            ->where([
                new Condition('Field1', '>', 20),
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
        $this->assertSame($expectedSQL, $facade->toSql());
    }

    /**
     * Test `selectFrom` static method
     * @return void
     */
    public static function testSelectFrom(): void
    {
        $facade = Select::selectFrom("Users");
        self::assertEquals("SELECT * FROM Users", $facade->toSql());

        $facade = Select::selectFrom("Users", "U");
        self::assertEquals("SELECT * FROM Users AS U", $facade->toSql());

        $facade = Select::selectFrom(["Users", "Clients"]);
        self::assertEquals("SELECT * FROM (SELECT * FROM Users) UNION ALL (SELECT * FROM Clients)", $facade->toSql());

        $facade = Select::selectFrom([
            ["Users", "U"],
            ["Clients", "C"]
        ]);
        self::assertEquals("SELECT * FROM (SELECT * FROM Users AS U) UNION ALL (SELECT * FROM Clients AS C)", $facade->toSql());

        $facade = Select::selectFrom([
            Select::selectFrom('Users', 'U'),
            Select::selectFrom('Clients', 'C'),
        ]);
        self::assertEquals("SELECT * FROM (SELECT * FROM Users AS U) UNION ALL (SELECT * FROM Clients AS C)", $facade->toSql());

        $facade = Select::selectFrom([
            new SelectQuery('Users', 'U'),
            new SelectQuery('Clients', 'C'),
        ]);
        self::assertEquals("SELECT * FROM (SELECT * FROM Users AS U) UNION ALL (SELECT * FROM Clients AS C)", $facade->toSql());
    }

    /**
     * Test create facade with empty query
     * @return void
     */
    public function testCreateSampleQuery(): void
    {
        $facade = new Select(new SelectQuery("ExampleTable"));
        $this->assertSame(
            "SELECT * FROM ExampleTable",
            $facade->toSql()
        );

        $facade = new Select(new SelectQuery("ExampleTable", "ET"));
        $this->assertSame(
            "SELECT * FROM ExampleTable AS ET",
            $facade->toSql()
        );

        $facade = Select::selectFrom("ExampleTable");
        $this->assertSame(
            "SELECT * FROM ExampleTable",
            $facade->toSql()
        );

        $facade = Select::selectFrom("ExampleTable", "ET");
        $this->assertSame(
            "SELECT * FROM ExampleTable AS ET",
            $facade->toSql()
        );
    }

    /**
     * Test WITH
     */
    public function testWith(): void
    {
        $facade = new Select(new SelectQuery("ExampleTable"));
        $facade->with([
            'var1' => '152',
            'var2' => 'SomeValue',
            'subQuery' => new SelectQuery("AnotherTable"),
        ]);

        $this->assertSame(
            "WITH 152 AS var1, SomeValue AS var2, subQuery AS (SELECT * FROM AnotherTable) SELECT * FROM ExampleTable",
            $facade->toSql()
        );
    }

    /**
     * Test SELECT
     */
    public function testSelect(): void
    {
        $facade = new Select(new SelectQuery("ExampleTable"));
        $facade->select([
            "Field1",
            ["Field2", "F2"],
            new Column("Field3", "F3")
        ]);

        $this->assertSame(
            "SELECT Field1, Field2 AS F2, Field3 AS F3 FROM ExampleTable",
            $facade->toSql()
        );
    }

    /**
     * Test JOIN
     */
    public function testJoin(): void
    {
        $ordersSubqueryFacade = new Select(new SelectQuery("Orders"));
        $ordersSubqueryFacade->where("date BETWEEN '2021-01-01' AND '2021-01-31'");

        $facade = new Select(new SelectQuery("Users", "U"));
        $facade->select([
            "U.Login",
            ["D.Name", "Department"],
            ["R.Name", "Role"],
            ["O.ID", "OrderID"]
        ])
            ->leftJoin("Departments", "D", new Condition('U.DepartmentID', '=', 'D.ID'))
            ->rightJoin(new SelectQuery("Roles"), "R", 'U.RoleID = R.ID')
            ->innerJoin($ordersSubqueryFacade, "O", 'U.ID = O.UserID');

        $this->assertSame(
            "SELECT U.Login, D.Name AS Department, R.Name AS Role, O.ID AS OrderID FROM Users AS U LEFT JOIN Departments AS D ON ((U.DepartmentID = D.ID)) RIGHT JOIN (SELECT * FROM Roles) AS R ON (U.RoleID = R.ID) INNER JOIN (SELECT * FROM Orders WHERE date BETWEEN '2021-01-01' AND '2021-01-31') AS O ON (U.ID = O.UserID)",
            $facade->toSql()
        );
    }

    /**
     * Test WHERE
     */
    public function testWhere(): void
    {
        $facade = new Select(new SelectQuery("ExampleTable"));
        $facade->where([
            new Condition('Field1', '>', 20),
            ['AND', 'Field2', '=', 15],
            new Condition('Field3', 'IN', [1, '4', 5, 'str', true])
        ]);

        $this->assertSame(
            "SELECT * FROM ExampleTable WHERE ((Field1 > 20) AND (Field2 = 15) AND (Field3 IN (1, '4', 5, 'str', TRUE)))",
            $facade->toSql()
        );
    }

    /**
     * Test LIMIT
     */
    public function testLimit(): void
    {
        $facade = new Select(new SelectQuery("ExampleTable"));
        $facade->limit(50, 450);

        $this->assertSame(
            "SELECT * FROM ExampleTable LIMIT 50 OFFSET 450",
            $facade->toSql()
        );
    }

    /**
     * Test GROUP BY
     */
    public function testGroupBy(): void
    {
        $facade = new Select(new SelectQuery("ExampleTable"));
        $facade->groupBy(['UserID', 'GroupID']);

        $this->assertSame(
            "SELECT * FROM ExampleTable GROUP BY UserID, GroupID",
            $facade->toSql()
        );
    }

    /**
     * Test HAVING
     */
    public function testHaving(): void
    {
        $facade = new Select(new SelectQuery("ExampleTable"));
        $facade->groupBy(['UserID', 'GroupID'])
            ->having([
                ['count(*)', '>', 15],
                ['OR', 'count(*)', '<', 10]
            ]);

        $this->assertSame(
            "SELECT * FROM ExampleTable GROUP BY UserID, GroupID HAVING ((count(*) > 15) OR (count(*) < 10))",
            $facade->toSql()
        );
    }
}
