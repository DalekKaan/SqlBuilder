# SQL Builder 

Useful to build some simple SQL queries with php interfaces.


## Usage

General example.

```php
$queryFacade = new QueryFacade(new Query("Users", "U"));

// using `WITH`
$queryFacade->with([
    'var1' => '152',
    'var2' => 'SomeValue',
    'subQuery' => new Query("AnotherTable"),
])

// select columns
    ->select([
        "UserID",
        "GroupID",
        new Column("count(*)", "cnt")
    ])

// joining tables
    ->innerJoin("Posts", "P", "P.UserID = U.ID")
// with conditions
    ->where([
        [
            ["RegistrationDate", ">", "'2021-09-01'"],
            ["RegistrationDate", "<", "'2021-09-30'"] // `AND` by default
        ],
        [
            ['GroupID', '=', '313'],
            ['OR', 'GroupID', '=', '348']
        ]
    ])

// aggregate
    ->groupBy(["UserID", "GroupID"])
    ->having([
        ["count(*)", ">", "10"]
    ])

// limit, offset
    ->limit(5, 2);

$sql = $queryFacade->buildSql();

echo $sql;
```

The result will look like:
c
```sql
WITH 152 AS var1,
    SomeValue AS var2,
    subQuery AS (SELECT * FROM AnotherTable)
SELECT UserID,
       GroupID,
       count(*) AS cnt
FROM Users AS U
         INNER JOIN Posts AS P ON (P.UserID = U.ID)
WHERE (((RegistrationDate > '2021-09-01') AND (RegistrationDate < '2021-09-30')) AND
       ((GroupID = 313) OR (GroupID = 348)))
GROUP BY UserID, GroupID
HAVING ((count(*) > 10))
    LIMIT 5 OFFSET 2
```

