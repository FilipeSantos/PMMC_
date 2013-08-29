<?php
	$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : FALSE;
	if($referer == 'http://www.jogosabertos2011.com.br/cadastramento_voluntario.php' || $referer == 'http://jogosabertos2011.com.br/cadastramento_voluntario.php'){
		$nome = isset($_POST['nome']) && !empty($_POST['nome']) ? "'" . addslashes(strip_tags($_POST['nome'])) . "'" : FALSE;
		$telefone = isset($_POST['telefone']) && !empty($_POST['telefone']) ? "'" . addslashes(strip_tags($_POST['telefone'])) . "'" : FALSE;
		$email = isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? "'" . addslashes(strip_tags($_POST['email'])) . "'" : FALSE;
		$rua = isset($_POST['rua']) && !empty($_POST['rua']) ? "'" . addslashes(strip_tags($_POST['rua'])) . "'" : FALSE;
		$bairro = isset($_POST['bairro']) && !empty($_POST['bairro']) ? "'" . addslashes(strip_tags($_POST['bairro'])) . "'" : FALSE;
		$cidade = isset($_POST['cidade']) && !empty($_POST['cidade']) ? "'" . addslashes(strip_tags($_POST['cidade'])) . "'" : FALSE;
		$profissao = isset($_POST['profissao']) && !empty($_POST['profissao']) ? "'" . addslashes(strip_tags($_POST['profissao'])) . "'" : FALSE;
		$data_nasc = isset($_POST['data_nasc']) && !empty($_POST['data_nasc']) ? "'" . addslashes(strip_tags($_POST['data_nasc'])) . "'" : FALSE;
		
		$emailRemetente = 'comunicacao.smel@pmmc.com.br';
		$nomeremetente = 'Prefeitura de Mogi das Cruzes - Jogos Abertos do Interior 2011';
		$subject = '[Jogos Abertos] Confirmação de Cadastro';
		$emailDest = stripslashes(str_replace('\'', '', $email));
		$msgError = 'Houve um erro no seu cadastro. Tente novamente ou entre em <a href="/contato.php">contato</a> conosco.';
		$msgErrorIdade = 'A data de nascimento informada não atende os requisitos de idade mínima para o voluntariado.';
		$msgSucesso = 'Obrigado por vestir a camisa dos Jogos Abertos. Estamos torcendo pra você entrar em campo com a gente, em breve.';
		
		if($data_nasc){
			list($dia, $mes, $ano) = explode('/', stripslashes(str_replace('\'', '', $data_nasc)));
			
			if(!checkdate($mes, $dia, $ano)){
				echo $msgError;
				exit();
			} else {
				if((intval(substr(date('Ymd') - date('Ymd', strtotime($ano . '-' . $mes . '-' . $dia)), 0, -4))) < 18){
					echo $msgErrorIdade;
					exit();
				}
			}
			
			$data_nasc = "'$ano-$mes-$dia'";
		}
		
		if($nome && $telefone && $email && $rua && $bairro && $cidade && $profissao && $data_nasc){
			require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/db.class.php');
			require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/phpmailer.class.php');
			
			$conn = new DbConnect();
			mysql_set_charset('utf8', $conn->conn);

			$rs = mysql_query("insert into cadastro_voluntario(nome, telefone, email, rua, bairro, cidade, profissao, dataNascimento, dataCadastro, ip)
				    values($nome, $telefone, $email, $rua, $bairro, $cidade, $profissao, $data_nasc, CURRENT_TIMESTAMP, '$_SERVER[REMOTE_ADDR]');") or die($msgError);
			
			$idItem = mysql_insert_id();
			
			if(isset($_POST['periodo']) && is_array($_POST['periodo'])){
				foreach($_POST['periodo'] as $item){
					if(is_numeric($item)){
						$rs = mysql_query("insert into cadastrovoluntario_disponibilidade(idVoluntario, idDisponibilidade) values($idItem, $item);") or die($msgError);
					}
				}
			}
			
			if(isset($_POST['modalidade']) && is_array($_POST['modalidade'])){
				foreach($_POST['modalidade'] as $item){
					if(is_numeric($item)){
						$rs = mysql_query("insert into cadastrovoluntario_modalidade(idVoluntario, idModalidade) values($idItem, $item);") or die($msgError);
					}
				}
			}
			
			$bodyEmail = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/newsletter/Cadastro_Voluntarios/disparo.html');
						
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->From = $emailRemetente;
			$mail->FromName = $nomeremetente;
			$mail->AddAddress($emailDest);
			$mail->IsHTML(true);
			$mail->CharSet = 'utf-8';
			$mail->Subject = $subject;
			$mail->Body = $bodyEmail;
			
			if($mail->Send()){
				echo $msgSucesso;
				exit();
			}

			unset($mail);
			echo $msgError;
			exit();
			
		}
		echo $msgError;
	}
?>