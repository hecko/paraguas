<?php
$id = $_GET['id'];
if ($id=="") {
        header('Location: list.php');
} else {
        header('Location: event_detail.php?id='.$id);
}
?>
