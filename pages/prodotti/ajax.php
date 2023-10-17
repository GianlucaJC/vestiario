<?php
session_start();
if (!isset($_SESSION['user_vest'])) exit;
$is_admin=$_SESSION['vest_access'];
if ($is_admin!=1) exit;

include_once '../../database.php';
$database = new Database();
$db = $database->getConnection();
include_once '../../MVC/Models/M_prodotti.php';

$main_prodotti = new Main_Prodotti($db);

$operazione=$_POST['operazione'];
if ($operazione=="dele_foto") {
	$product_id=$_POST['product_id'];
	@unlink("files/".$product_id.".jpg");
	@unlink("files/".$product_id.".jpeg");
	@unlink("files/".$product_id.".png");
	@unlink("files/".$product_id.".gif");
	$risp=array();
	$risp['status']="OK";
	print json_encode($risp);
}

if ($operazione=="check_prodotto") {
	$codice_prodotto=$_POST['codice_prodotto'];
	$check_prodotto=$main_prodotti->check_prodotto($codice_prodotto);
	print json_encode($check_prodotto);	
}

if ($operazione=="info_prezzo") {
	$product_id=$_POST['product_id'];
	$storia_prezzo=$main_prodotti->storia_prezzo($product_id);
	print json_encode($storia_prezzo);	
}
if ($operazione=="delete_prezzo") {
	$id_delete=$_POST['id_delete'];
	$delete_prezzo=$main_prodotti->delete_prezzo($id_delete);
	print json_encode($delete_prezzo);	
}


exit;
?>