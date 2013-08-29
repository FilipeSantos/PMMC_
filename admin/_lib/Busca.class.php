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
    
    class Busca {
        protected $strSearch;
        protected $terms;
        public $total;
        protected $tipo;
        protected $conn;
        protected $stopWords;
        public $results;
        
        public function __construct($str){
            $this->tipo = $this->total = array();
            $this->Set_strSearch($str);
            //$this->stopWords = array('a', 'à', 'agora', 'ainda', 'alguém', 'algum', 'alguma', 'algumas', 'alguns', 'ampla', 'amplas', 'amplo', 'amplos', 'ante', 'antes', 'ao', 'aos', 'após', 'aquela', 'aquelas', 'aquele', 'aqueles', 'aquilo', 'as', 'até', 'através', 'cada', 'coisa', 'coisas', 'com', 'como', 'contra', 'contudo', 'da', 'daquele', 'daqueles', 'das', 'de', 'dela', 'delas', 'dele', 'deles', 'depois', 'dessa', 'dessas', 'desse', 'desses', 'desta', 'destas', 'deste', 'deste', 'destes', 'deve', 'devem', 'devendo', 'dever', 'deverá', 'deverão', 'deveria', 'deveriam', 'devia', 'deviam', 'disse', 'disso', 'disto', 'dito', 'diz', 'dizem', 'do', 'dos', 'e', 'é', 'e', 'ela', 'elas', 'ele', 'eles', 'em', 'enquanto', 'entre', 'era', 'essa', 'essas', 'esse', 'esses', 'esta', 'está', 'estamos', 'estão', 'estas', 'estava', 'estavam', 'estávamos', 'este', 'estes', 'estou', 'eu', 'fazendo', 'fazer', 'feita', 'feitas', 'feito', 'feitos', 'foi', 'for', 'foram', 'fosse', 'fossem', 'grande', 'grandes', 'há', 'isso', 'isto', 'já', 'la', 'la', 'lá', 'lhe', 'lhes', 'lo', 'mas', 'me', 'mesma', 'mesmas', 'mesmo', 'mesmos', 'meu', 'meus', 'minha', 'minhas', 'muita', 'muitas', 'muito', 'muitos', 'na', 'não', 'nas', 'nem', 'nenhum', 'nessa', 'nessas', 'nesta', 'nestas', 'ninguém', 'no', 'nos', 'nós', 'nossa', 'nossas', 'nosso', 'nossos', 'num', 'numa', 'nunca', 'o', 'os', 'ou', 'outra', 'outras', 'outro', 'outros', 'para', 'pela', 'pelas', 'pelo', 'pelos', 'pequena', 'pequenas', 'pequeno', 'pequenos', 'per', 'perante', 'pode', 'pôde', 'podendo', 'poder', 'poderia', 'poderiam', 'podia', 'podiam', 'pois', 'por', 'porém', 'porque', 'posso', 'pouca', 'poucas', 'pouco', 'poucos', 'primeiro', 'primeiros', 'própria', 'próprias', 'próprio', 'próprios', 'quais', 'qual', 'quando', 'quanto', 'quantos', 'que', 'quem', 'são', 'se', 'seja', 'sejam', 'sem', 'sempre', 'sendo', 'será', 'serão', 'seu', 'seus', 'si', 'sido', 'só', 'sob', 'sobre', 'sua', 'suas', 'talvez', 'também', 'tampouco', 'te', 'tem', 'tendo', 'tenha', 'ter', 'teu', 'teus', 'ti', 'tido', 'tinha', 'tinham', 'toda', 'todas', 'todavia', 'todo', 'todos', 'tu', 'tua', 'tuas', 'tudo', 'última', 'últimas', 'último', 'últimos', 'um', 'uma', 'umas', 'uns', 'vendo', 'ver', 'vez', 'vindo', 'vir', 'vos', 'vós');
            $this->stopWords = array();
            $this->Set_terms();            
            $this->conn = new DbConnect();
        }
        
        protected function Set_strSearch($str){
            $this->strSearch = isset($str) && strlen($str) >= 3 ? addslashes($str) : FALSE;
        }
        
        protected function Get_strSearch(){
            if(isset($this->strSearch) && !empty($this->strSearch)){
                return $this->strSearch;
            } else {
                return FALSE;
            }
        }
        
        protected function Set_terms(){
            if($this->Get_strSearch() !== FALSE){
                $arr = explode(' ', $this->Get_strSearch());
                foreach($arr as $i=>$item){
                    if(strlen($item) < 3 || in_array($item, $this->stopWords)){
                        unset($arr[$i]);
                    }
                }
                for($i=0; $i < $count=count($arr); $i++){
                    if(isset($arr[$i])){
                        $arr[$i] = mb_strtolower($arr[$i], 'utf8');
                    }                   
                }
                $this->terms = $arr;
            } else {
                $this->terms = FALSE;
            }
        }
        
        protected function Get_terms($isStr = FALSE){
            if(isset($this->terms) && !empty($this->terms) && is_array($this->terms)){
                if($isStr !== FALSE){
                    return implode(', ', $this->terms);
                } else {
                    return $this->terms;
                }
            } else {
                return FALSE;
            }
        }
        
        public function Add_tipo($tlt = FALSE, $tbl = FALSE, $columns = FALSE){
            if($tlt !== FALSE && $tbl !== FALSE && $columns !== FALSE && is_array($columns)){
                for($i=0; $i < count($columns); $i++){
                    $columns[$i]['nome'] = '`' . addslashes($columns[$i]['nome']) . '`';
                }
                $this->Set_tipo(array('tipo' => $tlt, 'tabela' =>  '`' . addslashes($tbl) . '`', 'colunas' => $columns));
            }
        }
        
        protected function Set_tipo($arr){
            if(is_array($arr) && !empty($arr)){
                array_push($this->tipo, $arr);
            }
        }
        
        public function Set_total(){
            $terms = $this->Get_terms();
            if(count($terms) === 2 && in_array('jogos', $terms) && in_array('abertos', $terms)){
                $all = TRUE;
            } else {
                $all = FALSE;
            }
            if($this->Get_terms(TRUE) !== FALSE){
                foreach($this->tipo as $tipos){
                    $this->total[$tipos['tipo']] = 0;
                }
                $this->total['total'] = 0;
                foreach($this->tipo as $infoTipo){
                    $query = $all ? "SELECT count(*) as total FROM $infoTipo[tabela] WHERE 1;" : "SELECT count(*) as total FROM $infoTipo[tabela] WHERE ";
                    if($all === FALSE){
                        $terms = $this->Get_terms(TRUE);
                        $query = $query . "MATCH (";
                        foreach($infoTipo['colunas'] as $column){
                            $query = $query . "$column[nome],";
                        }
                        $query = substr($query, 0, -1) . ") AGAINST('$terms')";
                    }
                    $rs = mysql_query($query);
                    $total = 0;
                    if($rs){
                        $total = mysql_result($rs, 0, 'total');
                    }
                    
                    if($total > 0){
                        $this->total[$infoTipo['tipo']] = $total;
                    } else {
                        $terms = $this->Get_terms();
                        $cond = '(';
                        foreach($terms as $term){
                            $cond = $cond . "categoria like '%$term%' or tags like '%$term%' or ";
                        }
                        $cond = substr($cond, 0, -4) . ")";
                        $query = "SELECT count(*) as total from search_$infoTipo[tipo] where $cond;";
                        $rs = mysql_query($query);
                        if($rs){
                            $total = mysql_result($rs, 0, 'total');
                            if($total > 0){
                                $this->total[$infoTipo['tipo']] = $total;
                            }
                        }
                    }
                }
                foreach($this->total as $total){
                    $this->total['total'] = $this->total['total'] + $total;
                }
            } else {
                $this->total = 0;
            }
        }
        
        public function Buscar($limit = ''){
            $terms = $this->Get_terms(TRUE);
            if(count($this->Get_terms()) === 2 && in_array('jogos', $this->Get_terms()) && in_array('abertos', $this->Get_terms())){
                $all = TRUE;
            } else {
                $all = FALSE;
            }            
            
            if($all === FALSE) {
                $query = "SELECT a.tipo, a.titulo, left(a.texto, 200) as texto, a.categoria, c.permalink as permalink_categ, c.categ as mod_categ, date_format(b.dataPublicacao, '%d\/%m\/%Y') as data, b.permalink, ((";
                foreach($this->tipo[0]['colunas'] as $column){
                    $query = $query . "MATCH (
                        a.$column[nome]
                        )
                        AGAINST ('$terms') * $column[peso] + ";
                }
                $query = substr($query, 0, -3);
                $query = $query . ") / 5) AS `score` FROM search_noticia as a inner join noticia as b on a.idItem = b.id and (MATCH (";
                foreach($this->tipo[0]['colunas'] as $column){
                    $query = $query . "a.$column[nome],";
                }
                $query = substr($query, 0, -1) . ") AGAINST('$terms')";
                $query = $query . ") inner join categoria as c on a.categoria = c.titulo order by a.tipo, `score` DESC, a.idItem DESC $limit;";
                
                $queryGal = "SELECT distinct a.tipo, a.idItem as id, a.titulo, a.categoria, b.thumb, c.permalink as permalink_categ, c.categ as mod_categ, ((";
                foreach($this->tipo[1]['colunas'] as $column){
                    $queryGal = $queryGal . "MATCH (
                        a.$column[nome]
                        )
                        AGAINST ('$terms') * $column[peso] + ";
                }
                $queryGal = substr($queryGal, 0, -3);
                $queryGalFoto = $queryGal . ") / 5) AS `score` FROM search_galeria as a inner join categoria as c on a.categoria = c.titulo and a.tipo = 1 inner join galeria as b on a.idItem = b.id and (MATCH (";
                $queryGalVideo = $queryGal . ") / 5) AS `score` FROM search_galeria as a inner join categoria as c on a.categoria = c.titulo and a.tipo = 2 inner join galeria as b on a.idItem = b.id and (MATCH (";
                foreach($this->tipo[1]['colunas'] as $column){
                    $queryGalFoto = $queryGalFoto . "a.$column[nome],";
                    $queryGalVideo = $queryGalVideo . "a.$column[nome],";
                }
                $queryGalFoto = substr($queryGalFoto, 0, -1) . ") AGAINST('$terms')";
                $queryGalVideo = substr($queryGalVideo, 0, -1) . ") AGAINST('$terms')";
                $queryGalFoto = $queryGalFoto . ")  order by a.tipo, `score` DESC, a.idItem DESC limit 0,16;";
                $queryGalVideo = $queryGalVideo . ") order by a.tipo, `score` DESC, a.idItem DESC limit 0,2;";
                
            } else {
                $query = "SELECT a.tipo, a.titulo, left(a.texto, 200) as texto, a.categoria, c.permalink as permalink_categ, c.categ as mod_categ, date_format(b.dataPublicacao, '%d\/%m\/%Y') as data, b.permalink
                        FROM search_noticia as a inner join noticia as b on a.idItem = b.id inner join categoria as c on a.categoria = c.titulo
                        order by a.tipo, b.dataPublicacao, a.idItem DESC $limit;";
                $queryGalFoto = "SELECT distinct a.idItem as id, a.tipo, a.idItem as id, a.titulo, a.categoria, a.thumb, c.permalink as permalink_categ, c.categ as mod_categ, date_format(b.dataPublicacao, '%d\/%m\/%Y') as data, b.permalink
                        FROM search_galeria as a inner join noticia as b on a.idItem = b.id inner join categoria as c on a.categoria = c.titulo
                        order by a.tipo, b.dataPublicacao, a.idItem DESC limit 0,16;";
                $queryGalVideo = "SELECT distinct a.idItem as id, a.tipo, a.idItem as id, a.titulo, a.categoria, a.thumb, c.permalink as permalink_categ, c.categ as mod_categ, date_format(b.dataPublicacao, '%d\/%m\/%Y') as data, b.permalink
                        FROM search_galeria as a inner join noticia as b on a.idItem = b.id inner join categoria as c on a.categoria = c.titulo
                        order by a.tipo, b.dataPublicacao, a.idItem DESC limit 0,2;";
            }

            $rsNot = mysql_query($query);
            $arr = array();
            
            if($rsNot && mysql_num_rows($rsNot)){
                while($cursor = mysql_fetch_object($rsNot)){
                    $arr[] = $cursor;
                }
            } else {
                $terms = $this->Get_terms();
                $cond = '(';
                foreach($terms as $term){
                    $cond = $cond . "a.categoria like '%$term%' or a.tags like '%$term%' or ";
                }
                $cond = substr($cond, 0, -4) . ")";
                $query = "SELECT a.tipo, a.titulo, left(a.texto, 200) as texto, a.categoria, c.permalink as permalink_categ, c.categ as mod_categ,
                            date_format(b.dataPublicacao, '%d\/%m\/%Y') as data, b.permalink from search_noticia as a
                            inner join noticia as b on a.idItem = b.id inner join categoria as c on a.categoria = c.titulo and $cond
                            order by a.tipo, b.dataPublicacao, a.idItem DESC $limit;";
                $rsNot = mysql_query($query);
                
                $arr = array();
                if($rsNot && mysql_num_rows($rsNot)){
                    while($cursor = mysql_fetch_object($rsNot)){
                        $arr[] = $cursor;
                    }
                }
            }

            $rsGalFoto = mysql_query($queryGalFoto);
            if($rsGalFoto && mysql_num_rows($rsGalFoto)){
                while($cursor = mysql_fetch_object($rsGalFoto)){
                    $arr[] = $cursor;
                }
            } else {
                $terms = $this->Get_terms();
                $cond = '(';
                foreach($terms as $term){
                    $cond = $cond . "a.categoria like '%$term%' or a.tags like '%$term%' or ";
                }
                $cond = substr($cond, 0, -4) . ")";
                $query = "SELECT a.idItem as id, a.tipo, a.titulo, a.categoria, b.thumb, c.permalink as permalink_categ, c.categ as mod_categ
                            from search_galeria as a
                            inner join categoria as c on a.categoria = c.titulo inner join galeria as b on a.idItem = b.id and $cond and a.tipo = 1
                            order by a.tipo, b.dataPublicacao, a.idItem DESC limit 0,16;";
                $rsGal = mysql_query($query);
                
                if($rsGal && mysql_num_rows($rsGal)){
                    while($cursor = mysql_fetch_object($rsGal)){
                        $arr[] = $cursor;
                    }
                }
            }

            $rsGalVideo = mysql_query($queryGalVideo);
            if($rsGalVideo && mysql_num_rows($rsGalVideo)){
                while($cursor = mysql_fetch_object($rsGalVideo)){
                    $arr[] = $cursor;
                }
            } else {
                $terms = $this->Get_terms();
                $cond = '(';
                foreach($terms as $term){
                    $cond = $cond . "a.categoria like '%$term%' or a.tags like '%$term%' or ";
                }
                $cond = substr($cond, 0, -4) . ")";
                $query = "SELECT a.idItem as id, a.tipo, a.titulo, a.categoria, b.thumb, c.permalink as permalink_categ, c.categ as mod_categ
                            from search_galeria as a
                            inner join categoria as c on a.categoria = c.titulo inner join galeria as b on a.idItem = b.id and $cond and a.tipo = 2
                            order by a.tipo, b.dataPublicacao, a.idItem DESC limit 0,2;";
                $rsGal = mysql_query($query);
                
                if($rsGal && mysql_num_rows($rsGal)){
                    while($cursor = mysql_fetch_object($rsGal)){
                        $arr[] = $cursor;
                    }
                }
            }
            
            if(empty($arr)){
                return FALSE;
            } else {
                return $arr;
            }
            
        }
        
        public function __destruct(){
            $this->conn->close();
        }
        
    }
?>