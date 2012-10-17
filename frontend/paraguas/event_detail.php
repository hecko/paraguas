<?php

include("head.php");

$id = $_GET['id'];

$now = time();

$sql = "SELECT * FROM active WHERE id=$id";
if ($raw = mysql_query($sql)) {
  $row = mysql_fetch_assoc($raw);
  echo '<pre>';
  print_r($row);
  echo '</pre>';
} else {
  echo mysql_error();
}

?>
