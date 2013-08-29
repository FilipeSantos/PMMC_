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

    class Valida{
        public $totalErros;
        public $descErros = '';
        public function __construct(){
            $this->totalErros = 0;
        }
        private function erro($err = FALSE){
            $this->totalErros += 1;
            $this->descErros = $this->descErros . "\"$err\", ";
        }
        public function email($val){
            if(isset($val) && !empty($val)){
                $pattern = "/^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\._-]+.([a-zA-Z]{2,4})$/";
                if(!preg_match($pattern, $val)){
                    $this->erro('email');
                }
            }
        }
        public function tel($val){
            if($this->notNull($val)){
                $pattern = "/^\(\d{2}\)\s\d{4}-\d{4}$/";                
                if(!preg_match($pattern, $val)){
                    $this->erro('tel');
                }
            }
        }
        public function cpf($val){
            if(isset($val) && !empty($val)){
                $pattern = "/^\d{3}\.\d{3}\.\d{3}-\d{2}$/";
                if(!preg_match($pattern, $val)){
                    $this->erro('cpf');
                } else {
                    $val = str_replace('.', '', $val);
                    $cpf = str_replace('-', '', $val);
                    //echo $cpf;
                    if(($cpf == '11111111111') || ($cpf == '22222222222') || ($cpf == '33333333333') || ($cpf == '44444444444') || ($cpf == '55555555555') || ($cpf == '66666666666') || ($cpf == '77777777777') || ($cpf == '88888888888') || ($cpf == '99999999999') || ($cpf == '00000000000')) {
                        $this->erro('cpf-inválido');
                    } else {
                        $dv_informado = substr($cpf, 9,2);
                        for($i = 0; $i <= 8; $i++){
                            $digito[$i] = substr($cpf, $i,1);
                        }
                        
                        $posicao = 10;
                        $soma = 0;
                        for($i = 0; $i <= 8; $i++){
                            $soma = $soma + $digito[$i] * $posicao;
                            $posicao--;                     
                        }
                        $digito[9] = $soma % 11;
                        $digito[9] = ($digito[9] < 2) ? 0 : (11 - $digito[9]);

                        $posicao = 11;
                        $soma = 0;
                        for ($i = 0; $i <= 9; $i++){
                            $soma = $soma + $digito[$i] * $posicao;
                            $posicao--;
                        }
                        $digito[10] = $soma % 11;
                        $digito[10] = ($digito[10] < 2) ? 0 : (11 - $digito[10]);

                        if(($digito[9] * 10 + $digito[10]) != $dv_informado){
                            $this->erro('cpf-inválido');
                        }

                    }
                }
            }
        }
        public function tipoVideo($val){
            if(!empty($val)){
                if(stripos($val, 'youtube.com') !== FALSE || stripos($val, 'youtu.be') !== FALSE){
                    return 'youtube';   
                } elseif(stripos($val, 'vimeo.com') !== FALSE){
                    return 'vimeo';
                } else {
                    return 'NULL';
                }
            }
        }
        public function urlVideo($val, $id = true){
            if(!empty($val)){
                $val = urldecode($val);
                if(stripos($val, 'youtube.com') !== FALSE || stripos($val, 'youtu.be') !== FALSE){
                    if($id){
                        if(stripos($val,'watch?') !== FALSE){
                            $test = explode('?v=', $val);
                            if(!isset($test[1])){
                                $test = explode('&v=', $val);
                            }
                            $id = explode('&', $test[1]);
                            if(!$id[0]){
                                $test = explode('&v=', $val);
                                if(isset($test[1])){
                                    $id = explode('&', $test[1]);
                                }
                                if(!$id[0]){
                                    $test = explode('/', $val);
                                    $id = end($test);
                                }
                            }
                        }
                        if(strlen($id[0]) != 11){
                            $this->erro('youtube');
                        } else {
                            return $id[0];
                        }
                    }
                } elseif(stripos($val, 'vimeo.com') !== FALSE){
                    if($id){
                        $test = explode('vimeo.com/', $val);
                        $id = array_pop($test);
                        if(is_numeric($id)){
                            return $id;
                        } else {
                            $this->erro('youtube');
                        }
                    }
                } else {
                    $this->erro('youtube');
                }
            }
        }
        public function notNull($str = array()){
            if(is_array($str)){
                foreach($str as $item){
                    if(!isset($item) || (empty($item) && $item === FALSE)){
                        $this->erro('not-null');
                        return false;
                    }
                }
            } else {
                if(!isset($str) || empty($str) || $str === FALSE){
                    $this->erro('not-null');
                    return false;
                }
            }            
            return true;
        }
        public function campo_unico($val, $tbl, $ent, $idItem = FALSE){
            $conn = new DbConnect();
            $cond = $idItem ? "and id != $idItem"  : "";
            if($rs = mysql_query("select $ent from $tbl where $ent = '$val' $cond limit 1;")){
                if(mysql_num_rows($rs)){
                     $this->erro("$ent-cadastrado");
                }
            }
        }
    }
?>