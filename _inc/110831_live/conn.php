<?php
	class dbconnect{
		public $conn;
		public $db;		
		public function __construct(){
			if(strripos($_SERVER['HTTP_HOST'], 'jogosabertos.dev') !== FALSE){
				$server = 'localhost';
				$user = 'root';
				$pass = '';
				$db = 'dev_jogosabertos';
			} else {
				$server = '187.45.196.242';
				$user = 'tboom14';
				$pass = 'Tbjogos11';
				$db = 'tboom14';
			}
			$this->conn = @mysql_connect($server, $user, $pass) || die('Sem Conexao');
			$this->db = mysql_select_db($db) || die('Banco nao encontrado');
		}	
	}
?>