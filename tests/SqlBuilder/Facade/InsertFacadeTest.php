<?php

namespace SqlBuilder\Facade;

use PHPUnit\Framework\TestCase;
use SqlBuilder\Query\InsertQuery;
use SqlBuilder\Query\SelectQuery;
use SqlBuilder\QueryPart\Column\Column;

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
        self::assertEquals("INSERT INTO Users VALUES ()", $stmt->buildSQL());
        
        $stmt = Insert::into('Users', ['name', 'surname', 'age']);        
        self::assertEquals("INSERT INTO Users (name, surname, age) VALUES ()", $stmt->buildSQL());
        
        $stmt = Insert::into('Users', ['name', 'surname', 'age'], 
        [['John', 'Smith', 25], ['Alan', 'Clark']]);        
        self::assertEquals("INSERT INTO Users (name, surname, age) VALUES ('John', 'Smith', 25), ('Alan', 'Clark')", $stmt->buildSQL());
        
        $select = new SelectQuery("Clients");
        $select->addColumn(new Column('name'));
        $select->addColumn(new Column('surname'));
        $select->addColumn(new Column('age'));
        $stmt = Insert::into('Users', ['name', 'surname', 'age'],
            $select);        
        self::assertEquals("INSERT INTO Users (name, surname, age) SELECT name, surname, age FROM Clients", $stmt->buildSQL());
    }

    /**
     * Test `values` method
     * @return void
     */
    public function testValues():void {
        $stmt = new Insert(new InsertQuery("Users"));
        $stmt->values(['John', 'Smith', 25]);
        self::assertEquals("INSERT INTO Users VALUES ('John', 'Smith', 25)", $stmt->buildSQL());
            
        $stmt = new Insert(new InsertQuery("Users"));
        $stmt->values([['John', 'Smith', 25], ['Alan', 'Clark']]);
        self::assertEquals("INSERT INTO Users VALUES ('John', 'Smith', 25), ('Alan', 'Clark')", $stmt->buildSQL());
    }


}
