<?php
	$id_edit="";$id_delete="";
	if (isset($_POST['id_edit'])) $id_edit=$_POST['id_edit'];
	if (isset($_POST['id_delete'])) $id_delete=$_POST['id_delete'];
	

	$main_dipendenti = new Main_Dipendenti($db);
	
	$reparto_ref="";
	if (isset($_POST['reparto_ref'])) {
		$reparto_ref=$_POST['reparto_ref'];
		$save_reparto_user=$main_dipendenti->save_reparto_user($reparto_ref,$id_user);
	}	
	
	
	if (strlen($id_edit)!=0) {
		$info_edit=$main_dipendenti-->elenco($id_edit);
		$codice_fornitore=$info_edit[0]['codice_fornitore'];

	}
	
	if (strlen($id_delete)!=0) {
		$delete_prodotto=$main_dipendenti->delete_prodotto($id_delete);
	}
	
	if (isset($_POST['btn_save'])) {
		$save=$main_dipendenti->save();
	}
	
	$elenco=$main_dipendenti->elenco();
	$reparti_e_sr=$main_dipendenti->reparti_e_sr();
?>