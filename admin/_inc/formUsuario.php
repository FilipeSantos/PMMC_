<form id="formCadastrarUsuario" class="boxForm" method="post" action="/admin/_func/usuario.php">
    <div class="item">
       <label for="email">*E-mail</label>
       <?php if(isset($usuario)) : ?>
            <span class="userEmail"><?php echo $usuario->email; ?></span>
       <?php else : ?>
            <input type="text" name="email" id="email" class="form email email-list required" autocomplete="off" />
       <?php endif; ?>
       
       <div class="itemVerify itemVerifyEmail">
          <div class="boxLoading"><img src="/admin/img/loading2.gif" alt="Carregando" /></div>
          <span>O e-mail "<em></em>" já está cadastrado.</span>
       </div>
       <br clear="all" />
    </div>
    <div class="item">
       <label for="senha">*Senha</label>
       <?php if(isset($usuario)) : ?>
            <span class="boxAltSenha"><a class="btnAltSenha" href="#">[alterar senha]</a></span>
       <?php else : ?>
            <input type="password" name="senha" id="senha" class="form form2 required" autocomplete="off" />
       <?php endif; ?>
       <br clear="all" />
    </div>
    <?php if(isset($usuario)) : ?>
        <div class="item itemNovaSenha">
            <label for="senhaAtual">*Senha Atual</label>
            <input type="password" name="senha" id="senhaAtual" class="form form2" autocomplete="off" />
            <br clear="all" />
            <label for="senhaNova">*Nova Atual</label>
            <input type="password" name="senhaNova" id="senhaNova" class="form form2" autocomplete="off" />
        </div>
    <?php endif; ?>
    <div class="item">
       <label for="perfil">*Perfil</label>
       <?php if(isset($usuario)) : ?>
            <?php if($_SESSION['capability'] === '1') : ?>
                <select name="capability" id="perfil" class="form form2 required">
                    <option value="1" <?php echo (isset($usuario) && $usuario->idPerfil == 1) ? 'selected="selected"' : ''; ?>>Master</option>
                    <option value="2" <?php echo (isset($usuario) && $usuario->idPerfil == 2) ? 'selected="selected"' : ''; ?>>Editor</option>
                    <option value="3" <?php echo (isset($usuario) && $usuario->idPerfil == 3) ? 'selected="selected"' : ''; ?>>Jornalista</option>
                </select>
            <?php else : ?>
                <span class="userEmail"><?php echo $usuario->perfil; ?></span>
            <?php endif; ?>
        <?php else : ?>
            <select name="capability" id="perfil" class="form form2 required">
               <option value="1" <?php echo (isset($usuario) && $usuario->idPerfil == 1) ? 'selected="selected"' : ''; ?>>Master</option>
               <option value="2" <?php echo (isset($usuario) && $usuario->idPerfil == 2) ? 'selected="selected"' : ''; ?>>Editor</option>
               <option value="3" <?php echo (isset($usuario) && $usuario->idPerfil == 3) ? 'selected="selected"' : ''; ?>>Jornalista</option>
            </select>
       <?php endif; ?>
       <br clear="all" />
    </div>
    <div class="item item-border">
       <label for="status">*Status</label>
        <?php if($_SESSION['capability'] === '1') : ?>
            <select name="status" id="status" class="form form2 required">
               <option value="1" <?php echo (isset($usuario) && $usuario->status == 1) ? 'selected="selected"' : ''; ?>>Ativo</option>
               <option value="0" <?php echo (isset($usuario) && $usuario->status == 0) ? 'selected="selected"' : ''; ?>>Inativo</option>
            </select>
        <?php else : ?>
            <span class="userEmail"><?php echo $usuario->status == 1 ? 'Ativo' : 'Inativo'; ?></span>
        <?php endif; ?>
       <br clear="all" />
    </div>
    <div class="item">
       <label for="nome">*Nome</label>
       <input type="text" name="nome" id="nome" class="form required" <?php echo isset($usuario) ? 'value="' . $usuario->nome . '"' : ''; ?> />
       <br clear="all" />
    </div>
    <div class="item">
       <label for="cpf">*CPF</label>
       <input type="text" name="cpf" id="cpf" class="form cpf cpf-list form2 required" <?php echo isset($usuario) ? 'value="' . $usuario->cpf . '"' : ''; ?> />
        <?php if(isset($usuario)) : ?>
            <input type="hidden" name="cpfOrigin" id="cpfOrigin" value="<?php echo $usuario->cpf; ?>" />
        <?php endif; ?>
       <div class="itemVerify itemVerifyCpf">
          <div class="boxLoading"><img src="/admin/img/loading2.gif" alt="Carregando" /></div>
          <span>O CPF "<em></em>" já está cadastrado.</span>
       </div>
       <br clear="all" />
    </div>
    <div class="item">
       <label for="telefone">Telefone</label>
       <input type="text" name="telefone" id="telefone" class="form form2 tel" <?php echo isset($usuario) ? 'value="' . $usuario->telefone . '"' : ''; ?> />
       <br clear="all" />
    </div>
    <div class="item">
       <label for="celular">Celular</label>
       <input type="text" name="celular" id="celular" class="form form2 tel" <?php echo isset($usuario) ? 'value="' . $usuario->celular . '"' : ''; ?> />
       <br clear="all" />
    </div>
    <div class="item">
       <label for="empresa">Empresa</label>
       <input type="text" name="empresa" id="empresa" class="form" <?php echo isset($usuario) ? 'value="' . $usuario->empresa . '"' : ''; ?> />
       <br clear="all" />
    </div>
    <div class="contLoading">
       <div id="boxLoading"><img src="/admin/img/loading2.gif" alt="Carregando" /></div>
    </div>
    <input type="submit" id="submit" value="<?php echo isset($usuario) ? 'Salvar' : 'Cadastrar'; ?>" />
    <br clear="all" />
    <div class="boxMsg">
       <p class="error">Preencha os campos corretamente.</p>
       <p class="errorServerCpf">Erro: CPF já cadastrado.</p>
       <p class="errorServerEmail">Erro: E-mail já cadastrado.</p>
       <p class="errorServerSenha">Erro: Digite a senha atual corretamente.</p>
       <p class="errorServer">Ocorreu um erro durante o cadastramento. Tente novamente.</p>
    </div>
    <div id="msgLabel">&nbsp;</div>
    <?php if(isset($usuario)) : ?>
        <input type="hidden" name="id" id="id" value="<?php echo $usuario->id; ?>" />
    <?php endif; ?>
 </form>