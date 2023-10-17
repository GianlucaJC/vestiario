<?php
	session_start();
	include_once '../../database.php';

	$database = new Database();
	$db = $database->getConnection();
	include_once '../../MVC/Models/M_richiesta.php';

	$main = new Main_Richiesta($db);

	
$operazione=$_POST['operazione'];
if ($operazione=="popola_taglia") {
	$codice_fornitore=$_POST['codice_fornitore'];
	
	$elenco=$main->popola_taglia($codice_fornitore);
	print json_encode($elenco);
}
if ($operazione=="anteprima") {
	$codice_fornitore=$_POST['codice_fornitore'];
	$taglia=$_POST['taglia'];
	$elenco=$main->elenco_img($codice_fornitore,$taglia);
	print json_encode($elenco);
}

?>