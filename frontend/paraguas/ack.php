<?php

include("head.php");

$id = $_GET['id'];

$now = time();

$sql = "SELECT * FROM active WHERE id=$id";
$raw = mysql_query($sql);
$row = mysql_fetch_assoc($raw);

if ($row['status'] == 2) {
	$sql = "DELETE FROM active WHERE id=$id";
	$flash = 'Event has been ACked and deleted as this is not paired event';
} else {
	$sql = "UPDATE active SET ack_time=$now,ack_note='ocakavany stav' WHERE id=$id";
	$flash = "Event #$id has been acknowledged at ".date("H:i:s",$now);
}

if (mysql_query($sql)) {
	$_SESSION['flash'] = $flash; 
} else {
	$_SESSION['flash'] = mysql_error(); 
}
header('Location: list.php');

?>
