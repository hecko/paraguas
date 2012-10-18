<?php

include("head.php");

$id = $_GET['id'];

$now = time();

$sql = "SELECT * FROM active WHERE id=$id";
if ($raw = mysql_query($sql)) {
  $row = mysql_fetch_assoc($raw);
  foreach ($row as $key=>$val) {
	$data[$key] = $val;
  }
} else {
  echo mysql_error();
}

echo '<pre>';
print_r($data);
echo '</pre>';

include('foot.php');

?>
