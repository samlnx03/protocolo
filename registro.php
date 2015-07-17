<?php
// this is:                           INFORMADOR
// registrar a servr entrante

$iporigen=$_SERVER['REMOTE_ADDR'];
$error=0;
if(!isset($_GET['ip']))
	$error=1;
else
	$ip_reportada=$_GET['ip'];

if(!$error and !isset($_GET['puerto']))
	$error=2;
else
	$puerto=$_GET['puerto'];

if($error){
	echo "<html><body>Error $error en paso de parametros</body></html>";
	exit;
}

if($ip_reportada != $iporigen){
	echo "<html><body>Error las ip's no corresponden</body></html>";
	exit;
}

$lines = file("online.txt", FILE_SKIP_EMPTY_LINES);
// el formato es 123.123.123.123 65535
$servers=Array();
foreach($lines as $line){
	list($server_ip, $server_port) = split(' ', $line);
	$servers[$server_ip]=$server_port;
}
if(!isset($servers[$ip_reportada]))
	$servers[$ip_reportada]=$puerto;

// ecribir la db
unlink("online.txt");
$fh=fopen("online.txt",'a');
foreach($servers as $server_ip => $server_port){
	fwrite($fh, "$server_ip $server_port\n");
}
fclose($fh);
?>

