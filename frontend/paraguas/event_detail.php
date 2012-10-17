<?php

include("head.php");

$id = $_GET['id'];

$now = time();

$sql = "SELECT * FROM active WHERE id=$id";
if ($raw = mysql_query($sql)) {
  $row = mysql_fetch_assoc($raw);
  foreach ($row as $key=>$val) {
    echo $key.": ".nl2br($val)."<br>";
  }
} else {
  echo mysql_error();
}

include('foot.php');

?>
