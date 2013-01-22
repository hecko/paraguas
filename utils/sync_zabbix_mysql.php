#!/usr/bin/php
<?php

mysql_connect('localhost','zabbix','xxx');
mysql_select_db('zabbix');
mysql_set_charset('utf8');

$sql = "SELECT * FROM triggers";
$raw = mysql_query($sql);

while ($r = mysql_fetch_assoc($raw)) {
  if ($r['value'] == 1) { //if value is not OK=0, PROBLEM=1
	$fields_string = '';
    $fields = array();
    $fields['n'] = urlencode('zabbix_db_sync');
    $fields['i'] = urlencode('4');
    $fields['m'] = urlencode($r['description']);
    $fields['s'] = urlencode($r['value']); //if value is not OK=0, PROBLEM=1
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
