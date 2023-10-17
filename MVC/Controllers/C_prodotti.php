<?php
	$id_edit="";$id_delete="";
	$codice_fornitore="";$descrizione="";$taglia="";$tipo_prod="";$btn_clone="";
	$id_fornitore="";$giacenza="";$sottoscorta="";$scadenza="";$prezzo=0;
	if (isset($_POST['id_edit'])) $id_edit=$_POST['id_edit'];
	if (isset($_POST['id_delete'])) $id_delete=$_POST['id_delete'];
	
	$clone_foto=array();
	if (isset($_POST['clone_foto'])) $clone_foto=$_POST['clone_foto'];
	if (isset($_POST['btn_clone'])) $btn_clone=$_POST['btn_clone'];
	

	$main = new Main_Prodotti($db);
	
	if (strlen($id_edit)!=0) {
		$info_edit=$main->elenco($id_edit);
		$tipo_prod=$info_edit[0]['tipo_prod'];
		$codice_fornitore=$info_edit[0]['codice_fornitore'];
		$id_fornitore=$info_edit[0]['id_fornitore'];
		$descrizione=$info_edit[0]['descrizione'];
		$descrizione=stripslashes($descrizione);
		$taglia=$info_edit[0]['taglia'];
		$giacenza=$info_edit[0]['giacenza'];
		$sottoscorta=$info_edit[0]['sottoscorta'];
		$scadenza=$info_edit[0]['scadenza'];
		$prezzo=$info_edit[0]['prezzo'];
	}
	
	if (strlen($id_delete)!=0) {
		$delete_prodotto=$main->delete_prodotto($id_delete);
	}
	
	if (isset($_POST['btn_save'])) {
		$save=$main->save();
	}
	if ($btn_clone=="1") {
		$id_save=$_POST['id_save'];
		for ($sc=0;$sc<=count($clone_foto)-1;$sc++) {
			for ($sc2=1;$sc2<=4;$sc2++) {
				$ext="";
				if ($sc2==1) $ext="jpg";
				if ($sc2==2) $ext="jpeg";
				if ($sc2==3) $ext="png";
				if ($sc2==4) $ext="gif";
				$orig="../../pages/prodotti/files/".$id_save.".$ext";
				if (file_exists($orig)) {
					$new_f=$clone_foto[$sc];
					@unlink("../../pages/prodotti/files/".$new_f.".jpg");
					@unlink("../../pages/prodotti/files/".$new_f.".jpeg");
					@unlink("../../pages/prodotti/files/".$new_f.".png");
					@unlink("../../pages/prodotti/files/".$new_f.".gif");
					$dest="../../pages/prodotti/files/".$new_f.".$ext";
					copy($orig,$dest);
					break;
				}
			}
		}
	}
	
	$elenco=$main->elenco();
	$fornitori=$main->fornitori();
	
?>