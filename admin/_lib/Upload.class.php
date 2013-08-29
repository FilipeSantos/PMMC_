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
    
    class Upload {
        private $arquivo;
        private $tipo = array();
        private $nome;
        private $tamanho;
        private $mimeType = array();
        private $temp;
        private $upload;
        
        public function __construct(){
            $this->set_mimeType();
        }
        
        public function set_dir_temp($dir){
            $this->temp = $_SERVER['DOCUMENT_ROOT'] . $dir . '/';
        }
        
        public function set_dir_upload($dir){
            $this->upload = $_SERVER['DOCUMENT_ROOT'] . $dir . '/';
        }
        
        private function set_mimeType(){
            $this->mimeType = array(
                'txt' => 'text/plain',
                'htm' => 'text/html',
                'html' => 'text/html',
                'php' => 'text/html',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'json' => 'application/json',
                'xml' => 'application/xml',
                'swf' => 'application/x-shockwave-flash',
                'flv' => 'video/x-flv',
    
                // images
                'png' => 'image/png',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'gif' => 'image/gif',
                'bmp' => 'image/bmp',
                'ico' => 'image/vnd.microsoft.icon',
                'tiff' => 'image/tiff',
                'tif' => 'image/tiff',
                'svg' => 'image/svg+xml',
                'svgz' => 'image/svg+xml',
    
                // archives
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                'exe' => 'application/x-msdownload',
                'msi' => 'application/x-msdownload',
                'cab' => 'application/vnd.ms-cab-compressed',
    
                // audio/video
                'mp3' => 'audio/mpeg',
                'qt' => 'video/quicktime',
                'mov' => 'video/quicktime',
    
                // adobe
                'pdf' => 'application/pdf',
                'psd' => 'image/vnd.adobe.photoshop',
                'ai' => 'application/postscript',
                'eps' => 'application/postscript',
                'ps' => 'application/postscript',
    
                // ms office
                'doc' => 'application/msword',
                'rtf' => 'application/rtf',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',
    
                // open office
                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
                
                'all' => 'application/octet-stream',
            );
        }
        
        public function set_valid_mimeType($tipos){
            if($tipos == 'ALL'){
                $this->tipo = $this->mimeType;
            } else {
                foreach($tipos as $tipo){
                    $this->tipo[$tipo] = $this->mimeType[$tipo];
                }
            }
        }
        
        public function set_tamanho($size){
            $this->tamanho = $size * 1024;
        }
        
        public function set_arquivo($file = FALSE, $title = FALSE){
            if(isset($file) && !empty($file)){
                $this->arquivo = $file;
                $ext = strrchr($file['name'],'.');
                $nome = str_replace($ext, '', $file['name']);
                $slug = new Slug();
                $slug->noRepeat = TRUE;
                $slug->directory = $this->temp;
                $slug->replace = '_';
                $slug->SetSlug(utf8_decode($nome), $ext);
                $this->nome = $slug->GetSlug() . $ext;
                $tipo = $file['type'];
                $tamanho = $file['size'];
                if(array_search($tipo, $this->tipo) === FALSE || $tamanho > $this->tamanho){
                    return false;
                } else {
                    return true;
                }
            } elseif($title){
                $this->nome = $title;
            } else {
                return false;
            }
        }
        
        public function salvar($tipo = 'upload', $nome = ''){
            if($tipo == 'temp'){
                if (move_uploaded_file($this->arquivo['tmp_name'], $this->temp . $this->nome)){
                    return $this->nome;
                } else {
                    return false;
                }
            } elseif($tipo == 'upload' && !empty($nome)) {
                if(@rename($this->temp . $nome, $this->upload . $nome) !== FALSE){
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
?>