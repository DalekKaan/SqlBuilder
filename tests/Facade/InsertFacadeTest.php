<?php

namespace SqlBuilder\Facade;

use PHPUnit\Framework\TestCase;
use SqlBuilder\Model\Query\InsertQuery;
use SqlBuilder\Model\Query\SelectQuery;
use SqlBuilder\Model\QueryPart\Column\Column;

/**
 * Test for InsertFacade
 */
class InsertFacadeTest extends TestCase
{
    /**
     * Test `into()` method
     * @return void
     */
    public function testInto(): void
    {
        $stmt = Insert::into('Users');        
        self::assertEquals("INSERT INTO Users VALUES ()", $stmt->toSql());
        
        $stmt = Insert::into('Users', ['name', 'surname', 'age']);        
        self::assertEquals("INSERT INTO Users (name, surname, age) VALUES ()", $stmt->toSql());
        
        $stmt = Insert::into('Users', ['name', 'surname', 'age'], 
        [['John', 'Smith', 25], ['Alan', 'Clark']]);        
        self::assertEquals("INSERT INTO Users (name, surname, age) VALUES ('John', 'Smith', 25), ('Alan', 'Clark')", $stmt->toSql());
        
        $select = new SelectQuery("Clients");
        $select->addColumn(new Column('name'));
        $select->addColumn(new Column('surname'));
        $select->addColumn(new Column('age'));
        $stmt = Insert::into('Users', ['name', 'surname', 'age'],
            $select);        
        self::assertEquals("INSERT INTO Users (name, surname, age) SELECT name, surname, age FROM Clients", $stmt->toSql());
    }

    /**
     * Test `values` method
     * @return void
     */
    public function testValues():void {
        $stmt = new Insert(new InsertQuery("Users"));
        $stmt->values(['John', 'Smith', 25]);
        self::assertEquals("INSERT INTO Users VALUES ('John', 'Smith', 25)", $stmt->toSql());
            
        $stmt = new Insert(new InsertQuery("Users"));
        $stmt->values([['John', 'Smith', 25], ['Alan', 'Clark']]);
        self::assertEquals("INSERT INTO Users VALUES ('John', 'Smith', 25), ('Alan', 'Clark')", $stmt->toSql());
    }


}
