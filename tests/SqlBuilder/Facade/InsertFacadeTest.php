<?php

namespace SqlBuilder\Facade;

use PHPUnit\Framework\TestCase;
use SqlBuilder\Insert;
use SqlBuilder\Select;

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
        $stmt = InsertFacade::into('Users');        
        self::assertEquals("INSERT INTO Users VALUES ()", $stmt->buildSQL());
        
        $stmt = InsertFacade::into('Users', ['name', 'surname', 'age']);        
        self::assertEquals("INSERT INTO Users (name, surname, age) VALUES ()", $stmt->buildSQL());
        
        $stmt = InsertFacade::into('Users', ['name', 'surname', 'age'], 
        [['John', 'Smith', 25], ['Alan', 'Clark']]);        
        self::assertEquals("INSERT INTO Users (name, surname, age) VALUES ('John', 'Smith', 25), ('Alan', 'Clark')", $stmt->buildSQL());
        
        $select = new Select("Clients");
        $select->addColumn('name');
        $select->addColumn('surname');
        $select->addColumn('age');
        $stmt = InsertFacade::into('Users', ['name', 'surname', 'age'],
            $select);        
        self::assertEquals("INSERT INTO Users (name, surname, age) (SELECT name, surname, age FROM Clients)", $stmt->buildSQL());
    }

    /**
     * Test `values` method
     * @return void
     */
    public function testValues():void {
        $stmt = new InsertFacade(new Insert("Users"));
        $stmt->values(['John', 'Smith', 25]);
        self::assertEquals("INSERT INTO Users VALUES ('John', 'Smith', 25)", $stmt->buildSQL());
            
        $stmt = new InsertFacade(new Insert("Users"));
        $stmt->values([['John', 'Smith', 25], ['Alan', 'Clark']]);
        self::assertEquals("INSERT INTO Users VALUES ('John', 'Smith', 25), ('Alan', 'Clark')", $stmt->buildSQL());
    }


}
