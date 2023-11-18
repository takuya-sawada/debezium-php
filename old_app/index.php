<?php

echo "old_app";
echo "<hr>";

//echo phpinfo();

$mysqli = new mysqli("old_mysql", "phper", "secret", "old_mysql");

$sql = "SELECT * FROM t1";

if ($result = $mysqli->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        echo $row["col1"] . $row["col2"] . "<br>";
    }
    $result->close();
}
