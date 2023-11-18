<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>new form to old mysql</title>
  </head>
  <body>
    <h1>new form to old mysql</h1>
    <p>new_mysqlのカラム数でold_mysqlへの書き込みをする</p>
    <form action="post.php" method="post">
        <label for="col1">col1:</label>
        <input type="text" name="col1"><br>
        <label for="col2">col2:</label>
        <input type="text" name="col2"><br>
        <label for="col3">col3:</label>
        <input type="text" name="col3"><br>
        <input type="submit" value="Submit">
    </form>

    <p><a href='index.php'>new_mysql の index.php</a></p>

  </body>
</html>
