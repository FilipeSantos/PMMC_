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
    
    class BancoArquivo {
        public $id = FALSE;
        public $titulo;
        public $arquivo;
        public $thumb;
        public $tamanho;
        public $formato;
        public $status;
        private $upload;
        
        public function __construct(){
            
        }
        
        public function set_bancoArquivo($titulo, $formato, $thumb, $arquivo, $status, $upload){
            $valida = new Valida();
            $valida->notNull(array($titulo,  $formato, $thumb, $arquivo, $status));
            if($valida->totalErros){
                echo '{"status":"erro","info":[' . substr($valida->descErros, 0, -2) . ']}';
                exit();
            }

            $this->upload = $upload;
            $this->titulo = isset($titulo) && !empty($titulo) ? "'" . trim(htmlspecialchars(addslashes($titulo))) . "'" : 'NULL';
            $this->formato = isset($formato) && !empty($arquivo) ? "'" . trim(htmlspecialchars(addslashes($formato))) . "'" : 'NULL';
            $this->arquivo = isset($arquivo) && !empty($arquivo) ? "'" . trim(htmlspecialchars(addslashes($arquivo))) . "'" : 'NULL';
            $this->thumb = isset($thumb) && !empty($thumb) ? "'" . trim(htmlspecialchars(addslashes($thumb))) . "'" : 'NULL';
            $this->status = isset($status) && is_numeric($status) ? $status : 'NULL';
            if(!$this->upload){
                $this->tamanho = ceil(filesize($_SERVER['DOCUMENT_ROOT'] . '/upload/banco_de_arquivos/' . str_replace('\'', '', $this->arquivo)) / 1024);
            } else {
                $this->tamanho = ceil(filesize($_SERVER['DOCUMENT_ROOT'] . '/upload/temp/' . str_replace('\'', '', $this->arquivo)) / 1024);
            }            
            
        }

        public function get_bancoArquivo($id, $limit = '', $conds = ''){
            if(!empty($conds)){
                $cond = '';
                foreach($conds as $i=>$item){
                    if($item !== NULL){
                        $cond .= " $i = $item ";
                    }
                }
            }
            
            $cond = empty($cond) ? ' 1 ' : $cond;
            
            $conn = new DbConnect();
            $arr = array();
            if($rs = mysql_query("select id, titulo, formato, tamanho, thumb, arquivo, `status` from arquivo where $cond order by id desc $limit;")){
                while($item = mysql_fetch_object($rs)){
                    $arr[] = $item;
                }
                return $arr;
            } else {
                return false;
            }
        }
        
        public function get_bancoArquivo_front($limit){
            $conn = new DbConnect();
            $arr = array();
            if($rs = mysql_query("select titulo, formato, tamanho, thumb, arquivo from `arquivo` where `status` = 1 order by id desc $limit;")){
                while($item = mysql_fetch_object($rs)){
                    $arr[] = $item;
                }
                return $arr;
            } else {
                return false;
            }
        }
        
        public function get_total($conds = FALSE){
            if(!empty($conds)){
                $cond = '';
                foreach($conds as $i=>$item){
                    if($item !== NULL){
                        $cond .= " $i = $item ";
                    }
                }
            }
            
            $cond = empty($cond) ? ' 1 ' : $cond;
            
            $conn = new DbConnect();
            $rs = mysql_query("select count(*) as total from `arquivo` where $cond;");
            return mysql_result($rs, 0, 'total');
        }
        
        public function get_total_front(){
            $conn = new DbConnect();
            $rs = mysql_query("select count(*) as total from `arquivo` where `status` = 1;");
            return mysql_result($rs, 0, 'total');
        }
        
        public function get_ativos(){
            $conn = new DbConnect();
            $rs = mysql_query("select count(*) as total from `arquivo` where `status` = 1;");
            return mysql_result($rs, 0, 'total');
        }
        
        public function set_id($id){
            $this->id = $id;
        }

        public function salvar(){
            $conn = new DbConnect();
            if($this->id){
                if($rs = mysql_query("update `arquivo` set titulo = $this->titulo, thumb = $this->thumb, arquivo = $this->arquivo,
                                     formato = $this->formato, tamanho = $this->tamanho, `status` = $this->status where id = $this->id 
                                     limit 1;")){
                    if($this->upload){
                        $img = new Upload();
                        $img->set_dir_temp('/upload/temp');
                        $img->set_dir_upload('/upload/banco_de_arquivos');
                        if($img->salvar('upload', str_replace('\'', '', $this->arquivo))){
                            echo '{"status":"ok"}';
                            exit();
                        } else {
                            echo '{"status":"erro","info":"imagem"}';
                            exit();
                        }
                    }
                    echo '{"status":"ok"}';
                } else {
                    echo '{"status":"erro","info":"update"}';
                }
            } else {
                if($rs = mysql_query("insert into `arquivo`(idCriador, titulo, thumb, arquivo, formato, tamanho, dataCadastro,`status`)
                                  values($_SESSION[idUser], $this->titulo, $this->thumb, $this->arquivo, $this->formato, $this->tamanho,
                                  CURRENT_TIMESTAMP, $this->status);")){
                        if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/upload/temp/' . str_replace('\'', '', $this->arquivo)) === TRUE){
                            $img = new Upload();
                            $img->set_dir_temp('/upload/temp');
                            $img->set_dir_upload('/upload/banco_de_arquivos');
                            if($img->salvar('upload', str_replace('\'', '', $this->arquivo))){
                                echo '{"status":"ok"}';
                                exit();
                            } else {
                                echo '{"status":"erro","info":"imagem"}';
                                exit();
                            }
                        }
                        echo '{"status":"ok"}';
                } else {
                    echo '{"status":"erro","info":"insert"}';
                }
            }
            $conn->close();
        }
    }
?>