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
    
    class Noticia {
        public $id = FALSE;
        public $titulo;
        public $descricao;
        public $texto;
        public $imgUploadThumb;
        public $imgUploadImagem1;        
        public $imgUploadImagem2;
        public $dataPub;
        public $ativo;
        public $destaque;
        public $tags = array();
        public $categorias = array();
        public $tipoNoticia;
        public $exibirHome;
        public $tituloSlug;
        public $aprovacao = 'NULL';

        public function __construct(){}

        public function set_noticia($titulo, $descricao, $texto, $imgUploadThumb, $imgUploadImagem1, $imgUploadImagem2, $dataPub, $ativo, $destaque,
                                    $tags, $categorias, $tipoNoticia, $exibirHome, $aprovacao){
            $valida = new Valida();
            $valida->notNull(array($titulo, $texto, $dataPub, $categorias));
            if($valida->totalErros){
                echo '{"status":"erro","info":[' . substr($valida->descErros, 0, -2) . ']}';
                exit();
            }            

            list($data, $hora) = explode(' ', $dataPub);
            list($dia, $mes, $ano) = explode('/', $data);
            list($hora, $min) = explode(':', $hora);
            if(checkdate($mes, $dia, $ano)){
                $dataPub = "'" . "$ano-$mes-$dia $hora:$min:00" . "'";
            } else {
                echo '{"status":"erro","info":"data"}';
                exit();
            }

            $this->titulo = isset($titulo) && !empty($titulo) ? "'" . addslashes($titulo) . "'" : 'NULL';
            $this->descricao = isset($descricao) && !empty($descricao) ? "'" . addslashes($descricao) . "'" : 'NULL';
            $this->texto = isset($texto) && !empty($texto) ? "'" . addslashes($texto) . "'" : 'NULL';
            $imgUploadThumb = explode('?', $imgUploadThumb);
            $imgUploadThumb = $imgUploadThumb[0];
            $this->imgUploadThumb = isset($imgUploadThumb) && !empty($imgUploadThumb) ? "'" . addslashes($imgUploadThumb) . "'" : 'NULL';
            $this->imgUploadImagem1 = isset($imgUploadImagem1) && !empty($imgUploadImagem1) ? "'" . addslashes($imgUploadImagem1) . "'" : 'NULL';
            $this->imgUploadImagem2 = isset($imgUploadImagem2) && !empty($imgUploadImagem2) ? "'" . addslashes($imgUploadImagem2) . "'" : 'NULL';
            $this->dataPub = isset($dataPub) && !empty($dataPub) ? $dataPub : 'NULL';
            $this->ativo = $ativo;
            $this->destaque = isset($destaque) && is_numeric($destaque) ? $destaque : 0;
            if($tags){
                foreach($tags as $tag){
                    $this->tags[] = isset($tag) && !empty($tag) ? "'" . addslashes($tag) . "'" : 'NULL';
                }
            }
            if($categorias){
                foreach($categorias as $categoria){
                    $this->categorias[] = isset($categoria) && !empty($categoria) ? "'" . addslashes($categoria) . "'" : 'NULL';
                }
            }
            $this->tipoNoticia = isset($tipoNoticia) && !empty($tipoNoticia) ? "'" . addslashes($tipoNoticia) . "'" : 'NULL';
            $this->exibirHome = isset($exibirHome) && is_numeric($exibirHome) ? $exibirHome : 0;
            $this->tituloSlug = stripslashes($titulo);

            $slug = new Slug();
            $slug->noRepeat = TRUE;
            $slug->table = 'noticia';
            if($this->id){
                $slug->idItem = $this->id;
            }            
            $slug->SetSlug(utf8_decode($this->tituloSlug));
            $this->tituloSlug = $slug->GetSlug();
            $this->aprovacao = $aprovacao;
            
            if($this->imgUploadThumb == 'NULL' && ($this->exibirHome !== 0 || $this->destaque !== 0)){
                echo '{"status":"erro","info":"destaque"}';
                exit();
            }
        }

        public function get_noticias($id, $limit = '', $arrCond){
            $cond = '';
            $filtro = 0;
            if(!empty($arrCond)){
                foreach($arrCond as $item){
                    // echo '';
                }
            }
            
            if($filtroCateg){
               $cond .= " inner join noticia_categoria as b on a.id = b.idNoticia and b.idCategoria = $filtroCateg and ";
               $filtro = 1;
            }
            if($filtroDestaque){
               $cond .= (!$filtro ? 'where ' : '') . "a.importancia = $filtroDestaque and ";
               $filtro = 1;
            }
            if($filtroStatus){
               $cond .= (!$filtro ? 'where ' : '') . "a.status = " . ($filtroStatus > 1 ? 1 : $filtroStatus) . ($filtroStatus > 1 ? " and a.dataPublicacao > CURRENT_TIMESTAMP " : "") . ' and ';
            }   
            $cond = !empty($cond) ? substr($cond, 0, -4) : '';
            
            $noticias = array();
            $rs = mysql_query("select a.titulo, a.descricao, a.texto, a.imagemThumb, a.imagem1, a.imagem2, a.permalink, a.exibirHome, a.importancia, a.status,
                           date_format(a.dataPublicacao, '%d/%m/%Y %H:%i') as dataPublicacao, unix_timestamp(a.dataPublicacao) as data from noticia as a where id = $id ;");
            
            
            $cond = ($id == 'all') ? "where 1" : "where id = $id";
            $conn = new DbConnect();
            $arr = array();
            if($rs = mysql_query("select id, titulo, arquivo, linkNoticia, date_format(dataPublicacao, '%d\/%m\/%Y') as data, date_format(dataPublicacao, '%d\/%m\/%Y %H:%i') as dataFull, `status` from `release` $cond order by id desc $limit;")){
                while($item = mysql_fetch_object($rs)){
                    $arr[] = $item;
                }
                return $arr;
            } else {
                return false;
            }
        }
        
        public function get_noticias_tag($tag = NULL, $limit = 0){
            if(is_null($tag)){
                return FALSE;
            }
            $lim = '';
            
            if($limit && is_numeric($limit)){
                $lim = 'limit ' . (integer) $limit;
            }
            
            $tag = mysql_real_escape_string($tag);
            $conn = new DbConnect();
            $noticias = array();

            $rs = mysql_query("select a.titulo, a.descricao, a.texto, a.imagemThumb, a.permalink, 
                            e.titulo as categoria_titulo, e.permalink as categoria_permalink, date_format(a.dataPublicacao, '%d/%m') as data
                            from noticia as a inner join noticia_tag as b on a.id = b.idNoticia 
                            inner join tag as c on c.id = b.idTag and c.titulo = '{$tag}' 
                            inner join noticia_categoria as d on a.id = d.idNoticia 
                            inner join categoria as e on e.id = d.idCategoria 
                            order by a.dataPublicacao desc $lim;");
            
            if($rs && mysql_num_rows($rs)){
                while($item = mysql_fetch_object($rs)){
                    $noticias[] = $item;
                }
            }
            
            $conn->close();
            return !empty($noticias) ? $noticias : FALSE;
        }
        
        public function get_noticias_categoria($categ = NULL, $limit = 0){
            if(is_null($categ)){
                return FALSE;
            }
            $lim = '';
            
            if($limit && is_numeric($limit)){
                $lim = 'limit ' . (integer) $limit;
            }
            
            $categ = mysql_real_escape_string($categ);
            $conn = new DbConnect();
            $noticias = array();
            
            $rs = mysql_query("select a.titulo, a.descricao, a.texto, a.imagemThumb, a.permalink, 
                            c.titulo as categoria_titulo, c.permalink as categoria_permalink, date_format(a.dataPublicacao, '%d/%m') as data
                            from noticia as a inner join noticia_categoria as b on a.id = b.idNoticia 
                            inner join categoria as c on c.id = b.idCategoria and c.titulo = '{$categ}'
                            order by a.dataPublicacao desc $lim;");
            
            if($rs && mysql_num_rows($rs)){
                while($item = mysql_fetch_object($rs)){
                    $noticias[] = $item;
                }
            }
            
            $conn->close();
            return !empty($noticias) ? $noticias : FALSE;
        }
        
        public static function get_destaque($area = 'noticias'){
            $conn = new DbConnect();
            $utility = new Utility();
            $noticia = array();
            $i = 0;

            if($area == 'noticias'){
                $order = 'importancia';
                $limit = '16';
                $tbl = 'dataImportancia';
            } elseif($area == 'home'){
                $order = 'exibirHome';
                $limit = '10';
                $tbl = 'dataImportanciaHome';
            }

            $rs = mysql_query("SELECT a.id, a.titulo, a.texto, a.imagemThumb, a.permalink, a.$order, date_format( a.dataPublicacao, '%d\/%m\/%Y' ) AS data,
                              a.$tbl  FROM (
                                SELECT *
                                FROM noticia
                                WHERE $order !=0
                                AND imagemThumb IS NOT NULL
                                AND STATUS =1
                                AND aprovacao IS NULL
                                AND dataPublicacao <=
                                CURRENT_TIMESTAMP ORDER BY $order, $tbl DESC
                            ) AS a WHERE 1 GROUP BY a.$order ORDER BY a.$order, a.$tbl DESC LIMIT $limit;");
           
            if($rs && mysql_num_rows($rs)){
                while($item = mysql_fetch_assoc($rs)){
                    $rsCateg = mysql_query("select a.titulo, a.permalink, a.categ from categoria as a
                                           inner join noticia_categoria as b on a.id = b.idCategoria and b.idNoticia = " . $item['id'] . " order by a.titulo limit 1;");
                    
                    $txtCateg = '';
                    
                    if($rsCateg && mysql_num_rows($rsCateg)) {
                        
                        while($itemCateg = mysql_fetch_assoc($rsCateg)){
                                $baseUrl = (integer) $itemCateg['categ'] !== 0 ? 'modalidade' : 'categoria';
                                $txtCateg = $txtCateg . '<a href="/' . $baseUrl . '/' . $itemCateg['permalink'] .'">' . $itemCateg['titulo'] . '</a>, ';
                        }
                    }
                    
                    $noticia[$i] = $item;
                    $noticia[$i++]['categoria'] = !empty($txtCateg) ? substr($txtCateg, 0, -2) : '';
                }    
                return $noticia;
            } else {
                return false;
            }
        }
        
        public static function get_ultimas($idNot = '', $limit = 0){
            if(!empty($idNot) && is_array($idNot)){
                $idNot = '(\'' . implode('\',\'', $idNot) . '\')';                
                $noticia = array();
                
                if(is_numeric($limit) && (integer) $limit !== 0){
                    $limit = (integer) $limit;
                } else {
                    $limit = 6;
                }
                
                $lim = "limit 0, {$limit}";
                $conn = new DbConnect();
                $rs = mysql_query("select id, titulo, permalink, date_format(dataPublicacao, '%d\/%m\/%Y') as data, date_format(dataPublicacao, '%H:%i') as hora from
                                          noticia where status = 1 and dataPublicacao <= CURRENT_TIMESTAMP and id not in $idNot
                                          order by dataPublicacao desc $lim;") or die();
                $i = 0;
                if($rs && mysql_num_rows($rs)){
                    while($item = mysql_fetch_assoc($rs)){
                        $noticia[$i] = $item;

                        $rsCateg = mysql_query("select a.titulo, a.permalink, a.categ from categoria as a
                                               inner join noticia_categoria as b on a.id = b.idCategoria and b.idNoticia = " . $item['id'] . " order by a.titulo limit 0,1;") or die();
                        $txtCateg = '';
                        while($itemCateg = mysql_fetch_assoc($rsCateg)){
                                $baseUrl = (integer) $itemCateg['categ'] !== 0 ? 'modalidade' : 'categoria';
                                $txtCateg = $txtCateg . '<a href="/' . $baseUrl . '/' . $itemCateg['permalink'] .'">' . $itemCateg['titulo'] . '</a>, ';
                        }
                        $txtCateg = substr($txtCateg, 0, -2);
                        $noticia[$i++]['categoria'] = $txtCateg;
                    }
                }
                return (!empty($noticia) ? $noticia : FALSE);
            }
            return FALSE;
        }

        public function get_total(){
            $conn = new DbConnect();
            $rs = mysql_query("select count(*) as total from `release` where 1;");
            return mysql_result($rs, 0, 'total');
        }

        public function get_ativos(){
            $conn = new DbConnect();
            $rs = mysql_query("select count(*) as total from `release` where `status` = 1;");
            return mysql_result($rs, 0, 'total');
        }
        
        public function get_categs($id = FALSE){
            $cond = $id ? " inner join noticia_categoria as b on a.id = b.idCategoria and b.idNoticia = $id " : ' where 1 ';
            $rs = mysql_query("select a.id, a.titulo from categoria as a $cond order by a.titulo");
            if(mysql_num_rows($rs)){
               $arrayCateg = array();
               while($item = mysql_fetch_object($rs)){
                  $arrayCateg[] = $item;
               }
               return $arrayCateg;
            }
            return FALSE;
        }
        
        public function get_tags($id = FALSE){
            $cond = $id ? " inner join noticia_tag as b on a.id = b.idTag and b.idNoticia = $id " : ' where 1 ';
            $rs = mysql_query("select a.id, a.titulo from tag as a $cond order by a.titulo");
            if(mysql_num_rows($rs)){
               $arrayTag = array();
               while($item = mysql_fetch_object($rs)){
                  $arrayTag[] = $item;
               }
               return $arrayTag;
            }
            return FALSE;
        }

        public function salvar(){
            $conn = new DbConnect();
            if($this->id){
                $rs = mysql_query("select importancia, exibirHome, dataImportancia, dataImportanciaHome, idLog, status, aprovacao from noticia where id = $this->id limit 1;");
                $userCad = mysql_result($rs, 0, 'idLog');
                $stat = mysql_result($rs, 0, 'status');
                $aprov = mysql_result($rs, 0, 'aprovacao');
                $valDestaq = mysql_result($rs, 0, 'importancia');
                $valDestaqHome = mysql_result($rs, 0, 'exibirHome');
                $dataDestaq = "'" . mysql_result($rs, 0, 'dataImportancia') . "'";
                $dataDestaqHome = "'" . mysql_result($rs, 0, 'dataImportanciaHome') . "'";
                
                if($_SESSION['capability'] > 2 && $stat === '1' && $aprov === NULL){
                    echo '{"status":"erro","info":"capability"}';
                    exit();
                }
                
                if($this->destaque != $valDestaq){
                    $dataDestaq = 'CURRENT_TIMESTAMP';
                }
                if($this->exibirHome != $valDestaqHome){
                    $dataDestaqHome = 'CURRENT_TIMESTAMP';
                }
                
                if(!$rs = mysql_query("update noticia set titulo = $this->titulo,
                          descricao = $this->descricao, texto = $this->texto, imagemThumb = $this->imgUploadThumb,
                          imagem1 = $this->imgUploadImagem1, imagem2 = $this->imgUploadImagem2, permalink = '$this->tituloSlug',
                          exibirHome = $this->exibirHome, importancia = $this->destaque, status = $this->ativo, dataPublicacao = $this->dataPub, 
                          dataImportancia = $dataDestaq, dataImportanciaHome = $dataDestaqHome, aprovacao = $this->aprovacao where id = $this->id limit 1;")){
                    echo '{"status":"erro","info":"update"}';
                    exit();
                }
                $newNot = FALSE;
            } else {
                if(!$rs = mysql_query("insert into noticia(idLog, titulo, descricao, texto, imagemThumb, imagem1, imagem2, permalink, exibirHome,
                                      importancia, status, dataPublicacao, dataImportancia, dataImportanciaHome, aprovacao)
                                      values(" . $_SESSION['idUser'] . ", $this->titulo, $this->descricao, $this->texto, $this->imgUploadThumb, $this->imgUploadImagem1,
                                      $this->imgUploadImagem2, '$this->tituloSlug', $this->exibirHome, $this->destaque, $this->ativo, $this->dataPub,
                                      CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, $this->aprovacao);")){
                    echo '{"status":"erro","info":"insert ' . mysql_error() . '"}';
                    exit();
                }
                $newNot = TRUE;
                $this->id = mysql_insert_id();
            }
            
            $tagSearch = '';
            $categSearch = '';
            $textoSeach = strip_tags(html_entity_decode($this->texto, ENT_NOQUOTES, 'UTF-8'));
            
            mysql_query("delete from noticia_tag where idNoticia = $this->id;");
            mysql_query("delete from noticia_categoria where idNoticia = $this->id;");
            
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
                if(!$rs = mysql_query("insert into noticia_tag(idNoticia, idTag) values($this->id, $idTag);")){
                    echo '{"status":"erro","info":"insert-tag"}';
                    exit();
                }
            }

            foreach($this->categorias as $categoria){
                $rs = mysql_query("select titulo from categoria where id = $categoria limit 1;");
                if($rs && mysql_num_rows($rs)){
                    $categSearch = $categSearch . mysql_result($rs, 0, 'titulo') . ', ';
                }
                
                if(!$rs = mysql_query("insert into noticia_categoria(idNoticia, idCategoria) values($this->id, $categoria);")){
                    echo '{"status":"erro","info":"insert-categ"}';
                    exit();
                }
            }
            
            $tagSearch = "'" . substr($tagSearch, 0, -2) . "'";
            $categSearch = "'" . substr($categSearch, 0, -2) . "'";
            
            if($newNot === FALSE){
                $rs = mysql_query("update search_noticia set titulo = $this->titulo, descricao = $this->descricao, texto = $textoSeach, tags = $tagSearch, categoria = $categSearch where idItem = $this->id limit 1;") or die('{"status":"erro","info":"update"}');
            } else {
                $rs = mysql_query("insert into search_noticia(idItem, titulo, descricao, texto, tags, categoria)
                                  values($this->id, $this->titulo, $this->descricao, $textoSeach, $tagSearch, $categSearch);") or die('{"status":"erro","info":"insert"}');
            }
            
            echo '{"status":"ok"}';
            
            $conn->close();
        }
        
        public function aprovar(){
            if($this->id){
                $conn = new DbConnect();
                if($rs = mysql_query("update noticia set `status` = 1, aprovacao = NULL where id = $this->id;")){
                    return TRUE;
                }
                $conn->close();
                return FALSE;
            }
        }
        
        public function excluir(){
            if($this->id){
                $conn = new DbConnect();
                if(!($rs = mysql_query("delete from noticia_tag where idNoticia = $this->id;"))){
                    return FALSE;
                }
                if(!($rs = mysql_query("delete from noticia_categoria where idNoticia = $this->id;"))){
                    return FALSE;
                }
                if(!($rs = mysql_query("delete from noticia where id = $this->id;"))){
                    return FALSE;
                }
                if(!($rs = mysql_query("delete from search_noticia where idItem = $this->id;"))){
                    return FALSE;
                }
                $conn->close();
                return TRUE;
            }
        }
    }
?>