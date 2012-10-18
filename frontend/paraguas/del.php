<?php

include("db.php");

$id = $_GET['id'];

$sql = "DELETE FROM active WHERE id=$id";
mysql_query($sql);

header('Location: list.php');

?>
