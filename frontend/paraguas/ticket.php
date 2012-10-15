<?php
include('head.php');

$id = $_GET['id'];

$sql = "SELECT * FROM active WHERE id=$id";
$raw = mysql_query($sql);
echo mysql_error();
$row = mysql_fetch_assoc($raw);

echo '<pre>';
print_r($row);
echo '</pre>';

include('foot.php');
?>
