<?php

include("../db.php");

function get_contact_group($message) {
	preg_match("/^\[(.*?)\]/",$message,$temp);
	$ttemp = explode(';',$temp[1]);
	return $ttemp[0];
}

$n = mysql_real_escape_string($_POST['n']);
$i = $_POST['i'];
$m = mysql_real_escape_string($_POST['m']);
$s = $_POST['s'];
$a = mysql_real_escape_string($_POST['a']);
$t = time();

$contact_group = get_contact_group($m);

//do we already have this kind of messagge in state PROBLEM ?
$sql = "SELECT id FROM active WHERE name='$n' AND message='$m'";
$raw = mysql_query($sql);

if ((mysql_num_rows($raw)>=1) and ($s==0)) {
  $row = mysql_fetch_assoc($raw);
  //execute this if we already have message of this type and received is a OK message
  //is the problem in the database acknowledged?
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
    //do not update `count` as `count` should only be for problem messages
    $sql = "UPDATE active SET last_time=$t,severity=$i,status=$s,notes_ok='$a' WHERE id=".$row['id'].";";
	if (mysql_query($sql)) {
		echo "Event updated to status OK.\n";
	} else {
		echo mysql_error();
	}
  }
} elseif ((mysql_num_rows($raw)>=1) and (($s==1) OR ($s==2))) {
  $row = mysql_fetch_assoc($raw);
  //execute this if we aldeady have this message and received message has problem status or is an unpaired message
  //update count as this is a problem message
  $sql = "UPDATE active SET count=count+1,last_time=$t,severity=$i,status=$s,last_problem_time=$t,notes='$a',notes_ok='' WHERE id=".$row['id'].";";
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
	$sql = "INSERT INTO active (name,severity,message,contact_group,status,first_time,last_time,last_problem_time,count,notes) VALUES ('$n',$i,'$m','$contact_group',$s,$t,$t,$t,1,'$a')";
	echo $sql."\n";
	if (mysql_query($sql)) {
		echo "New problem received.\n";
	} else {
		echo mysql_error();
	}
  } elseif ($s==0) {
	//this is an OK message
	echo "This is an OK message without matching PROBLEM pair. Ignoring\n";
  } elseif ($s==2) {
		echo "Event received!";
		$sql = "INSERT INTO active (name,severity,message,contact_group,status,first_time,last_time,last_problem_time,count,notes) VALUES ('$n',$i,'$m','$contact_group',$s,$t,$t,$t,1,'$a')";
		if (mysql_query($sql)) {
			echo "New one-time event received.\n";
		} else {
		echo mysql_error();
		}
  } else {
	//this will be alter used for log monitoring ets, for now it is just any other status value than 0 or 1
	echo "We dont know what this message is supposed to do. Ignoring it.";
  }
}

echo "Done\n";

?>
