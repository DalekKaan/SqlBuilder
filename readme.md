# SQL Builder 

Useful to build some simple SQL queries with php interfaces.

## Usage

### Select

#### General example.

```php
Select::selectFrom("Users", "U")
    ->columns([
        "Name",
        "Surname",
        "Age",
        ["R.Name", "Role"]
    ])
    ->innerJoin("Roles", "R", ["R.ID", "=", "U.RoleID"])
    ->where([
        ["R.ID", 15],
        ["Surname", "LIKE", "'%son'"]
    ])
    ->toSql();
```
```sql
SELECT Name,
       Surname,
       Age,
       R.Name AS Role
FROM Users AS U
    INNER JOIN Roles AS R ON ((R.ID = U.RoleID))
WHERE ((R.ID = 15) AND (Surname LIKE '%son'))
```

#### Creating queries
Creating a simple query to table `Users`:
```php
Select::selectFrom("Users");
```
```sql
SELECT * FROM Users
```

Specifying alias for the table:
```php
Select::selectFrom("Users", "U")
```
```sql
SELECT * FROM Users AS U
```

Using nested queries as source:
```php
Select::selectFrom(
    Select::selectFrom("Clients"),
    "U");
```
```sql
SELECT * FROM (SELECT * FROM Clients) AS U
```

You can also use raw nested queries:
```php
Select::selectFrom(
    "SELECT * FROM Clients WHERE AddDate >= '2020-01-01'",
    "U");
```
```sql
SELECT * FROM (SELECT * FROM Clients WHERE AddDate >= '2020-01-01') AS U
```

#### Specifying columns
General example:
```php
Select::selectFrom("Users")
    ->columns([
        "Name",
        "Surname",
        "Age"
    ]);
```
```sql
SELECT Name, Surname, Age FROM Users
```

Using aliases:
```php
Select::selectFrom("Users")
    ->columns([
        ["Name", "Firstname"],
        ["Surname", "Lastname"],
        ["Age > 18", "IsAdult"]
    ]);
```
```sql
SELECT Name AS Firstname, Surname AS Lastname, Age > 18 AS IsAdult FROM Users
```

Or via associative array:
```php
Select::selectFrom("Users")
    ->columns([
        "Firstname" => "Name",
        "Lastname" => "Surname",
        "IsAdult" => "Age > 18"
    ]);
```
```sql
SELECT Name AS Firstname, Surname AS Lastname, Age > 18 AS IsAdult FROM Users
```

It can be mixed up:
```php
Select::selectFrom("Users")
    ->columns([
        "Name",
        ["Surname", "Lastname"],
        "IsAdult" => "Age > 18"
    ]);
```
```sql
SELECT Name, Surname AS Lastname, Age > 18 AS IsAdult FROM Users
```

#### Joining tables or nested queries
Joining a table:
```php
Select::selectFrom("Users", "U")
    ->leftJoin("Roles", "R", ['U.RoleID', '=', 'R.ID'])
```
```sql
SELECT * FROM Users AS U LEFT JOIN Roles AS R ON ((U.RoleID = R.ID))
```
_Note:_ learn more about conditions in [Adding conditions](#Adding conditions).

Joining nested query:
```php
Select::selectFrom("Users", "U")
    ->leftJoin(
        Select::selectFrom("Roles"),
        "R", ['U.RoleID', '=', 'R.ID']);
```
```sql
SELECT * FROM Users AS U LEFT JOIN (SELECT * FROM Roles) AS R ON ((U.RoleID = R.ID))
```

Or you can use raw nested query string:
```php
Select::selectFrom("Users", "U")
    ->leftJoin(
        "SELECT * FROM Roles",
        "R", ['U.RoleID', '=', 'R.ID']);
```
```sql
SELECT * FROM Users AS U LEFT JOIN (SELECT * FROM Roles) AS R ON ((U.RoleID = R.ID))
```

You can also use `leftJoin`, `rightJoin`, `innerJoin` and `crossJoin` methods.

To specify specific join you should use the `join` method:
```php
Select::selectFrom("Users", "U")
    ->join("FULL OUTER", "Roles", "R", ['U.RoleID', '=', 'R.ID'])
```
```sql
SELECT * FROM Users AS U FULL OUTER JOIN Roles AS R ON ((U.RoleID = R.ID))
```

You also can specify you own raw `JOIN` statement by using `rawJoin` method:
```php
Select::selectFrom("Users", "U")
    ->rawJoin("ANY LEFT JOIN Roles ON (R.ID NOT IN U.RoleID)");
```
```sql
SELECT * FROM Users AS U ANY LEFT JOIN Roles ON (R.ID NOT IN U.RoleID)
```


#### Adding conditions
Using simple comparative condition:
```php
Select::selectFrom("Users", "U")
    ->where(['Age', '>=', 18]);
```
```sql
SELECT * FROM Users AS U WHERE (Age >= 18)
```

Using multiple `AND` conditions:
```php
Select::selectFrom("Users", "U")
    ->where([
        ['Age', '>=', 18],
        ['Surname', '<>', 'Smith']
    ]);
```
```sql
SELECT * FROM Users AS U WHERE ((Age >= 18) AND (Surname <> Smith))
```

Changing logic:
```php
Select::selectFrom("Users", "U")
    ->where([
        ['Age', '>=', 18],
        ['OR', 'Surname', '<>', 'Smith']
    ]);
```
```sql
SELECT * FROM Users AS U WHERE ((Age >= 18) OR (Surname <> Smith))
```

Using nested conditions:
```php
Select::selectFrom("Users", "U")
    ->where([
        ['City', '=', 'NY'],
        [
            ['Age', '>=', 18],
            ['OR', 'Surname', '<>', 'Smith']
        ]
    ]);
```
```sql
SELECT * FROM Users AS U WHERE ((City = NY) AND ((Age >= 18) OR (Surname <> Smith)))
```

You can also use your own raw condition string:
```php
Select::selectFrom("Users", "U")
    ->where("(isOdd(Id) AND (Id * Id) > 100)");
```
```sql
SELECT * FROM Users AS U WHERE (isOdd(Id) AND (Id * Id) > 100)
```

And you can mix it up:
```php
Select::selectFrom("Users", "U")
    ->where([
        ['City', '=', 'NY'],
        "(isOdd(Id) AND (Id * Id) > 100)"
    ])
```
```sql
SELECT * FROM Users AS U WHERE ((City = NY) AND (isOdd(Id) AND (Id * Id) > 100))
```
_Note:_ Work in progress.

#### Using aggregation
General example:
```php
Select::selectFrom("Users")
    ->columns([
        "RoleID",
        ["min(Age)", "MinAge"]
    ])->groupBy(["RoleID"])
```
```sql
SELECT RoleID, min(Age) AS MinAge FROM Users GROUP BY RoleID
```

Using `HAVING` condition:
```php
Select::selectFrom("Users")
    ->columns([
        "RoleID",
        ["min(Age)", "MinAge"]
    ])->groupBy(["RoleID"])
    ->having([
        ['min(Age)', '>', 30]
    ])
```
```sql
SELECT RoleID, min(Age) AS MinAge FROM Users GROUP BY RoleID HAVING ((min(Age) > 30))
```
_Note:_ learn more about conditions in [Adding conditions](#Adding conditions).

#### Using `WITH` statements:
With nested queries:
```php
Select::selectFrom("Users", "U")
    ->with([
        "Customers" => Select::selectFrom("Clients")
            ->where(["Category", "=", 3]),
        "AdminRoles" => Select::selectFrom("Roles")
            ->where("IsAdmin")
    ]);
```
```sql
WITH Customers AS (SELECT * FROM Clients WHERE (Category = 3)),
     AdminRoles AS (SELECT * FROM Roles WHERE IsAdmin)
SELECT * FROM Users AS U
```

Using scalars:
```php
Select::selectFrom("Users", "U")
    ->with([
        "AdultAge" => 18,
        "Capital" => "'Washington'"
    ]);
```
```sql
WITH 18 AS AdultAge,
    'Washington' AS Capital
SELECT * FROM Users AS U
```

You can fix it up:
```php
Select::selectFrom("Users", "U")
    ->with([
        "AdultAge" => 18,
        "Capital" => "'Washington'",
        "Customers" => Select::selectFrom("Clients")
            ->where(["Category", "=", 3]),
        "AdminRoles" => Select::selectFrom("Roles")
            ->where("IsAdmin")
    ]);
```
```sql
WITH 18 AS AdultAge,
    'Washington' AS Capital,
    Customers AS (SELECT * FROM Clients WHERE (Category = 3)),
    AdminRoles AS (SELECT * FROM Roles WHERE IsAdmin)
SELECT * FROM Users AS U
```

### Insert

Using values
```php
Insert::into("Users", ["Name", "Surname", "Age"])
    ->values([
        ['John', 'Smith', 25],
        ['Alan', 'Clark']
    ]);
```
```sql
INSERT INTO Users (Name, Surname, Age)
VALUES ('John', 'Smith', 25),
       ('Alan', 'Clark')
```

Using nested query:
```php
Insert::into("Users", ["Name", "Surname", "Age"])
    ->select(
        Select::selectFrom("Clients")
            ->columns(["Name", "Surname", "Age"])
    );
```
```sql
INSERT INTO Users (Name, Surname, Age)
SELECT Name, Surname, Age FROM Clients
```
