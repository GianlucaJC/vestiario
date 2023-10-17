<?php
class Main_Richieste
	{
	private $conn;

	
	// costruttore
	public function __construct($db){
		$this->conn = $db;
		$this->conn->set_charset("utf8");
	}


	function elenco($id="",$id_user,$is_admin) {
		$cond="1";
		$id_rep_user=$_SESSION['vest_id_rep'];
		$id_sr_user=$_SESSION['vest_id_sr'];		
		if ($is_admin!=1) {
			//ogni capo reparto vedere tutte le richieste del proprio reparto ahce se ad es. è stata
			//fatta da un admin per quel reparto...ovviamente in sola lettura
			//$cond="r.id_richiedente=$id_user ";
			$cond=" (rep.id=$id_rep_user and sr.id=$id_sr_user) ";
		}
		if (strlen($id)!=0) {
			$cond="id=$id";
			if ($id=="from_analisi") {
				$codice=$_GET['codice'];
				$taglia=$_GET['taglia'];
				$cond="ri.codice_articolo='$codice' and ri.taglia='$taglia'";
			}
		}	
		else if (isset($_GET['view'])) $cond.=" and stato=".$_GET['view'];
		
		/*
		$sql="SELECT r.*,rep.reparto,ri.codice_articolo,ri.taglia,ri.qta_richiesta,ri.qta_impegno,u.operatore richiedente,di.dipendente dipendente from `richieste` r 
				INNER JOIN `richieste_items` ri ON r.id=ri.id_richiesta
				INNER JOIN `Sql58368_4`.`utenti` u ON r.id_richiedente=u.id
				INNER JOIN `reparti` rep ON u.vest_id_rep=rep.id 
				LEFT OUTER JOIN `sotto_reparti` sr ON u.vest_id_sr=sr.id
				INNER JOIN `dipendenti` di ON r.id_dipendente=di.id 
				WHERE $cond
				GROUP BY r.id
				ORDER BY r.data_richiesta desc,rep.reparto,operatore,dipendente";
		*/
		
		$sql="SELECT r.*,rep.reparto,ri.codice_articolo,ri.taglia,ri.qta_richiesta,ri.qta_impegno,u.operatore richiedente,di.dipendente dipendente from `richieste` r 
				INNER JOIN `richieste_items` ri ON r.id=ri.id_richiesta
				INNER JOIN `Sql58368_4`.`utenti` u ON r.id_richiedente=u.id
				INNER JOIN `reparti` rep ON r.id_reparto=rep.id 
				LEFT OUTER JOIN `sotto_reparti` sr ON r.id_sr=sr.id
				INNER JOIN `dipendenti` di ON r.id_dipendente=di.id 
				WHERE $cond
				GROUP BY r.id
				ORDER BY r.data_richiesta desc,rep.reparto,operatore,dipendente";
		
		$resp=array();
		$result=$this->conn->query($sql);
		$elem=0;
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}
		return $resp;
	}

	function delete_richiesta($id_delete) {
		//reinserimento delle quantità impegnate nella giacenza virtuale
		$sql="SELECT id_fornitore,codice_articolo,taglia,qta_impegno FROM `richieste_items` WHERE id_richiesta=$id_delete";
		$result=$this->conn->query($sql);
		
		while($results = $result->fetch_assoc()){
			$id_fornitore=$results['id_fornitore'];
			$codice_articolo=$results['codice_articolo'];
			$taglia=$results['taglia'];
			$qta_impegno=$results['qta_impegno'];
			$sql="UPDATE prodotti SET giacenza_impegno=giacenza_impegno+$qta_impegno WHERE codice_fornitore='$codice_articolo' and taglia='$taglia' and id_fornitore=$id_fornitore";
			$res1=$this->conn->query($sql);

		}
		//cancellazione effettiva dei prodotti dalla richiesta
		$sql="DELETE FROM `richieste_items` WHERE id_richiesta=$id_delete";
		$result=$this->conn->query($sql);
		$sql="DELETE FROM `richieste` WHERE id=$id_delete";
		$result=$this->conn->query($sql);
		//dal controller viene invocato anche l'aggiornamento globale update_stock()
		return result;
	}

}
?>