<?php
	$id_edit="";
	if (isset($_POST['id_edit'])) $id_edit=$_POST['id_edit'];
	

	$main_scadenze = new Main_Scadenze($db);
	$min_scad="";
	if (isset($_POST['min_scad'])) $min_scad=$_POST['min_scad'];
	
	if (isset($_POST['btn_avvia'])) $annulla_scadenze=$main_scadenze->annulla_scadenze();
	$elenco=$main_scadenze->elenco();
	if (isset($_POST['btn_reset'])) unset($_POST);
	
	
?>