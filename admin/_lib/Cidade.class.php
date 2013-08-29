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
	
	class Cidade{

		public function __construct(){}
		
		public static function get_list_cidades($hasAloj = FALSE, $divisao = FALSE){
			$conn = new DbConnect();
			
			$cond = 'where';
			if($hasAloj !== FALSE){
				$cond = 'inner join cidade_alojamento as b on a.id = b.idCidade and';
			}
			if($divisao !== FALSE){
				$cond = $cond . " a.divisao = $divisao and";
			  }

			$rs = mysql_query("select distinct a.id, a.nome, a.slug from cidade as a $cond a.status = 1 order by a.nome;");
			if($rs && mysql_num_rows($rs)){
				$arr = array();
				while($item = mysql_fetch_object($rs)){
					$arr[] = $item;
				}
				return $arr;
			}
			return FALSE;
		}
		
		public static function get_cidade($id = FALSE, $slug = FALSE){
			if($id){
				$busca = "a.id = $id";
			} else if($slug){
				$busca = "a.slug = '$slug'";
			} else {
				die();
			}
			
			$cidade = '';
			$conn = new DbConnect();

			$rs = mysql_query("select a.id, a.nome, a.bandeira, a.slug, a.divisao, a.colocacao_2010, a.divisao_2010, a.modalidades from cidade as a where $busca and a.status = 1 limit 1;");
			if($rs && mysql_num_rows($rs)){
				while($item = mysql_fetch_object($rs)){
					$cidade = $item;
				}
				$cidade->modalidades_esp = $cidade->modalidades_pcd = NULL;
				if($cidade->modalidades != NULL){
					$mod = "'" . str_replace(';', '\',\'', $cidade->modalidades) . "'";
					
					$rs = mysql_query("select distinct a.id, a.titulo, a.slug, b.divisao, b.pcd from modalidade as a
						        inner join modalidade_sub as b on a.id = b.modalidade and b.id in ($mod)
						        order by a.titulo, b.pcd, b.divisao, a.id;");

					if($rs && mysql_num_rows($rs)){
						$cidade->modalidades = array();
						while($item = mysql_fetch_object($rs)){
							$cidade->modalidades[] = $item;
						}
						if($cidade->modalidades != NULL){
							$modPri = $modEsp = $modExt = array();
							
							foreach($cidade->modalidades as $mod){
								/*if($mod->divisao < 3 && !$mod->pcd){
									$modPri[] = $mod;
								} elseif($mod->divisao == 3 && !$mod->pcd){
									$modEsp[] = $mod;
								} elseif($mod->pcd){
									$modPcd[] = $mod;
								}*/
								if($mod->divisao < 3){
									$modPri[] = $mod;
								} elseif($mod->divisao == 3 && !$mod->pcd){
									$modEsp[] = $mod;
								} elseif($mod->divisao == 4){
									$modExt[] = $mod;
								}
							}
							
							$cidade->modalidades = $modPri;
							$cidade->modalidades_esp = $modEsp;
							$cidade->modalidades_ext = $modExt;
						}
					}
				} else {
					$cidade->modalidades = FALSE;
				}

				return $cidade;
			}
			
			$conn->close();
			return FALSE;
		}
		
		public static function get_cidades_medalhas_cidade($idCidade = FALSE){
		    if($idCidade === FALSE){
			  return FALSE;
		    }
		    $conn = new DbConnect();
		    $query = "select a.id, a.nome, a.divisao, a.slug, a.sigla, a.medalha_total_ouro, a.medalha_total_prata, a.medalha_total_bronze,
			  (a.medalha_total_ouro + a.medalha_total_prata + a.medalha_total_bronze) as medalha_total, a.pos_medalhas
			  from cidade as a inner join cidade_modalidade_medalha as b on
			  a.id = $idCidade and a.status = 1 and a.id = b.id_cidade limit 1;";
		    $rs = mysql_query($query);
		    if($rs && mysql_num_rows($rs)){
			  $arr = array();
			  while($cursor = mysql_fetch_object($rs)){
				$itemArr = & $arr[];
				$modArr = array();
				
				$rsModalidades = mysql_query("select a.titulo, b.medalha_ouro, b.medalha_prata, b.medalha_bronze,
						(b.medalha_ouro + b.medalha_prata + b.medalha_bronze) as total 
						from modalidade as a inner join cidade_modalidade_medalha as b on
						a.id = b.id_modalidade and b.id_cidade = {$idCidade} order by a.titulo;");
				
				if($rsModalidades && mysql_num_rows($rsModalidades)){
					while($cursorMod = mysql_fetch_object($rsModalidades)){
						if($cursorMod->medalha_ouro !== '0' || $cursorMod->medalha_prata !== '0' || $cursorMod->medalha_bronze !== '0'){
							$modArr[] = $cursorMod;
						}						
					}
				}
				
				$itemArr = $cursor;
				$itemArr->modalidades = $modArr;
			  }
			  $conn->free($rs);
			  $conn->close();
			  return $arr[0];
		    }
		    return FALSE;
		    $conn->close();
		}
		
		public static function get_cidades_medalhas_divisao($idDivisao = FALSE, $limit = ''){
		    if($idDivisao === FALSE){
			  return FALSE;
		    }
		    if(is_numeric($limit)){
			  $limit = 'limit ' . (integer) $limit;
		    }
		    $conn = new DbConnect();
		    $query = "select distinct a.id, a.nome, a.slug, a.sigla, a.bandeira, a.medalha_total_ouro, a.medalha_total_prata, a.medalha_total_bronze,
			  (a.medalha_total_ouro + a.medalha_total_prata + a.medalha_total_bronze) as medalha_total, a.pos_medalhas
			  from cidade as a inner join cidade_modalidade_medalha as b on
			  a.divisao = $idDivisao and a.status = 1 and a.id = b.id_cidade order by a.pos_medalhas, a.nome $limit;";
		    $rs = mysql_query($query);
		    if($rs && mysql_num_rows($rs)){
			  $arr = array();
			  while($cursor = mysql_fetch_object($rs)){
				$arr[] = $cursor;
			  }
			  $conn->free($rs);
			  $conn->close();
			  return $arr;
		    }
		    return FALSE;
		    $conn->close();
		}
		
		public static function get_cidades_classificacao_cidade($idCidade = FALSE){
		    if($idCidade === FALSE){
			  return FALSE;
		    }
		    $conn = new DbConnect();
		    $query = "select id, nome, divisao, slug, sigla, pontos, pos_pontos
			  from cidade as a where id = $idCidade and status = 1 limit 1;";
		    $rs = mysql_query($query);
		    if($rs && mysql_num_rows($rs)){
			  $arr = array();
			  while($cursor = mysql_fetch_object($rs)){
				$arr[] = $cursor;
			  }
			  return $arr;
			  $conn->free($rs);
			  $conn->close();
		    }
		    return FALSE;
		    $conn->close();
		}
		
		public static function get_cidades_classificacao_divisao($idDivisao = FALSE, $limit = ''){
		    if($idDivisao === FALSE){
			  return FALSE;
		    }
		    if(is_numeric($limit)){
			  $limit = 'limit ' . (integer) $limit;
		    }
		    $conn = new DbConnect();
		    $query = "select distinct a.id, a.nome, a.slug, a.sigla, a.bandeira, a.pontos, a.pos_pontos
			  from cidade as a inner join cidade_modalidade_classificacao as b on
			  a.divisao = $idDivisao and a.status = 1 and a.id = b.id_cidade order by a.pos_pontos, a.nome $limit;";
		    $rs = mysql_query($query);
		    if($rs && mysql_num_rows($rs)){
			  $arr = array();
			  while($cursor = mysql_fetch_object($rs)){
				$arr[] = $cursor;
			  }
			  $conn->free($rs);
			  $conn->close();
			  return $arr;
		    }
		    return FALSE;
		    $conn->close();
		}
		
		public static function get_divisao($id = FALSE){
		    if($id === FALSE){
			  return FALSE;
		    }
		    $conn = new DbConnect();
		    $id = (integer) $id;
		    $rs = mysql_query("select divisao from cidade where id = $id limit 1;");
		    if($rs && mysql_num_rows($rs)){
			return mysql_result($rs, 0, 'divisao');
		    }
		    return FALSE;
		}
		
		public static function get_divisao_provas($id = FALSE){
			if(!$id || !is_numeric($id)){
				return FALSE;
			}
			
			$conn = new DbConnect();
			$id = mysql_real_escape_string($id);
			
			$rs = mysql_query("select distinct a.divisao from modalidade_sub as a
				        inner join cidade as b on b.modalidades like concat('%',a.id,'%')
				        and b.id = {$id} order by a.divisao;");
			
			if($rs && mysql_num_rows($rs)){
				$arr = array();
				while($item = mysql_fetch_object($rs)){
					$arr[] = $item->divisao;
				}
			}
			
			$conn->close();
			return !empty($arr) ? $arr : FALSE;
		}
		
		public static function atualiza_medalhas($id = FALSE, $ouro = FALSE, $prata = FALSE, $bronze = FALSE){
		    if($id === FALSE || ($ouro === FALSE && $prata === FALSE && $bronze === FALSE)){
			  return FALSE;
		    }
		    $conn = new DbConnect();
		    mysql_query("start transaction;");
		    if(is_numeric($ouro)){
			  $ouro = mysql_real_escape_string((integer) $ouro);
			  mysql_query("update cidade set medalha_total_ouro = $ouro where id = $id limit 1;");
		    }
		    if(is_numeric($prata)){
			  $prata = mysql_real_escape_string((integer) $prata);
			  mysql_query("update cidade set medalha_total_prata = $prata where id = $id limit 1;");
		    }
		    if(is_numeric($bronze)){
			  $bronze = mysql_real_escape_string((integer) $bronze);
			  mysql_query("update cidade set medalha_total_bronze = $bronze where id = $id limit 1;");
		    }
		    if(mysql_error()){
			  mysql_query("rollback;");
			  return FALSE;
		    } else {
			  mysql_query("commit;");
			  return TRUE;
		    }
		}
		
		public static function atualiza_pontos($id = FALSE, $pts = FALSE){
		    if($id === FALSE || $pts === FALSE){
			  return FALSE;
		    }
		    $conn = new DbConnect();
		    if(is_numeric($pts)){
			  $pts = (float) mysql_real_escape_string($pts);
			  $rs = mysql_query("update cidade set pontos = $pts where id = $id limit 1;");
		    }
		    if(isset($rs) && $rs){
			  return TRUE;
		    }
		    return FALSE;		    
		}
		
		public static function atualiza_posicao($tipo = 'medalhas', $divisao = 1){
		    if($tipo != 'medalhas' && $tipo != 'pontos' && !is_numeric($divisao)){
			  return FALSE;
		    }
		    $divisao = (integer) $divisao;
		    switch($tipo){
			  case 'medalhas':
				$query = "select id, medalha_total_ouro, medalha_total_prata, medalha_total_bronze from cidade where divisao = $divisao order by medalha_total_ouro desc, medalha_total_prata desc, medalha_total_bronze desc, nome;";
				$rs = mysql_query($query);
				if($rs && mysql_num_rows($rs)){
				        $cont = $contAtual = 0;
				        $prevOuro = $prevPrata = $prevBronze = '';
				        $arr = array();
				        mysql_query("start transaction;");
				        while($cursor = mysql_fetch_object($rs)){
					      $cont++;
					      if($prevOuro != $cursor->medalha_total_ouro || $prevPrata != $cursor->medalha_total_prata || $prevBronze != $cursor->medalha_total_bronze){
						    $contAtual = $cont;
					      }
					      mysql_query("update cidade set pos_medalhas = $contAtual where id = $cursor->id limit 1;");
					      $prevOuro = $cursor->medalha_total_ouro;
					      $prevPrata = $cursor->medalha_total_prata;
					      $prevBronze = $cursor->medalha_total_bronze;
				        }
				        if(mysql_error()){
					      mysql_query("rollback;");
					      return FALSE;
				        } else {
					      mysql_query("commit;");
					      return TRUE;
				        }
				}
				break;
			  case 'pontos':
				$query = "select id, pontos from cidade where divisao = $divisao order by pontos desc, nome;";
				$rs = mysql_query($query);
				if($rs && mysql_num_rows($rs)){
				        $cont = $contAtual = 0;
				        $prevPontos = '';
				        $arr = array();
				        mysql_query("start transaction;");
				        while($cursor = mysql_fetch_object($rs)){
						$cont++;
					      if($prevPontos != $cursor->pontos){
						    $contAtual = $cont;
					      }
					      mysql_query("update cidade set pos_pontos = $contAtual where id = $cursor->id limit 1;");
					      $prevPontos = $cursor->pontos;
				        }
				        if(mysql_error()){
					      mysql_query("rollback;");
					      return FALSE;
				        } else {
					      mysql_query("commit;");
					      return TRUE;
				        }
				}
			}
		}
		
		public static function verificar_cidade_valida($cidade = 0){
			$conn = new DbConnect();
			if($cidade === 0 || !is_numeric($cidade)){
				return FALSE;
			}
			$cidade = (integer) $cidade;
			
			$rs = mysql_query("select a.id from cidade as a where a.id = $cidade limit 1;");
			if($rs && mysql_num_rows($rs)){
			        return TRUE;
			}
			return FALSE;
		}
		
	}
?>