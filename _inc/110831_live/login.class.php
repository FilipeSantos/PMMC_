<?php
    class Login(){
        private $user;
        private $pass;
        public $salt = FALSE;
        public $logado;
        public __construct(){
            
        }
        public dados($user, $pass){
            $this->user = addslashes($user);
            $this->pass = $this->salt ? md5($this->salt . $pass) : md5($pass);
        }
        public logar(){
            require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/db.class.php');
            $conn = new dbconnect();
        }
        public deslogar(){
            
        }
        public verificaLogin(){
            
        }
        public resetSenha(){

        }
        public error(){
            
        }
    }
?>