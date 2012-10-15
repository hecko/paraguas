<?php

include('head.php');

?>

<meta http-equiv="refresh" content="3"> 

<h3>Umbrella monitoring</h3>

<?php

$sql = 'SELECT * FROM active ORDER BY first_time DESC';

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
  $out.='<th>'.$val.'</th>';
}
$out.='<td></td></tr>';

foreach ($data as $r) {
  if ($r['severity'] == 5) {
      $data[$r['id']]['btn_color'] = "btn-inverse";
	  $data[$r['id']]['status_btn_value'] = "DISASTER";
  } elseif ($r['severity'] >= 3) {
      $data[$r['id']]['btn_color'] = "btn-danger";
	  $data[$r['id']]['status_btn_value'] = "MAJOR";
  } elseif ($r['severity'] >=1) {
      $data[$r['id']]['btn_color'] = "btn-warning";
	  $data[$r['id']]['status_btn_value'] = "WARNING";
  }
  if ($r['status'] == 0) {
    $data[$r['id']]['btn_color'] = "btn-success";
	$data[$r['id']]['status_btn_value'] = "OK";
  }
}

//echo '<pre>';
//print_r($data);
//echo '</pre>';

foreach ($data as $r) {
  $out.='<tr>';
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
	  if ($r['notes']!="None") { $out.='<div><pre><em>'.substr($r['notes'],0,80).'...</em></pre></div>'; };
	  $out.='</td>'."\n";
    } else {
      $out.='<td>'.$r[$c].'</td>';
	}
  }
  $out.='<td>
	<a class="btn btn-mini" href="del.php?id='.$r['id'].'">del</a>
	<a class="btn btn-mini" href="ticket.php?id='.$r['id'].'">create ticket</a></a>
	<a class="btn btn-mini" href="ack.php?id='.$r['id'].'">ack</a></a>
    </td>';
  $out.='</tr>';
}
$out.='</table>';
echo $out;
?>
<hr>
<small>We.Love.Open.Source <em><?php echo date("Y-m-d H:i:s"); ?></em></small>
<?php

include('foot.php');


?>
