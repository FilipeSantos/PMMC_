<?php
    class Login{
        private $user;
        private $pass;
        private $timeout = 3600;
        public function __construct(){
            require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/db.class.php');
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
            $this->pass = md5($pass);
        }
        
        public function logar(){
            $conn = new DbConnect();
            $rs = mysql_query("select id from usuario where nome = '" . $this->user . "' and senha = '" . $this->pass . "' limit 0,1");
            if(mysql_numrows($rs)){
                $idUser = mysql_result($rs, 0, 'id');
                $sId = session_id();
                if(mysql_query("insert into log_acesso(idUsuario, idSession, ip) values(" . $idUser . ", '" . $sId . "', '" . $_SERVER["REMOTE_ADDR"] . "');")){
                    $_SESSION['user'] = $this->user;
                    $_SESSION['idUser'] = mysql_insert_id();
                    $_SESSION['timeout'] = time();
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
            $conn = NULL;
        }
        
        public function verificaLogin(){
            if(isset($_SESSION['user']) && isset($_SESSION['idUser']) && isset($_SESSION['timeout'])){
                $sessionLive = time() - $_SESSION['timeout'];
                if($sessionLive > $this->timeout){
                    session_destroy();
                    return FALSE;
                } else {
                    return TRUE;
                }
            } else {
                return FALSE;
            }
        }
        
        public function atualizaSession(){
            if($this->verificaLogin()){
                $_SESSION['timeout'] = time();
            }
        }
    }
?>