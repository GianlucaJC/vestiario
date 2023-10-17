<?php
	$id_edit="";$id_delete="";
	
	if (isset($_POST['id_edit'])) $id_edit=$_POST['id_edit'];
	if (isset($_POST['id_delete'])) $id_delete=$_POST['id_delete'];
	

	$main_richieste = new Main_Richieste($db);
	$main = new Main_Main($db);
	
	if (strlen($id_edit)!=0) {
		$info_edit=$main_richieste->elenco($id_edit,0,0);
		/*
		$denominazione=$info_edit[0]['denominazione'];
		$telefono=$info_edit[0]['telefono'];
		$mail=$info_edit[0]['mail'];
		*/
	}
	//Chiamata dalla procedura di analisi per ordini
	
	if (strlen($id_delete)!=0) {
		$delete_richiesta=$main_richieste->delete_richiesta($id_delete);
	}
	
	
	if (isset($_GET['analisi'])) {
		if ($_GET['analisi']=="1") {
			$elenco=$main_richieste->elenco("from_analisi",0,0);
		}
	} else $elenco=$main_richieste->elenco("",$id_user,$is_admin);	
?>