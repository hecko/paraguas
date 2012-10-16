<?php

include("../db.php");


$n = mysql_real_escape_string($_POST['n']);
$i = $_POST['i'];
$m = $_POST['m'];
$s = $_POST['s'];
$a = mysql_real_escape_string($_POST['a']);
$t = time();

//do we already have this kind of messagge in state PROBLEM ?
$sql = "SELECT id FROM active WHERE name='$n' AND message='$m'";
$raw = mysql_query($sql);

if ((mysql_num_rows($raw)>=1) and ($s==0)) {
  //execute this if we already have message of this type and received is a OK message
  //is the problem in the database acknowledged?
  $row = mysql_fetch_assoc($raw);
  if ($row['ack_note'] != "") {
    //delete the row
    while ($row = mysql_fetch_assoc($raw)) {
      $sql = "DELETE FROM active WHERE id=".$row['id'].";";
      if (mysql_query($sql)) {
        echo "Problem message cleared\n";
      } else {
    	print_r($row);
        echo $sql."\n";
        echo mysql_error();
      }
    }
  } else {
    //the problem is not acked yet, just update its status to OK
    $sql = "UPDATE active SET count=count+1,last_time=$t,severity=$i,status=$s,notes='$a' WHERE id=".$row['id'].";";
	mysql_query($sql);
  }
} elseif ((mysql_num_rows($raw)>=1) and ($s==1)) {
  //execute this if we aldeady have this message and received message has problem status
  $row = mysql_fetch_assoc($raw);
  $sql = "UPDATE active SET count=count+1,last_time=$t,severity=$i,status=$s,last_problem_time=$t,notes='$a' WHERE id=".$row['id'].";";
  if (mysql_query($sql)) {
	  echo "Problem message count +1ned\n";
  } else {
	  echo "Problem updating problem count\n";
	  echo mysql_error();
  }
} else {
  //execute this if we do not have this message in problem status in database already
  //is this new message a problem message?
  if ( $s==1 ) {
    //yes, it is - insert it into database
	$sql = "INSERT INTO active (name,severity,message,status,first_time,last_time,first_problem_time,count,notes) VALUES ('$n',$i,'$m',$s,$t,$t,$t,1,'$a')";
	echo $sql."\n";
	mysql_query($sql);
	echo "New problem received.\n";
  } elseif ($s==0) {
	//this is an OK message
	echo "This is an OK message without matching PROBLEM pair. Ignoring\n";
  } else {
	//this will be alter used for log monitoring ets, for now it is just any other status value than 0 or 1
	echo "We dont know what this message is supposed to do. Ignoring it.";
  }
}

echo "Done\n";

?>
