<?php
$col1 = $_POST['col1'];
$col2 = $_POST['col2'];

$mysqli = new mysqli("old_mysql", "phper", "secret", "old_mysql");

$sql = "INSERT INTO t1 (id, col1, col2) values(NULL, '$col1', '$col2')";

$mysqli->query($sql);

printf("New record has ID %d.\n", $mysqli->insert_id);

$mysqli->close();
