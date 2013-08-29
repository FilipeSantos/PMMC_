<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    $login = new Login();
    if(!$login->verificaLogin()){
       header('Location:/admin/login.php');
       exit();
    }
    $login->atualizaSession();
    
    $token = isset($_POST['token']) && !empty($_POST['token']) ? $_POST['token'] : FALSE;
    $id = isset($_POST['id']) && is_numeric($_POST['id']) ? (integer) $_POST['id'] : FALSE;

    if($token === FALSE || $token != $_SESSION['idRelease'] || $id === FALSE){
        exit('{"status":"error"}');
    }
    
    set_time_limit(0);
    sleep(1);
    
    unset($_SESSION['idRelease']);
    $release = new Release();
    $release = $release->get_release($id, 'limit 1');
    if($release === FALSE){
        exit('{"status":"error"}');
    }
    
    $release = array_pop($release);
    
    $imprensa = new Imprensa();
    $imprensa = $imprensa->get_imprensa(array('nome', 'email'));
    if($imprensa === FALSE){
        exit('{"status":"error"}');
    }
    $totalEmails = count($imprensa);
    
    $bodyEmail = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/admin/_email/Release/index.html');
    $bodyEmail = str_replace('{_PATH_}', BASE_URL, $bodyEmail);
    $bodyEmail = str_replace('{_DATA_}', $release->data, $bodyEmail);
    $bodyEmail = str_replace('{_TITULO_}', $release->titulo, $bodyEmail);
    $bodyEmail = str_replace('{_LINK_PDF_}', $release->arquivo, $bodyEmail);
    $bodyEmail = str_replace('{_LINK_NOTICIA_}', $release->linkNoticia, $bodyEmail);

    $mail = new PHPMailer();
    $mail->From = 'comunicacao.smel@pmmc.com.br';
    $mail->FromName = 'Jogos Abertos do Interior';
    $mail->IsHTML(true);
    $mail->CharSet = 'utf-8';
    $mail->Subject = 'MOGI "IN LOCO" - As últimas dos jogos abertos na sua caixa de entrada.';
    $mail->Body = $bodyEmail;

    foreach($imprensa as $item){
        $mail->AddAddress($item->email, $item->nome);
        $mail->Send();
        $mail->ClearAddresses();
    }
    
    exit('{"status":"ok"}');
?>