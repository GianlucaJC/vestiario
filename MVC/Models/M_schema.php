<?php
class Main_Schema
	{
	private $conn;

	
	// costruttore
	public function __construct($db){
		$this->conn = $db;
		$this->conn->set_charset("utf8");
	}



	public function reparti() {
		$sql="SELECT r.id,r.reparto,s.id id_sr,s.reparto sotto_reparto from `reparti` r
				LEFT OUTER JOIN `sotto_reparti` s ON r.id=s.id_reparto
				 ORDER BY r.reparto,s.reparto";
		
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}
		
		return $resp;
	}
	
	public function schema($reparto,$tipo_prod) {
		if (strlen($reparto)==0) return array();
		$info=explode("|",$reparto);
		$id_reparto=$info[0];
		$id_sr=$info[1];
		if (strlen($id_sr)==0) $id_sr=0;
		$sql="SELECT s.id,s.id_reparto,s.id_sr,r.reparto,p.id id_prod,p.codice_fornitore,p.descrizione,f.fornitore FROM `schema_reparti` s
				INNER JOIN `reparti` r ON s.id_reparto=r.id 
				LEFT OUTER JOIN sotto_reparti sr ON sr.id_reparto=r.id
				INNER JOIN `prodotti` p ON p.codice_fornitore=s.codice_prodotto_fornitore
				INNER JOIN `fornitori` f ON p.id_fornitore=f.id
				WHERE s.id_reparto=$id_reparto and s.id_sr=$id_sr and p.tipo_prod='$tipo_prod' and p.dele=0
				GROUP BY s.id
				ORDER BY p.descrizione";
		
			
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}
		return $resp;				
	}
	
	public function info_articolo_schema($id_schema) {
		$sql="SELECT codice_prodotto_fornitore FROM `schema_reparti` WHERE id=$id_schema";
		$result = $this->conn->query($sql);
		$row = $result->fetch_assoc();
		return $row['codice_prodotto_fornitore'];
	}


	public function elenco_prodotti($tipo_prod) {
		$cond="1";
		//if (strlen($id)!=0) $cond="p.id=$id";
		
		$cond=" tipo_prod='$tipo_prod' ";

		$sql="SELECT p.*,f.fornitore from `prodotti` p
				INNER JOIN `fornitori` f ON p.id_fornitore=f.id
				WHERE p.dele=0 and $cond
				GROUP BY p.codice_fornitore
				ORDER BY p.descrizione";
				//GROUP BY p.id_fornitore,p.codice_fornitore
		$resp=array();
		$result=$this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			$resp[]=$results;
		}
		return $resp;
	}	
	
	public function save() {
		$id_save=$_POST['id_save'];
		$prodotto_schema=$_POST['prodotto_schema'];
		//nuovo prodotto da inserire nello schema
		if (isset($_POST['save_new']) && $_POST['save_new']=="1") {
			//in caso di nuovo prodotto la select è in multiple
			$info=explode("|",$_POST['reparto_ref']);
			$tipo_prod=$_POST['tipo_prod_ref'];
			$id_reparto=$info[0];
			$id_sr=$info[1];
			if (strlen($id_sr)==0) $id_sr=0;
			for ($sca=0;$sca<=count($prodotto_schema)-1;$sca++) {
				$prodotto_schema_ref=$prodotto_schema[$sca];
				$sql="INSERT INTO `schema_reparti` (`codice_prodotto_fornitore`, `id_reparto`, `id_sr`, `tipo_prod`) VALUES ('$prodotto_schema_ref', $id_reparto, $id_sr, '$tipo_prod' )";
				$res=$this->conn->query($sql);
				$result=$this->conn->error;
			}	
		} else {
			//in caso di modifica la select è riferita al singolo articolo (quindi no multiple)
			$sql="UPDATE `schema_reparti` SET codice_prodotto_fornitore='$prodotto_schema' WHERE id=$id_save";
			$res=$this->conn->query($sql);
			$result=$this->conn->error;
		}	
		return $result;
				
	}
	
	public function save_clone() {
		$reparto_copia=$_POST['reparto_copia'];
		$info_dest=explode("|",$reparto_copia);
		$r_dest=$info_dest[0];
		$sr_dest=$info_dest[1];
		if (strlen($sr_dest)==0) $sr_dest=0;

		$reparto_origine=$_POST['reparto'];
		$info_origine=explode("|",$reparto_origine);
		$r_orig=$info_origine[0];
		$sr_orig=$info_origine[1];
		if (strlen($sr_orig)==0) $sr_orig=0;

		
		//cancellazione degli eventuali allestimenti presenti nel reparto di destinazione
		$sql="DELETE FROM `schema_reparti` WHERE id_reparto=$r_dest and id_sr=$sr_dest";
		$result=$this->conn->query($sql);
		
		//creazione allestimento sul reparto di destinazione clonando l'origine
		$sql="INSERT INTO `schema_reparti` (id_reparto, id_sr, codice_prodotto_fornitore)
					SELECT $r_dest, $sr_dest, codice_prodotto_fornitore
				FROM `schema_reparti` s2
				WHERE s2.id_reparto=$r_orig and s2.id_sr=$sr_orig";
		$result=$this->conn->query($sql);
		
	}
	
	public function delete_prodotto($id_delete) {
		$sql="DELETE FROM `schema_reparti` WHERE id=$id_delete";
		$result=$this->conn->query($sql);
		return result;
	}
}
?>