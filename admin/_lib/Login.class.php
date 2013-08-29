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
    
    class Login{
        public $userAsEmail = FALSE;
        private $user;
        private $pass;
        private $timeout = 3600;
        private $capability;
        public function __construct(){
            session_start();
            if(!$this->verificaLogin()){
                session_destroy();
                session_id(md5('session_adm' . time()));
                session_start();
            }
        }
        
        public function set_user($user){
            $this->user = addslashes($user);
        }
        
        public function set_pass($pass){
            $salt = 'j_OgoS%';
            $this->pass = md5($salt . $pass);
        }
        
        public function verify_email(){
            $pattern = '/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i';
            if(preg_match($pattern, $this->user) !== 0){
                return TRUE;
            } else {
                return FALSE;
            }
        }
        
        public function logar(){
            $conn = new DbConnect();
            $rs = mysql_query("select nome, id, idPerfil from usuario where email = '" . $this->user . "' and senha = '" . $this->pass . "' limit 1;");
            if(mysql_numrows($rs)){
                $idUser = mysql_result($rs, 0, 'id');
                $nomeUser = mysql_result($rs, 0, 'nome');
                $capabilityUser = mysql_result($rs, 0, 'idPerfil');
                $sId = session_id();
                if(mysql_query("insert into log_acesso(idUsuario, idSession, ip) values(" . $idUser . ", '" . $sId . "', '" . $_SERVER["REMOTE_ADDR"] . "');")){
                    $_SESSION['user'] = $nomeUser;
                    $_SESSION['idUser'] = $idUser;
                    $_SESSION['timeout'] = time();
                    $_SESSION['capability'] = $capabilityUser;
                    echo '{"status":"ok"}';
                } else {
                    session_destroy();
                    echo '{"status":"erro","msg":"log"}';
                }
            } else {
                session_destroy();
                echo '{"status":"erro","msg":"user-passwd"}';
            }
            $conn->close();
        }
        
        public function deslogar(){
            session_unset();
            session_destroy();
        }
        
        public function verificaLogin(){
            if(isset($_SESSION['user']) && isset($_SESSION['idUser']) && isset($_SESSION['timeout']) && isset($_SESSION['capability'])){
                $sessionLive = time() - $_SESSION['timeout'];
                if($sessionLive > $this->timeout){
                    session_unset();
                    session_destroy();
                    return FALSE;
                } else {
                    return TRUE;
                }
            } else {
                return FALSE;
            }
        }
        
        function get_capability(){
            return $_SESSION['capability'];
        }
        
        public function atualizaSession(){
            if($this->verificaLogin()){
                $_SESSION['timeout'] = time();
            }
        }
        
        public function resetarSenha($referer){
            $verifyReferer = explode('?', (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''));
            if($verifyReferer[0] == $referer){
                if($email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : FALSE){
                    if($this->verify_email($_POST['email'])){
                        $this->set_user($_POST['email']);
                        $conn = new DbConnect();
                        $rs = mysql_query("select count(*) from usuario where email = '$this->user' limit 0,1;") or die('{"status":"erro","info":"' . mysql_error() .'"}');
                        if(mysql_num_rows($rs)){
                            // Aqui
                        } else {
                            echo '{"status":"erro","info":"email-nao-cadastrado"}';
                        }
                    }
                } else {
                    echo '{"status":"erro","info":"email-nao-informado"}';
                }
            } else {
                echo '{"status":"erro","info":"referer"}';
            }
        }
    }
?>