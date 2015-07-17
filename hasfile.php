<?php
// informa si tiene un archivo en particular

$error=0;
if(!isset($_GET['archivo']))
        $error=1;
else
        $archivo=$_GET['archivo'];

if($error){
	echo "<html><body>Error $error en paso de parametros</body></html>";
	exit;
}
//echo "Archivo: $archivo\n";

$lines = file("lista.txt", FILE_SKIP_EMPTY_LINES);
// el formato es filename.ext 
$files=Array();
foreach($lines as $line){
	//echo "Leido: $line";
	list($md5, $arch) = split('[ ]+', $line);
	$arch=substr($arch,0,-1);
	//echo "split: $md5 $arch\n";
	$files[$arch]=$md5;
}
if(isset($files[$archivo]))
	echo $files[$archivo]." ".$archivo."\n";
else
	echo "0 0\n";
//print_r($files);
?>
