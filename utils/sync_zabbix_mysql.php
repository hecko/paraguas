#!/usr/bin/php
<?php

mysql_connect('localhost','zabbix','mongr3l21');
mysql_select_db('zabbix');
mysql_set_charset('utf8');

$sql = "SELECT * FROM triggers";
$raw = mysql_query($sql);

while ($r = mysql_fetch_assoc($raw)) {
  if ($r['value'] == 1) { //if value is not OK=0, PROBLEM=1
	$fields_string = '';
    $fields = array();
    $fields['n'] = 'zabbix_db_sync';
    $fields['i'] = '4';
    $fields['m'] = $r['description'];
    $fields['s'] = $r['value']; //if value is not OK=0, PROBLEM=1
    $data = http_build_query($fields);
	echo $data."\n";

	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,'http://lists.blava.net/paraguas/post/');
    curl_setopt($ch,CURLOPT_POST, 1);
    //curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);	
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_VERBOSE, TRUE);
	$result = curl_exec($ch);
	$info = curl_getinfo($ch);
	print_r($info);
	curl_close($ch);

    echo $result."\n";
	unset($fields,$fields_string,$ch);
  }
}

?>
