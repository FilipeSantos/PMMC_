<form id="formCadastrarRelease" class="boxForm boxFormRelease" method="post" enctype="multipart/form-data" action="/admin/_func/release.php">
    <div class="item">
       <label for="titulo">*Título</label>
       <input type="text" name="titulo" id="titulo" class="form required" autocomplete="off" value="<?php echo isset($item) ? $item->titulo : ''; ?>" />
       <br clear="all" />
    </div>
    <div class="item">
       <label for="data">*Data</label>
       <input type="text" name="data" id="data" class="form form2 required" autocomplete="off" value="<?php echo isset($item) ? $item->dataFull : date('d\/m\/Y H:i', time()); ?>" />
       <br clear="all" />
    </div>
    <div class="item">
        <label for="arquivo">*Arquivo</label>
        <input type="hidden" name="arquivo" id="hdArquivo" value="<?php echo isset($item) ? $item->arquivo : ''; ?>" class="required" />
        <input type="hidden" name="upload" id="uploadArquivo" value="<?php echo isset($item) ? '' : 'true'; ?>" />
        <input type="file" name="Filedata" id="arquivo" class="form<?php echo isset($item) ? ' disabled' : '';?>" autocomplete="off" />
        <span class="userArquivo"<?php echo isset($item) ? ' style="display: block;"' : '';?>><?php echo isset($item) ? $item->arquivo : ''; ?></span>
        <a class="excArquivo" href="#"<?php echo isset($item) ? ' style="display: block;"' : '';?>>[alterar]</a>
        <br clear="all" />
    </div>
    <div class="item itemPad">
        <label for="linkNoticia" class="lbl2">*Link da Notícia</label>
        <input type="text" name="linkNoticia" id="linkNoticia" class="form required form3" autocomplete="off" value="<?php echo isset($item) ? $item->linkNoticia : ''; ?>" />
        <br clear="all" />
    </div>
    <div class="item">
        <input type="checkbox" name="status" id="status" value="1" class="check" <?php echo (isset($item) && $item->status === '0') ? '' : 'checked="checked"'; ?> />
        <label for="status" class="lblStatus">Ativo</label>
        <br clear="all" />
    </div>
    <div class="enviandoEmail">
        <p>Enviando e-mails, aguarde...</p>
    </div>    
    <div class="contLoading">
       <div id="boxLoading"><img src="/admin/img/loading2.gif" alt="Carregando" /></div>
    </div>
    <input type="submit" id="submit" value="<?php echo isset($item) ? 'Salvar' : 'Cadastrar'; ?>" />
    <br clear="all" />
    <div class="boxMsg">
       <p class="error">Preencha os campos corretamente.</p>
       <p class="errorServerPdf">Erro: Arquivo inválido.</p>
       <p class="errorServer">Ocorreu um erro durante o cadastramento. Tente novamente.</p>
    </div>
    <div id="msgLabel">&nbsp;</div>
    <?php if(isset($_GET['id']) && is_numeric($_GET['id'])) : ?>
        <input type="hidden" name="id" id="idRelease" value="<?php echo $_GET['id']; ?>" />
    <?php else :
        $_SESSION['idRelease'] = md5(uniqid('JAI'));
    ?>
        <input type="hidden" name="newRelease" id="newRelease" value="<?php echo $_SESSION['idRelease']; ?>" />
    <?php endif; ?>
 </form>