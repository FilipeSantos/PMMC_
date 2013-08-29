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
        
        class Classificacao{
	      private $conn;
	      private $cidade;
	      private $pontos;
	      private $totalPontos;
	      private $validaClassificacao;
	        
	      public function __construct(){
		    $this->conn = new DbConnect();
		    $this->validaClassificacao = FALSE;
		    $this->pontos = array();
		    $this->totalPontos = 0;
	      }
	      
	      public function set_save_classificacao($cidade = FALSE, $pontos = FALSE){
		    if($cidade === FALSE || $pontos === FALSE){
			  $this->validaClassificacao = FALSE;
			  return FALSE;
		    }
		    $this->cidade = mysql_real_escape_string((integer) $cidade);
		    $conds = '';
		    if(is_array($pontos) && !empty($pontos)){
			  $cont = 0;
			  foreach($pontos as $i=>$item){
				$pontos[$i] = str_replace(',', '.', $pontos[$i]);
				if(is_numeric($pontos[$i])){
				        $pontos[$i] = mysql_real_escape_string($pontos[$i]);
				        $cont += $pontos[$i];
				} else {
				        unset($pontos[$i]);
				}
			  }
			  $this->pontos = $pontos;
			  $this->totalPontos = $cont;
		    }		    
		    $this->validaClassificacao = TRUE;
		    return TRUE;
	      }
	      
	      public function salvar(){
		    if($this->validaClassificacao === TRUE){
			  mysql_query("start transaction;");
			  if(!empty($this->pontos)){
				foreach($this->pontos as $i=>$item){
				        mysql_query("update cidade_modalidade_classificacao set pontuacao = $item, data_atualizacao = CURRENT_TIMESTAMP where id_cidade = $this->cidade and id_modalidade = $i limit 1;");
				}
			  }
			  if(mysql_error()){
				mysql_query("rollback;");
				return FALSE;
			  } else {
				mysql_query("commit;");
				if(Cidade::atualiza_pontos($this->cidade, $this->totalPontos) === TRUE){
				        return Cidade::atualiza_posicao('pontos', Cidade::get_divisao($this->cidade));
				}
				return FALSE;
			  }
		    }
	      }
	      
	      public function get_classificacao($idCidade = FALSE, $idModalidade = FALSE){
		    $conds = '';
		    if($idCidade !== FALSE && is_numeric($idCidade)){
			  $idCidade = (integer) $idCidade;
			  $conds = "id_cidade = $idCidade and ";
		    }
		    if($idModalidade !== FALSE && is_numeric($idModalidade)){
			  $idModalidade = (integer) $idModalidade;
			  $conds = $conds . "id_modalidade = $idModalidade and ";
		    }
		    if(empty($conds)){
			  return FALSE;
		    }
		    $conds = substr($conds, 0, -5);
		    $rs = mysql_query("select pontuacao from cidade_modalidade_classificacao where $conds;");
		    if($rs && mysql_num_rows($rs)){
			  while($cursor = mysql_fetch_object($rs)){
				$arr = $cursor;
			  }
			  $this->conn->free($rs);
			  return $arr;
		    }
		    return FALSE;
	      }
	      
	      public static function data_atualizacao($idCidade = FALSE, $idDivisao = FALSE, $idModalidade = FALSE){
		    if($idCidade !== FALSE && is_numeric($idCidade)){
			  $idCidade = (integer) $idCidade;
			  $query = "select date_format(data_atualizacao_pontos, '%d\/%m - %H:%i') as data from cidade where id = $idCidade;";
		    } elseif($idDivisao !== FALSE && is_numeric($idDivisao) && $idDivisao > 0 && $idDivisao < 3){
			  $idDivisao = (integer) $idDivisao;
			  $query = "select date_format(max(data_atualizacao_pontos), '%d\/%m - %H:%i') as data from cidade where divisao = $idDivisao;";
		    } elseif($idModalidade !== FALSE && is_numeric($idModalidade)){
			  return FALSE;
			  /*$idModalidade = (integer) $idModalidade;
			  $query = "select date_format(max(data_atualizacao), '%d\/%m - %H:%i') as data from cidade_modalidade_classificacao where id_modalidade = $idModalidade;";*/
		    } else {
			  $query = "select date_format(max(data_atualizacao_pontos), '%d\/%m - %H:%i') as data from cidade where 1;";
		    }
		    
		    $conn = new DbConnect();
		    $rs = mysql_query($query);
		    if($rs && mysql_num_rows($rs)){
			  return mysql_result($rs, 0, 'data');
		    }
		    $conn->close();
		    return FALSE;
	      }
	      
	      public function __destruct(){
		    $this->conn->close();
	      }
        }
?>