<?php
        $break = explode('/', basename(__FILE__));
        $current = array_pop($break);
        $break = explode('/', $_SERVER['SCRIPT_NAME']);
        $parent = array_pop($break);
        
        if($parent == $current){
	      Header("HTTP/1.0 404 Not Found");
	      @include_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/404.php');
	      die();
        }
        
        require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
        
        class Prova{
	      private $conn;
	        
	      public function __construct(){
		    $this->conn = new DbConnect();
	      }
	      
	      public function verificar_prova_valida($prova = 0, $modalidade = 0){
		    if($prova === 0 || !is_numeric($prova) || !is_numeric($modalidade)){
			  return FALSE;
		    }
		    $prova = (integer) $prova;
		    $cond = '';
		    if($modalidade = ($modalidade > 0) ? mysql_real_escape_string($modalidade) : FALSE){
			  $cond = "where a.id = $prova and a.id_modalidade = $modalidade";
		    } else {
			  $cond = "where a.id = $prova";
		    }
		    
		    $rs = mysql_query("select a.id from programacao_prova as a $cond limit 1;");
		    if($rs && mysql_num_rows($rs)){
			  return TRUE;
		    }
		    return FALSE;
	      }
	      
	      public function __destruct(){
		    $this->conn->close();
	      }
        }
?>