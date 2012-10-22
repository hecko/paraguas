<?php

include('db.php');

#$sql = "UPDATE `active` SET `archived`=1 WHERE `status`=0 AND `ack_time`!=0";
$sql = "UPDATE `active` SET `archived`=1 WHERE `status`=0";
mysql_query($sql);
echo mysql_error();

?>
