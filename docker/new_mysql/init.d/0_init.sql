CREATE DATABASE new_mysql;
GRANT ALL PRIVILEGES ON *.* TO 'phper'@'%';

USE new_mysql;

DROP TABLE IF EXISTS t1;
CREATE TABLE t1
(
    id   int auto_increment,
    col1 varchar(50),
    col2 varchar(10),
    col3 varchar(100),
    PRIMARY KEY (id)
);
