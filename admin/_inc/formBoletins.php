<form id="formCadastrarBoletim" class="boxForm" method="post" enctype="multipart/form-data" action="/admin/_func/boletim.php">
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
        <input type="file" name="Filedata" id="arquivo" class="form<?php echo isset($item) ? ' disabled' : '';?> fileBoletim" autocomplete="off" />
        <span class="userArquivo"<?php echo isset($item) ? ' style="display: block;"' : '';?>><?php echo isset($item) ? $item->arquivo : ''; ?></span>
        <a class="excArquivo" href="#"<?php echo isset($item) ? ' style="display: block;"' : '';?>>[alterar]</a>
        <br clear="all" />
    </div>
    <div class="item">
        <input type="checkbox" name="status" id="status" value="1" class="check" <?php echo (isset($item) && $item->status === '0') ? '' : 'checked="checked"'; ?> />
        <label for="status" class="lblStatus">Ativo</label>
        <br clear="all" />
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
        <input type="hidden" name="id" id="idBoletim" value="<?php echo $_GET['id']; ?>" />
    <?php endif; ?>
 </form>