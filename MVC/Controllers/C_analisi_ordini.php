<?php
	$id_edit="";
	if (isset($_POST['id_edit'])) $id_edit=$_POST['id_edit'];
	

	$main_sottoscorta = new Main_Sottoscorta($db);
	$min_ss="2";
	if (isset($_POST['min_ss'])) $min_ss=$_POST['min_ss'];
	$elenco=$main_sottoscorta->elenco();
	$qta_from_richieste=$main_sottoscorta->qta_from_richieste();
	if (isset($_POST['btn_reset'])) unset($_POST);
?>