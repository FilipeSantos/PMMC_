<?php
	$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : FALSE;
	if($referer == 'http://www.jogosabertos2011.com.br/cadastramento.php' || $referer == 'http://jogosabertos2011.com.br/cadastramento.php'){
		$nome = isset($_POST['nome']) && !empty($_POST['nome']) ? "'" . addslashes(strip_tags($_POST['nome'])) . "'" : FALSE;
		$email = isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? "'" . addslashes(strip_tags($_POST['email'])) . "'" : FALSE;
		$rg = isset($_POST['rg']) && !empty($_POST['rg']) ? "'" . addslashes(strip_tags($_POST['rg'])) . "'" : FALSE;
		$veiculo = isset($_POST['veiculo']) && !empty($_POST['veiculo']) ? "'" . addslashes(strip_tags($_POST['veiculo'])) . "'" : FALSE;
		$funcao = isset($_POST['funcao']) && !empty($_POST['funcao']) ? "'" . addslashes(strip_tags($_POST['funcao'])) . "'" : FALSE;
		$telefone = isset($_POST['telefone']) && !empty($_POST['telefone']) ? "'" . addslashes(strip_tags($_POST['telefone'])) . "'" : FALSE;
		$fax = isset($_POST['fax']) && !empty($_POST['fax']) ? "'" . addslashes(strip_tags($_POST['fax'])) . "'" : 'NULL';
		
		$emailRemetente = 'comunicacao.smel@pmmc.com.br';
		$nomeremetente = isset($_POST['nome']) && !empty($_POST['nome']) ? strip_tags($_POST['nome']) : FALSE;
		$emailDest = 'comunicacao.smel@pmmc.com.br';
		$msgError = 'Houve um erro no seu cadastro. Tente novamente ou entre em <a href="/contato.php">contato</a> conosco.';
		$msgSucesso = 'Seu cadastro foi realizado com sucesso. Agora, você receberá nossos releases atualizados em seu e-mail e poderá garantir a cobertura completa do evento com conteúdos exclusivos sobre os Jogos Abertos 2011.';
		
		if($nome && $email && $rg && $veiculo && $funcao && $telefone){
			require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/db.class.php');
			require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/phpmailer.class.php');
			
			$conn = new DbConnect();
			mysql_set_charset('utf8', $conn->conn);
			
			$rs = mysql_query("insert into cadastro_imprensa(nome, email, rg, veiculo, funcao, telefone, fax, dataCadastro, ip)
				    values($nome, $email, $rg, $veiculo, $funcao, $telefone, $fax, CURRENT_TIMESTAMP, '" . $_SERVER['REMOTE_ADDR'] ."');") or die($msgError  . ' BD');
			
			$bodyEmail = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/newsletter/Cadastro_Imprensa_Info/disparo.html');
			$bodyEmail = str_replace('{_NOME_}', stripslashes(str_replace('\'', '', $nome)), $bodyEmail);
			$bodyEmail = str_replace('{_EMAIL_}', stripslashes(str_replace('\'', '', $email)), $bodyEmail);
			$bodyEmail = str_replace('{_RG_}', stripslashes(str_replace('\'', '', $rg)), $bodyEmail);
			$bodyEmail = str_replace('{_VEICULO_}', stripslashes(str_replace('\'', '', $veiculo)), $bodyEmail);
			$bodyEmail = str_replace('{_FUNCAO_}', stripslashes(str_replace('\'', '', $funcao)), $bodyEmail);
			$bodyEmail = str_replace('{_TELEFONE_}', stripslashes(str_replace('\'', '', $telefone)), $bodyEmail);
			$bodyEmail = str_replace('{_FAX_}', stripslashes(str_replace('\'', '', $fax)), $bodyEmail);
			
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->From = $emailRemetente;
			$mail->FromName = $nomeremetente;
			$mail->AddAddress($emailDest);
			$mail->IsHTML(true);
			$mail->CharSet = 'utf-8';
			$mail->Subject = '[Jogos Abertos] Cadastro via site';
			$mail->Body = $bodyEmail;
			
			if($mail->Send()){
				$nomeremetente = 'Jogos Abertos';
				$emailDest = isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : FALSE;
				
				$bodyEmail = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/newsletter/Cadastro_Imprensa/disparo.html');
				
				$mail->ClearAllRecipients();
				$mail->FromName = $nomeremetente;				
				$mail->AddAddress($emailDest);
				$mail->Subject = '[Jogos Abertos] Confirmação de Cadastro';
				$mail->Body = $bodyEmail;
				
				if($mail->Send()){
					echo $msgSucesso;
					exit();
				}
				
			}

			unset($mail);
			echo $msgError;
			
		}
		echo $msgError;
	}
?>