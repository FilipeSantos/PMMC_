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
    
    class Usuario{
        protected $conn;
        private $userId = FALSE;
        private $nome;
        private $senha;
        private $senhaOrigin;
        private $novaSenha;
        private $email;
        private $capability;
        private $cpf;
        private $telefone;
        private $celular;
        private $empresa;
        private $status;
        private $senhaNova = FALSE;
        public function __construct(){
            $this->conn = new DbConnect();
        }
        public function valida_usuario($nome, $senha, $novaSenha, $email, $capability = 3, $cpf, $telefone = FALSE, $celular = FALSE, $empresa = FALSE, $status = 0){
            $valida = new Valida();
            $validaNull = array($nome, $cpf);
            
            if(!$this->userId){
                $validaNull = array($senha, $email, $capability, $status);
                $valida->email($email);
                $valida->campo_unico($email, 'usuario', 'email', ($this->userId ? $this->userId : FALSE));
            } else{
                $email = NULL;
                if(!empty($senha)){
                    array_push($validaNull, $novaSenha);
                }
                if($_SESSION['capability'] >= 2){
                    $capability = NULL;
                    $status = NULL;
                } else {
                    array_push($validaNull, $capability, $status);
                }
            }
            
            $valida->notNull($validaNull);            
            $valida->cpf($cpf);
            $valida->campo_unico($cpf, 'usuario', 'cpf', ($this->userId ? $this->userId : FALSE));
            if($valida->totalErros){
                echo '{"status":"erro","info":[' . substr($valida->descErros, 0, -2) . ']}';
                exit();
            } else {
                $this->set_usuario($nome, $senha, $novaSenha, $email, $capability, $cpf, $telefone, $celular, $empresa, $status);
            }
        }
        public function set_user_id($id){
            $this->userId = $id;
        }
        protected function set_usuario($nome, $senha, $novaSenha, $email, $capability = 3, $cpf, $telefone = FALSE, $celular = FALSE, $empresa = FALSE, $status = 0){
            $salt = 'j_OgoS%';
            $this->nome = isset($nome) && !empty($nome) ? "'" . trim(htmlspecialchars(addslashes($nome))) . "'" : 'NULL';
            $this->email = isset($email) && !empty($email) ? "'" . trim(htmlspecialchars(addslashes($email))) . "'" : 'NULL';
            $this->capability = isset($capability) && is_numeric($capability) ? addslashes($capability) : 3;
            $this->cpf = isset($cpf) && !empty($cpf) ? "'" . trim(htmlspecialchars(addslashes($cpf))) . "'" : 'NULL';
            $this->telefone = isset($telefone) && !empty($telefone) ? "'" . trim(htmlspecialchars(addslashes($telefone))) . "'" : 'NULL';
            $this->celular = isset($celular) && !empty($celular) ? "'" . trim(htmlspecialchars(addslashes($celular))) . "'" : 'NULL';
            $this->empresa = isset($empresa) && !empty($empresa) ? "'" . trim(htmlspecialchars(addslashes($empresa))) . "'" : 'NULL';
            $this->status = isset($status) && is_numeric($status) ? addslashes($status) : 0;
            if($this->userId){
                $this->senha = isset($senha) && !empty($senha) ? "'" . md5($salt . $senha) . "'" : FALSE;
                $this->novaSenha = isset($novaSenha) && !empty($novaSenha) ? "'" . md5($salt . $novaSenha) . "'" : FALSE;

                if($this->senha && $this->novaSenha){
                    $rs = mysql_query("update usuario set senha = $this->novaSenha where id = $this->userId and senha = $this->senha limit 1;");
                    if(!mysql_affected_rows()){
                        echo '{"status":"erro","info":"senha"}';
                        exit();
                    } else {
                        $this->senhaOrigin = isset($novaSenha) && !empty($novaSenha) ? $novaSenha : FALSE;
                    }
                }
            } else {
                $this->senha = isset($senha) && !empty($senha) ? "'" . md5($salt . $senha) . "'" : 'NULL';
                $this->senhaOrigin = isset($senha) && !empty($senha) ? $senha : FALSE;
            }
        }
        public function get_usuario($limit = ''){
            $cond = ($this->userId == 'all') ? 'and 1 order by data_cadastro' : "and a.id = $this->userId limit 1";
            $arr = array();
            if($rs = mysql_query("select a.id, a.nome, a.cpf, a.email, a.telefone, a.celular, a.empresa, b.titulo as perfil, a.idPerfil as idPerfil, a.status from usuario as a
                                 inner join usuario_perfil as b on a.idPerfil = b.id $cond $limit;")){
                while($item = mysql_fetch_object($rs)){
                    $arr[] = $item;
                }
                return $arr;
            }
        }
        
        public function get_total(){
            $conn = new DbConnect();
            $rs = mysql_query("select count(*) as total from usuario where 1;");
            return mysql_result($rs, 0, 'total');
        }
        
        public function get_ativos(){
            $conn = new DbConnect();
            $rs = mysql_query("select count(*) as total from usuario where `status` = 1;");
            return mysql_result($rs, 0, 'total');
        }
        
        public function salvar(){
            if(!$this->userId){
                if($rs = mysql_query("insert into usuario(idCriador, nome, cpf, email, telefone, celular, empresa, idPerfil, status, senha, data_cadastro)
                                  values($_SESSION[idUser], $this->nome, $this->cpf, $this->email, $this->telefone, $this->celular, $this->empresa, $this->capability,
                                  $this->status, $this->senha, CURRENT_TIMESTAMP);")){
                    $idUser =  mysql_insert_id();
                    if($this->status){
                        $rs = mysql_query("select titulo from usuario_perfil where id = $this->capability limit 1;");
                        $tipoUser = mysql_result($rs, 0, 'titulo');
                        $bodyEmail = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/admin/_email/Cadastro_Usuario/disparo.html');
			$bodyEmail = str_replace('{_URL_}', 'http://' . $_SERVER['HTTP_HOST'], $bodyEmail);
                        $bodyEmail = str_replace('{_NOME_}', str_replace('\'', '', $this->nome), $bodyEmail);
                        $bodyEmail = str_replace('{_TIPO_}', $tipoUser, $bodyEmail);
                        $bodyEmail = str_replace('{_EMAIL_}', str_replace('\'', '', $this->email), $bodyEmail);
                        $bodyEmail = str_replace('{_SENHA_}', $this->senhaOrigin, $bodyEmail);
                        
                        $mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->From = 'comunicacao.smel@pmmc.com.br';
			$mail->FromName = 'Jogos Abertos do Interior';
		 	$mail->AddAddress(trim($this->email, '\''));
			$mail->IsHTML(true);
			$mail->CharSet = 'utf-8';
			$mail->Subject = '[Jogos Abertos] Cadastro de usuário';
                        $mail->Body = $bodyEmail;
                        $mail->Send();
                    }
                    exit('{"status":"ok","id":"' . $idUser .'"}');
                } else {
                    exit('{"status":"erro"}');
                }
            } else {
                if($_SESSION['capability'] === '1'){
                    $query = "update usuario set nome = $this->nome, cpf = $this->cpf, telefone = $this->telefone, celular = $this->celular,
                            empresa = $this->empresa, idPerfil = $this->capability, status = $this->status where id = $this->userId limit 1;";
                } else {
                    $this->userId = $_SESSION['idUser'];
                    $query = "update usuario set nome = $this->nome, cpf = $this->cpf, telefone = $this->telefone, celular = $this->celular,
                            empresa = $this->empresa where id = $this->userId limit 1;";
                }
                
                if($rs = mysql_query($query)){
                    $_SESSION['user'] = str_replace('\'', '', $this->nome);
                    echo '{"status":"ok","id":"' . $this->userId .'"}';
                } else {
                    echo '{"status":"erro"}';
                }
            }
        }
    }
?>