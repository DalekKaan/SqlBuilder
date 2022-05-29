<?php

namespace SqlBuilder\Facade;

use SqlBuilder\Helpers\SqlHelper;
use SqlBuilder\Select;
use SqlBuilder\QueryPart\Column\Column;
use SqlBuilder\QueryPart\Column\IColumn;
use SqlBuilder\QueryPart\Join\CrossJoinStmt;
use SqlBuilder\QueryPart\Join\JoinStmt;
use SqlBuilder\QueryPart\Order\OrderStmt;
use SqlBuilder\QueryPart\With\WithStmt;

/**
 * Facade for query.
 *
 * Usage:
 *
 * ```php
 *  $queryFacade = new QueryFacade(new Query("Users", "U"));
 * 
 *  // using `WITH`
 *  $queryFacade->with([
 *      'var1' => '152',
 *      'var2' => 'SomeValue',
 *      'subQuery' => new Query("AnotherTable"),
 *  ])
 *
 *  // select columns
 *  ->select([
 *      "UserID",
 *      "GroupID",
 *      new Column("count(*)", "cnt")
 *  ]);
 *
 *  // joining tables
 *  ->innerJoin("Posts", "P", "P.UserID = U.ID")
 *
 *  // with conditions
 *  ->where([
 *      [
 *          ["RegistrationDate", ">", "'2021-09-01'"],
 *          ["RegistrationDate", "<", "'2021-09-30'"]
 *      ],
 *      [
 *          ['GroupID', '=', '313'],
 *          ['OR', 'GroupID', '=', '348']
 *      ]
 *  ]);
 *
 *  // aggregate
 *  ->groupBy(["UserID", "GroupID"])
 *  ->having([
 *      ["count(*)", ">", "10"]
 *  ])
 *
 *  // limit, offset
 *  ->limit(5, 2);
 *  $sql = $queryFacade->buildSql();
 *  ```
 */
class SelectFacade
{
    /**
     * Query
     * @var Select
     */
    protected Select $stmt;

    /**
     * Create facade with new query
     * @param Select|string $from table or sub query
     * @param string|null $alias source alias
     * @return static
     */
    public static function selectFrom($from, string $alias = null): self {
        return new self(new Select($from, $alias));
    }

    /**
     * Returns query from this facade
     * @return Select
     */
    public function getStatement(): Select
    {
        return $this->stmt;
    }

    /**
     * @param Select $query query
     */
    public function __construct(Select $query)
    {
        $this->stmt = $query;
    }

    /**
     * Set WITH
     *
     * Example:
     *
     * PHP
     * ```php
     * $queryFacade->with([
     *  'dateFrom' => '2021-10-01',
     *  'dateTo' => '2021-11-01',
     *  'rawData' => $rawDataQuery
     * ])
     * ```
     * Generated SQL
     * ```sql
     * WITH '2021-10-01' AS dateFrom,
     *  '2021-11-01' AS dateTo,
     *  'rawData' AS (
     *      // some sub query from
     *  )
     * ```
     * @param array $with associative array of sub queries or data. Each key will be used an alias gor sub query or data.
     * @return self
     */
    public function with(array $with): self
    {
        foreach ($with as $alias => $data) {
            if ($data instanceof WithStmt) {
                $this->stmt->addWith($data);
            } else {
                $this->stmt->addWith(new WithStmt($data, $alias));
            }
        }
        return $this;
    }

    /**
     * Set SELECT.
     *
     * Example:
     *
     * PHP
     * ```php
     * $queryFacade->select([
     *  new Column('avg(*)', 'averageValue')
     *  ['sum(*)', 'sumValue'],
     *  'maxValue' => 'max(*)',
     *  'Login'
     * ])
     * ```
     * Generated SQL
     * ```sql
     * SELECT avg(*) AS averageValue,
     *  sum(*) AS sumValue,
     *  max(*) AS maxValue,
     *  Login
     * FROM ...
     * ```
     *
     * @param array $columns array of columns data.
     * Each column data be presented as:
     * <ul>
     *  <li>object, that implements `IColumn` interface;</li>
     *  <li>array, that contains:
     *      <ol>
     *          <li>expression;</li>
     *          <li>alias.</li>
     *      </ol>
     *  </li>
     *  <li>string expression. If array is associative, then the key of each item will be used as column's alias</li>
     * </ul>
     * @return self
     */
    public function select(array $columns): self
    {
        foreach ($columns as $alias => $column) {
            if ($column instanceof IColumn) {
                $this->stmt->addColumn($column);
            } else if (is_array($column)) {
                $this->stmt->addColumn(new Column($column[0], $column[1]));
            } else if (!is_string($alias)) {
                $this->stmt->addColumn(new Column($column));
            } else {
                $this->stmt->addColumn(new Column($column, $alias));
            }
        }
        return $this;
    }

    /**
     * Join table or subquery using LEFT JOIN.
     * Special case of `join` method.
     *
     * **Example #1:**
     *
     * PHP
     *
     * ```php
     * $queryFacade->leftJoin($subQueryOrTable, "SQ", ["SomeField", "SomeAnotherField"])
     * ```
     *
     * Generated SQL
     * ```sql
     * ... LEFT JOIN (
     *  // sub query here
     * ) AS SQ USING (SomeField, SomeAnotherField)
     * ```
     *
     * **Example #2:**
     *
     * PHP
     *
     * ```php
     * $queryFacade->leftJoin("Users", null, "Users.ID = UserID")
     * ```
     *
     * Generated SQL
     * ```sql
     * ... LEFT JOIN Users ON (Users.ID = UserID)
     * ```
     *
     * @param string $table table or subquery
     * @param string|null $as alias of joining table or subquery
     * @param string|array|null $on join condition. In case of `USING` statement must contain array of filed names,
     * @return self
     */
    public function leftJoin(string $table, ?string $as = null, $on = null): self
    {
        return $this->join("LEFT", $table, $as, $on);
    }

    /**
     * Join table or subquery using RIGHT JOIN.
     * Special case of `join` method.
     *
     * **Example #1:**
     *
     * PHP
     *
     * ```php
     * $queryFacade->rightJoin($subQueryOrTable, "SQ", ["SomeField", "SomeAnotherField"])
     * ```
     *
     * Generated SQL
     * ```sql
     * ... RIGHT JOIN (
     *  // sub query here
     * ) AS SQ USING (SomeField, SomeAnotherField)
     * ```
     *
     * **Example #2:**
     *
     * PHP
     *
     * ```php
     * $queryFacade->rightJoin("Users", null, "Users.ID = UserID")
     * ```
     *
     * Generated SQL
     * ```sql
     * ... RIGHT JOIN Users ON (Users.ID = UserID)
     * ```
     *
     * @param string $table table or subquery
     * @param string|null $as alias of joining table or subquery
     * @param string|array|null $on join condition. In case of `USING` statement must contain array of filed names,
     * @return self
     */
    public function rightJoin(string $table, ?string $as = null, $on = null): self
    {
        return $this->join("RIGHT", $table, $as, $on);
    }

    /**
     * Join table or subquery using INNER JOIN.
     * Special case of `join` method.
     *
     * **Example #1:**
     *
     * PHP
     *
     * ```php
     * $queryFacade->innerJoin($subQueryOrTable, "SQ", ["SomeField", "SomeAnotherField"])
     * ```
     *
     * Generated SQL
     * ```sql
     * ... INNER JOIN (
     *  // sub query here
     * ) AS SQ USING (SomeField, SomeAnotherField)
     * ```
     *
     * **Example #2:**
     *
     * PHP
     *
     * ```php
     * $queryFacade->innerJoin("Users", null, "Users.ID = UserID")
     * ```
     *
     * Generated SQL
     * ```sql
     * ... INNER JOIN Users ON (Users.ID = UserID)
     * ```
     *
     * @param string $table table or subquery
     * @param string|null $as alias of joining table or subquery
     * @param string|array|null $on join condition. In case of `USING` statement must contain array of filed names,
     * @return self
     */
    public function innerJoin(string $table, ?string $as = null, $on = null): self
    {
        return $this->join("INNER", $table, $as, $on);
    }

    /**
     * Join table or subquery using CROSS JOIN.
     *
     * **Example #1:**
     *
     * PHP
     *
     * ```php
     * $queryFacade->crossJoin($subQueryOrTable, "SQ", ["SomeField", "SomeAnotherField"])
     * ```
     *
     * Generated SQL
     * ```sql
     * ... CROSS JOIN (
     *  // sub query here
     * ) AS SQ
     * ```
     *
     * **Example #2:**
     *
     * PHP
     *
     * ```php
     * $queryFacade->crossJoin("Users", null, "Users.ID = UserID")
     * ```
     *
     * Generated SQL
     * ```sql
     * ... CROSS JOIN Users
     * ```
     *
     * @param string $table table or subquery
     * @param string|null $as alias of joining table or subquery
     * @return self
     */
    public function crossJoin(string $table, ?string $as = null): self
    {
        $this->stmt->addJoin(new CrossJoinStmt($table, $as));
        return $this;
    }

    /**
     * Join table or subquery.
     *
     * **Example #1:**
     *
     * PHP
     *
     * ```php
     * $queryFacade->join('ANY LEFT', $subQueryOrTable, "SQ", ["SomeField", "SomeAnotherField"])
     * ```
     *
     * Generated SQL
     * ```sql
     * ... ANY LEFT JOIN (
     *  // sub query here
     * ) AS SQ USING (SomeField, SomeAnotherField)
     * ```
     *
     * **Example #2:**
     *
     * PHP
     *
     * ```php
     * $queryFacade->join('INNER', "Users", null, "Users.ID = UserID")
     * ```
     *
     * Generated SQL
     * ```sql
     * ... INNER JOIN Users ON (Users.ID = UserID)
     * ```
     *
     * @param string $type type of join ('LEFT', 'RIGHT', 'INNER', ...)
     * @param string $table table or subquery
     * @param string|null $as alias of joining table or subquery
     * @param string|array|null $on join condition. In case of `USING` statement must contain array of filed names,
     * @return self
     */
    public function join(string $type, string $table, ?string $as = null, $on = null): self
    {
        $this->stmt->addJoin(new JoinStmt($type, $table, $as, $on));
        return $this;
    }

    /**
     * Add columns to `GROUP BY` statement.
     *
     * **Example:**
     *
     * PHP     *
     * ```php
     * $queryFacade->groupBy(["UserID", "ParentID"]);
     * ```
     *
     * Generated SQL
     * ```sql
     *  ...
     *  GROUP BY (UserID, ParentID)
     * ```
     * @param string[] $fields
     * @return self
     */
    public function groupBy(array $fields): self
    {
        foreach ($fields as $field) {
            $this->stmt->addGroupBy($field);
        }
        return $this;
    }

    /**
     * Add conditions to `HAVING` statement.
     *
     *
     * **Example:**
     *
     * PHP
     * ```php
     * $queryFacade->having([
     *  ["count(*)", ">", 15],
     *  ["AND", "sum(Views)", "<", 400],
     *  new ConditionStmt("avg(Age)", "<", 25, "OR"),
     *  "max(Age) > 30",
     *  new ConditionsGroup([
     *      new ConditionStmt("min(Age)", ">", 18)
     *      new ConditionStmt("count(*)", ">", 40)
     *  ], "OR")
     * ]);
     * ```
     *
     * Generated SQL
     * ```sql
     *  ...
     *  HAVING (count(*) > 15)
     *      AND (sum(Views) < 400)
     *      OR (avg(Age) < 25)
     *      AND (max(Age) > 30)
     *      OR (
     *          (min(Age) > 18)
     *          AND (count(*) > 40)
     *      )
     * ```
     * @param mixed $data conditions data (see example)
     * @return self
     */
    public function having($data): self
    {
        $this->stmt->setHawing(SqlHelper::makeCondition($data));
        return $this;
    }

    /**
     * Add conditions to `WHERE` statement.
     *
     * **Example:**
     *
     * PHP
     * ```php
     * $queryFacade->where([
     *  ["Age", ">", 15],
     *  ["AND", "Views", "<", 400],
     *  new ConditionStmt("Rating", "<", 25, "OR"),
     *  "Posts > 30",
     *  new ConditionsGroup([
     *      new ConditionStmt("Age", ">", 18)
     *      new ConditionStmt("City", "=", "New York")
     *  ], "OR")
     * ]);
     * ```
     *
     * Generated SQL
     * ```sql
     *  ...
     *  WHERE (Age > 15)
     *      AND (Views < 400)
     *      OR (Rating < 25)
     *      AND (Posts > 30)
     *      OR (
     *          (Age > 18)
     *          AND (City = 'New York')
     *      )
     * ```
     * @param mixed $conditions conditions data (see example)
     * @return self
     */
    public function where($conditions): self
    {
        $this->stmt->setWhere(SqlHelper::makeCondition($conditions));
        return $this;
    }

    /**
     * Set limit and offset
     *
     * **Example #1:**
     *
     * PHP
     * ```php
     * $queryFacade->limit(10);
     * ```
     *
     * Generated SQL
     * ```sql
     *  ...
     *  LIMIT 10
     * ```
     *
     * **Example #2:**
     *
     * PHP
     * ```php
     * $queryFacade->limit(5, 25);
     * ```
     *
     * Generated SQL
     * ```sql
     *  ...
     *  LIMIT 5 OFFSET 25
     * ```
     *
     * @param integer $limit
     * @param integer|null $offset
     * @return self
     */
    public function limit(int $limit, ?int $offset = null): self
    {
        $this->stmt->setLimit($limit);

        if ($offset !== null) {
            $this->stmt->setOffset($offset);
        }
        return $this;
    }

    /**
     * Add columns to `ORDER BY` statement
     *
     * **Example:**
     *
     * PHP
     * ```php
     * $queryFacade->orderBy(["Name", "Age"]);
     * ```
     *
     * Generated SQL
     * ```sql
     *  ...
     *  ORDER BY Name, Age
     * ```
     *
     * @param array $fields
     * @return self
     */
    public function orderBy(array $fields): self
    {
        foreach ($fields as $field) {
            if ($field instanceof OrderStmt) {
                $this->stmt->addOrderBy($field);
            } elseif (is_array($field)) {
                $this->stmt->addOrderBy(new OrderStmt($field[0], $field[1] ?? OrderStmt::DIRECTION_ASC));
            } else {
                $this->stmt->addOrderBy(new OrderStmt($field));
            }
        }
        return $this;
    }

    /**
     * Build sql using Query::buildSql() method
     * @return string
     */
    public function buildSql(): string
    {
        return $this->stmt->buildSql();
    }

    public function __toString()
    {
        return $this->stmt->__toString();
    }
}
