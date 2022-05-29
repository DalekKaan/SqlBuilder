<?php

namespace SqlBuilder;

use PHPUnit\Framework\TestCase;

class InsertTest extends TestCase
{
    public function testBuildSql(): void
    {
        $insertStmt = new Insert("Users");
        $this->assertEquals("INSERT INTO Users VALUES ()", $insertStmt->toSql());

        $insertStmt = new Insert("Users");
        $insertStmt->setColumns(['name', 'surname', 'age']);
        $this->assertEquals("INSERT INTO Users (name, surname, age) VALUES ()", $insertStmt->toSql());

        $insertStmt = new Insert("Users");
        $insertStmt->setColumns(['name', 'surname', 'age'])
            ->setValues(['John', 'Smith', 25]);
        $this->assertEquals("INSERT INTO Users (name, surname, age) VALUES ('John', 'Smith', 25)", $insertStmt->toSql());

        $insertStmt = new Insert("Users");
        $insertStmt->setColumns(['name', 'surname', 'age'])
            ->setValues([['John', 'Smith', 25], ['Alan', 'Clark']]);
        $this->assertEquals("INSERT INTO Users (name, surname, age) VALUES ('John', 'Smith', 25), ('Alan', 'Clark')", $insertStmt->toSql());

        $selectStmt = new Select("Clients");
        $selectStmt->addColumn("name");
        $selectStmt->addColumn("surname");
        $selectStmt->addColumn("age");
        $insertStmt = new Insert("Users");    
        $insertStmt->setColumns(['name', 'surname', 'age'])
            ->setSelect($selectStmt);
        $this->assertEquals("INSERT INTO Users (name, surname, age) (SELECT name, surname, age FROM Clients)", $insertStmt->toSql());
    }
}
