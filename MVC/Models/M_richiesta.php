<?php
class Main_Richiesta
	{
	private $conn;

	
	// costruttore
	public function __construct($db){
		$this->conn = $db;
		$this->conn->set_charset("utf8");
	}

	
	function elenco_dpi() {
		$sql="SELECT p.*,f.fornitore from `prodotti` p
				INNER JOIN `fornitori` f ON p.id_fornitore=f.id
				WHERE p.dele=0 and p.tipo_prod='dpi'
				GROUP BY p.codice_fornitore
				ORDER BY p.descrizione";
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}
		return $resp;
	}

	function elenco_prodotti($tipo_prod,$id_rep_user,$id_sr_user,$tipo_richiesta) {
		
		$cond="s.id_reparto=$id_rep_user and s.id_sr=$id_sr_user and p.tipo_prod='$tipo_richiesta'";

		$sql="SELECT p.*,f.fornitore,s.codice_prodotto_fornitore from `prodotti` p
				INNER JOIN `fornitori` f ON p.id_fornitore=f.id
				INNER JOIN `schema_reparti` s ON p.codice_fornitore=s.codice_prodotto_fornitore
				WHERE p.dele=0 and $cond
				GROUP BY p.codice_fornitore
				ORDER BY p.descrizione";

		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}


		return $resp;
		
	}
	
	
	public function popola_taglia($codice_fornitore) {
		
		$sql="SELECT p.taglia FROM `prodotti` p
				WHERE codice_fornitore='$codice_fornitore'
				GROUP BY codice_fornitore,taglia
				ORDER BY p.taglia";
	
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}


		return $resp;

	}
	
	public function elenco_dipendenti($id_rep_user,$id_sr_user) {
		$cond="d.id_reparto=$id_rep_user and d.id_sr=$id_sr_user";

		$is_admin=$_SESSION['vest_access'];
		if ($is_admin=="1") $cond="1";
		$sql="SELECT d.* from `dipendenti` d
				WHERE d.dele=0 and $cond
				ORDER BY d.dipendente";
	
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}


		return $resp;
		
	}
	
	public function save_richiesta($id_user) {
		$id_rep_user=$_SESSION['vest_id_rep'];
		$id_sr_user=$_SESSION['vest_id_sr'];
		
		$tipo_richiesta=$_POST['tipo_richiesta'];
		$id_dipendente=$_POST['dipendente'];
		$prodotto=$_POST['prodotto'];
		$taglie=$_POST['taglia'];
		$qta=$_POST['qta'];
		
		$id_edit_ref=$_POST['id_edit_ref'];
		$data_richiesta=date("Y-m-d");
		if (strlen($id_edit_ref)==0)
			$sql="INSERT INTO `richieste`(`tipo_richiesta`,`id_reparto`,`id_sr`, `id_richiedente`, `id_dipendente`, `data_richiesta`) 
				VALUES 
				('$tipo_richiesta', $id_rep_user, $id_sr_user, $id_user, $id_dipendente, '$data_richiesta' )";
		else 		
			$sql="UPDATE `richieste` SET `tipo_richiesta`='$tipo_richiesta', `id_dipendente`=$id_dipendente  WHERE id=$id_edit_ref";
			
		//`id_richiedente`=$id_user,	
		

		$result=$this->conn->query($sql);
		if (strlen($id_edit_ref)==0) {
			$id_richiesta = $this->conn->insert_id;
			$sql="UPDATE `richieste` set dataora_creazione=dataora_update WHERE id=$id_richiesta";
			$result=$this->conn->query($sql);
		}	
		else {
			$id_richiesta=$id_edit_ref;
			/*
				Visto che sia in caso di modifica che nuova richiesta: cancello tutti gli items della richiesta e li riscrivo...in caso di impegno già fatto prima della modifica, il valore dell'impegno viene perso e nella scheda prodotto risulta ancora l'impegno fatto...quindi tramite un array di appoggio salvo tutta la precedente richiesta per riproporla sui nuovi items che creerò
			*/
			$sql="SELECT codice_articolo,taglia,id_fornitore,qta_impegno,qta_consegnata,storia FROM `richieste_items` WHERE id_richiesta=$id_richiesta";
			$result=$this->conn->query($sql);
			$prec=array();$prev=array();
			/*
				per creare un alert in caso di modifica affinchè l'admin possa avere
				percezione che un richiedente ha cambiato qualcosa (magari una taglia...quindi di fatto altro articolo...o quantità) a patto ovviamente che ci sia stato almeno un impegno...a tal proposito monitoro qta_impegno lungo tutto il flusso degli items
			*/	
			$alert_edit=false;
			while($results = $result->fetch_assoc()){
				$codice_articolo=$results['codice_articolo'];
				$taglia=$results['taglia'];
				$id_fornitore=$results['id_fornitore'];
				$qta_impegno=$results['qta_impegno'];
				if ($qta_impegno!=0) $alert_edit=true;
				
				$qta_consegnata=$results['qta_consegnata'];
				$storia=$results['storia'];
				$prev[]=$codice_articolo;
				
				$prec[$codice_articolo]['taglia']=$taglia;
				$prec[$codice_articolo]['qta_impegno']=$qta_impegno;
				$prec[$codice_articolo]['qta_consegnata']=$qta_consegnata;
				$prec[$codice_articolo]['storia']=$storia;

				/*
				$sql="UPDATE `prodotti` SET giacenza_impegno=giacenza_impegno+$qta_impegno
						WHERE codice_fornitore='$codice_articolo' and taglia='$taglia' and id_fornitore='$id_fornitore'";
				$res=$this->conn->query($sql);						
				*/
			}
			
			if ($alert_edit==true) {
				//salvo tutta la situazione precedente alla modifica in old_request_items
				//sarà mostra unitamente all'alert per capire cosa è successo!
				$sql="INSERT INTO old_request_items (`id_richiesta`, `codice_articolo`, `taglia`, `id_fornitore`, `qta_richiesta`, `qta_impegno`, `qta_consegnata`, `storia`, `data_scadenza`)
						SELECT `id_richiesta`, `codice_articolo`, `taglia`, `id_fornitore`, `qta_richiesta`, `qta_impegno`, `qta_consegnata`, `storia`, `data_scadenza`
						FROM richieste_items r
						WHERE r.id_richiesta=$id_richiesta; 
					";
				$result=$this->conn->query($sql);	
			}

			
			//cancello tutte la richiesta negli items...per poi ricostruire ex-novo
			$sql="DELETE FROM  `richieste_items` WHERE id_richiesta=$id_richiesta";
			$result=$this->conn->query($sql);
		}	


		for ($sca=0;$sca<=count($prodotto)-1;$sca++) {
			$codice_articolo=$prodotto[$sca];
			$taglia=$taglie[$sca];
			$qta_richiesta=$qta[$sca];
			
			$sql="SELECT id_fornitore,scadenza FROM prodotti WHERE codice_fornitore='$codice_articolo' and taglia='$taglia'";
			$result=$this->conn->query($sql);

			while($results = $result->fetch_assoc()){
				$id_fornitore=$results['id_fornitore'];
				$scadenza=$results['scadenza'];
				if ($scadenza!=0) {
					$startDate = time();
					$data_scadenza=date('Y-m-d', strtotime("+$scadenza day", $startDate));
					$sql="INSERT INTO `richieste_items`
					(`id_richiesta`, `codice_articolo`, `taglia`,  `id_fornitore`, `qta_richiesta`, `data_scadenza`) 
					VALUES 
					($id_richiesta,'$codice_articolo','$taglia', $id_fornitore, $qta_richiesta,'$data_scadenza')";
				} else 
					$sql="INSERT INTO `richieste_items`
					(`id_richiesta`, `codice_articolo`, `taglia`,  `id_fornitore`, `qta_richiesta`, `data_scadenza`) 
					VALUES 
					($id_richiesta,'$codice_articolo','$taglia', $id_fornitore, $qta_richiesta,NULL)";
				
			

				$result1=$this->conn->query($sql);
				
				//ripopolo i nuovi items creato con i valori eventuali precedenti
				if (in_array($codice_articolo,$prev)) {
					$taglia_prec=$prec[$codice_articolo]['taglia'];
					$qta_consegnata=$prec[$codice_articolo]['qta_consegnata'];
					$qta_impegno=$prec[$codice_articolo]['qta_impegno'];
					$storia=$prec[$codice_articolo]['storia'];
					if ($taglia_prec==$taglia) {
						$sql="UPDATE `richieste_items`
								SET qta_consegnata=$qta_consegnata, qta_impegno=$qta_impegno, storia=$storia 
								WHERE codice_articolo='$codice_articolo' and taglia='$taglia' and id_fornitore='$id_fornitore' and id_richiesta=$id_richiesta";
						$result2=$this->conn->query($sql);
					}
				}
			}	
		}

	}

	public function load_richiesta($id_richiesta) {
		
		
			$sql="SELECT r.*,rep.reparto,ri.codice_articolo,ri.taglia,ri.qta_richiesta,d.operatore richiedente,di.dipendente dipendente from `vestiario`.`richieste` r 
					INNER JOIN `vestiario`.`richieste_items` ri ON r.id=ri.id_richiesta
					INNER JOIN `Sql58368_4`.`utenti` d ON r.id_richiedente=d.id
					INNER JOIN `vestiario`.`reparti` rep ON d.vest_id_rep=rep.id 
					LEFT OUTER JOIN `vestiario`.`sotto_reparti` sr ON d.vest_id_sr=sr.id
					INNER JOIN `vestiario`.`dipendenti` di ON r.id_dipendente=di.id 
					WHERE r.id=$id_richiesta
					GROUP BY ri.codice_articolo,ri.taglia
					ORDER BY rep.reparto,richiedente,dipendente";

			/*		
			$sql="SELECT r.*,rep.reparto,ri.codice_articolo,ri.taglia,ri.qta_richiesta,d.dipendente richiedente,di.dipendente dipendente,p.giacenza_impegno qta_impegno
				FROM `richieste` r
				INNER JOIN `richieste_items` ri ON r.id=ri.id_richiesta
				INNER JOIN `prodotti` p ON ri.codice_articolo=p.codice_fornitore and ri.taglia=p.taglia
				INNER JOIN `dipendenti` d ON r.id_richiedente=d.id
				INNER JOIN `reparti` rep ON d.id_reparto=rep.id
				LEFT OUTER JOIN `sotto_reparti` sr ON d.id_sr=sr.id
				INNER JOIN `dipendenti` di ON r.id_dipendente=di.id
				WHERE r.id=$id_richiesta
				ORDER BY rep.reparto,richiedente,dipendente";	
			*/


		$resp=array();
		$result=$this->conn->query($sql);
		$elem=0;
		while($results = $result->fetch_assoc()){
			/*
			$codice_articolo=$results['codice_articolo'];
			$taglia=$results['taglia'];
			$sql="SELECT SUM(giacenza_impegno) qta_impegno FROM prodotti WHERE codice_fornitore='$codice_articolo' and taglia='$taglia'";
			$result1 = $this->conn->query($sql);
			$row = $result1->fetch_assoc();
			$qta_impegno=$row['qta_impegno'];
			$results['qta_impegno']=$qta_impegno;
			*/
			$resp[]=$results;
		}
		
		return $resp;

	}
	
	public function taglie($codice_prodotto) {
		$sql="SELECT taglia FROM prodotti WHERE codice_fornitore='$codice_prodotto' ORDER BY taglia;";
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}
		return $resp;		
	}


	public function elenco_img($codice_fornitore,$taglia) {
		$sql="SELECT id FROM prodotti WHERE codice_fornitore='$codice_fornitore' and taglia='$taglia';";
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$id_prod=$results['id'];
			for ($sca=1;$sca<=4;$sca++) {
				$ext="";
				if ($sca==1) $ext="jpg";
				if ($sca==2) $ext="jpeg";
				if ($sca==3) $ext="png";
				if ($sca==4) $ext="gif";
				$fx="../prodotti/files/".$id_prod.".$ext";
				if (file_exists($fx)) {
					$resp[]=$fx;
					break;
				}
			}	
		}
		return $resp;		
	}


}
?>