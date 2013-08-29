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
        
        class LocalCompeticao{
	      private $conn;
	        
	      public function __construct(){
		    $this->conn = new DbConnect();
	      }
	      
	      public function verificar_local_valido($local = 0, $modalidade = 0){
		    if($local === 0 || !is_numeric($local) || !is_numeric($modalidade)){
			  return FALSE;
		    }
		    $local = (integer) $local;
		    $cond = '';
		    if($modalidade = ($modalidade > 0) ? mysql_real_escape_string($modalidade) : FALSE){
			  $cond = "inner join local_competicao_modalidade as b on a.id = b.id_local and a.id = $local and b.id_modalidade = $modalidade";
		    } else {
			  $cond = "where a.id = $local";
		    }
		    
		    $rs = mysql_query("select a.id from local_competicao as a $cond limit 1;");
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