<?php

include("db.php");

$id = $_GET['id'];

$sql = "DELETE FROM active WHERE id=$id";
mysql_query($sql);
echo mysql_error();
echo 'done';

?>
<meta http-equiv="refresh" content="1;URL='list.php'">
