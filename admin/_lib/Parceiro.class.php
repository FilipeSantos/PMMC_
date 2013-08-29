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
    
    class Parceiro {
        private $id = FALSE;
        private $nome;
        private $descricao;
        private $desconto;
        private $endereco;
        private $telefone;
        private $email;
        private $site;
        private $status;
        private $tipos = array();
        
        public function __construct(){}
        
        public function set_id($id = FALSE){
            $this->id = $id && is_numeric($id) ? $id : FALSE;
        }
        
        public static function get_total($ativos = FALSE){
            if($ativos){
                $cond = 'status = 1';
            } else {
                $cond = '1';
            }
            $conn = new DbConnect();
            if($rs = mysql_query("select id from parceiro where $cond;")){
                return mysql_num_rows($rs);
                $conn->close();
            }
        }
        
        public static function get_servicos(){
            $conn = new DbConnect();
            $rs = mysql_query("select id, titulo from parceiro_tipo where 1 order by titulo;");
            if($rs && mysql_num_rows($rs)){
                $arr = array();
                while($item = mysql_fetch_object($rs)){
                    $arr[] = (object) array_map('stripslashes', (array) $item);
                }
                $conn->close();
                return $arr;
            }
            $conn->close();
            return FALSE;            
        }
        
        public static function delete_servico($ids = FALSE){
            if($ids && is_array($ids) && !empty($ids)){
                $conn = new DbConnect();
                foreach($ids as $id){
                    $rs = mysql_query("delete from parceiro_parceirotipo where idTipo = $id;") or die('{"status":"error"}');
                    $rs = mysql_query("delete from parceiro_tipo where id = $id;") or die('{"status":"error"}');
                }
                $conn->close();
                return TRUE;
            }
            $conn->close();
            return FALSE;
        }
        
        public static function adicionar_servico($titulo){
            $conn = new DbConnect();
            $rs = mysql_query("select id from parceiro_tipo where titulo = '$titulo' limit 1;");
            if($rs !== FALSE && !mysql_num_rows($rs)){
                if($rs = mysql_query("insert into parceiro_tipo(titulo, dataCadastro) values('$titulo', CURRENT_TIMESTAMP);")){
                    return '{"status":"ok","id":"' . mysql_insert_id() . '"}';
                } else {
                    return '{"status":"error","msg":"' . mysql_error() .'"}';
                }
            } else {
                $id = mysql_result($rs, 0, 'id');
                return '{"status":"existe","id":"' . $id . '"}';
            }
        }
        
        public function set_parceiro($nome, $descricao, $desconto, $endereco, $telefone, $email, $site, $status, $tipos){
            $valida = new Valida();
            $valida->notNull(array($nome, $descricao, $desconto));
            if($valida->totalErros){
                echo '{"status":"erro","info":[' . substr($valida->descErros, 0, -2) . ']}';
                exit();
            }
            
            if(!empty($email)){
                if(filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE){
                    echo '{"status":"erro","info":"email"}';
                }
            }
            
            
            if(!empty($site)){
                if(filter_var($site, FILTER_VALIDATE_URL) === FALSE){
                    echo '{"status":"erro","info":"url"}';
                }
            }
            
            $this->nome = isset($nome) && !empty($nome) ? "'" . trim(htmlspecialchars(addslashes($nome))) . "'" : 'NULL';
            $this->descricao = isset($descricao) && !empty($descricao) ? "'" . trim(htmlspecialchars(addslashes($descricao))) . "'" : 'NULL';
            $this->desconto = isset($desconto) && !empty($desconto) ? "'" . trim(htmlspecialchars(addslashes($desconto))) . "'" : 'NULL';
            $this->endereco = isset($endereco) && !empty($endereco) ? "'" . trim(htmlspecialchars(addslashes($endereco))) . "'" : 'NULL';
            $this->telefone = isset($telefone) && !empty($telefone) ? "'" . trim(htmlspecialchars(addslashes($telefone))) . "'" : 'NULL';
            $this->email = isset($email) && !empty($email) ? "'" . trim(htmlspecialchars(addslashes($email))) . "'" : 'NULL';
            $this->site = isset($site) && !empty($site) ? "'" . trim(htmlspecialchars(addslashes($site))) . "'" : 'NULL';
            $this->status = isset($status) && is_numeric($status) ? (integer) $status : 0;

            if($tipos){
                foreach($tipos as $i){
                    if(!is_numeric($i)){
                        echo '{"status":"erro","info":"tipos"}';
                        exit();
                    }
                    $this->tipos[] = $i;
                }
            }
        }

        public function salvar(){
            $conn = new DbConnect();
            if($this->id){
                if($rs = mysql_query("update `parceiro` set nome = $this->nome, descricao = $this->descricao, desconto = $this->desconto,
                                     endereco = $this->endereco, telefone = $this->telefone, email = $this->email, site = $this->site,
                                     status = $this->status where id = $this->id limit 1;")){
                    
                    $rs = mysql_query("delete from parceiro_parceirotipo where idParceiro = $this->id;") or die('{"status":"erro","info":"update"}');

                    if($this->tipos){
                        foreach($this->tipos as $categ){
                            $rs = mysql_query("insert into parceiro_parceirotipo(idTipo, idParceiro) values($categ, $this->id);") or die('{"status":"erro","info":"insert"}');
                        }
                    }
                    
                    echo '{"status":"ok"}';
                } else {
                    echo '{"status":"error","info":"update"}';
                }
            } else {
                if($rs = mysql_query("insert into `parceiro`(nome, descricao, desconto, endereco, telefone, email, site, dataCadastro, status, idCriador)
                                  values($this->nome, $this->descricao, $this->desconto, $this->endereco, $this->telefone, $this->email,
                                  $this->site, CURRENT_TIMESTAMP, $this->status, $_SESSION[idUser]);")){
                    
                    $this->id = mysql_insert_id();

                    if($this->tipos){
                        foreach($this->tipos as $categ){
                            $rs = mysql_query("insert into parceiro_parceirotipo(idTipo, idParceiro) values($categ, $this->id);") or die('{"status":"erro","info":"insert"}');
                        }
                    }
                    echo '{"status":"ok"}';
                } else {
                    echo '{"status":"erro","info":"insert"}';
                }
            }
            $conn->close();
        }

        public static function get_parceiro($id = FALSE, $pub = TRUE, $retTipos = FALSE, $limit = '', $tipos = array(), $conds = array()){
            $conn = new DbConnect();
            $itensCond = '';
            $itensCondJoin = '';
            $arr = array();
            
            if($pub !== FALSE){
                $itensCond = $itensCond . "status = $pub";
            }

            if(is_array($tipos) && !empty($tipos)){
                $itensCondJoin = 'inner join parceiro_parceirotipo as b on a.id = b.idParceiro and b.idTipo in (' . implode(', ', $tipos) . ') ';
            }

            if(is_array($conds) && !empty($conds)){
                $itensCond = $itensCond . implode(' and ', $conds);
            }

            if($rs = mysql_query("select a.id, a.nome, a.descricao, a.desconto, a.endereco, a.telefone, a.email, a.site, a.status
                                 from parceiro as a " . (!empty($itensCondJoin) ? ($id !== FALSE ? ($itensCondJoin . ' and id = ' . $id) : $itensCondJoin) : ($id !== FALSE ? 'where id = ' . $id : 'where 1')) . " " . ($pub === FALSE ? '' : 'and ' . $itensCond) . " order by id desc $limit;")){
                if(mysql_num_rows($rs)){
                    $i = 0;
                    while($item = mysql_fetch_object($rs)){
                        $arr[$i] = (object) array_map('stripslashes', (array) $item);
                        $arr[$i]->servicos = array();
                        if($retTipos){
                            if($rsTipo = mysql_query("select a.id, a.titulo from parceiro_tipo as a
                                                     inner join parceiro_parceirotipo as b on a.id = b.idTipo and b.idParceiro = $item->id;")){
                                if(mysql_num_rows($rsTipo)){
                                    while($itemTipo = mysql_fetch_object($rsTipo)){
                                        $arr[$i]->servicos[] = (object) array_map('stripslashes', (array) $itemTipo);
                                    }
                                }
                            }
                        }
                        $i++;
                    }
                }
            }
            
            $conn->close();
            if(!empty($arr)){
                return $arr;
            }
            return FALSE;
        }

        public function set_status($status = FALSE){
            $conn = new DbConnect();
            if($this->id && $status !== FALSE){
                if($rs = mysql_query("update parceiro set status = $status where id = $this->id;")){
                    $conn->close();
                    return TRUE;
                } else {
                    $conn->close();
                    return FALSE;
                }
            }            
        }
        
        public function excluir(){
            $conn = new DbConnect();
            if($this->id){
                if($rs = mysql_query("delete from parceiro_parceirotipo where idParceiro = $this->id;")){
                    if($rs = mysql_query("delete from parceiro where id = $this->id;")){
                        $conn->close();
                        return TRUE;
                    }
                } else {
                    $conn->close();
                    return FALSE;
                }
            } else {
                $conn->close();
                return FALSE;
            }
        }
    }
?>