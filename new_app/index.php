<?php

echo "<h1>new_app</h1>";

//echo phpinfo();

$mysqli = new mysqli("new_mysql", "phper", "secret", "new_mysql");

$sql = "SELECT * FROM t1";

if ($result = $mysqli->query($sql)) {
    echo "<table border='1'><tr><th>col1</th><th>col2</th><th>col3</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["col1"]."</td><td>".$row["col2"]."</td><td>".$row["col3"]."</td></tr>";
    }
    echo "</table>";
    $result->close();
}

$mysqli->close();

echo "<p><a href='form.php'>new_mysql に書き込む form.php</a></p>";

echo "<p><a href='to_old_app/form.php'>old_mysql に書き込む form.php</a></p>";
