<?php
include('head.php');

$emails = explode(',', $_POST['emails']);
$message = $_POST['body'];
$subject = $_POST['subject'];

echo "<pre>Emails: ";
print_r($emails);
echo '</pre>'."<hr>";

$headers = 'From: '.$from_email. "\r\n" .
    'Reply-To: '.$from_email;

echo '<pre>';
echo $headers;
echo '</pre>';

foreach ($emails as $email) {
	echo 'Sending to '.$email."<br>";
	mail($email,$subject,"$message",$headers);
}
?>
<hr>
<a class="btn" href="list.php">Back to list.</a>
<?php

include('foot.php');
?>
