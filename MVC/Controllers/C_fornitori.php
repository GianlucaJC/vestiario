<?php
	$id_edit="";$id_delete="";
	$denominazione="";$telefono="";$mail="";
	if (isset($_POST['id_edit'])) $id_edit=$_POST['id_edit'];
	if (isset($_POST['id_delete'])) $id_delete=$_POST['id_delete'];
	

	$main = new Main_Fornitori($db);
	
	if (strlen($id_edit)!=0) {
		$info_edit=$main->elenco($id_edit);
		$denominazione=$info_edit[0]['denominazione'];
		$telefono=$info_edit[0]['telefono'];
		$mail=$info_edit[0]['mail'];
	}
	
	if (strlen($id_delete)!=0) {
		$delete_fornitore=$main->delete_fornitore($id_delete);
	}
	
	if (isset($_POST['btn_save'])) {
		$save=$main->save();
	}
	
	$elenco=$main->elenco();	
?>