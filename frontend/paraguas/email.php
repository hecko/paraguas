<?php
include('head.php');

$id = $_GET['id'];

$sql = "SELECT * FROM active WHERE id=$id";
$raw = mysql_query($sql);
echo mysql_error();
$row = mysql_fetch_assoc($raw);

?>
<form class="form" action="email_send.php" method="post">
	<input name="subject" value="Paraguas @ <?php echo date("Y-m-d H:m:s"); ?>"><br>
	<textarea class="field span8" rows=23 name="body"><?php print_r($row) ?></textarea>
	<br>
	emails: 
	<input name="emails" width=200>
	<hr>
	<input type=submit value=send>
</form>
<?

include('foot.php');
?>
