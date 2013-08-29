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
    
    class Slug{
        public $noRepeat;
        public $directory;
        public $table;
        public $idItem;
        public $idEnt;
        public $slugEnt;
        public $replace = '-';
        private $str;
        private $slug;
        
        public function __construct(){
            $this->noRepeat = FALSE;
            $this->directory = FALSE;
            $this->idItem = FALSE;
            $this->table = FALSE;
            $this->slugEnt = 'permalink';
            $this->idEnt = 'id';
        }
        
        public function SetSlug($str, $ext = FALSE){
            global $conn;
            $str = utf8_encode(strtolower(stripslashes(trim($str))));
            $str = preg_replace('/[@àáâãäåæ]+/iu', 'a', $str);
            $str = preg_replace('/[èéêë]+/iu', 'e', $str);
            $str = preg_replace('/[ìíîï]+/iu', 'i', $str);
            $str = preg_replace('/[òóôõö]+/iu', 'o', $str);
            $str = preg_replace('/[ùúûü]+/iu', 'u', $str);
            $str = preg_replace('/[ç]+/iu', 'c', $str);
            $str = preg_replace('/[^_a-zA-Z0-9-]/', $this->replace, $str);
            $str = preg_replace("/$this->replace+/", $this->replace, $str);
            $str = trim($str, '-');
            $verifica = true;
            $i = 0;
            $hash = '';        
            if($this->noRepeat){
                if($this->directory){
                    while ($verifica !== FALSE){
                        if($i++){
                            $hash = $this->replace . $i;
                        }
                        $verifica = file_exists($this->directory . $str . $hash . $ext);
                    }
                } else {
                    echo is_resource($conn) . ' ';
                    if(!isset($conn) && !is_resource($conn)){
                        $conn = new DbConnect();
                        $closeConn = TRUE;
                    }
                    
                    $setId = $this->idItem ? " and $this->idEnt != $this->idItem" : "";
                    while ($verifica !== 0){
                        if($i++){
                            $hash = '-' . $i;
                        }
                        $rs = mysql_query("select * from $this->table where $this->slugEnt = '$str$hash'$setId;");
                        $verifica = mysql_num_rows($rs);
                    }
                    if(isset($closeConn)){
                        $conn->close();
                    }                    
                }
            }            
            $this->slug = $str . $hash;
        }
        
        public function GetSlug(){
            return trim($this->slug);
        }
    }
?>