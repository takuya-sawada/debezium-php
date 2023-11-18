# laravel-debezium

## Docker

```shell
docker compose up -d

docker compose down
```

## MySQL

### docker exec command

```shell
# old_mysql
docker compose exec old_mysql bash -c 'mysql -u phper -psecret old_mysql'

# new_mysql
docker compose exec new_mysql bash -c 'mysql -u phper -psecret new_mysql'
```

### old_mysql

```sql
create table t1(id int auto_increment, col1 varchar(10), col2 varchar(10), PRIMARY KEY (id));

-- | t1    | CREATE TABLE `t1` (
--   `id` int NOT NULL AUTO_INCREMENT,
--   `col1` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `col2` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci |

insert into t1 values(0, 'col1', 'col2');

-- mysql> select * from t1;
-- +----+------+------+
-- | id | col1 | col2 |
-- +----+------+------+
-- |  1 | col1 | col2 |
-- +----+------+------+
-- 1 row in set (0.00 sec)
```

### new_mysql

#### old_mysql.t1 と new_mysql.t1 の差

1. col1カラムの桁数を10->50に変更
2. col2カラムはnew_mysqlでは使用しない
2. col3カラムを追加

```sql
create table t1(id int auto_increment, col1 varchar(50), col2 varchar(10), col3 varchar(100), PRIMARY KEY (id));

-- | t1    | CREATE TABLE `t1` (
--   `id` int NOT NULL AUTO_INCREMENT,
--   `col1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `col2` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `col3` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci |
```

## 検証結果

### new から old に書き込む場合

- new は 3カラム
- old は 2カラム

INSERTのクエリでカラムを指定しているので以下のエラーが発生する

```php
Fatal error: Uncaught mysqli_sql_exception: Unknown column 'col3' in 'field list' in /var/www/html/to_old_app/post.php:11 Stack trace: #0 /var/www/html/to_old_app/post.php(11): mysqli->query('INSERT INTO t1 ...') #1 {main} thrown in /var/www/html/to_old_app/post.php on line 11
```
