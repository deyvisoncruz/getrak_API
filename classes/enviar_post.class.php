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
		const USER = "root";
		const PASS = "";

		private static $instance = null;

		public function setOAUTH_URL()
		{
			$this->OAUTH_URL='https://api.getrak.com/newkoauth/oauth/token?grant_type=password&username='.$this->username.'&password='.$this->password;
		}
		public function setURL( $a)
		{
			$this->URL=$a.'?access_token='.$this->getTokenOnline();
		}
		
		public function updateTokenBD()
		{
			$pdo = $this->getDB();
			$this->setOAUTH_URL();

			$jToken = $this->requestPostOAuth();
			$json = json_decode($jToken );

			//echo $json->{'access_token'};
			$sql = "update token set token='".  $json->{'access_token'}."',  token_type='".  $json->{'token_type'}."',  expires_in=".  $json->{'expires_in'}.",  scope='".  $json->{'scope'}."',  jti='".  $json->{'jti'}."' where id=0";

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
				"Content-Type: application/x-www-form-urlencoded",
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
				"Content-Type: application/x-www-form-urlencoded",
				 "Accept: application/json"
			  ),
			));


				$response = curl_exec($curl);
				$err = curl_error($curl);
				
				curl_close($curl);
				echo $response;
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
				"Content-Type: application/x-www-form-urlencoded",
				 "Accept: application/json"
			  ),
			));


				$response = curl_exec($curl);
				$err = curl_error($curl);
				
				curl_close($curl);
				return $response;
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
