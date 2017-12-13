<?php

	require_once "classes/Conexao.class.php";
	class veiculos {
		
		public $id_veiculo;
		public $placa;
		public $icone;
		public $timezone;
		public $modulo;
		public $data;
		
		public $lat;
		public $lon;
		
		public $velocidade;
		public $status_online;
		public $tipo;
		
		public function printVeiculo()
		{
			echo "id_veiculo: ". $this->id_veiculo." </br>";
			echo "placa: ". $this->placa." </br>";
			echo "icone: ". $this->icone." </br>";
			echo "timezone: ". $this->timezone." </br>";
			echo "modulo: ". $this->modulo." </br>";
			echo "data: ". $this->data." </br>";
			echo "lat: ". $this->lat." </br>";
			echo "lon: ". $this->lon." </br>";
			echo "velocidade: ". $this->velocidade." </br>";
			echo "status_online: ". $this->status_online." </br>";
			echo "tipo: ". $this->tipo." </br>";
			
			
		}
	}
?>
	