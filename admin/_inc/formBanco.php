<form id="formCadastrarBanco" class="boxForm" method="post" enctype="multipart/form-data" action="/admin/_func/banco-de-arquivos.php">
    <div class="item">
       <label for="titulo">*Título</label>
       <input type="text" name="titulo" id="titulo" class="form required" autocomplete="off" value="<?php echo isset($item) ? $item->titulo : ''; ?>" />
       <br clear="all" />
    </div>
    <div class="item">
       <label for="formato">*Formato</label>
       <input type="text" name="formato" id="formato" class="form form2 required" autocomplete="off" value="<?php echo isset($item) ? $item->formato : ''; ?>" />
       <span class="exemplo">Exemplo: ZIP (EPS)</span>
       <br clear="all" />
    </div>
    <div class="item itemImgThumbBanco" rel="thumbBanco">
        <label for="imagemThumb">*Thumb</label>
        <input type="file" name="imagemThumb" id="imagemThumb" class="form imgUpload" />
        <?php if(isset($item) && !empty($item->thumb)) : ?>
           <div class="jcropContent jcropContentBanco">
              <img alt="Thumb" src="/upload/banco_de_arquivos/thumb/<?php echo isset($item) ? $item->thumb : ''; ?>">
              <a href="<?php echo $item->thumb; ?>" id="btnExcluirImg" class="btnActImg btnActImgAlt">Excluir</a>
              <br clear="all">
           </div>
        <?php endif; ?>
        <input type="hidden" name="imgUploadThumb" id="imgUploadThumb" class="imgUploadUrl" value="<?php echo isset($item) ? $item->thumb : ''; ?>" />
    </div>
    <div class="item">
        <label for="arquivo">*Arquivo</label>
        <input type="hidden" name="arquivo" id="hdArquivo" class="required" value="<?php echo isset($item) ? $item->arquivo : ''; ?>" />
        <input type="hidden" name="upload" id="uploadArquivo" value="<?php echo isset($item) ? '' : 'true'; ?>" />
        <input type="file" name="Filedata" id="arquivo" class="form typeAll<?php echo isset($item) ? ' disabled' : '';?>" autocomplete="off" />
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
    <input type="hidden" id="tipoNoticia" value="<?php echo isset($item) ? 'editar' : ''; ?>" rel="<?php echo isset($item) ? $item->id : ''; ?>" />
    <?php if(isset($item)) : ?>
        <input type="hidden" name="id" value="<?php echo isset($item) ? $item->id : ''; ?>" />
    <?php endif; ?> 
</form>