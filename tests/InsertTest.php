<?php

namespace DalekKaan\SqlBuilder;

use PHPUnit\Framework\TestCase;
use DalekKaan\SqlBuilder\Model\Query\InsertQuery;
use DalekKaan\SqlBuilder\Model\Query\SelectQuery;
use DalekKaan\SqlBuilder\Model\QueryPart\Column\Column;

class InsertTest extends TestCase
{
    public function testBuildSql(): void
    {
        $insertStmt = new InsertQuery("Users");
        $this->assertEquals("INSERT INTO Users VALUES ()", $insertStmt->toSQL());

        $insertStmt = new InsertQuery("Users");
        $insertStmt->setColumns(['name', 'surname', 'age']);
        $this->assertEquals("INSERT INTO Users (name, surname, age) VALUES ()", $insertStmt->toSQL());

        $insertStmt = new InsertQuery("Users");
        $insertStmt->setColumns(['name', 'surname', 'age'])
            ->setValues(['John', 'Smith', 25]);
        $this->assertEquals("INSERT INTO Users (name, surname, age) VALUES ('John', 'Smith', 25)", $insertStmt->toSQL());

        $insertStmt = new InsertQuery("Users");
        $insertStmt->setColumns(['name', 'surname', 'age'])
            ->setValues([['John', 'Smith', 25], ['Alan', 'Clark']]);
        $this->assertEquals("INSERT INTO Users (name, surname, age) VALUES ('John', 'Smith', 25), ('Alan', 'Clark')", $insertStmt->toSQL());

        $selectStmt = new SelectQuery("Clients");
        $selectStmt->addColumn(new Column("name"));
        $selectStmt->addColumn(new Column("surname"));
        $selectStmt->addColumn(new Column("age"));
        $insertStmt = new InsertQuery("Users");    
        $insertStmt->setColumns(['name', 'surname', 'age'])
            ->setSelect($selectStmt);
        $this->assertEquals("INSERT INTO Users (name, surname, age) SELECT name, surname, age FROM Clients", $insertStmt->toSQL());
    }
}
