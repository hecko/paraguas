<?php

include('head.php');

?>

<div id=content>
<script src="js/jquery.js"></script>

<?php
//default behavior: show all messages, only remove those which are OK and ACKed
//automatically delete OK messages, do not wait for ACKing
//include('clear_ok.php');
//automatically move OK messages to archive
//include('archive.php');

echo date("H:i:s");

//select all events which are not archived
$sql = 'SELECT * FROM active WHERE archived=0 ORDER BY first_time DESC';

if (!$raw = mysql_query($sql)) {
	echo mysql_error();
}


while ($row = mysql_fetch_assoc($raw)) {
	$data[$row['id']] = $row;
}

$cols = array('message','first_time','last_problem_time');


$out.='<table class="table table-condensed table-hover table-bordered">';

//echo table header
$out.='<tr>';
foreach ($cols as $key=>$val) {
	if ($val == 'last_problem_time') {
		$val = 'last';
	}
	if ($val == 'contact_group') {
		$val = 'solver';
	}
	if ($val == 'status') {
		$val = 'sta';
	}
	$out.='<th style="text-align: center">'.str_replace("_"," ",strtoupper($val)).'</th>';
}
$out.='<th style="text-align: center">ACTION</th></tr>';

foreach ($data as $r) {
	if (strtolower(trim($r['source'])) == 'none') {
		$data[$r['id']]['source'] = '';
	}
  if ($r['severity'] == 5) {
      $data[$r['id']]['btn_color'] = "btn-inverse";
	  $data[$r['id']]['status_btn_value'] = "D";
	  $data[$r['id']]['tr_color'] = "error";
  } elseif ($r['severity'] == 4) {
      $data[$r['id']]['btn_color'] = "btn-danger";
          $data[$r['id']]['status_btn_value'] = "C";
          $data[$r['id']]['tr_color'] = "error";
  } elseif ($r['severity'] == 3) {
      $data[$r['id']]['btn_color'] = "btn-orange";
	  $data[$r['id']]['status_btn_value'] = "M";
	  $data[$r['id']]['tr_color'] = "warning";
  } elseif ($r['severity'] == 2) {
      $data[$r['id']]['btn_color'] = "btn-warning";
          $data[$r['id']]['status_btn_value'] = "W";
          $data[$r['id']]['tr_color'] = "warning";
  } elseif ($r['severity'] == 1) {
      $data[$r['id']]['btn_color'] = "btn-info";
	  $data[$r['id']]['status_btn_value'] = "I";
	  $data[$r['id']]['tr_color'] = "";
  } else {
	//default button for not classified (0) and unknown severities 
	$data[$r['id']]['btn_color'] = "";
	$data[$r['id']]['status_btn_value'] = "N";
	$data[$r['id']]['tr_color'] = "";
  }
  //if status is OK
  if ($r['status'] == 0) {
    $data[$r['id']]['btn_color'] = "btn-success";
	$data[$r['id']]['status_btn_value'] = "OK";
	$data[$r['id']]['tr_color'] = "success";
	$data[$r['id']]['notes'] = 'Last OK note: '.$data[$r['id']]['notes_ok']."\nLast problem note: ".$data[$r['id']]['notes'];
  }
}

foreach ($data as $r) {
  $out.='</tr>';
  $out.='<tr class="'.$data[$r['id']]['tr_color'].'">';
  foreach ($cols as $c) {
	if ($c=='status') {
		$out.='<td style="vertical-align: middle; text-align: center"><button class="btn btn-small '.$r['btn_color'].'" style="height: 40px;">'.$r['status_btn_value'].'-'.$r['severity'].'</button></td>';
	} elseif ($c == 'message') {
		$out.='<td><em>'.$r['source_ip'].' '.$r['source'].'</em><strong>'.$r['name'].'</strong>: ';
		if (($r['notes']!="None") & ($r['notes']!="")) {
			if (strlen($r['notes'])>=72) {
				$r['notes'] = substr($r['notes'],0,73).'...';
			}
			$out.='<a href="event_detail.php?id='.$r['id'].'">'.$r['message'].'</a>';
			//$out.='<div><a href="event_detail.php?id='.$r['id'].'"><pre><em>'.trim($r['notes']).'</em></pre></a></div>';
		} else {
			$out.=$r['message'];
		};
		$out.='</td>'."\n";
	} elseif ($c == 'last_problem_time') {
		$r[$c] = (time()-$r[$c])/3600;
		$out.='<td style="vertical-align: middle; text-align: right">'.round($r[$c],1).'h</td>';
	} elseif ($c == 'first_time') {
		$r['first_time_human'] = date("j.M H:i",$r[$c]);
		$out.='<td style="vertical-align: middle; text-align: right">('.$r['count'].'x) '.$r['first_time_human'].'</td>';
	} elseif ($c == 'name') {
		$out.='<td style="vertical-align: middle"><strong><a href="">'.$r[$c].'</a></strong></td>'."\n";
	} elseif ($c == 'contact_group') {
		if (($r[$c] != "") & (strtolower($r[$c]) != 'none')) {
			$out.='<td style="vertical-align: middle; text-align: center;"><a class="btn btn-small" href="ticket.php?id='.$r['id'].'">'.strtoupper($r[$c]).'</a>';
		} else {
			$out.='<td style="vertical-align: middle; text-align: center;"><em>Contact group is not defined!</em>';
		}
		$out.='<br><a class="btn btn-small" href="email.php?id='.$r['id'].'">email</a>';
		$out.='</td>';
	} else {
		$out.='<td>'.$r[$c].'</td>';
	}
  }
  $out.='<td style="vertical-align: middle; text-align: center;">
	<a class="icon-remove" href="del.php?id='.$r['id'].'"></a>';
	if ($r['ack_time']!=0) {
		$out.='<a class="icon-thumbs-up" href="ack.php?id='.$r['id'].'"></a>';
	} else {
		$out.='<a class="icon-hand-up" href="ack.php?id='.$r['id'].'"></a>';
	}
	$out.='<a class="icon-info-sign" href="event_detail.php?id='.$r['id'].'"></a>';
  $out.='</td>';
  $out.='</tr>';
}
$out.='</table>';
echo $out;
?>
</div>

<!-- refresh part of the page -->
<script>
setInterval(function(){
	$("#content").load("list.php #content");
	$("#flash").load("list.php #flash");
}, 2000);
</script>

<?php

include('foot.php');


?>
