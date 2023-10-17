<?php
class Main_Carico
	{
	private $conn;

	
	// costruttore
	public function __construct($db){
		$this->conn = $db;
		$this->conn->set_charset("utf8");
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
	

	public function popola_fornitori($codice_prodotto,$taglia) {
		
		$sql="SELECT f.id,f.fornitore FROM `prodotti` p
				INNER JOIN `fornitori` f ON p.id_fornitore=f.id
				WHERE codice_fornitore='$codice_prodotto' and taglia='$taglia'
				GROUP BY f.id
				ORDER BY f.fornitore";
	
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}


		return $resp;

	}	
	
	
	function save_carico() {
		//inizialmente proveniente da GET (nel controller) quando si richiama la pagina per indicare se si tratta di carico o scarico		
		$from=$_POST['from'];
		
		$prodotto_a=$_POST['prodotto'];
		$taglia_a=$_POST['taglia'];
		$fornitore_a=$_POST['fornitore'];
		$qta_a=$_POST['qta'];
	
		for ($sca=0;$sca<=count($prodotto_a)-1;$sca++) {
			$prodotto=$prodotto_a[$sca];
			$taglia=$taglia_a[$sca];
			$fornitore=$fornitore_a[$sca];
			$qta=$qta_a[$sca];
			if ($from=="1")
				$sql="UPDATE `prodotti` 
					SET giacenza=giacenza+$qta,giacenza_impegno=giacenza_impegno+$qta
					WHERE codice_fornitore='$prodotto' and taglia='$taglia' and id_fornitore=$fornitore";
			else		
				$sql="UPDATE `prodotti` 
					SET giacenza=giacenza-$qta,giacenza_impegno=giacenza_impegno-$qta
					WHERE codice_fornitore='$prodotto' and taglia='$taglia' and id_fornitore=$fornitore";
			$result=$this->conn->query($sql);
		}
	}


}
?>