<?php
$error=0;
if(!isset($_GET['archivo']))
	$error=1;
else
	$archivo=$_GET['archivo'];

if(!$error and !isset($_GET['parte'])) 
	$error=2;
else
	$parte=$_GET['parte'];

if(!$error and !isset($_GET['partes'])) 
	$error=3;
else
	$partes=$_GET['partes'];

if($error){
	echo "<html><body>Error $error en paso de parametros</body></html>";
	exit;
}

$fichero = $archivo;

if (!file_exists($fichero)) {
	$error=4;
	echo "<html><body>Error $error No existe el archivo</body></html>";
	exit;
}

$largot = filesize($fichero);
$tbloque= ceil($largot/$partes);
$salida="/tmp/pieces";

// a=1 no mas de 10 piezas
$cmd="split -a 1 -d -b $tbloque $archivo $salida";
$Aoutput=array();
$Vreturn=0;
$r=exec($cmd,$Aoutput,$Vreturn);

// debug
if(0){
	echo "<html><body>\n";
	for($i=0; $i<$partes; $i++){
		if(file_exists($salida.$i)) 
			echo "$salida $i existe ".filesize($salida.$i)." bytes<br>\n";
	}
	echo "Fin de lista de piezas del archivo<br>\n";
	echo "split reglreso: $Vreturn, y el arreglo:<br>\n";
	print_r($Aoutput);
	echo "</body></html>\n";
	exit;
}


$parte=$parte-1;  // 0 based
$filebloque=$salida.$parte;

if (!file_exists($filebloque)) {
	$error=5;
	echo "<html><body>\n";
	echo "Error $error No existe el bloque del archivo $filebloque<br>\n";
	echo "split regreso: $r<br>\n";
	echo "</body></html>\n";
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
//header('Content-Disposition: attachment; filename='.basename($filebloque));
header('Content-Disposition: attachment; filename='.$filebloque);
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filebloque));
readfile($filebloque);
exit;
?>
