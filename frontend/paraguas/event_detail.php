<?php

include("head.php");

$id = $_GET['id'];

$now = time();

$sql = "SELECT * FROM active WHERE id=$id";
if ($raw = mysql_query($sql)) {
  $row = mysql_fetch_assoc($raw);
  foreach ($row as $key=>$val) {
	$data[$key] = $val;
	if ($key=='last_time') {
		$data[$key] = date("d.M Y H:i:s",$val).' ('.$val.')';
	}
	if ($key=='last_problem_time') {
		$ago = round((time()-$val)/3600,1);
		$data[$key] = '<strong>'.date("d.M Y H:i:s",$val).' - '.$ago.' hours ago</strong> ('.$val.')';
	}
	if ($key=='first_time') {
		$data[$key] = date("d.M Y H:i:s",$val).' ('.$val.')';
	}
	if ($key=='notes' or $key=='name' or $key=='message') {
		$data[$key] = '<strong>'.nl2br($val).'</strong>';
	}
	if ($key=='count') {
		$data[$key] = $val.'x';
	}
  }
} else {
  echo mysql_error();
}

echo '<table class="table">';
foreach ($data as $key=>$val) {
	if ($val!="") {
		echo '<tr><td>'.$key.'</td><td>'.$val.'</td></tr>';
	}
}
echo '</table>';
?>
<hr>
<a class="btn btn-small" href="email.php?id=<?php echo $id ?>">email</a>
<?

include('foot.php');

?>
