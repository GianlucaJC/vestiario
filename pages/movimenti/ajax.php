<?php
	session_start();
	include_once '../../database.php';

	$database = new Database();
	$db = $database->getConnection();
	include_once '../../MVC/Models/M_carico.php';

	$main_carico = new Main_Carico($db);

	
$operazione=$_POST['operazione'];
if ($operazione=="popola_taglia") {
	$codice_prodotto=$_POST['codice_prodotto'];
	
	$elenco=$main_carico->popola_taglia($codice_prodotto);
	print json_encode($elenco);
}

if ($operazione=="fornitori") {
	$codice_prodotto=$_POST['codice_prodotto'];
	$taglia=$_POST['taglia'];
	
	$elenco=$main_carico->popola_fornitori($codice_prodotto,$taglia);
	print json_encode($elenco);
}

?>