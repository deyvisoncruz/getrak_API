<?php

	require_once "classes/Conexao.class.php";
	class curl {
		
		public $username;
		public $password;
		public $URL;
		public $OAUTH_URL;
		public $token;
		public $con;
		public $pdo ;
		
		public $listVeiculos; 
		
		
		
		const USER = "root";
		const PASS = "";

		private static $instance = null;

		public function setOAUTH_URL()
		{
			$this->OAUTH_URL='https://api.getrak.com/newkoauth/oauth/token?grant_type=password&username='.$this->username.'&password='.$this->password;
		}
		public function setURL( $a)
		{
			$this->URL=$a.'?access_token='.$this->tokenUse();
		}
		
		public function tokenUse()
		{
			$v = $this->verifyToken() ;
			if($v)
			{
				return $this->getTokenBD();
			}
			else
			{
				
				$this->updateTokenBD();
				return $this->getTokenBD();
				
			}
			
		}
		
		public function updateTokenBD()
		{
			$pdo = $this->getDB();
			$this->setOAUTH_URL();

			$jToken = $this->requestPostOAuth();
			$json = json_decode($jToken );
			
			$timeseg = $json->{'expires_in'} ;
			
			$exp = " ,expires_date_at = DATE_ADD(NOW(),INTERVAL ".$timeseg." SECOND) ";
			$sql = "update token set token='".  $json->{'access_token'}."',  token_type='".  $json->{'token_type'}."',  expires_in=".  $json->{'expires_in'}.",  scope='".  $json->{'scope'}."',  jti='".  $json->{'jti'}."' ".$exp." where id=0";

			$con = $pdo->prepare($sql );

			$con->execute();
		}
		public function getTokenBD()
		{
			$pdo = $this->getDB();

			$con = $pdo->prepare("SELECT * FROM token where id=0");
			$con->execute();
			if ($con->rowCount() == 1)
			{
				$dados = $con->fetch(PDO::FETCH_OBJ);
				$this->token =$dados->token;
				return ($this->token) ;
			}
			else
			{
				return "sem token";
			}
		}
		
		public function getTokenOnline()
		{
			$this->setOAUTH_URL();

			$jToken = $this->requestPostOAuth();
			$json = json_decode($jToken );
			return  $json->{'access_token'};
		}
		
		
		public function verifyToken()
		{
			$pdo = $this->getDB();

			$con = $pdo->prepare("SELECT *,now() as atual FROM token where id=0");
			$con->execute();
			if ($con->rowCount() == 1)
			{
				$dados = $con->fetch(PDO::FETCH_OBJ);
				/*echo  $dados->expires_date_at ;
				echo "<br>";
				
				echo $dados->atual;
				echo "<br>";*/
			$data1 = new DateTime($dados->expires_date_at );
			$data2 = new DateTime($dados->atual);

				
				if ($data1>$data2)
					return true;
				else	
					return false;
					
			}
			else
			{
				return false;
			}
		}
		
		
		public function requestPostOAuth()
		{ 
			
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->OAUTH_URL,
			  CURLOPT_SSL_VERIFYPEER, false,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => "validityInSec=120000",
			  CURLOPT_HTTPHEADER => array(
				"Authorization: Basic Z2V0cmFrOjI5MTViZjRhM2VkNQ==",      
				"Content-Type: application/ x-www-form-urlencoded;charset=UTF-8",
				 "Accept: application/json"
			  ),
			));


				$response = curl_exec($curl);
				$err = curl_error($curl);
				
				curl_close($curl);
				
				return $response;
				
		}
		
		
		
		public function requestGet()
		{ 
			
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->URL,
			  CURLOPT_SSL_VERIFYPEER, false,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_POSTFIELDS => "validityInSec=120000",
			  CURLOPT_HTTPHEADER => array(
				"Authorization: Basic Z2V0cmFrOjI5MTViZjRhM2VkNQ==",      
				"Content-Type: application/ x-www-form-urlencoded;charset=UTF-8",
				 "Accept: application/json"
			  ),
			));


				$response = curl_exec($curl);
				$err = curl_error($curl);
				//echo $response;
				curl_close($curl);
				return $response;
		}
		
		public function requestPost()
		{ 
			
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $this->URL,
			  CURLOPT_SSL_VERIFYPEER, false,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => "validityInSec=120000",
			  CURLOPT_HTTPHEADER => array(
				"Authorization: Basic Z2V0cmFrOjI5MTViZjRhM2VkNQ==",      
				"Content-Type: application/ x-www-form-urlencoded;charset=UTF-8",
				 "Accept: application/json"
			  ),
			));


				$response = curl_exec($curl);
				$err = curl_error($curl);
				
				curl_close($curl);
				return $response;
		}
		
	public function printVeiculos()
	{
		foreach ( $this->listVeiculos as $item)
		{
			$item->printVeiculo();
		}
	}	
		
	public function insertVeiculos()
	{
			$pdo = $this->getDB();

			
		foreach ( $this->listVeiculos as $item)
		{
			$con = $pdo->prepare("insert into veiculos 
														(
														id_veiculo,
														placa,
														icone,
														timezone,
														modulo,
														data,
														lat,
														lon,
														velocidade,
														status_online,
														tipo
														) values 
														(
															?,
															?,
															?,
															?,
															?,
															?,
															?,
															?,
															?,
															?,
															?
														);
								");
			$con->bindValue(1, $item->id_veiculo);
			$con->bindValue(2, $item->placa);
			$con->bindValue(3, $item->icone);
			$con->bindValue(4, $item->timezone);
			$con->bindValue(5, $item->modulo);
			$con->bindValue(6, $item->data);
			$con->bindValue(7, $item->lat);		
			$con->bindValue(8, $item->lon);		
			$con->bindValue(9, $item->velocidade);		
			$con->bindValue(10, $item->status_online);		
			$con->bindValue(11, $item->tipo);				
			$con->execute();
			
			
		}
	}
	
	
	
	
	public function updateVeiculos()
	{
			$pdo = $this->getDB();

			
		foreach ( $this->listVeiculos as $item)
		{
			$con = $pdo->prepare("update veiculos set
													placa =?,
													icone =?,
													timezone =?,
													modulo =?,
													data =?,
													lat =?,
													lon =?,
													velocidade =?,
													status_online =?,
													tipo =?
								 where id_veiculo = ?"
								
								);
			$con->bindValue(1, $item->placa);
			$con->bindValue(2, $item->icone);
			$con->bindValue(3, $item->timezone);
			$con->bindValue(4, $item->modulo);
			$con->bindValue(5, $item->data);
			$con->bindValue(6, $item->lat);		
			$con->bindValue(7, $item->lon);		
			$con->bindValue(8, $item->velocidade);		
			$con->bindValue(9, $item->status_online);		
			$con->bindValue(10, $item->tipo);
			$con->bindValue(11, $item->id_veiculo);
			
			$con->execute();
			
			
		}
	}		
	
	
	
	public function insertVeiculo($item)
	{
		$pdo = $this->getDB();

		$con = $pdo->prepare("insert into veiculos 
														(
														id_veiculo,
														placa,
														icone,
														timezone,
														modulo,
														data,
														lat,
														lon,
														velocidade,
														status_online,
														tipo
														) values 
														(
															?,
															?,
															?,
															?,
															?,
															?,
															?,
															?,
															?,
															?,
															?
														);
							");
		$con->bindValue(1, $item->id_veiculo);
		$con->bindValue(2, $item->placa);
		$con->bindValue(3, $item->icone);
		$con->bindValue(4, $item->timezone);
		$con->bindValue(5, $item->modulo);
		$con->bindValue(6, $item->data);
		$con->bindValue(7, $item->lat);		
		$con->bindValue(8, $item->lon);		
		$con->bindValue(9, $item->velocidade);		
		$con->bindValue(10, $item->status_online);		
		$con->bindValue(11, $item->tipo);				
		$con->execute();
		if ($con->rowCount() >0)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	
	}
	
	public function updateVeiculo($item)
	{
		$pdo = $this->getDB();

		$con = $pdo->prepare("update veiculos set
													placa =?,
													icone =?,
													timezone =?,
													modulo =?,
													data =?,
													lat =?,
													lon =?,
													velocidade =?,
													status_online =?,
													tipo =?
								 where id_veiculo = ?"
								
								);
		$con->bindValue(1, $item->placa);
		$con->bindValue(2, $item->icone);
		$con->bindValue(3, $item->timezone);
		$con->bindValue(4, $item->modulo);
		$con->bindValue(5, $item->data);
		$con->bindValue(6, $item->lat);		
		$con->bindValue(7, $item->lon);		
		$con->bindValue(8, $item->velocidade);		
		$con->bindValue(9, $item->status_online);		
		$con->bindValue(10, $item->tipo);
		$con->bindValue(11, $item->id_veiculo);
		
		$con->execute();
		if ($con->rowCount() >0)
		{
			return true;
		}
		else
		{
			return false;
		}	
			
		
	}		
	
	
	
	public function verifyVeiculo($item)
	{
			$pdo = $this->getDB();

			
			$con = $pdo->prepare(
								"select * 
								 from veiculos
								 where id_veiculo = ?"					
								);
		
			$con->bindValue(1, $item->id_veiculo);
			
			$con->execute();
			if ($con->rowCount() >0)
			{
				return true;
			}
			else
			{
				return false;
			}
			
		
	}		
	private static function conectar() {

		try {
			if (self::$instance == null):
				$dsn = "mysql:host=localhost;dbname=sistema_getrack";
				self::$instance = new PDO($dsn, self::USER, self::PASS);
				self::$instance->exec("set names utf8");
				self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			endif;
		} catch (PDOException $e) {
			echo "Erro: " . $e->getMessage();
		}
		return self::$instance;
	}

	protected static function getDB() {
		return self::conectar();
	}
		
	}
	
?>
