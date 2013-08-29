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
    
    class Cache {
        private $diretorio;
        private $tempo;
        private $arquivo;
        private $maxTamanho;
        private $caching;
        public $cached;
        public function __construct() {
            $this->diretorio = $_SERVER['DOCUMENT_ROOT'] . '/_cache/';
            $this->set_arquivo($_SERVER['REQUEST_URI']);
            $this->set_tempo(30);
            $this->caching = FALSE;
            $this->cached = FALSE;
        }
        public function set_arquivo($arquivo){
            $this->arquivo = $this->diretorio . md5(urlencode($arquivo)) . '.tpl';
        }
        public function set_tempo($tempo){
            $this->tempo = $tempo * 60;
        }
        public function start(){
            if(file_exists($this->arquivo) && (time() - $this->tempo < filemtime($this->arquivo))) {
                $this->cached = TRUE;
                $content = @file_get_contents($this->arquivo);
                echo strlen($content) > 0 ? $content : '';
            } else {
                $this->caching = TRUE;
                ob_start();
            }
        }
        public function close() {
            if($this->caching) {
                $content = ob_get_clean();
                //$content = preg_replace('/\r+|\n+|\t+/', '', $content);
                echo $content;
                file_put_contents($this->arquivo, $content);
            }
        }
    }
?>