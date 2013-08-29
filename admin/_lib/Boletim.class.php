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
    
    class Boletim {
        public $id = FALSE;
        public $titulo;
        public $arquivo;
        public $data;
        public $status;
        private $upload;
        
        public function __construct(){}

        public function set_boletim($titulo, $arquivo, $data, $status, $upload){
            $valida = new Valida();
            $valida->notNull(array($titulo, $arquivo, $data, $status));
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

            $this->titulo = isset($titulo) && !empty($titulo) ? "'" . trim(htmlspecialchars(addslashes($titulo))) . "'" : 'NULL';
            $this->arquivo = isset($arquivo) && !empty($arquivo) ? "'" . trim(htmlspecialchars(addslashes($arquivo))) . "'" : 'NULL';
            $this->data = $dataPub;
            $this->status = isset($status) && is_numeric($status) ? $status : 0;
            $this->upload = $upload;
        }

        public function get_boletim($id = 'all', $limit = '', $conds = ''){
            if($id === 'all'){
                $id = '';
            } else {
                $id = "and id = $id";
            }
            
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
            if($rs = mysql_query("select id, titulo, arquivo, date_format(dataPublicacao, '%d\/%m\/%Y') as data, date_format(dataPublicacao, '%d\/%m\/%Y %H:%i') as dataFull, `status` from `boletim` where $cond $id order by id desc $limit;")){
                while($item = mysql_fetch_object($rs)){
                    $arr[] = $item;
                }
                return $arr;
            } else {
                return false;
            }
        }
        
        public function get_boletim_front($limit){
            $conn = new DbConnect();
            $arr = array();
            if($rs = mysql_query("select titulo, arquivo, date_format(dataPublicacao, '%d\/%m\/%Y') as data from `boletim` where `status` = 1 and dataPublicacao <= CURRENT_TIMESTAMP order by dataPublicacao desc $limit;")){
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
            $rs = mysql_query("select count(*) as total from `boletim` where $cond;");
            return mysql_result($rs, 0, 'total');
        }
        
        public function get_total_front(){
            $conn = new DbConnect();
            $rs = mysql_query("select count(*) as total from `boletim` where status = 1 and dataPublicacao <= CURRENT_TIMESTAMP;");
            return mysql_result($rs, 0, 'total');
        }

        public function get_ativos(){
            $conn = new DbConnect();
            $rs = mysql_query("select count(*) as total from `boletim` where `status` = 1;");
            return mysql_result($rs, 0, 'total');
        }

        public function salvar(){
            $conn = new DbConnect();
            if($this->id){
                if($rs = mysql_query("update `boletim` set titulo = $this->titulo, arquivo = $this->arquivo, dataPublicacao = $this->data,
                                     `status` = $this->status where id = $this->id limit 1;")){
                    if($this->upload){
                        $img = new Upload();
                        $img->set_dir_temp('/upload/temp');
                        $img->set_dir_upload('/upload/boletins');
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
                if($rs = mysql_query("insert into `boletim`(idCriador, titulo, arquivo, dataCadastro, dataPublicacao, `status`)
                                  values($_SESSION[idUser], $this->titulo, $this->arquivo, CURRENT_TIMESTAMP, $this->data, $this->status);")){
                    $img = new Upload();
                    $img->set_dir_temp('/upload/temp');
                    $img->set_dir_upload('/upload/boletins');
                    if($img->salvar('upload', str_replace('\'', '', $this->arquivo))){
                        echo '{"status":"ok"}';
                    } else {
                        echo '{"status":"erro","info":"imagem"}';
                    }
                } else {
                    echo '{"status":"erro","info":"insert"}';
                }
            }
            $conn->close();
        }
    }
?>