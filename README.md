# debezium-php

## 使い方

### 1. Docker 環境を構築する

```shell
make init && make up
```

### 2. Debezium のコネクタを old_mysql に接続する

```shell
curl -i -X POST -H "Accept:application/json" -H  "Content-Type:application/json"  http://localhost:8083/connectors -d @connector.json
```

### 3. new_app のコンテナにログインしてコンシューマーを起動する

```shell
make new_app
php kafka.php
```

### 4. 旧DBにデータを追加する（更新処理はコンシューマーが未実装なので確認できない）

```shell
make old_mysql
```

```sql
INSERT INTO t1 VALUES (0, 'col1', 'col2');
```

### 5. 新DBに旧DBに追加したデータが反映されていることを確認する

```shell
make new_mysql
```

```sql
SELECT * FROM t1;
```

## Reference

- [Debezium connector for MySQL](https://debezium.io/documentation/reference/stable/connectors/mysql.html)
- [Debezium MySQL Source Connector 構成プロパティ](https://docs.confluent.io/ja-jp/kafka-connectors/debezium-mysql-source/1.2/mysql_source_connector_config.html)
- [Debeziumを利用したDBを同期する仕組みづくり](https://techblog.raksul.com/entry/2021/12/10/debezium%25e3%2582%2592%25e5%2588%25a9%25e7%2594%25a8%25e3%2581%2597%25e3%2581%259fdb%25e3%2582%2592%25e5%2590%258c%25e6%259c%259f%25e3%2581%2599%25e3%2582%258b%25e4%25bb%2595%25e7%25b5%2584%25e3%2581%25b)
- [DebeziumでCDCを構築してみた](https://zenn.dev/stafes_blog/articles/ikkitang-691e9913644952)
- [Integrating Apache Kafka in Laravel: Real-time Database Synchronization with Debezium Connector](https://medium.com/simform-engineering/integrating-apache-kafka-in-laravel-real-time-database-synchronization-with-debezium-connector-2506bc8f37a7)
- [arnaud-lb/php-rdkafka](https://github.com/arnaud-lb/php-rdkafka)

## MySQL

### docker exec command

各 MySQL への接続は Make コマンドから実行できる

```shell
# old_mysql
make old_mysql

# new_mysql
make new_mysql
```

### old_mysql

```sql
CREATE TABLE t1
(
    id   int auto_increment,
    col1 varchar(10),
    col2 varchar(10),
    PRIMARY KEY (id)
);

-- | t1    | CREATE TABLE `t1` (
--   `id` int NOT NULL AUTO_INCREMENT,
--   `col1` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `col2` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci |

INSERT INTO t1 VALUES (0, 'col1', 'col2');
UPDATE t1 SET col1 = 'col1', col2 = 'col2' WHERE id = 1;

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
3. col3カラムを追加

```sql
CREATE TABLE t1
(
    id   int auto_increment,
    col1 varchar(50),
    col2 varchar(10),
    col3 varchar(100),
    PRIMARY KEY (id)
);

-- | t1    | CREATE TABLE `t1` (
--   `id` int NOT NULL AUTO_INCREMENT,
--   `col1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `col2` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `col3` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci |
```

## Debeziumの設定

[Debezium connector for MySQL](https://debezium.io/documentation/reference/stable/connectors/mysql.html)

### コネクターを起動

```shell
$ curl -i -X POST -H "Accept:application/json" -H  "Content-Type:application/json"  http://localhost:8083/connectors -d @connector.json
HTTP/1.1 201 Created
Date: Sun, 19 Nov 2023 02:22:07 GMT
Location: http://localhost:8083/connectors/debezium-sample-connector
Content-Type: application/json
Content-Length: 735
Server: Jetty(9.4.51.v20230217)

{"name":"debezium-sample-connector","config":{"connector.class":"io.debezium.connector.mysql.MySqlConnector","tasks.max":"1","schema.history.internal.kafka.bootstrap.servers":"kafka:9092","schema.history.internal.kafka.topic":"schema-changes.test","database.hostname":"old_mysql","database.port":"3306","database.user":"phper","database.password":"secret","database.dbname":"old_mysql","database.server.id":"1","database.include.list":"old_mysql","table.include.list":"old_mysql.t1","topic.prefix":"debezium_cdc_topic","database.history.kafka.bootstrap.servers":"localhost:9092","database.history.kafka.topic":"schemahistory.fullfillment","include.schema.changes":"false","name":"debezium-sample-connector"},"tasks":[],"type":"source"}

$ curl -X GET http://localhost:8083/connectors/debezium-sample-connector
{"name":"debezium-sample-connector","config":{"connector.class":"io.debezium.connector.mysql.MySqlConnector","database.user":"phper","database.dbname":"old_mysql","database.server.id":"1","tasks.max":"1","database.history.kafka.bootstrap.servers":"localhost:9092","database.history.kafka.topic":"schemahistory.fullfillment","schema.history.internal.kafka.bootstrap.servers":"kafka:9092","database.port":"3306","include.schema.changes":"false","topic.prefix":"debezium_cdc_topic","schema.history.internal.kafka.topic":"schema-changes.test","database.hostname":"old_mysql","database.password":"secret","name":"debezium-sample-connector","table.include.list":"old_mysql.t1","database.include.list":"old_mysql"},"tasks":[{"connector":"debezium-sample-connector","task":0}],"type":"source"}
```

## kafka

### kafka consumer 確認

```shell
# topic確認
docker compose exec kafka bin/kafka-topics.sh --list --bootstrap-server kafka:9092

# 購読
docker compose exec kafka bin/kafka-console-consumer.sh --bootstrap-server kafka:9092 --from-beginning --topic debezium_cdc_topic.old_mysql.t1
```

## 検証結果

### new から old に書き込む場合

- new は 3カラム
- old は 2カラム

INSERTのクエリでカラムを指定しているので以下のエラーが発生する

```php
Fatal error: Uncaught mysqli_sql_exception: Unknown column 'col3' in 'field list' in /var/www/html/to_old_app/post.php:11 Stack trace: #0 /var/www/html/to_old_app/post.php(11): mysqli->query('INSERT INTO t1 ...') #1 {main} thrown in /var/www/html/to_old_app/post.php on line 11
```
