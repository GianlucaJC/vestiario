<?php
class Main_Prodotti
	{
	private $conn;

	
	// costruttore
	public function __construct($db){
		$this->conn = $db;
		$this->conn->set_charset("utf8");
	}


	function elenco($id="") {
		$cond="1";
		if (strlen($id)!=0) $cond="p.id=$id";

		$sql="SELECT p.*,pz.data data_prezzo,prezzo,f.fornitore from `prodotti` p
				INNER JOIN `fornitori` f ON p.id_fornitore=f.id
				LEFT OUTER JOIN `prezzi` pz ON p.id=pz.id_prodotto
				WHERE p.dele=0 and $cond
				GROUP BY p.id
				ORDER BY p.descrizione,pz.id desc";
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}
		return $resp;
	}
	
	function fornitori() {
		$sql="SELECT id,fornitore from `fornitori` ORDER BY fornitore";
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}
		return $resp;
	}

	function delete_prodotto($id_delete) {
		$sql="UPDATE `prodotti` SET dele=1 WHERE id=$id_delete";
		$result=$this->conn->query($sql);
		return result;
	}


	function save() {
		$id_save=$_POST['id_save'];
		$tipo_prod=$_POST['tipo_prod'];
		$codice_fornitore=$_POST['codice_fornitore'];
		$descrizione=$_POST['descrizione'];
		$descrizione=addslashes($descrizione);
		$taglia=$_POST['taglia'];
		$prezzo=$_POST['prezzo'];

		$fornitore=$_POST['fornitore'];
		$fornitore=addslashes($fornitore);
		$giacenza=$_POST['giacenza'];
		$sottoscorta=$_POST['sottoscorta'];
		$scadenza=$_POST['scadenza'];
		
		
		if (strlen($id_save)!=0)
			$sql="UPDATE `prodotti` SET `tipo_prod`='$tipo_prod' ,`codice_fornitore`='$codice_fornitore' ,`descrizione`='$descrizione' ,`taglia`='$taglia' ,`id_fornitore`=$fornitore, `giacenza`=$giacenza, `giacenza_impegno`=$giacenza, `sottoscorta`=$sottoscorta,`scadenza`=$scadenza
			WHERE id=$id_save";
		else
			$sql="INSERT INTO `prodotti`(`tipo_prod`, `codice_fornitore`, `descrizione`, `taglia`, `id_fornitore`, `giacenza`, `giacenza_impegno`, `sottoscorta`, `scadenza`) VALUES ('$tipo_prod', '$codice_fornitore', '$descrizione', '$taglia', $fornitore, $giacenza, $giacenza, $sottoscorta, $scadenza)";
		
		$result=$this->conn->query($sql);
		
		//Storicizzazione prezzi
		$id_prod=$this->id_prod_from_codice_taglia($codice_fornitore,$taglia);
		if (strlen($id_prod)!=0) {
			$today=date("Y-m-d");
			$sql="SELECT data,prezzo FROM `vestiario`.prezzi WHERE id_prodotto=$id_prod ";
			$result = $this->conn->query($sql);
			$row = $result->fetch_assoc();
			$data=$row['data'];
			$prezzo_old=$row['prezzo'];
			$new_ins=0;
			if (strlen($data)!=0) {
				if ($data==$today) {
					if ($prezzo!=$prezzo_old) $new_ins=1;
				} else $new_ins=1;	
			} else $new_ins=1;	
			echo "prezzo $prezzo prezzo_old $prezzo_old data $data tday $today new_ins $new_ins";
			if ($new_ins==1) {
				$sql="INSERT INTO `vestiario`.prezzi (id_prodotto,data,prezzo) VALUES ($id_prod,'$today',$prezzo)";
				$result = $this->conn->query($sql);
			}
			
		}
		//
		return $result;
		
	}
	
	function check_prodotto($codice_prodotto) {
		$sql="SELECT count(id) q FROM `vestiario`.prodotti where codice_fornitore='$codice_prodotto'";
		$result = $this->conn->query($sql);
		$row = $result->fetch_assoc();
		$resp=array();
		$resp[]=$row['q'];
		return $resp;
	}
	
	function id_prod_from_codice_taglia($codice,$taglia) {
		$sql="SELECT id FROM `vestiario`.prodotti where codice_fornitore='$codice' and taglia='$taglia' LIMIT 0,1";

		$result = $this->conn->query($sql);
		$row = $result->fetch_assoc();
		return $row['id'];
	}
	
	function storia_prezzo($product_id) {
		$sql="SELECT id,prezzo,DATE_FORMAT(data,'%d-%m-%Y') data FROM `vestiario`.prezzi WHERE id_prodotto=$product_id";
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}
		return $resp;
	}
	
	function delete_prezzo($id_delete) {
		$sql="DELETE FROM `vestiario`.prezzi WHERE id=$id_delete";
		$result=$this->conn->query($sql);
		return array("status"=>"OK");
	}


}
?>