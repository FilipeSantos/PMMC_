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
    
    class Galeria {
        private $id = FALSE;
        private $tipo;
        private $titulo;
        private $descricao;
        private $data;
        private $midia;
        private $thumb;
        private $status;
        private $upload;
        private $categorias = array();
        private $tags = array();
        
        public function __construct(){}
        
        public function set_id($id = FALSE){
            $this->id = $id && is_numeric($id) ? $id : FALSE;
        }
        
        public static function get_total($ativos = FALSE){
            if($ativos){
                $cond = 'status = 1';
            } else {
                $cond = '1';
            }
            $conn = new DbConnect();
            if($rs = mysql_query("select id from galeria where $cond;")){                
                return mysql_num_rows($rs);
                $conn->close();
            }
        }
        
        public function set_galeria($tipo, $titulo, $data, $descricao, $midia, $thumb, $status, $categorias, $tags){
            $valida = new Valida();
            $valida->notNull(array($tipo, $titulo, $data, $midia, $status, $categorias, $thumb));
            if($valida->totalErros){
                echo '{"status":"erro","info":[' . substr($valida->descErros, 0, -2) . ']}';
                exit();
            }

            list($data, $hora) = explode(' ', $data);
            list($dia, $mes, $ano) = explode('/', $data);
            list($hora, $min) = explode(':', $hora);
            
            if(checkdate($mes, $dia, $ano)){
                $dataPub = "'" . "$ano-$mes-$dia $hora:$min:00" . "'";
            } else {
                echo '{"status":"erro","info":"data"}';
                exit();
            }

            $this->tipo = isset($tipo) && !empty($tipo) ? "'" . trim(htmlspecialchars(addslashes($tipo))) . "'" : 'NULL';
            $this->titulo = isset($titulo) && !empty($titulo) ? "'" . trim(htmlspecialchars(addslashes($titulo))) . "'" : 'NULL';
            $this->descricao = isset($descricao) && !empty($descricao) ? "'" . trim(htmlspecialchars(addslashes($descricao))) . "'" : 'NULL';
            $this->data = $dataPub;
            $this->midia = isset($midia) && !empty($midia) ? "'" . trim(htmlspecialchars(addslashes($midia))) . "'" : 'NULL';
            $this->status = isset($status) && is_numeric($status) ? $status : 0;
            $this->categorias = isset($categorias) && is_array($categorias) ? $categorias : FALSE;
            $this->tags = isset($tags) && is_array($tags) ? $tags : FALSE;
            $this->thumb = isset($thumb) ? "'" . trim(htmlspecialchars(addslashes($thumb))) . "'" : 'NULL';
            
            if($this->categorias){
                foreach($this->categorias as $i){
                    if(!is_numeric($i)){
                        echo '{"status":"erro","info":"categ"}';
                        exit();
                    }
                }
            }
            
            if($this->tags){
                for($cont=0; $cont < (count($this->tags)); $cont++){
                    $this->tags[$cont] = "'" . trim(htmlspecialchars(addslashes($this->tags[$cont]))) . "'";
                }
            }
        }

        public function salvar(){
            $conn = new DbConnect();
            if($this->id){
                $tagSearch = '';
                $categSearch = '';
                
                if($rs = mysql_query("update `galeria` set titulo = $this->titulo, dataPublicacao = $this->data, url = $this->midia,
                                     descricao = $this->descricao, status = $this->status, thumb = $this->thumb, tipo = $this->tipo
                                     where id = $this->id limit 1;")){
                    
                    $rs = mysql_query("delete from galeria_categoria where idGaleria = $this->id;") or die('{"status":"erro","info":"update"}');
                    $rs = mysql_query("delete from galeria_tag where idGaleria = $this->id;") or die('{"status":"erro","info":"update"}');

                    if($this->categorias){
                        $rs = mysql_query("delete from galeria_categoria where idGaleria = $this->id;") or die('{"status":"erro","info":"insert"}');
                        foreach($this->categorias as $categ){
                            $rs = mysql_query("select titulo from categoria where id = $categ limit 1;");
                            if($rs && mysql_num_rows($rs)){
                                $categSearch = $categSearch . mysql_result($rs, 0, 'titulo') . ', ';
                            }
                            $rs = mysql_query("insert into galeria_categoria(idCategoria, idGaleria) values($categ, $this->id);") or die('{"status":"erro","info":"insert"}');
                        }
                        $categSearch = substr($categSearch, 0, -2);
                    }
                    
                    if($this->tags){
                        $rs = mysql_query("delete from galeria_tag where idGaleria = $this->id;") or die('{"status":"erro","info":"insert"}');
                        foreach($this->tags as $tag){
                            $rs = mysql_query("select id from tag where titulo = $tag limit 0,1;");
                            if(mysql_num_rows($rs)){
                                $idTag = mysql_result($rs, 0, 'id');
                                $tagSearch = $tagSearch . str_replace("'", "", $tag) . ', ';
                            } else {
                                $rs = mysql_query("insert into tag(titulo) values($tag);");
                                $idTag = mysql_insert_id();
                                $tagSearch = $tagSearch . str_replace("'", "", $tag) . ', ';
                            }
                            if(!$rs = mysql_query("insert into galeria_tag(idGaleria, idTag) values($this->id, $idTag);")){
                                echo '{"status":"erro","info":"insert-tag"}';
                                exit();
                            }
                        }
                    }
                    $tagSearch = substr($tagSearch, 0, -2);

                    echo '{"status":"ok"}';
                } else {
                    echo '{"status":"erro","info":"update"}';
                }
                $newItem = FALSE;
            } else {
                $tagSearch = '';
                $categSearch = '';
                
                if($rs = mysql_query("insert into `galeria`(idCriador, titulo, dataPublicacao, url, thumb, descricao, dataCadastro, `status`, tipo)
                                  values($_SESSION[idUser], $this->titulo, $this->data, $this->midia, $this->thumb, $this->descricao, CURRENT_TIMESTAMP, $this->status, $this->tipo);")){
                    
                    $this->id = mysql_insert_id();
                    
                    if($this->categorias){
                        foreach($this->categorias as $categ){
                            $rs = mysql_query("select titulo from categoria where id = $categ limit 1;");
                            if($rs && mysql_num_rows($rs)){
                                $categSearch = $categSearch . mysql_result($rs, 0, 'titulo') . ', ';
                            }
                            
                            $rs = mysql_query("insert into galeria_categoria(idCategoria, idGaleria) values($categ, $this->id);") or die('{"status":"erro","info":"insert"}');
                        }
                    }
                    $categSearch = substr($categSearch, 0, -2);

                    if($this->tags){
                        foreach($this->tags as $tag){
                            $rs = mysql_query("select id from tag where titulo = $tag limit 0,1;");
                            if(mysql_num_rows($rs)){
                                $idTag = mysql_result($rs, 0, 'id');
                                $tagSearch = $tagSearch . str_replace("'", "", $tag) . ', ';
                            } else {
                                $rs = mysql_query("insert into tag(titulo) values($tag);");
                                $idTag = mysql_insert_id();
                                $tagSearch = $tagSearch . str_replace("'", "", $tag) . ', ';
                            }
                            if(!$rs = mysql_query("insert into galeria_tag(idGaleria, idTag) values($this->id, $idTag);")){
                                echo '{"status":"erro","info":"insert-tag"}';
                                exit();
                            }
                        }
                        $tagSearch = substr($tagSearch, 0, -2);
                    }

                    echo '{"status":"ok"}';
                } else {
                    echo '{"status":"erro","info":"insert"}';
                }
                $newItem = TRUE;
            }
            
            if($newItem === FALSE){
                $rs = mysql_query("update search_galeria set titulo = $this->titulo, tipo = $this->tipo, descricao = $this->descricao, tags = '$tagSearch', categoria = '$categSearch' where idItem = $this->id limit 1;") or die('{"status":"erro","info":"update"}');
            } else {
                $rs = mysql_query("insert into search_galeria(idItem, tipo, titulo, descricao, tags, categoria)
                                  values($this->id, $this->tipo, $this->titulo, $this->descricao, '$tagSearch', '$categSearch');") or die('{"status":"erro","info":"insert"}');
            }
            
            $conn->close();
        }

        public static function get_galeria($id = FALSE, $tipo = FALSE, $tags = FALSE, $categs = FALSE, $desc = FALSE, $limit = '', $conds = array()){
            $conn = new DbConnect();
            if($id){
                $arr = $arrCategs = $arrTags = array();
                $i = 0;
                
                $conn = new DbConnect();
                
                $rs = mysql_query("select id, titulo, date_format(dataPublicacao, '%d\/%m\/%Y') as data, date_format(dataPublicacao, '%d\/%m\/%Y %H:%i') as dataFull, url, thumb, descricao, status, tipo from galeria where id = $id limit 1;") or die('erro1');
            } elseif($tipo){
                if(is_array($conds) && !empty($conds)){
                    foreach($conds as $key=>$cond){
                        if($key == 'idCateg'){
                            $condQuery = "inner join galeria_categoria as b on a.id = b.idGaleria and b.idCategoria = $cond and a.tipo = $tipo";
                            break;
                        }
                    }
                } else {
                    $condQuery = "where tipo = $tipo";
                }
                $rs = mysql_query("select a.id, a.titulo, date_format(a.dataPublicacao, '%d\/%m\/%Y') as data, a.thumb, " . ($desc ? 'a.descricao,' : '' ) . "a.status, a.tipo from galeria as a $condQuery and a.status = 1 and a.dataPublicacao <= CURRENT_TIMESTAMP order by a.dataPublicacao DESC $limit;") or die('erro2');
                
            } else {
                $rs = mysql_query("select a.id, a.titulo, date_format(a.dataPublicacao, '%d\/%m\/%Y') as data, a.thumb, " . ($desc ? 'a.descricao,' : '' ) . "a.status, a.tipo from galeria as a where " . (!empty($conds) ? $conds : '1') ." order by id DESC $limit;") or die('erro2');
            }
            
            if(mysql_num_rows($rs)){
                $i = 0;
                while($item = mysql_fetch_object($rs)){
                    $id = $item->id;
                    $arr[$i] = (object) array_map('stripslashes', (array) $item);

                    if($tags !== FALSE){
                        $rsTags = mysql_query("select a.id, a.titulo from tag as a inner join galeria_tag as b on a.id = b.idTag and b.idGaleria = $id;") or die('erro3');
                        if(mysql_num_rows($rsTags)){
                            while($itemTag = mysql_fetch_assoc($rsTags)){
                                $arrTags[] = $itemTag;
                            }
                            $arr[$i]->tags = $arrTags;
                        }
                    }
                    
                    if($categs !== FALSE){
                        $rsCategs = mysql_query("select a.id, a.titulo, a.permalink, a.categ from categoria as a inner join galeria_categoria as b on a.id = b.idCategoria and b.idGaleria = $id;") or die();
                        if(mysql_num_rows($rsCategs)){
                            while($itemCateg = mysql_fetch_assoc($rsCategs)){
                                $arrCategs[] = $itemCateg;
                            }
                            $arr[$i]->categorias = $arrCategs;
                        }
                    }
                    $i++;
                }
                $conn->close();
                return $arr;
            }
            $conn->close();
            return FALSE;
        }
        
        public function get_galeria_tag($tag = NULL, $tipo = 0, $limit = 0){
            if($tag === NULL || !is_numeric($tipo) || (integer) $tipo === 0 || !is_numeric($tipo) || (integer) $tipo === 0){
                return FALSE;
            }
            
            $conn = new DbConnect();
            
            $lim = '';
            $arr = array();
            $tag = "'" . mysql_real_escape_string($tag) . "'";
            $tipo = (integer) $tipo;
            
            if(is_numeric($limit) && (integer) $limit !== 0){
                $lim = 'limit ' . (integer) $limit;
            }
            
            $rs = mysql_query("select a.id, a.titulo, date_format(a.dataPublicacao, '%d\/%m\/%Y') as data,
                            a.thumb, c.titulo as categoria_titulo, c.permalink as categoria_slug 
                            from galeria as a 
                        inner join galeria_categoria as b on a.id = b.idGaleria
                        inner join categoria as c on c.id = b.idCategoria
                        inner join galeria_tag as d on a.id = d.idGaleria
                        inner join tag as e on e.id = d.idTag and e.titulo = {$tag}
                            and a.tipo = {$tipo} 
                        order by a.dataPublicacao DESC $lim;");

            if($rs && mysql_num_rows($rs)){
                while($item = mysql_fetch_object($rs)){
                    $arr[] = $item;
                }
            }
        
            $conn->close();
            
            return !empty($arr) ? $arr : FALSE;
        }
        
        public function get_galeria_categoria($categ = NULL, $tipo = 0, $limit = 0){
            if(empty($categ) || !is_numeric($tipo) || (integer) $tipo === 0){
                return FALSE;
            }
            
            $conn = new DbConnect();
            
            $lim = '';
            $arr = array();
            $tag = "'" . mysql_real_escape_string($categ) . "'";
            $tipo = (integer) $tipo;
            
            if(is_numeric($limit) && (integer) $limit !== 0){
                $lim = 'limit ' . (integer) $limit;
            }
            
            $rs = mysql_query("select a.id, a.titulo, date_format(a.dataPublicacao, '%d\/%m\/%Y') as data,
                            a.thumb, c.titulo as categoria_titulo, c.permalink as categoria_slug 
                            from galeria as a 
                        inner join galeria_categoria as b on a.id = b.idGaleria
                        inner join categoria as c on c.id = b.idCategoria and c.titulo = '{$categ}'
                            and a.tipo = {$tipo} 
                        order by a.dataPublicacao DESC $lim;");

            if($rs && mysql_num_rows($rs)){
                while($item = mysql_fetch_object($rs)){
                    $arr[] = $item;
                }
            }
        
            $conn->close();
            
            return !empty($arr) ? $arr : FALSE;
            
        }
        
        public function set_status($status = FALSE){
            $conn = new DbConnect();
            if($this->id && $status !== FALSE){
                if($rs = mysql_query("update galeria set status = $status where id = $this->id;")){
                    $conn->close();
                    return TRUE;
                } else {
                    $conn->close();
                    return FALSE;
                }
            }            
        }
        
        public function excluir(){
            $conn = new DbConnect();
            if($this->id){
                if($rs = mysql_query("select tipo, url from galeria where id = $this->id limit 1;")){
                    $tipo = mysql_result($rs, 0, 'tipo');
                    $img = mysql_result($rs, 0, 'url');
                    if($tipo == '1'){
                        @unlink($_SERVER['DOCUMENT_ROOT'] . "/upload/galeria/$img");
                        @unlink($_SERVER['DOCUMENT_ROOT'] . "/upload/galeria/thumb/$img");
                    }
                }
                
                if($rs = mysql_query("delete from galeria_categoria where idGaleria = $this->id;")
                   && mysql_query("delete from galeria_tag where idGaleria = $this->id;")
                   && mysql_query("delete from galeria where id = $this->id;")){
                    $conn->close();
                    return TRUE;
                } else {
                    $conn->close();
                    return FALSE;
                }
            } else {
                echo '{"status":"erro"}';
            }
        }
    }
?>