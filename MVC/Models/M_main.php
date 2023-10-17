<?php
class Main_Main
	{
	private $conn;

	
	// costruttore
	public function __construct($db){
		$this->conn = $db;
		$this->conn->set_charset("utf8");
	}

	public function login() {
		$datx=date("Y-m-d");
		$ora = date('H:i:s', time());
		$user=$_POST['user'];
		$pass=$_POST['pass'];
		$sql ="SELECT userid,passkey,operatore,u.id,vest_access,vest_id_rep,vest_id_sr,r.reparto,sr.reparto sotto_reparto FROM `Sql58368_4`.`utenti` u
		INNER JOIN `vestiario`.`reparti` r ON u.vest_id_rep=r.id
		INNER JOIN `vestiario`.`sotto_reparti` sr ON u.vest_id_sr=sr.id
		WHERE userid='$user' and passkey='$pass' and vest_access is not null;";
		$rows=array();
		$rows['header']['error']="";

		if ($result = $this->conn->query($sql)) {
			$row_cnt = $result->num_rows;
			if ($row_cnt==0 || $row_cnt>1) {
				$rows['header']['login']="KO";	
				$rows['header']['error']="Nome utente o password errata";
				print json_encode($rows);
				exit;
			}
			$res = $result->fetch_row();
			$operatore=$res[2];
			$id_user=$res[3];
			$vest_access=$res[4];
			$vest_id_rep=$res[5];
			$vest_id_sr=$res[6];
			$descr_reparto=$res[7];
			$descr_sr=$res[8];

			if ($result) {
				$_SESSION['user_vest'] = $user;
				$_SESSION['pass_vest'] = $pass;
				$_SESSION['operatore_vest'] = $operatore;
				$_SESSION['id_user_vest'] = $id_user;
				$_SESSION['vest_access'] = $vest_access;
				$_SESSION['vest_id_rep'] = $vest_id_rep;
				$_SESSION['vest_id_sr'] = $vest_id_sr;
				$_SESSION['descr_reparto'] = $descr_reparto;
				$_SESSION['descr_sr'] = $descr_sr;
								
				$rows['header']['login']="OK";
			} else {
				$rows['header']['login']="KO";	
				$rows['header']['error']="Utente o password errata";				
			}
			/*
			$t_oper="Accesso archivio Impegno Lotti";
			$sql="INSERT INTO log_fo(ip,data,sezione,operazione,utente,ora) VALUES('$ip','$datx','IMPEGNOLOTTI','$t_oper','$operatore','$ora')";
			$result = $mysqli->query($sql);
			*/

			
		} else  {
			$rows['header']['login']="KO";	
			$rows['header']['error']="Nome utente o password errata o accesso non consentito per l'utenza";	
		}
		return $rows;		
	}

	public function check_sottoscorta() {
			$sql="SELECT count(p.id) q 
				FROM `vestiario`.`prodotti` p 
				WHERE giacenza<=sottoscorta and p.dele=0";
			$result = $this->conn->query($sql);
			$row = $result->fetch_assoc();
			
			return $row['q'];
	}

	public function check_scadenze() {
			//prossimi alla scadenza
			$date = date("Y-m-d");
			$data=date('Y-m-d', strtotime($date. ' + 10 days'));			
			$sql="SELECT count(ri.id) q 
				FROM `vestiario`.`richieste_items` ri 
				WHERE ri.data_scadenza<='$data' and ri.data_scadenza>'$date'";
			$result = $this->conn->query($sql);
			$row = $result->fetch_assoc();
			$prossimi=$row['q'];
			
			//prossimi alla scadenza
			$date = date("Y-m-d");
			$sql="SELECT count(ri.id) q 
				FROM `vestiario`.`richieste_items` ri 
				WHERE ri.data_scadenza<='$date'";
			$result = $this->conn->query($sql);
			$row = $result->fetch_assoc();
			$scaduti=$row['q'];
			
			$resp=array();
			$resp['prossimi']=$prossimi;
			$resp['scaduti']=$scaduti;
			
			return $resp;
	}


	public function count_ric() {
		$is_admin=$_SESSION['vest_access'];
		$vest_id_rep=$_SESSION['vest_id_rep'];
		$vest_id_sr=$_SESSION['vest_id_sr'];
		$resp=array();

		if ($is_admin!="1") {
			$sql="SELECT count(r.id) q 
			FROM `vestiario`.`richieste` r 
			INNER JOIN `Sql58368_4`.utenti u ON r.id_richiedente=u.id 
			WHERE r.stato='0' and r.id_reparto='$vest_id_rep' and r.id_sr='$vest_id_sr'";
			$result = $this->conn->query($sql);
			$row = $result->fetch_assoc();
			$resp['new']=$row['q'];

			$sql="SELECT count(r.id) q 
			FROM `vestiario`.`richieste` r 
			INNER JOIN `vestiario`.dipendenti d ON r.id_dipendente=d.id 
			WHERE r.stato='1' and r.id_reparto='$vest_id_rep' and r.id_sr='$vest_id_sr'";
			$result = $this->conn->query($sql);
			$row = $result->fetch_assoc();
			$resp['view']=$row['q'];
			
			$sql="SELECT count(r.id) q 
			FROM `vestiario`.`richieste` r 
			INNER JOIN `vestiario`.dipendenti d ON r.id_dipendente=d.id 
			WHERE r.stato='3' and r.id_reparto='$vest_id_rep' and r.id_sr='$vest_id_sr'";
			$result = $this->conn->query($sql);
			$row = $result->fetch_assoc();
			$resp['close']=$row['q'];

		} else {
			$sql="SELECT count(id) q FROM `richieste` where stato='0'";
			$result = $this->conn->query($sql);
			$row = $result->fetch_assoc();
			$resp['new']=$row['q'];

			$sql="SELECT count(id) q FROM `richieste` where stato='1'";
			$result = $this->conn->query($sql);
			$row = $result->fetch_assoc();
			$resp['view']=$row['q'];

			$sql="SELECT count(id) q FROM `richieste` where stato='3'";
			$result = $this->conn->query($sql);
			$row = $result->fetch_assoc();
			$resp['close']=$row['q'];
		}	
		
		return $resp;
	
	}


	public function update_stock() {
		return;



		$sql="SELECT ri.codice_articolo, ri.taglia, ri.id_fornitore,sum(ri.qta_impegno) qta_impegno
				FROM `richieste_items` ri
				INNER JOIN `richieste` r ON ri.id_richiesta=r.id
				WHERE r.stato<>3
				GROUP BY codice_articolo,taglia,id_fornitore";
		$result = $this->conn->query($sql);
		while($results = $result->fetch_assoc()){
			
			$codice_articolo=$results['codice_articolo'];		
			$taglia=$results['taglia'];
			$id_fornitore=$results['id_fornitore'];
			
			$qta_impegno=$results['qta_impegno'];
			
			
			$sql="UPDATE `prodotti`
					SET giacenza_impegno=giacenza-$qta_impegno
					WHERE id_fornitore=$id_fornitore and codice_fornitore='$codice_articolo' and  taglia='$taglia'";
				
				
			$res1=$this->conn->query($sql);		
		}
		
	}



	public function check_full($id_richiesta,$prodotto_load,$taglia_load,$qta_richiesta) {

		$sql="SELECT SUM(qta_consegnata) q FROM `richieste_items` WHERE codice_articolo='$prodotto_load' and taglia='$taglia_load' and id_richiesta=$id_richiesta";


		$result = $this->conn->query($sql);
		$row = $result->fetch_assoc();
		$q=$row['q'];
		if ($q==$qta_richiesta) return "1";
		else return 0;
	}




	function entiTab($idarc) {
		$sql="SELECT count(id) q FROM `online`.fo_argo where id_arch='$idarc'";
		$result = $this->conn->query($sql);
		$row = $result->fetch_assoc();
		return $row['q'];
	}






}
?>