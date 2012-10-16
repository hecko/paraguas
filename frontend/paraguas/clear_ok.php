<?php

include('db.php');

$sql = "DELETE FROM `active` WHERE `status`=0";
mysql_query($sql);
echo mysql_error();

?>
