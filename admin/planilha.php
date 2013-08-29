<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    $login = new Login();
    if(!$login->verificaLogin()){
       header('Location:/admin/login.php');
       exit();
    } else {
       if($_SESSION['capability'] !== '1'){
          header('Location:/admin/index.php?erro=perfil');
          exit();
       }
    }
    $login->atualizaSession();
    
    if(isset($_GET['tipo']) && !empty($_GET['tipo'])){
       
        switch($_GET['tipo']){
            
            case 'voluntarios':
                header("Content-type: application/vnd.ms-excel; charset=iso-8859-2");
                header("Content-Disposition: attachment; filename=JogosAbertos-Cadastro-Voluntarios.xls");
                
                $voluntarios = array();
                $total = 0;
                $conn = new DbConnect();
                mysql_set_charset('latin2', $conn->conn);
                
                $rs = mysql_query("select id, nome, telefone, email, rua, bairro, cidade, profissao, date_format(dataNascimento, '%d\/%m\/%Y') as nascimento,
                                  date_format(dataCadastro, '%d\/%m\/%Y') as cadastro, ip from cadastro_voluntario where 1 order by id desc;");
                if(mysql_num_rows($rs)){
                    $total = mysql_num_rows($rs);
                    $i = 0;
                    while($item = mysql_fetch_assoc($rs)){
                        $voluntarios[$i] = $item;
                        $idVoluntario = $voluntarios[$i]['id'];

                        $rsPeriodo = mysql_query("select distinct a.titulo from disponibilidade as a inner join cadastrovoluntario_disponibilidade as b on a.id = b.idDisponibilidade
                                          and b.idVoluntario = $idVoluntario;");
                        if(mysql_num_rows($rsPeriodo)){
                            $itensPeriodo = '';
                            while($itemPeriodo = mysql_fetch_assoc($rsPeriodo)){
                                $itensPeriodo = $itensPeriodo . $itemPeriodo['titulo'] . ', ';
                            }
                            $voluntarios[$i]['periodo'] = (!empty($itensPeriodo) ? substr($itensPeriodo, 0, -2) : '<em>(nenhum)</em>');
                        }
                        $voluntarios[$i]['periodo'] = isset($voluntarios[$i]['periodo']) ? $voluntarios[$i]['periodo'] : '<em>(nenhum)</em>';
                        
                        $rsModalidade = mysql_query("select a.titulo from modalidade as a inner join cadastrovoluntario_modalidade as b on a.id = b.idModalidade
                                                    and b.idVoluntario = $idVoluntario;");
                        if(mysql_num_rows($rsModalidade)){
                            $itensModalidade = '';
                            while($itemModalidade = mysql_fetch_assoc($rsModalidade)){
                                $itensModalidade = $itensModalidade . $itemModalidade['titulo'] . ', ';
                            }
                            $voluntarios[$i]['modalidade'] = (!empty($itensModalidade) ? substr($itensModalidade, 0, -2) : '<em>(nenhuma)</em>');
                        }
                        $voluntarios[$i]['modalidade'] = isset($voluntarios[$i]['modalidade']) ? $voluntarios[$i]['modalidade'] : '<em>(nenhuma)</em>';
                        $i++;
                    }
                }
                $conn->close();
                
                echo <<< EOL
                    <table border="1" bordercolor="#CCCCCC">
                        <tr>
                            <td colspan="12" align="center" bgcolor="#CCCCCC"><strong>Jogos Abertos do Interior 2011 - Voluntários Cadastrados</strong></td>
                        </tr>
                        <tr>
                            <td colspan="12">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="12"><strong>Total de Cadastros: $total</strong></td>
                        </tr>
                        <tr>
                            <td colspan="12">&nbsp;</td>
                        </tr>
                        <tr>
                            <th bgcolor="#CCCCCC"><strong>Nome</strong></th>
                            <th bgcolor="#CCCCCC"><strong>Telefone</strong></th>
                            <th bgcolor="#CCCCCC"><strong>E-mail</strong></th>
                            <th bgcolor="#CCCCCC"><strong>Rua</strong></th>
                            <th bgcolor="#CCCCCC"><strong>Bairo</strong></th>
                            <th bgcolor="#CCCCCC"><strong>Cidade</strong></th>
                            <th bgcolor="#CCCCCC"><strong>Profissão</strong></th>
                            <th bgcolor="#CCCCCC"><strong>Data Nasc.</strong></th>
                            <th bgcolor="#CCCCCC"><strong>Períodos</strong></th>
                            <th bgcolor="#CCCCCC"><strong>Modalidades</strong></th>
                            <th bgcolor="#CCCCCC"><strong>Data Cadastro</strong></th>
                            <th bgcolor="#CCCCCC"><strong>IP</strong></th>
                        </tr>
EOL;
                foreach($voluntarios as $item){
                    echo <<< EOL
                        <tr>
                            <td valign="top">$item[nome]</td>
                            <td valign="top">$item[telefone]</td>
                            <td valign="top">$item[email]</td>
                            <td valign="top">$item[rua]</td>
                            <td valign="top">$item[bairro]</td>
                            <td valign="top">$item[cidade]</td>
                            <td valign="top">$item[profissao]</td>
                            <td valign="top">$item[nascimento]</td>
                            <td valign="top">$item[periodo]</td>
                            <td valign="top">$item[modalidade]</td>
                            <td align="left" valign="top">$item[cadastro]</td>
                            <td valign="top">$item[ip]</td>
                        </tr>
EOL;
                }
                echo '<table>';
                break;
                
                
            case 'imprensa':
                header("Content-type: application/vnd.ms-excel; charset=iso-8859-2");
                header("Content-Disposition: attachment; filename=JogosAbertos-Cadastro-Imprensa.xls");
                
                $cadastros = array();
                $total = 0;
                $conn = new DbConnect();
                mysql_set_charset('latin1', $conn->conn);
                
                $rs = mysql_query("select nome, email, rg, veiculo, funcao, telefone, fax, date_format(dataCadastro, '%d\/%m\/%Y') as cadastro, ip 
                                  from cadastro_imprensa where 1 order by id desc;");
                if(mysql_num_rows($rs)){
                    $total = mysql_num_rows($rs);
                    while($item = mysql_fetch_assoc($rs)){
                        $cadastros[] = $item;
                    }
                }
                $conn->close();

                echo <<< EOL
                    <table border="1" bordercolor="#CCCCCC">
                        <tr>
                            <td colspan="9" align="center" bgcolor="#CCCCCC"><strong>Jogos Abertos do Interior 2011 - Jornalistas Cadastrados</strong></td>
                        </tr>
                        <tr>
                            <td colspan="9">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="9"><strong>Total de Cadastros: $total</strong></td>
                        </tr>
                        <tr>
                            <td colspan="9">&nbsp;</td>
                        </tr>
                        <tr>
                            <th bgcolor="#CCCCCC"><strong>Nome</strong></th>
                            <th bgcolor="#CCCCCC"><strong>E-mail</strong></th>
                            <th bgcolor="#CCCCCC"><strong>RG</strong></th>
                            <th bgcolor="#CCCCCC"><strong>Veículo</strong></th>
                            <th bgcolor="#CCCCCC"><strong>Função</strong></th>
                            <th bgcolor="#CCCCCC"><strong>Telefone</strong></th>
                            <th bgcolor="#CCCCCC"><strong>Fax</strong></th>
                            <th align="left" bgcolor="#CCCCCC"><strong>Data Cadastro</strong></th>
                            <th bgcolor="#CCCCCC"><strong>IP</strong></th>
                        </tr>
EOL;
                foreach($cadastros as $item){
                    echo <<< EOL
                        <tr>
                            <td valign="top">$item[nome]</td>
                            <td valign="top">$item[email]</td>
                            <td valign="top">$item[rg]</td>
                            <td valign="top">$item[veiculo]</td>
                            <td valign="top">$item[funcao]</td>
                            <td valign="top">$item[telefone]</td>
                            <td valign="top">$item[fax]</td>
                            <td valign="top">$item[cadastro]</td>
                            <td valign="top">$item[ip]</td>
                        </tr>
EOL;
                }
                echo '<table>';
                break;
            
            default:
                exit();
        }
    }
?>