<?php

namespace SqlBuilder\DB\Structure;

/**
 * Database stricture factory
 */
class StructureFactory
{
    /**
     * Read structure from DB connection
     * @param \PDO $connection DB connection
     * @return Database
     */
    public static function formConnection(\PDO $connection): Database {
        // todo: implement
        return new Database(); // stub
    }
}
