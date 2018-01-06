<?php
$mysqli = mysqli_connect($_SERVER['RDS_HOSTNAME'], $_SERVER['RDS_USERNAME'], $_SERVER['RDS_PASSWORD'], $_SERVER['RDS_DB_NAME'], 10091);
$result = $mysqli->query("SELECT * FROM urler");
while($row = $result->fetch_array())
  {
  echo $row['author'] . " says " . $row['message'];
  echo "<br />";
  }
?>