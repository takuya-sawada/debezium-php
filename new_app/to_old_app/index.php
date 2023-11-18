<?php

echo "<h1>new_app to old_app</h1>";

//echo phpinfo();

$mysqli = new mysqli("old_mysql", "phper", "secret", "old_mysql");

$sql = "SELECT * FROM t1";

if ($result = $mysqli->query($sql)) {
    echo "<table border='1'><tr><th>col1</th><th>col2</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["col1"]."</td><td>".$row["col2"]."</td></tr>";
    }
    echo "</table>";
    $result->close();
}

$mysqli->close();

echo "<p><a href='form.php'>old_mysql に書き込む form.php</a></p>";
