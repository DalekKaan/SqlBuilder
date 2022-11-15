<?php

namespace DalekKaan\SqlBuilder\Model\Query;

use DalekKaan\SqlBuilder\Model\QueryPart\Condition\Condition;
use DalekKaan\SqlBuilder\Model\QueryPart\Set\SetPairStatement;
use DalekKaan\SqlBuilder\Model\RawSQLStatement;
use PHPUnit\Framework\TestCase;

class UpdateQueryTest extends TestCase
{
    public function testBuildSql(): void {
        $query = new UpdateQuery(
            "Users",
            [
                new SetPairStatement("Name", new RawSQLStatement("'John'")),
                new SetPairStatement("Surname", new RawSQLStatement("'Smith'"))
            ],
            new Condition("Age", ">", "18")
        );

        self::assertEquals("UPDATE Users SET Name = 'John', Surname = 'Smith' WHERE (Age > 18)", $query->toSQL());
    }

}
