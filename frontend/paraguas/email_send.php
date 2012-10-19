<?php
include('head.php');

$emails = explode(',', $_POST['emails']);
$message = $_POST['body'];
$subject = $_POST['subject'];

echo "Emails: ".$emails."<hr>";

foreach ($emails as $email) {
	echo 'Sending to '.$email."<br>";
	mail($email,$subject,"$message");
}
?>
<hr>
<a class="btn" href="list.php">Back to list.</a>
<?php

include('foot.php');
?>
