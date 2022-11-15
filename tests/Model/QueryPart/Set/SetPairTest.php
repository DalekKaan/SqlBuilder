<?php

namespace DalekKaan\SqlBuilder\Model\QueryPart\Set;

use DalekKaan\SqlBuilder\Model\Query\SelectQuery;
use DalekKaan\SqlBuilder\Model\QueryPart\Column\Column;
use DalekKaan\SqlBuilder\Model\RawSQLStatement;
use PHPUnit\Framework\TestCase;

class SetPairTest extends TestCase
{
    public function testBuildSql(): void
    {
        $query = new SetPairStatement(
            "Name",
            new RawSQLStatement("'John'")
        );
        self::assertEquals("Name = 'John'", $query->toSQL());

        $query = new SetPairStatement(
            "Name",
            (new SelectQuery("Clients"))
                ->addColumn(new Column('Name'))
                ->setLimit(1)
        );
        self::assertEquals("Name = (SELECT Name FROM Clients LIMIT 1)", $query->toSQL());
    }

}
