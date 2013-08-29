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
        
        class Medalha{
	      private $conn;
	      private $cidade;
	      private $ouro;
	      public $totalOuro;
	      private $prata;
	      public $totalPrata;
	      private $bronze;
	      public $totalBronze;
	      private $validaMedalhas;
	        
	      public function __construct(){
		    $this->conn = new DbConnect();
		    $this->validaMedalhas = FALSE;
		    $this->ouro = array();
		    $this->prata = array();
		    $this->bronze = array();
		    $this->totalOuro = 0;
		    $this->totalPrata = 0;
		    $this->totalBronze = 0;
	      }
	      
	      public function set_save_medalhas($cidade = FALSE, $ouro = FALSE, $prata = FALSE, $bronze = FALSE){
		    if($cidade === FALSE || ($ouro === FALSE && $prata === FALSE && $bronze === FALSE)){
			  $this->validaMedalhas = FALSE;
			  return FALSE;
		    }
		    $this->cidade = mysql_real_escape_string((integer) $cidade);
		    $conds = '';
		    if($ouro !== FALSE && is_array($ouro) && !empty($ouro)){
			  $cont = 0;
			  foreach($ouro as $i=>$item){
				if(is_numeric($ouro[$i])){
				        $ouro[$i] = mysql_real_escape_string((integer) $ouro[$i]);
				        $cont += $ouro[$i];
				} else {
				        unset($ouro[$i]);
				}
				$this->ouro = $ouro;
				$this->totalOuro = $cont;
			  }
		    }
		    if($prata !== FALSE && is_array($prata) && !empty($prata)){
			  $cont = 0;
			  foreach($prata as $i=>$item){
				if(is_numeric($prata[$i])){
				        $prata[$i] = mysql_real_escape_string((integer) $prata[$i]);
				        $cont += $prata[$i];
				} else {
				        unset($prata[$i]);
				}
				$this->prata = $prata;
				$this->totalPrata = $cont;
			  }
		    }
		    if($bronze !== FALSE && is_array($bronze) && !empty($bronze)){
			  $cont = 0;
			  foreach($bronze as $i=>$item){
				if(is_numeric($bronze[$i])){
				        $bronze[$i] = mysql_real_escape_string((integer) $bronze[$i]);
				        $cont += $bronze[$i];
				} else {
				        unset($bronze[$i]);
				}
				$this->bronze = $bronze;
				$this->totalBronze = $cont;
			  }
		    }
		    $this->validaMedalhas = TRUE;
		    return TRUE;
	      }
	      
	      public function salvar(){
		    if($this->validaMedalhas === TRUE){
			  mysql_query("start transaction;");
			  if(!empty($this->ouro)){
				foreach($this->ouro as $i=>$item){
				        mysql_query("update cidade_modalidade_medalha set medalha_ouro = $item, data_atualizacao = CURRENT_TIMESTAMP where id_cidade = $this->cidade and id_modalidade = $i limit 1;");
				}
			  }
			  if(!empty($this->prata)){
				foreach($this->prata as $i=>$item){
				        mysql_query("update cidade_modalidade_medalha set medalha_prata = $item, data_atualizacao = CURRENT_TIMESTAMP where id_cidade = $this->cidade and id_modalidade = $i limit 1;");
				}
			  }
			  if(!empty($this->bronze)){
				foreach($this->bronze as $i=>$item){
				        mysql_query("update cidade_modalidade_medalha set medalha_bronze = $item, data_atualizacao = CURRENT_TIMESTAMP where id_cidade = $this->cidade and id_modalidade = $i limit 1;");
				}
			  }
			  if(mysql_error()){
				mysql_query("rollback;");
				return FALSE;
			  } else {
				mysql_query("commit;");
				if(Cidade::atualiza_medalhas($this->cidade, $this->totalOuro, $this->totalPrata, $this->totalBronze) === TRUE){
				        return Cidade::atualiza_posicao('medalhas', Cidade::get_divisao($this->cidade));
				}
				return FALSE;
			  }
		    }
	      }
	      
	      public function get_medalhas($idCidade = FALSE, $idModalidade = FALSE){
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
		    $rs = mysql_query("select medalha_ouro, medalha_prata, medalha_bronze, (medalha_ouro + medalha_prata + medalha_bronze) as medalha_total
				  from cidade_modalidade_medalha where $conds limit 1;");
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
			  $query = "select date_format(data_atualizacao_medalhas, '%d\/%m - %H:%i') as data from cidade where id = $idCidade;";
		    } elseif($idDivisao !== FALSE && is_numeric($idDivisao)){
			  $idDivisao = (integer) $idDivisao;
			  $query = "select date_format(max(data_atualizacao_medalhas), '%d\/%m - %H:%i') as data from cidade where divisao = $idDivisao;";
		    } elseif($idModalidade !== FALSE && is_numeric($idModalidade)){
			  return FALSE;
			  /*$idModalidade = (integer) $idModalidade;
			  $query = "select date_format(max(data_atualizacao), '%d\/%m - %H:%i') as data from cidade_modalidade_medalha where id_modalidade = $idModalidade;";*/
		    } else {
			  $query = "select date_format(max(data_atualizacao_medalhas), '%d\/%m - %H:%i') as data from cidade where 1;";
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