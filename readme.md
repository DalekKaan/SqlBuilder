# SQL Builder 

Useful to build some simple SQL queries with php interfaces.


## Usage

General example.

```php
$queryFacade = new SelectFacade(new Query("Users", "U"));

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

### Creating queries

Create a simple query to table `Users`:

```php
$queryFacade = new SelectFacade(new Query("Users"));
$sql = $queryFacade->buildSql();
```
It will result in:
```sql
SELECT * FROM Users
```

Create a query to table `Users` with alias `U`:

```php
$queryFacade = new SelectFacade(new Query("Users", "U"));
$sql = $queryFacade->buildSql();
```
It will result in:
```sql
SELECT * FROM Users AS U
```

Create a query to some subquery `Users` with alias `U`:

```php
$subqueryFacade = new SelectFacade(new Query("Users", "U"));
$queryFacade = new SelectFacade(new Query($subqueryFacade, "SQ"));
$sql = $queryFacade->buildSql();
```
It will result in:
```sql
SELECT * FROM ((SELECT * FROM Users AS U)) AS SQ
```

### Specifying columns

```php
$queryFacade = new SelectFacade(new Query("Users"));
$queryFacade->select([
    'Name',
    25,
    ['PID', 'ParentID'],
    'SNN' => "'123-456-789 11'",
    new Column('Age > 18', 'IsAdult')
]);

$sql = $queryFacade->buildSql();
```
It will result in:
```sql
SELECT Name, 25, PID AS ParentID, '123-456-789 11' AS SNN, Age > 18 AS IsAdult FROM Users
```

### Joining tables

```php
$ordersSubqueryFacade = new SelectFacade(new Query("Orders"));
$ordersSubqueryFacade->where("date BETWEEN '2021-01-01' AND '2021-01-31'");

$queryFacade = new SelectFacade(new Query("Users", "U"));
$queryFacade->select([
    "U.Login",
    ["D.Name", "Department"],
    ["R.Name", "Role"],
    ["O.ID", "OrderID"]
])
    ->leftJoin("Departments", "D", new ConditionStmt('U.DepartmentID', '=', 'D.ID'))
    ->rightJoin(new Query("Roles"), "R", 'U.RoleID = R.ID')
    ->innerJoin($ordersSubqueryFacade, "O", 'U.ID = O.UserID');

$sql = $queryFacade->buildSql();
```
It will result in:
```sql
SELECT U.Login,
       D.Name AS Department,
       R.Name AS Role,
       O.ID AS OrderID
FROM Users AS U
    LEFT JOIN Departments AS D ON ((U.DepartmentID = D.ID)) 
    RIGHT JOIN (SELECT * FROM Roles) AS R ON (U.RoleID = R.ID) 
    INNER JOIN (
        SELECT * FROM Orders WHERE date BETWEEN '2021-01-01' AND '2021-01-31'
                             ) AS O ON (U.ID = O.UserID)
```

### Adding conditions

```php
$queryFacade = new SelectFacade(new Query("Users"));
$queryFacade->where([
    new ConditionStmt('Age', '>', 20),
    ['AND', 'DepartmentID', '=', 200],
    new ConditionStmt('SomeProp', 'IN', [1, '4', 5, 'str', true])
]);

$sql = $queryFacade->buildSql();
```
It will result in:
```sql
SELECT * FROM Users WHERE ((Age > 20) AND (DepartmentID = 200) AND (SomeProp IN (1, '4', 5, 'str', TRUE)))
```

### Using aggregation

```php
$queryFacade = new SelectFacade(new Query("Users"));
$queryFacade->select([
    ['count(*)', 'Cnt'],
    'DepartmentID',
    'PositionID',
])
    ->groupBy(['DepartmentID', 'PositionID'])
    ->having([
        ['count(*)', '>', 15],
        ['OR', 'count(*)', '<', 10]
    ]);

$sql = $queryFacade->buildSql();
```
It will result in:
```sql
SELECT count(*) AS Cnt, DepartmentID, PositionID FROM Users GROUP BY DepartmentID, PositionID HAVING ((count(*) > 15) OR (count(*) < 10))
```
