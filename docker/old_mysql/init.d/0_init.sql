CREATE DATABASE old_mysql;
GRANT ALL PRIVILEGES ON *.* TO 'phper'@'%';

USE old_mysql;

DROP TABLE IF EXISTS t1;
CREATE TABLE t1
(
    id   int auto_increment,
    col1 varchar(10),
    col2 varchar(10),
    PRIMARY KEY (id)
);
