<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
    if($referer === NULL){
        die();
    }
    if(strpos($referer, BASE_URL) !== 0){
        die();
    }
    
    $conn = new DbConnect();
    
    $rs = mysql_query("select id, titulo from tag where 1 order by titulo;");
    if(mysql_num_rows($rs) > 0){
        $echoItem = '';
        while($item = mysql_fetch_assoc($rs)){
            $echoItem = $echoItem . '{"key": "' . $item['titulo'] . '", "value": "' . $item['titulo'] . '"}, ';
        }
        echo '[' . substr($echoItem, 0, -2) . ']';
    }
    
    $conn->close();
?>