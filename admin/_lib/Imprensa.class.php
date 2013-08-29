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
    
    class Imprensa {
        private $conn;
        public function __construct(){
            $this->conn = new DbConnect();
        }
        
        public function get_imprensa($fields = FALSE){
            if($fields === FALSE || !is_array($fields)){
                $fields = array('id', 'nome', 'email');
            }
            $fields = implode(',', $fields);
            
            $rs = mysql_query("select distinct $fields from cadastro_imprensa where 1 order by id;");
            if($rs && mysql_num_rows($rs)){
                $arr = array();
                while($cursor = mysql_fetch_object($rs)){
                    $arr[] = $cursor;
                }
                return $arr;
            }
            return FALSE;
        }
        
        public function __destruct(){
            $this->conn->close();
        }
    }
?>