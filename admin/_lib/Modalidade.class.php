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
        
        class Modalidade{
	      private $conn;
	        
	      public function __construct(){
		    $this->conn = new DbConnect();
	      }
	      
	      public function get_modalidades($idCidade = FALSE, $pcd = TRUE, $orderId = FALSE){
			$itemPcd = '';
			if($pcd === FALSE){
				$itemPcd = 'and id not in (26, 27, 28)';
			}
		    
		    $order = $orderId ? 'id' : 'titulo';
		    
		    if($idCidade === FALSE || !is_numeric($idCidade)){
			  $query = "select id, titulo, slug, prefixo, modalidade_extra from modalidade where status = 1 $itemPcd order by $order";
		    } else {
			  $idCidade = (integer) $idCidade;
			  $query = "select distinct a.id, a.titulo, a.slug, a.prefixo from modalidade as a inner join
				modalidade_sub as b on a.id = b.modalidade and a.status = 1 inner join 
				cidade as c on c.id = $idCidade and c.modalidades like concat('%', b.id, '%') order by a.titulo;";
		    }
		    
		    $rs = mysql_query($query);
		    if($rs && mysql_num_rows($rs)){
			  $arr = array();
			  while($cursor = mysql_fetch_object($rs)){
				$arr[] = $cursor;
			  }
			  return $arr;
		    } else {
			  return FALSE;
		    }
	      }
		
		public function get_modalidade($id = 0, $slug = NULL){
			if(is_numeric($id) && (integer) $id !== 0){
				$cond = 'id = ' . (integer) $id;
			} elseif($slug !== NULL){
				$cond = 'slug = \'' . mysql_real_escape_string($slug) . '\'';
			} else {
				return FALSE;
			}
			
			$arr = array();

			$rs = mysql_query("select id, titulo, meta_description, meta_keywords, descricao, thumb, slug, prefixo from modalidade where $cond and status = 1 limit 1;");
			
			if($rs && mysql_num_rows($rs)){
				while($item = mysql_fetch_object($rs)){
					$arr[] = $item;
				}
			}
			
			return !empty($arr) ? $arr[0] : FALSE;
		}
	      
	      public function get_divisoes_modalidade($id = FALSE){
		    if($id === FALSE || !is_numeric($id) || !$this->verificar_modalidade_valida($id)){
			  return FALSE;
		    }
		    
		    $id = (integer) $id;
		    $divisao = array();
		    $rs = mysql_query("select distinct divisao from modalidade_sub where modalidade = $id order by divisao;");
		    if($rs && mysql_num_rows($rs)){
			  while($item = mysql_fetch_object($rs)){
				$divisao[] = $item->divisao;
			  }
		    }

		    return (!empty($divisao) ? $divisao : FALSE);
	      }
	      
	      public function get_locais_modalidade($id = FALSE){
		    if($id === FALSE || !is_numeric($id) || !$this->verificar_modalidade_valida($id)){
			  return FALSE;
		    }
		    
		    $id = (integer) $id;
		    $local = array();
		    $rs = mysql_query("select distinct a.id, a.nome from local_competicao as a
				inner join local_competicao_modalidade as b on a.id = id_local
				and b.id_modalidade = $id order by a.nome;");
		    if($rs && mysql_num_rows($rs)){
			  while($item = mysql_fetch_object($rs)){
				$local[] = $item;
			  }
		    }

		    return (!empty($local) ? $local : FALSE);
	      }
	      
	      public function get_sexo_modalidade($id = FALSE){
		    if($id === FALSE || !is_numeric($id) || !$this->verificar_modalidade_valida($id)){
			  return FALSE;
		    }
		    
		    $id = (integer) $id;
		    $sexo = array();
		    
		    $rs = mysql_query("select distinct sexo from modalidade_sub where modalidade = $id order by sexo;");
		    if($rs && mysql_num_rows($rs)){
			  while($item = mysql_fetch_object($rs)){
				$sexo[] = $item->sexo;
			  }
		    }
		    return (!empty($sexo) ? $sexo : FALSE);
	      }
	      
	      public function get_categoria_modalidade($id = FALSE){
		    if($id === FALSE || !is_numeric($id) || !$this->verificar_modalidade_valida($id)){
			  return FALSE;
		    }
		    
		    $id = (integer) $id;
		    $categoria = array();
		    
		    $rs = mysql_query("select distinct idade from modalidade_sub where modalidade = $id order by idade;");
		    if($rs && mysql_num_rows($rs)){
			  while($item = mysql_fetch_object($rs)){
				$categoria[] = $item->idade;
			  }
		    }
		    return (!empty($categoria) ? $categoria : FALSE);
	      }
	      
	      public function get_prova_modalidade($id){
		    if($id === FALSE || !is_numeric($id) || !$this->verificar_modalidade_valida($id)){
			  return FALSE;
		    }
		    
		    $id = (integer) $id;
		    $prova = array();
		    
		    $rs = mysql_query("select distinct id, titulo, divisao, sexo, categoria_idade from programacao_prova
				where id_modalidade = $id and `status` = 1 order by divisao, sexo, categoria_idade, titulo;");
		    if($rs && mysql_num_rows($rs)){
			  while($item = mysql_fetch_object($rs)){
				$prova[] = $item;
			  }
		    }
		    return (!empty($prova) ? $prova : FALSE);
	      }
	      
	      public function get_cidade_modalidade($id = FALSE){
		    if($id === FALSE || !is_numeric($id) || !$this->verificar_modalidade_valida($id)){
			  return FALSE;
		    }
		    
		    $id = (integer) $id;
		    $cidade = array();		    
		    
		    $rs = mysql_query("select distinct a.id, a.nome, a.divisao, b.id as modalidade_sub from cidade as a inner join modalidade_sub as b
				on a.modalidades like concat('%', b.id, '%') and b.modalidade = $id and a.`status` = 1 order by a.nome, b.divisao;");
		    if($rs && mysql_num_rows($rs)){
			  $id_prev = 0;
			  $index = 0;
			  while($item = mysql_fetch_object($rs)){
				if($id_prev != $item->id){
				        $cidade[$index]->id = $item->id;
				        $cidade[$index]->nome = $item->nome;
				        $cidade[$index]->divisao = $item->divisao;
				        $cidade[$index]->modalidade_sub = array();
				        array_push($cidade[$index]->modalidade_sub, $item->modalidade_sub);
				} else {
				        array_push($cidade[--$index]->modalidade_sub, $item->modalidade_sub);
				}
				$index++;
				$id_prev = $item->id;
			  }
		    }
		    return (!empty($cidade) ? $cidade : FALSE);
	      }
	      
	      public function verifica_modalidade_prova_confronto($id = 0){
		    if($id === 0 || !is_numeric($id)){
			  return FALSE;
		    }
		    $id = (integer) $id;
		    $rs = mysql_query("select prova_confronto from modalidade where id = $id limit 1;");
		    if($rs && mysql_num_rows($rs)){
			  if((integer) mysql_result($rs, 0, 'prova_confronto') === 1){
				return TRUE;
			  } else {
				return FALSE;
			  }
		    }
		    return FALSE;
	      }
	      
	      public function verificar_modalidade_valida($id = 0, $slug = NULL){
		    if(($id === 0 || !is_numeric($id)) && (is_null($slug) || empty($slug))){
			  return FALSE;
		    }
		    
		    $cond = '';
		    
		    if($id = ($id !== 0 && is_numeric($id)) ? (integer) $id : FALSE){
			  $cond = 'id = ' . $id;
		    } elseif($slug = (!is_null($slug) && !empty($slug)) ? mysql_real_escape_string($slug) : FALSE){
			  $cond = 'slug = \'' . $slug . '\'';
		    }
		    
		    if(!empty($cond)){
			  $rs = mysql_query("select id from modalidade where $cond limit 1;");
			  if($rs && mysql_num_rows($rs)){
				return TRUE;
			  }
		    }		    
		    return FALSE;
	      }
	      
	      public function __destruct(){
		    $this->conn->close();
	      }
        }
?>