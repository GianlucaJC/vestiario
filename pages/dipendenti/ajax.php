<?php
session_start();
if (!isset($_SESSION['user_vest'])) exit;
$is_admin=$_SESSION['vest_access'];
if ($is_admin!=1) exit;

include_once '../../database.php';
$database = new Database();
$db = $database->getConnection();
include_once '../../MVC/Models/M_dipendenti.php';

$main_dipendenti = new Main_Dipendenti($db);

$operazione=$_POST['operazione'];

if ($operazione=="view_story") {
	$ref=$_POST['ref'];
	$codice_articolo=$_POST['codice_articolo'];
	$taglia=$_POST['taglia'];
	$view_story=$main_dipendenti->view_story($ref,$codice_articolo,$taglia);
	print json_encode($view_story);	
}

exit;
?>