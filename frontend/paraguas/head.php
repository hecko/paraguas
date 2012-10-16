<?php
include('db.php');
session_start();
?>
<html>
<head>
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
</head>
<body>
<hr>
<div class="row-fluid">

<div class="row">
<div class="span1">
</div>
<div class="span10" id="flash">
<?php
if ($_SESSION['flash']!="") {
    echo '<div class="alert alert-info">';
    echo $_SESSION['flash'];
    echo '</div>';
    unset($_SESSION['flash']);
}
?>
</div>
<div class="span1">
</div>
</div>

<div class="row">
<div class="span1">
</div>
<div class="span10">
