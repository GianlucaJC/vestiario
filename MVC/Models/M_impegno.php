<?php
class Main_Impegno
	{
	private $conn;

	
	// costruttore
	public function __construct($db){
		$this->conn = $db;
		$this->conn->set_charset("utf8");
	}

	public function load_old_request($id_richiesta) {
		$sql="SELECT * 
				FROM old_request_items
				WHERE id_richiesta=$id_richiesta
				ORDER BY data_ora desc";
		$result=$this->conn->query($sql);
		$resp=array();
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}
		return $resp;
	}

	public function load_richiesta($id_richiesta) {
		
		
			$sql="SELECT r.*,rep.reparto,ri.id id_ref,ri.id_richiesta,ri.id_fornitore,ri.codice_articolo,ri.taglia,ri.qta_richiesta,ri.qta_impegno,ri.storia,ri.qta_consegnata,f.fornitore,d.operatore richiedente,di.dipendente dipendente,di.id id_dipendente from `vestiario`.`richieste` r 
					INNER JOIN `vestiario`.`richieste_items` ri ON r.id=ri.id_richiesta
					INNER JOIN `Sql58368_4`.`utenti` d ON r.id_richiedente=d.id
					INNER JOIN `vestiario`.`reparti` rep ON d.vest_id_rep=rep.id 
					LEFT OUTER JOIN `vestiario`.`sotto_reparti` sr ON d.vest_id_sr=sr.id
					INNER JOIN `vestiario`.`fornitori` f ON ri.id_fornitore=f.id 
					INNER JOIN `vestiario`.`dipendenti` di ON r.id_dipendente=di.id 
					WHERE r.id=$id_richiesta
					GROUP BY ri.id
					ORDER BY rep.reparto,richiedente,dipendente";
		

		$resp=array();
		$result=$this->conn->query($sql);
		$elem=0;
		while($results = $result->fetch_assoc()){
			
			$codice_articolo=$results['codice_articolo'];
			$taglia=$results['taglia'];
			$id_fornitore=$results['id_fornitore'];
			$sql="SELECT id,descrizione descrizione_articolo,giacenza,giacenza_impegno FROM prodotti WHERE codice_fornitore='$codice_articolo' and taglia='$taglia' and id_fornitore=$id_fornitore";
			$result1 = $this->conn->query($sql);
			$row = $result1->fetch_assoc();
			$giacenza=$row['giacenza'];
			$id_prodotto=$row['id'];
			$giacenza_impegno=$row['giacenza_impegno'];
			$descrizione_articolo=$row['descrizione_articolo'];
			$results['id_prodotto']=$id_prodotto;
			$results['giacenza']=$giacenza;
			$results['giacenza_impegno']=$giacenza_impegno;
			$results['descrizione_articolo']=$descrizione_articolo;
			
			$resp[]=$results;
		}
		
		return $resp;

	}
	
	public function check_save() {
		$id_ref_a=$_POST['id_ref'];
		$qta_impegno_a=$_POST['qta_impegno'];
		$codice_articolo_a=$_POST['codice_articolo'];
		$taglia_a=$_POST['taglia'];
		$qta_richiesta_a=$_POST['qta_richiesta'];
		
		$chec=array();
		for ($sca=0;$sca<=count($id_ref_a)-1;$sca++) {
			$id_ref=$id_ref_a[$sca];			
			$qta_impegno=$qta_impegno_a[$sca];
			$codice_articolo=$codice_articolo_a[$sca];
			$taglia=$taglia_a[$sca];
			$qta_richiesta=$qta_richiesta_a[$sca];
			
			$indice=$codice_articolo.$taglia;
			
			if (strlen($qta_impegno)==0) $qta_impegno=0;
			
			$check[$indice]['qta_impegno']+=$qta_impegno;
			$check[$indice]['qta_richiesta']=$qta_richiesta;
		}
		$risp=array();
		$risp['check']=1;
		foreach($check as $k=>$v) {
			
			$impegno=$check[$k]['qta_impegno'];
			$richiesta=$check[$k]['qta_richiesta'];
			if ($impegno>$richiesta) {
				$check[$k]['result_over']="1";
				$risp['check']=0;
			}	
			if ($impegno==$richiesta) 
				$check[$k]['result_over']="0";
			if ($impegno<$richiesta) 
				$check[$k]['result_over']="-1";
		}
		$risp['analisi']=$check;
		return $risp;
	}
	
	public function save_impegni() {
		$check=$this->check_save();
		if ($check['check']==0) {
			return $check;
		}
		
		$id_prodotto_a=$_POST['id_prodotto'];
		$id_ref_a=$_POST['id_ref'];
		$qta_impegno_a=$_POST['qta_impegno'];
		for ($sca=0;$sca<=count($id_ref_a)-1;$sca++) {
			$id_prodotto=$id_prodotto_a[$sca];
			$id_ref=$id_ref_a[$sca];
			$qta_impegno=$qta_impegno_a[$sca];
			if (strlen($qta_impegno)!=0) {
				$sql="UPDATE prodotti SET giacenza_impegno=giacenza_impegno-$qta_impegno WHERE id=$id_prodotto";
				$result=$this->conn->query($sql);
				
				$sql="SELECT storia storia_old FROM `richieste_items` WHERE id=$id_ref";

				$res1 = $this->conn->query($sql);
				$row = $res1->fetch_assoc();
				$storia_old=$row['storia_old'];
				$storia=$qta_impegno;
				if (strlen($storia_old)!=0) $storia="$storia_old,$qta_impegno";
				//aggiornamento quantità impegni su tabella richieste_items
				$sql="UPDATE `richieste_items`
						SET qta_impegno=qta_impegno+$qta_impegno,storia='$storia' 
						WHERE id=$id_ref";

				$result=$this->conn->query($sql);

			}
		}
		
		$id_refx=$id_ref_a[0];
		$sql="SELECT id_richiesta FROM `richieste_items` WHERE id=$id_refx ";
		$result = $this->conn->query($sql);
		$row = $result->fetch_assoc();
		$id_richiesta=$row['id_richiesta'];
		
		$sql="UPDATE `richieste` SET stato=1 WHERE id=$id_richiesta and stato=0";
		$result=$this->conn->query($sql);
		
		
		return $check;
	}
	
	public function archivia_pdf($testo_doc,$tipo_richiesta,$id_ref,$filename,$id_dipendente) {
		$testo_doc=addslashes($testo_doc);
		$sql="INSERT INTO `db_pdf` 
				(id_dipendente, filename, testo_doc, tipo_richiesta)
				VALUES 
				($id_dipendente,'$filename', '$testo_doc', '$tipo_richiesta')";
		$result=$this->conn->query($sql);
		//dopo la firma-consegna, azzero gli impegni
		$sql="UPDATE `richieste_items` SET qta_consegnata=qta_consegnata+qta_impegno,qta_impegno=0 WHERE id_richiesta=$id_ref";
		
		$result=$this->conn->query($sql);
		
		//Aggiornamento Giacenza REALE da giacenza virtuale
		$sql="UPDATE `prodotti` p
				INNER JOIN `richieste_items` r ON p.codice_fornitore=r.codice_articolo and p.taglia=r.taglia and p.id_fornitore=r.id_fornitore
				SET p.giacenza=p.giacenza_impegno
				WHERE r.id_richiesta=$id_ref";
		$result=$this->conn->query($sql);
		
		//verifica se la richiesta è conclusa
		$sql="SELECT codice_articolo,taglia,qta_consegnata, qta_richiesta FROM `richieste_items` WHERE id_richiesta=$id_ref";
		$result=$this->conn->query($sql);
		$tot_cons=0;
		$arr=array();
		while($results = $result->fetch_assoc()){
			$tot_cons+=$results['qta_consegnata'];
			$qta_richiesta=$results['qta_richiesta'];

			$codice_articolo=$results['codice_articolo'];
			$taglia=$results['taglia'];
			$codice=$codice_articolo.$taglia;
			$arr[$codice]=$qta_richiesta;
		}	
		$tot_rich=0;
		foreach($arr as $k=>$v) {
			$tot_rich+=$arr[$k];
		}
		if ($tot_cons==$tot_rich) {
			$sql="UPDATE richieste SET stato=3 WHERE id=$id_ref";
			$result = $this->conn->query($sql);
		}
	}

}
?>