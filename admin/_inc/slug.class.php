<?php
    function gera_slug($str, $table, $closeConn = FALSE, $idItem = FALSE){
        require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/db.class.php');
        $conn = new DbConnect();

        $str = utf8_encode(strtolower(trim($str)));
        $str = preg_replace('/[@àáâãäåæ]+/iu', 'a', $str);
        $str = preg_replace('/[èéêë]+/iu', 'e', $str);
        $str = preg_replace('/[ìíîï]+/iu', 'i', $str);
        $str = preg_replace('/[òóôõö]+/iu', 'o', $str);
        $str = preg_replace('/[ùúûü]+/iu', 'u', $str);
        $str = preg_replace('/[ç]+/iu', 'c', $str);
        $str = preg_replace('/[^a-zA-Z0-9-]/', '-', $str);
        $str = preg_replace('/-+/', "-", $str);
        $verifica = true;
        $i = 0;
        $hash = '';        
        $setId = $idItem ? " and id != $idItem" : "";
        
        while ($verifica !== 0){
            if($i++){
                $hash = '-' . $i;
            }
            $rs = mysql_query("select * from $table where permalink = '$str$hash'$setId;");
            $verifica = mysql_num_rows($rs);
        }
        
        if($closeConn){
            $conn->close();
        }
        return($str . $hash);
    }
?>