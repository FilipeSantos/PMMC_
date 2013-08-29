<?php
if(!isset($_POST)) die("Erro nos campos do formul&aacute;rio");
/* Medida preventiva para evitar que outros domínios sejam remetente da sua mensagem. */
if (eregi('tempsite.ws$|locaweb.com.br$|hospedagemdesites.ws$|websiteseguro.com$', $_SERVER[HTTP_HOST])) {
        $emailsender='comunicacao.smel@pmmc.com.br';
} else {
        $emailsender = 'comunicacao.smel@pmmc.com.br';
        //    Na linha acima estamos forçando que o remetente seja 'webmaster@seudominio',
        // você pode alterar para que o remetente seja, por exemplo, 'contato@seudominio'.
}
 
/* Verifica qual é o sistema operacional do servidor para ajustar o cabeçalho de forma correta. Não alterar */
if(PHP_OS == "Linux") $quebra_linha = "\n"; //Se for Linux
elseif(PHP_OS == "WINNT") $quebra_linha = "\r\n"; // Se for Windows
else die("Este script nao esta preparado para funcionar com o sistema operacional de seu servidor");
 
// Passando os dados obtidos pelo formulário para as variáveis abaixo
$nomeremetente     = $_POST['nome'];
$emailremetente    = trim($_POST['email']);
$emaildestinatario = 'comunicacao.smel@pmmc.com.br';
$comcopia          = '';
$comcopiaoculta    = '';
$assunto           = '[Jogos Abertos] Contato via site';
$cidade           = $_POST['cidade'];
$telefone           = $_POST['telefone'];
$mensagem          = $_POST['mensagem'];
 
 
/* Montando a mensagem a ser enviada no corpo do e-mail. */
$mensagemHTML = "Nome: {$nome}<br /><br />
				 E-mail: {$email}<br /><br />
				 Cidade: {$cidade}<br /><br />
				 Telefone: {$telefone}<br /><br />
				 Mensagem: {$mensagem}";
 
/* Montando o cabeçalho da mensagem */
$headers = "MIME-Version: 1.1".$quebra_linha;
$headers .= "Content-type: text/html; charset=utf-8".$quebra_linha;
// Perceba que a linha acima contém "text/html", sem essa linha, a mensagem não chegará formatada.
$headers .= "From: ".$emailsender.$quebra_linha;
$headers .= "Return-Path: " . $emailsender . $quebra_linha;
// Esses dois "if's" abaixo são porque o Postfix obriga que se um cabeçalho for especificado, deverá haver um valor.
// Se não houver um valor, o item não deverá ser especificado.
if(strlen($comcopia) > 0) $headers .= "Cc: ".$comcopia.$quebra_linha;
if(strlen($comcopiaoculta) > 0) $headers .= "Bcc: ".$comcopiaoculta.$quebra_linha;
$headers .= "Reply-To: ".$emailremetente.$quebra_linha;
// Note que o e-mail do remetente será usado no campo Reply-To (Responder Para)
 
/* Enviando a mensagem */
mail($emaildestinatario, $assunto, $mensagemHTML, $headers );
 
/* Mostrando na tela as informações enviadas por e-mail */
if(mail($emaildestinatario, $assunto, $mensagemHTML, $headers)){
	echo '<input type="submit" name="enviar" class="enviar" id="enviar" value="" /><img src="/_img/msg_sucesso.png" width="271" height="31" alt="Mensagem enviada com sucesso." class="msg_contato" />';
}else{
	echo '<input type="submit" name="enviar" class="enviar" id="enviar" value="" /><img src="/_img/msg_erro.png" width="271" height="31" alt="Erro, tente novamente." class="msg_contato" />';
}
?>
                            