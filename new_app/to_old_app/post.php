<?php

$col1 = $_POST['col1'];
$col2 = $_POST['col2'];
$col3 = $_POST['col3'];

$mysqli = new mysqli("old_mysql", "phper", "secret", "old_mysql");

$sql = "INSERT INTO t1 (id, col1, col2, col3) values(NULL, '$col1', '$col2', '$col3')";

$mysqli->query($sql);

printf("New record has ID %d.\n", $mysqli->insert_id);

$mysqli->close();

echo "<p><a href='form.php'>old_mysql に書き込む form.php</a></p>";
echo "<p><a href='index.php'>new_mysql の index.php</a></p>";
