<?php

namespace DalekKaan\SqlBuilder\Model\QueryPart\Set;

use DalekKaan\SqlBuilder\Model\RawSQLStatement;
use PHPUnit\Framework\TestCase;

class SetPairTest extends TestCase
{
    public function testBuildSql(): void {
        $query = new SetPairStatement(
            "Name",
            new RawSQLStatement("'John'")
        );
        self::assertEquals("Name = 'John'", $query->toSQL());

        $query = new SetPairStatement(
            "Name",
            new RawSQLStatement("'John'")
        );
        self::assertEquals("Name = 'John'", $query->toSQL());
    }

}
