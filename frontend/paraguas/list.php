<?php

include('head.php');

?>

<div id=content>
<script src="js/jquery.js"></script>

<?php
//automatically delete OK messages, do not wait for ACKing
//include('clear_ok.php');

echo date("H:i:s");

$sql = 'SELECT * FROM active WHERE status!=0 OR ack_time=0 ORDER BY first_time DESC';

if (!$raw = mysql_query($sql)) {
	echo mysql_error();
}


while ($row = mysql_fetch_assoc($raw)) {
	$data[$row['id']] = $row;
}

$cols = array('last_problem_time','name','status','message','contact_group','first_time');
$cols = array('status','message','contact_group','first_time','last_problem_time');


$out.='<table class="table table-condensed table-hover">';

//echo table header
$out.='<tr>';
foreach ($cols as $key=>$val) {
	$out.='<th>'.str_replace("_"," ",strtoupper($val)).'</th>';
}
$out.='<td></td></tr>';

foreach ($data as $r) {
  if ($r['severity'] == 5) {
      $data[$r['id']]['btn_color'] = "btn-inverse";
	  $data[$r['id']]['status_btn_value'] = "DISASTER";
	  $data[$r['id']]['tr_color'] = "error";
  } elseif ($r['severity'] >= 3) {
      $data[$r['id']]['btn_color'] = "btn-danger";
	  $data[$r['id']]['status_btn_value'] = "MAJOR";
	  $data[$r['id']]['tr_color'] = "error";
  } elseif ($r['severity'] >=0) {
      $data[$r['id']]['btn_color'] = "btn-warning";
	  $data[$r['id']]['status_btn_value'] = "WARNING";
	  $data[$r['id']]['tr_color'] = "warning";
  }
  //if status if OK
  if ($r['status'] == 0) {
    $data[$r['id']]['btn_color'] = "btn-success";
	$data[$r['id']]['status_btn_value'] = "OK";
	$data[$r['id']]['tr_color'] = "success";
	$data[$r['id']]['notes'] = $data[$r['id']]['notes_ok']." ".$data[$r['id']]['notes'];
  }
}

foreach ($data as $r) {
  $out.='<tr class="'.$data[$r['id']]['tr_color'].'">';
  foreach ($cols as $c) {
    if ($c=='severity') {
      $out.='<td><button class="btn btn-mini '.$r['btn_color'].'">'.$r[$c].'</button></td>';
	} elseif ($c=='status') {
		$out.='<td><button class="btn btn-small '.$r['btn_color'].'" style="height: 40px;">'.$r['status_btn_value'].'_('.$r['severity'].')</button></td>';
	} elseif ($c == 'last_problem_time') {
		$r[$c] = date("j.M H:i",$r[$c]);
		$out.='<td>'.$r[$c].'</td>';
    	} elseif ($c == 'first_time') {
		$r[$c] = date("j.M H:i",$r[$c]);
		$out.='<td>'.$r['count'].'x since<br>'.$r[$c].'</td>';
	} elseif ($c == 'name') {
		$out.='<td><strong><a href="">'.$r[$c].'</a></strong></td>'."\n";
	} elseif ($c == 'message') {
		$out.='<td><strong><a href="">'.$r['name'].'</a>: '.$r[$c].'</strong>';
		if (($r['notes']!="None") & ($r['notes']!="")) { 
			if (strlen($r['notes'])>=79) { 
				$r['notes'] = substr($r['notes'],0,80).'...';
			}
			$out.='<div><a href="event_detail.php?id='.$r['id'].'"><pre><em>'.$r['notes'].'</em></pre></a></div>'; 
		};
		$out.='</td>'."\n";
	} elseif ($c == 'contact_group') {
		if ($r[$c] != "") {
			$out.='<td><a class="btn btn-mini" href="ticket.php?id='.$r['id'].'">create ticket for '.strtoupper($r[$c]).'</a><br></td>';
		} else {
			$out.='<td><em>Contact is not defined!</em></td>';
		}
	} else {
		$out.='<td>'.$r[$c].'</td>';
	}
  }
  $out.='<td>
	<a class="icon-remove" href="del.php?id='.$r['id'].'"></a><br>';
	if ($r['ack_time']!=0) {
		$out.='<a class="btn btn-mini btn-success" href="ack.php?id='.$r['id'].'">acked @'.date("H:i",$r['ack_time']).'</a></a>';
	} else {
		$out.='<a class="btn btn-mini btn-warning" href="ack.php?id='.$r['id'].'">ack</a></a>';
	}
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
}, 5000);
</script>

<?php

include('foot.php');


?>
