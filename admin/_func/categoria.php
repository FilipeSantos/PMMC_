<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    $login = new Login();
    if(!$login->verificaLogin()){
       header('Location:/admin/login.php');
       exit();
    }
    
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
    if($referer === NULL){
        die();
    }
    if(strpos($referer, BASE_URL) !== 0){
        die();
    }
    
    $login->atualizaSession();
    $conn = new DbConnect();
    
    $categ = (isset($_POST['categ']) && !empty($_POST['categ'])) ? addslashes($_POST['categ']) : FALSE;
    $rs = mysql_query("select id, titulo from categoria where titulo = '$categ' limit 1;");
    if(!mysql_num_rows($rs)){
        $slug = new Slug();
        $slug->noRepeat = TRUE;
        $slug->table = 'categoria';
        $slug->SetSlug(utf8_decode($categ));
        $slug = $slug->GetSlug();
        if($rs = mysql_query("insert into categoria(titulo, permalink) values('$categ', '$slug');")){
            echo '{"status":"ok","id":"' . mysql_insert_id() . '"}';
        } else {
            echo '{"status":"error","msg":"' . mysql_error() .'"}';
        }
    } else {
        $id = mysql_result($rs, 0, 'id');
        echo '{"status":"existe","id":"' . $id . '"}';
    }
    
    $conn->close();
?>