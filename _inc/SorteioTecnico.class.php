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
    
    class SorteioTecnico {
        private $divisao = array();
        protected $validFiles = array();
        private $ignore;
        protected $conn;

        public function __construct($ignore = FALSE){
            $this->conn = new DbConnect();
            if($ignore === TRUE){
                $this->ignore = "and b.id not in ('Nat1lmas', 'Nat1lfem', 'Nat2lmas', 'Nat2lfem', 'Natpcd1lmas', 'Natpcd1lfem', 'Natpcd2lmas', 'Natpcd2lfem',
                            'Atl1lfem', 'Atl1lmas', 'Atl2lmas', 'Atl2lfem', 'Atlpcd1lmas', 'Atlpcd1lfem', 'Atlpcd2lmas', 'Atlpcd2lfem')";
            } else {
                $this->ignore = '';
            }
            for($count = 1; $count <= 4; $count++){
                $arr = array();
                
                switch($count){
                    case 1:
                        $query = "SELECT a.id, a.titulo, a.slug, b.sexo, b.sigla_pdf, b.idade from modalidade as a 
				inner join modalidade_sub as b on a.id = b.modalidade and b.divisao = 1 $this->ignore
				order by a.titulo, b.sexo, b.idade;";
                        break;
                    case 2:
                        $query = "SELECT a.id, a.titulo, a.slug, b.sexo, b.sigla_pdf, b.idade from modalidade as a
				inner join modalidade_sub as b on a.id = b.modalidade and b.divisao = 2 $this->ignore
				order by a.titulo, b.sexo, b.idade;";
                        break;
                    case 3:
                        $query = "SELECT a.id, a.titulo, a.slug, b.sexo, b.sigla_pdf, b.idade from modalidade as a
				inner join modalidade_sub as b on a.id = b.modalidade and b.divisao = 3 $this->ignore
				and b.pcd = 0 order by a.titulo, b.sexo, b.idade;";
                        break;
                    case 4:
                        $query = "SELECT a.id, a.titulo, a.slug, b.sexo, b.sigla_pdf, b.divisao, b.idade from modalidade as a
				inner join modalidade_sub as b on a.id = b.modalidade and b.pcd = 1 $ignore
				order by b.divisao, a.titulo, b.sexo, b.idade;";
                }
                
                if(isset($query) && !is_null($query)){
                    $rs = mysql_query($query);
                }
                
                
                if(!$rs or !mysql_num_rows($rs)){
                    continue;
                }
                
                while($cursor = mysql_fetch_object($rs)){
                    $arr[] = $cursor;
                }
                
                $this->conn->free($rs);
                $this->SetDivisao($count, $arr);
            }
        }

        private function SetDivisao($tipo, $arr){
            $index = '';
            
            if(!is_integer($tipo) or !$tipo or $tipo > 4 or !is_array($arr) or empty($arr)){
                return FALSE;
            }
            
            switch($tipo){
                case 1:
                    $index = 'primeira';
                    break;
                case 2:
                    $index = 'segunda';
                    break;
                case 3:
                    $index = 'especial';
                    break;
                case 4:
                    $index = 'pcd';
            }

            if(empty($index)){
                return FALSE;
            }

            $this->divisao[$index] = $arr;
        }
        
        public function GetDivisao($index){
            if(!isset($index) or !isset($this->divisao[$index])){
                return FALSE;
            }
            return $this->divisao[$index];
        }
        
        public function HasFile($file){
            if(isset($file)){
                if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/upload/sorteio_tecnico/' . $file . '.PDF') || file_exists($_SERVER['DOCUMENT_ROOT'] . '/upload/sorteio_tecnico/' . $file . '.pdf')){
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        }
        
        public function GetLoadedFiles($directory = NULL, $dateInit = NULL){
            $files = $arrName = $arrTimestamp = array();
            
            if($directory === NULL){
               $directory = $_SERVER['DOCUMENT_ROOT'] . '/upload/sorteio_tecnico/';
            }
            
            $iterator = new DirectoryIterator($directory);
            foreach($iterator as $i=>$fileinfo){
                if(!$fileinfo->isFile() || $fileinfo->isDot()){
                    continue;
                }
                
                $name = $fileinfo->getFilename();
                $timestamp = $fileinfo->getMTime();
                if($dateInit !== NULL && is_numeric($dateInit)){
                    if($timestamp <= $dateInit){
                        continue;
                    } else {
                        //$dateInit = $timestamp;
                    }
                }
                $arrExt = explode('.', $name);
                $ext = (strtolower(array_pop($arrExt)) == 'pdf');
                $nameUse = join($arrExt);
                unset($arrExt);

                $validName = $this->ValidFileName($nameUse);

                if(!$ext || !$validName){
                    continue;
                }
                
                $files[$nameUse] = $timestamp;
            }
            
            if(empty($files)){
                $files = FALSE;
            } else {
                array_multisort($files, SORT_DESC);            
                $this->GetInfosFile($files);
                $this->GetFormatInfosFile($files);
            }
            return $files;
        }
        
        private function ValidFileName($str = NULL){
            if($str === NULL){
                return FALSE;
            }
            if(empty($this->validFiles)){
                $rs = mysql_query("select b.sigla_pdf from modalidade_sub as b where 1 $this->ignore order by sigla_pdf;");
                if($rs && mysql_num_rows($rs)){
                    while($row = mysql_fetch_assoc($rs)){
                        $this->validFiles[] = $row['sigla_pdf'];
                    }
                } else {
                    return FALSE;
                }
            }
            return in_array($str, $this->validFiles);
        }
        
        private function GetInfosFile(&$files){
            if(empty($files) || !is_array($files) || empty($files) || !is_array($files)){
                return FALSE;
            }
            
            $arrTemp = array();
            $arrName = '\'' . implode('\',\'', array_keys($files)) . '\'';

            $rs = mysql_query("select b.sigla_pdf, a.titulo as modalidade, a.id as id_modalidade, a.slug, b.sexo, b.divisao, b.idade, b.pcd from modalidade as a
                              inner join modalidade_sub as b on a.id = b.modalidade and b.sigla_pdf in ($arrName);");
            if($rs && mysql_num_rows($rs)){
                $cont = 0;
                while($row = mysql_fetch_assoc($rs)){
                    $indexTemp = array_shift($row);
                    $timestampTemp = $files[$indexTemp];
                    $files[$indexTemp] = $row;
                    $files[$indexTemp]['hora'] = $timestampTemp;
                }
            } else {
                $files = FALSE;
            }
        }
        
        private function GetFormatInfosFile(&$files){
            if(empty($files) || !is_array($files) || empty($files) || !is_array($files)){
                return FALSE;
            }
            
            foreach($files as $key=>$file){
                $files[$key]['sexo'] = (integer) $file['sexo'] === 1 ? 'Masculino' : ((integer) $file['sexo'] === 2 ? 'Feminino' : 'Misto');
                $files[$key]['timestamp'] = $file['hora'];
                $files[$key]['hora'] = date('H:i', ($file['hora'] + 540));
                $files[$key]['idade'] = (integer) $file['idade'] === 0 ? 'Livre' : 'Até ' . $file['idade'] . ' anos';
                $files[$key]['divisao'] = (integer) $file['divisao'] === 3 ? 'Divisão Especial' : $file['divisao'] . 'ª Divisão';
            }
        }
        
        public function __destruct(){
            $this->conn->close();
        }
    }
?>