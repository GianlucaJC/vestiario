<?php
	$main = new Main_main($db);
	$main_richiesta = new Main_Richiesta($db);
	$tipo_richiesta=$_POST['tipo_richiesta'];
	

	$elenco_dipendenti=$main_richiesta->elenco_dipendenti($id_rep_user,$id_sr_user);
	
	$send_richiesta=$_POST['send_richiesta'];
	if (strlen($send_richiesta)!=0) {
		$save_richiesta=$main_richiesta->save_richiesta($id_user);
	}
	
	$id_edit=$_POST['id_edit'];

	$load_richiesta=array();
	$load_prod=1;
	$id_rep_user_ref=$id_rep_user;
	$id_sr_user_ref=$id_sr_user;
	if (strlen($id_edit)!=0) {
		$load_prod=2;
		$load_richiesta=$main_richiesta->load_richiesta($id_edit);
		$id_rep_user_ref=$load_richiesta[0]['id_reparto'];
		$id_sr_user_ref=$load_richiesta[0]['id_sr'];
	}


	$elenco_prodotti_abb=$main_richiesta->elenco_prodotti($load_prod,$id_rep_user_ref,$id_sr_user_ref,"abb");
	$elenco_prodotti_dpi=$main_richiesta->elenco_prodotti($load_prod,$id_rep_user_ref,$id_sr_user_ref,"dpi");
	
	
	$elenco_dpi=$main_richiesta->elenco_dpi();
	
?>