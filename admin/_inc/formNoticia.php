<div class="infoSecao">
    <p>* Campos Obrigatórios</p>
 </div>
<form id="formNoticia" action="/admin/_func/noticia.php" method="POST">
    <div class="boxFloat">
       <div class="item">
          <label for="titulo">*Título</label>
          <input type="text" name="titulo" id="titulo" class="form required" <?php echo $edit ? 'value="' . stripslashes($noticia['titulo']) . '"' : '' ?> />
       </div>
       <div class="item">
          <label for="descricao">Descrição</label>
          <input type="text" name="descricao" id="descricao" class="form" <?php echo $edit ? 'value="' . stripslashes($noticia['descricao']) . '"' : '' ?> />
       </div>
       <div class="item itemImgThumb" rel="thumb">
          <label for="imagemThumb">Thumb</label>
          <input type="file" name="imagemThumb" id="imagemThumb" class="form imgUpload" />
          <?php if($edit && $noticia['imagemThumb']) : ?>
             <div class="jcropContent jcropContentThumb">
                <img alt="Thumb" src="<?php echo $noticia['imagemThumb']; ?>">
                <a href="<?php echo $noticia['imagemThumb']; ?>" id="btnExcluirImg" class="btnActImg btnActImgAlt">Excluir</a>
                <br clear="all">
             </div>
          <?php endif; ?>
          <input type="hidden" name="imgUploadThumb" id="imgUploadThumb" class="imgUploadUrl" <?php echo $edit ? 'value="' . $noticia['imagemThumb'] . '"' : '' ?> /> 
       </div>
    </div>
    <div class="boxFloat">
       <div class="item">
          <label for="dataPub">*Publicar em</label>
          <input type="text" name="dataPub" id="dataPub" class="form form2 required" value="<?php echo ($edit && $noticia['dataPublicacao'] != NULL) ? $noticia['dataPublicacao'] : date('d\/m\/Y H:i', time()) ?>" />
       </div>
       <div class="item itemCateg">
          <label for="categoria">*Categoria</label>                     
          <div class="listCateg">
            <?php
                 $arrayCateg = Categoria::get_list_categ();                
                 if(isset($arrayCateg)) : ?>
              <ul>
                 <?php foreach($arrayCateg as $categ){ ?>
                    <li>
                       <input type="radio" name="categoria[]" id="categoria<?php echo $categ->id; ?>" value="<?php echo $categ->id; ?>" class="check required" <?php echo ($edit && in_array($categ->id, $noticaCateg)) ? 'checked="checked"' : '' ?> />
                       <label for="categoria<?php echo $categ->id; ?>"><?php echo stripslashes($categ->titulo); ?></label>&nbsp;&nbsp;
                       <a href="#<?php echo $categ->id ?>" class="excluirCateg">[Excluir]</a>
                    </li>
                 <?php } ?>
              </ul>
             <?php endif; ?>
         </div>
          <input type="text" id="categoriaTemp" class="form" />
          <a id="btnAddCateg" href="#">Adicionar</a>
       </div>
    </div>
    <br clear="all" />
    <div class="item itemTexto">
       <label for="texto">*Texto</label>
       <textarea name="texto" id="texto" cols="30" rows="10" class="form"><?php echo $edit ? stripslashes($noticia['texto']) : '' ?></textarea>
    </div>
    <div class="itemInfos">
       <div class="item itemTag">
          <label for="tags">Tags</label>
          <select id="tags" name="tags">
             <?php
                if($edit){
                   foreach($noticiaTag as $item){
                      echo '<option value="' . $item['titulo'] . '" class="selected">' . $item['titulo'] . '</option>';
                   }
                }
             ?>
          </select>
          <p>- Separe as tags com 'Enter'.</p>
       </div>
        <div class="boxItensDestaq<?php echo (!$edit || $noticia['imagemThumb'] == NULL) ? ' boxItensDestaqHide' : ''; ?>">
            <div class="item itemExibir">
                 <label for="exibirHome">Destaque Home <a class="fancybox<?php echo (!$edit || $noticia['imagemThumb'] == NULL) ? ' btnDisabled' : ''; ?>" href="/admin/img/img-posNoticiasHome.gif">(ver posição)</a></label>
                 <select name="exibirHome" id="exibirHome" <?php echo (!$edit || $noticia['imagemThumb'] == NULL) ? 'disabled="disabled"' : ''; ?>>
                     <option value="0">Nenhum</option>
                     <?php for($i=1; $i<=10; $i++) : ?>
                        <option value="<?php echo $i ?>" <?php echo ($edit && $i == $noticia['exibirHome'] && $noticia['imagemThumb'] != NULL) ? 'selected="selected"' : ''; ?>><?php echo $i ?></option>
                     <?php endfor; ?>
                  </select>
            </div>
            <div class="item itemDestaq">
               <label for="destaque">Destaque Notícias <a class="fancybox<?php echo (!$edit || $noticia['imagemThumb'] == NULL) ? ' btnDisabled' : ''; ?>" href="/admin/img/img-posNoticias.gif">(ver posição)</a></label>
               <select name="destaque" id="destaque" <?php echo (!$edit || $noticia['imagemThumb'] == NULL) ? 'disabled="disabled"' : ''; ?>>
                  <option value="0" <?php echo (!$edit || $noticia['imagemThumb'] == NULL) ? 'selected="selected"' : ''; ?>>Nenhum</option>
                  <?php for($i=1; $i<=16; $i++) : ?>
                     <option value="<?php echo $i ?>" <?php echo ($edit && $i == $noticia['importancia'] && $noticia['imagemThumb'] != NULL) ? 'selected="selected"' : ''; ?>><?php echo $i ?></option>
                  <?php endfor; ?>
               </select>
            </div>
        </div>
        <?php if(!$edit || $noticia['imagemThumb'] == NULL) : ?>
            <p>- Para dar destaque a esta notícia, insira um thumb.</p>
        <?php endif; ?>
        <?php if($_SESSION['capability'] <= 2) : ?>
            <div class="item itemAtivo">
               <input type="checkbox" name="ativo" id="ativo" value="1" class="check" <?php echo ($edit && !$noticia['status']) ? '' : 'checked="checked"'; ?> />
               <label for="ativo"><?php echo ($edit && !$noticia['status'] && $noticia['aprovacao'] == 1) ? 'Aprovar' : 'Ativo'; ?></label>
            </div>
        <?php endif; ?>
    </div>
    <br clear="all" />
    <div class="loadingNot"><img src="/admin/img/loading2.gif" alt="Carregando..." /></div>
    <input type="submit" id="submit" value="salvar" />
    <br clear="all" />
    <div class="boxMsgError">
        <p class="msgErrorServer">Ocorreu um erro ao tentar salvar essa notícia.<br /><span>Verifique se você preencheu todos os campos obrigatórios corretamente.</span></p>
    </div>
    <div id="msgLabel">&nbsp;</div>
    <input type="hidden" name="tipoNoticia" id="tipoNoticia" value="<?php echo $edit ? 'editar' : 'criar' ?>" <?php echo $edit ? 'rel="' . $id . '"' : ''; ?> />
    <?php if($edit) : ?>
       <input type="hidden" name="idNoticia" id="idNoticia" value="<?php echo $id; ?>" />
        <?php if($noticia['aprovacao'] === '1') : ?>
            <input type="hidden" name="aprovacao" id="aprovacao" value="1" />
        <?php endif; ?>
    <?php endif; ?>
 </form>