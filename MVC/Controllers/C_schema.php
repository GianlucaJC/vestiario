<?php

	$main = new Main_Schema($db);
	
	$id_edit="";$id_delete="";$btn_new="";$save_copia="";
	if (isset($_POST['id_edit'])) $id_edit=$_POST['id_edit'];
	if (isset($_POST['id_delete'])) $id_delete=$_POST['id_delete'];
	if (isset($_POST['save_copia'])) $save_copia=$_POST['save_copia'];
	
	if (isset($_POST['btn_new'])) {
		if (strlen($_POST['btn_new'])!=0) $btn_new="1";
	}	

	if ($save_copia=="1") {
		$save_clone=$main->save_clone();
	}

	$save="";
	if (isset($_POST['btn_save'])) {
		$save=$main->save();
	}

	if (strlen($id_delete)!=0) {
		$delete_prodotto=$main->delete_prodotto($id_delete);
	}
	$reparto="";$tipo_prod="";
	
	$schema=array();
	if ((isset($_POST['reparto']) || isset($_POST['reparto_ref'])) && (isset($_POST['tipo_prod']) || isset($_POST['tipo_prod_ref'])) ) {
		if (isset($_POST['reparto'])) {
			$reparto=$_POST['reparto'];
			$tipo_prod=$_POST['tipo_prod'];
		}	
		else {
			$reparto=$_POST['reparto_ref'];
			$tipo_prod=$_POST['tipo_prod_ref'];
		}
		$schema=$main->schema($reparto,$tipo_prod);
	}	
	$reparto_sel="";
	if (isset($_POST['reparto_sel'])) {
		$reparto_sel=$_POST['reparto_sel'];
	}
	$tipo_prod_sel="";
	if (isset($_POST['tipo_prod_sel'])) {
		$tipo_prod_sel=$_POST['tipo_prod_sel'];
	}	

	
	$info_articolo_schema=0;
	
	if (strlen($id_edit)!=0) {
		$info_articolo_schema=$main->info_articolo_schema($id_edit);
	}
	
	

	
	
	$elenco_prodotti=$main->elenco_prodotti($tipo_prod);
	$reparti=$main->reparti();

?>