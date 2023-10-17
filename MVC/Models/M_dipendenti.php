<?php
class Main_Dipendenti
	{
	private $conn;

	
	// costruttore
	public function __construct($db){
		$this->conn = $db;
		$this->conn->set_charset("utf8");
	}


	function elenco($id="") {
		$cond="1";
		if (strlen($id)!=0) $cond="d.id=$id";

		$sql="SELECT d.*,r.reparto,sr.reparto sotto_reparto from `dipendenti` d
				INNER JOIN `reparti` r ON d.id_reparto=r.id
				LEFT OUTER JOIN `sotto_reparti` sr ON d.id_sr=sr.id
				WHERE d.dele=0 and $cond
				ORDER BY d.dipendente";
			
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}

		return $resp;
	}
	
	function delete($id_delete) {
		$sql="UPDATE `prodotti` SET dele=1 WHERE id=$id_delete";
		$result=$this->conn->query($sql);
		return result;
	}
	
	public function elenco_pdf($id,$tipo) {
		$sql="SELECT db.*,d.dipendente FROM db_pdf db
			INNER JOIN dipendenti d ON d.id=db.id_dipendente			
				WHERE id_dipendente=$id and tipo_richiesta='$tipo'
				ORDER BY db.data_ora desc";
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}

		return $resp;		
	}

	public function reparti_e_sr() {
		$sql="SELECT r.id id_rep,r.reparto,sr.id id_sr,sr.reparto sotto_reparto FROM `reparti` r 
				LEFT OUTER JOIN sotto_reparti sr ON r.id=sr.id_reparto 
				ORDER BY r.reparto, sr.reparto";
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}

		return $resp;				
	}
	
	function save_reparto($reparto_ref,$id_dip) {
		$info=explode("|",$reparto_ref);
		$id_reparto=$info[0];
		$id_sr=$info[1];
		$sql="UPDATE `dipendenti`
			  SET id_reparto=$id_reparto, id_sr=$id_sr 
			  WHERE id=$id_dip";
		$result=$this->conn->query($sql);			  
	}

	function save_reparto_user($reparto_ref,$id_ref) {
		$info=explode("|",$reparto_ref);
		$id_reparto=$info[0];
		$id_sr=$info[1];
		$sql="UPDATE `Sql58368_4`.`utenti`
			  SET vest_id_rep=$id_reparto, vest_id_sr=$id_sr 
			  WHERE id=$id_ref";
		$result=$this->conn->query($sql);
		
		
		$sql ="SELECT r.reparto,sr.reparto sotto_reparto FROM `Sql58368_4`.`utenti` u
		INNER JOIN `vestiario`.`reparti` r ON u.vest_id_rep=r.id
		INNER JOIN `vestiario`.`sotto_reparti` sr ON u.vest_id_sr=sr.id
		WHERE u.id=$id_ref;";
		if ($result = $this->conn->query($sql)) {

			$res = $result->fetch_row();
			$descr_reparto=$res[0];
			$descr_sr=$res[1];

			$_SESSION['vest_id_rep']=$id_reparto;
			$_SESSION['vest_id_sr']=$id_sr;
			$_SESSION['descr_reparto'] = $descr_reparto;
			$_SESSION['descr_sr'] = $descr_sr;

		}	
		
		

		
	}
	
	public function elenco_dotazione($ref) {
		$resp=array();
		$sql="SELECT ri.id id_ref,d.dipendente,p.descrizione descrizione_articolo,p.tipo_prod,ri.qta_consegnata,ri.codice_articolo,ri.taglia,ri.data_scadenza
			FROM `vestiario`.`richieste_items` ri 
			INNER JOIN `vestiario`.richieste r ON ri.id_richiesta=r.id
			INNER JOIN `vestiario`.dipendenti d ON r.id_dipendente=d.id
			INNER JOIN `vestiario`.prodotti p ON ri.codice_articolo=codice_fornitore and ri.taglia=p.taglia
			WHERE r.id_dipendente=$ref and ri.qta_consegnata>0
			ORDER BY ri.codice_articolo,ri.taglia,ri.data_scadenza desc";
			
		$result = $this->conn->query($sql);

		$resp=array();

		
		$sca=0;
		while($results = $result->fetch_assoc()){
			$tipo_prod=$results['tipo_prod'];
			$codice_articolo=$results['codice_articolo'];
			$descrizione_articolo=stripslashes($results['descrizione_articolo']);
			$taglia=$results['taglia'];
			$data_scadenza=$results['data_scadenza'];
			if ($data_scadenza==NULL) $data_scadenza="N.A.";
			else $data_scadenza=date("d-m-Y",strtotime($data_scadenza));
			$qta_consegnata=$results['qta_consegnata'];
			
			if (array_key_exists($codice_articolo,$resp)) $sca=count($resp[$codice_articolo]);
			else $sca=0;	

			$resp[$codice_articolo][$sca]['tipo_prod']=$tipo_prod;
			$resp[$codice_articolo][$sca]['descrizione_articolo']=$descrizione_articolo;
			$resp[$codice_articolo][$sca]['taglia']=$taglia;
			$resp[$codice_articolo][$sca]['qta_consegnata']=$qta_consegnata;
			$resp[$codice_articolo][$sca]['data_scadenza']=$data_scadenza;
			
			
			
		}
		
		return $resp;
	}
	

	function view_story($ref,$codice_articolo,$taglia) {
		$sql="SELECT f.fornitore,DATE_FORMAT(r.data_richiesta,'%d-%m-%Y') data_richiesta,ri.qta_richiesta,ri.qta_consegnata,DATE_FORMAT(ri.data_scadenza,'%d-%m-%Y') data_scadenza FROM `vestiario`.richieste_items ri
					INNER JOIN `vestiario`.richieste r ON ri.id_richiesta=r.id
					INNER JOIN `vestiario`.fornitori f ON ri.id_fornitore=f.id
					WHERE r.id_dipendente=$ref and ri.codice_articolo='$codice_articolo' and ri.taglia='$taglia' and ri.qta_consegnata>0
					ORDER BY ri.id desc";
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}
		return $resp;
		
	}	
}
?>