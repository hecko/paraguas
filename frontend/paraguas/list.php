<?php

include('head.php');

//automatically delete OK messages, do not wait for ACKing
include('clear_ok.php');

?>

<div id=content>
<script src="js/jquery.js"></script>

<?php
echo date("H:i:s");

$sql = 'SELECT * FROM active WHERE status!=0 OR ack_time=0 ORDER BY first_time DESC';

if (!$raw = mysql_query($sql)) {
	echo mysql_error();
}


while ($row = mysql_fetch_assoc($raw)) {
	$data[$row['id']] = $row;
}

$cols = array('last_problem_time','name','status','message','first_time','count');

$out.='<table class="table table-condensed table-hover">';

$out.='<tr>';
foreach ($cols as $key=>$val) {
  $out.='<th>'.str_replace("_"," ",strtoupper($val)).'</th>';
}
$out.='<td></td></tr>';

if (count($data)<=0) {
  echo "<br><strong>No events to display.</strong>";
  die;
}

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
	  $out.='<td><button class="btn btn-mini '.$r['btn_color'].'">'.$r['status_btn_value'].' ('.$r['severity'].')</button></td>';
    } elseif (($c == 'first_time') or ($c == 'last_problem_time')) {
      $r[$c] = date("j.M H:i",$r[$c]);
	  $out.='<td>'.$r[$c].'</td>';
	} elseif ($c == 'last_time') {
  	  $r[$c] = time()-$r[$c]."s";
	  $out.='<td>'.$r[$c].'</td>';
	} elseif ($c == 'name') {
	  $out.='<td><strong><a href="">'.$r[$c].'</a></strong></td>'."\n";
	} elseif ($c == 'message') {
      $out.='<td><strong>'.$r[$c].'</strong>';
	  if (($r['notes']!="None") & ($r['notes']!="")) { $out.='<div><a href="event_detail.php?id='.$r['id'].'"><pre><em>'.substr($r['notes'],0,80).'...</em></pre></a></div>'; };
	  $out.='</td>'."\n";
    } else {
      $out.='<td>'.$r[$c].'</td>';
	}
  }
  $out.='<td>
	<a class="btn btn-mini" href="del.php?id='.$r['id'].'">del</a><br>
	<a class="btn btn-mini" href="ticket.php?id='.$r['id'].'">create ticket</a></a><br>
	<a class="btn btn-mini" href="ack.php?id='.$r['id'].'">ack</a></a>
    </td>';
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
