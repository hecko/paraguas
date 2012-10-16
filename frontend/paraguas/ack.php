<?php

include("head.php");

$id = $_GET['id'];

$now = time();

$sql = "UPDATE active SET ack_time=$now,ack_note='ocakavany stav' WHERE id=$id";
if (mysql_query($sql)) {
	$_SESSION['flash'] = "Event #$id has been acknowledged at ".date("H:i:s",$now);
} else {
	$_SESSION['flash'] = mysql_error(); 
}

?>
<meta http-equiv="refresh" content="1;URL='list.php'">
