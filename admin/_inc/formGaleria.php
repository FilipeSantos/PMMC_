<form id="formCadastrarGaleria" class="boxForm" method="post" enctype="multipart/form-data" action="/admin/_func/galeria.php">
    <?php if(isset($item)) : ?>
        <input type="hidden" name="id" id="id" value="<?php echo $item->id; ?>" />
    <?php endif; ?>
    <div class="item itemFirstGal">
        <label class="lblTlt">*Tipo de Mídia:</label>
        <input type="radio" name="tipo" id="TipoFoto" value="1" class="check required itemRequired" <?php echo (isset($item) && $item->tipo === '1') ? 'checked="checked" ' : ' '; ?> />
        <label for="TipoFoto">Foto</label>
        <input type="radio" name="tipo" id="TipoVideo" value="2" class="check required itemRequired" <?php echo (isset($item) && $item->tipo === '2') ? 'checked="checked" ' : ' '; ?> />
        <label for="TipoVideo">Vídeo <em>(Youtube)</em></label>
        <br clear="all" />
    </div>
    <div class="boxContainerGal">
        <div id="boxTipoFoto" class="itemCont">
            <div class="item">
                <label for="tituloFoto">*Título</label>
                <input type="text" name="tituloFoto" id="tituloFoto" class="form required itemRequired" autocomplete="off" value="<?php echo isset($item) && $item->tipo === '1' ? $item->titulo : ''; ?>" />
                <br clear="all" />
            </div>
            <div class="item">
                <label for="descricaoFoto">*Descrição</label>
                <textarea name="descricaoFoto" id="descricaoFoto" class="form required itemRequired" cols="30" rows="10"><?php echo isset($item) && $item->tipo === '1' ? $item->descricao : ''; ?></textarea>
                <br clear="all" />
            </div>
            <div class="item">
                <label for="dataFoto">*Data</label>
                <input type="text" name="dataFoto" id="dataFoto" class="form form2 required itemRequired inputDate" autocomplete="off" value="<?php echo isset($item) && $item->tipo === '1' ? $item->dataFull : date('d\/m\/Y H:i', time()); ?>" />
                <br clear="all" />
            </div>
            <div class="item">
                <label for="arquivo">*Mídia</label>
                <div class="item itemImgThumbGaleria" rel="thumbGaleria">
                    <input type="file" name="imagemThumb" id="imagemThumb" class="form imgUpload" />
                    <?php if(isset($item) && $item->tipo === '1') : ?>
                        <div class="jcropContent jcropContentGaleria">
                           <img alt="Thumb" src="/upload/galeria/<?php echo $item->url; ?>">
                           <a href="<?php echo $item->url; ?>" id="btnExcluirImg" class="btnActImg btnActImgAlt">Excluir</a>
                           <br clear="all">
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="imgUploadThumb" id="imgUploadThumb" class="imgUploadUrl required itemRequired" value="<?php echo isset($item) && $item->tipo === '1' ? $item->url : ''; ?>" /> 
                </div>
                <br clear="all" />
            </div>
            <div class="item">
                 <input type="checkbox" name="statusFoto" id="statusFoto" value="1" class="check" <?php echo (isset($item) && $item->tipo === '1' && $item->status === '0') ? '' : 'checked="checked"'; ?> />
                 <label for="statusFoto" class="lblStatus">Ativo</label>
                 <br clear="all" />
            </div>
            <div class="contLoading contLoadingGaler">
                <div class="boxLoading"><img src="/admin/img/loading2.gif" alt="Carregando" /></div>
            </div>
            <input type="submit" id="submitFoto" value="<?php echo isset($item) && $item->tipo === '1' ? 'Salvar' : 'Cadastrar'; ?>" />
            <br clear="all" />
            <div class="boxMsg">
                <p class="error">Preencha os campos corretamente.</p>
                <p class="errorServerPdf">Erro: Arquivo inválido.</p>
                <p class="errorServer">Ocorreu um erro durante o cadastramento. Tente novamente.</p>
            </div>
        </div>
        <div id="boxTipoVideo" class="itemCont">
            <div class="item itemUrlVideo" <?php echo (isset($item) && $item->tipo === '2') ? 'style="display: none;"' : ''; ?>>
                <span class="descVideo">Insira a URL de um vídeo do youtube no campo abaixo.<br /><span style="font-size: 11px; font-weight: normal;">Ex: http://www.youtube.com/watch?v=Q1UkVT-2sEs</span></span>
                <label for="youtubeUrl">*URL</label>
                <input type="text" id="youtubeUrl" class="form<?php echo (!isset($item) || $item->tipo !== '2') ? ' required itemRequired' : ''; ?>" autocomplete="off" />
                <a id="btnLoadVideo" href="#">Inserir</a>
                <div class="contLoading contLoadingInsertVideo">
                    <div class="boxLoading"><img src="/admin/img/loading2.gif" alt="Carregando" /></div>
                </div>
                <br clear="all" />
            </div>
            <div class="itemHideVideo<?php echo (isset($item) && $item->tipo === '2') ? ' itemVideoShow' : ''; ?>">
                <div class="item itemInfoVideo">
                    <input type="hidden" name="idVideo" id="idVideo" value="<?php echo (isset($item) && $item->tipo === '2') ? $item->url : ''; ?>" />
                    <label><strong>*Vídeo</strong></label>
                    <strong id="infoUrlVideo">
                        <?php if(isset($item) && $item->tipo === '2') : ?>
                            <iframe width="350" height="287" src="http://www.youtube.com/embed/<?php echo $item->url; ?>" frameborder="0" allowfullscreen></iframe>
                        <?php endif; ?>
                    </strong>
                    <br clear="all" />
                    <a id="btnAltVideo" href="#">[Inserir outro vídeo]</a>
                    <br clear="all" />
                </div>
                <div class="item">
                    <label for="tituloVideo">*Título</label>
                    <input type="text" name="tituloVideo" id="tituloVideo" class="form required itemRequired" autocomplete="off" value="<?php echo (isset($item) && $item->tipo === '2') ? $item->titulo : ''; ?>" />
                    <br clear="all" />
                </div>
                <div class="item">
                    <label for="descricaoVideo">*Descrição</label>
                    <textarea name="descricaoVideo" id="descricaoVideo" class="form required itemRequired" cols="30" rows="10"><?php echo isset($item) && $item->tipo === '2' ? $item->descricao : ''; ?></textarea>
                    <br clear="all" />
                </div>
                <div class="item">
                    <label for="dataVideo">*Data</label>
                    <input type="text" name="dataVideo" id="dataVideo" class="form form2 inputDate required itemRequired" autocomplete="off" value="<?php echo isset($item) && $item->tipo === '2' ? $item->dataFull : date('d\/m\/Y H:i', time()); ?>" />
                    <br clear="all" />
                </div>
                <div class="item">
                    <label>*Thumb</label>
                    <div class="jcropContent jcropContentThumb jcropContentThumbVideo">
                        
                        <?php
                            if(isset($item) && $item->tipo === '2'){
                                $size = strlen($item->thumb) - 5;
                                $imgSelect = explode('.', substr($item->thumb, $size));
                                $imgSelect = (integer) $imgSelect[0];
                            }
                        ?>
                        <ul id="listImgYb">
                            <li id="thumbVideoYb1"<?php echo ((isset($item) && $item->tipo === '2' && $imgSelect === 1) || !isset($item)) ? ' class="showItem"' : ''; ?>><?php echo (isset($item) && $item->tipo === '2') ? '<img src="http://i.ytimg.com/vi/' . $item->url . '/1.jpg" height="100" />' : ''; ?></li>
                            <li id="thumbVideoYb2"<?php echo (isset($item) && $item->tipo === '2' && $imgSelect === 2) ? ' class="showItem"' : ''; ?>><?php echo (isset($item) && $item->tipo === '2') ? '<img src="http://i.ytimg.com/vi/' . $item->url . '/2.jpg" height="100" />' : ''; ?></li>
                            <li id="thumbVideoYb3"<?php echo (isset($item) && $item->tipo === '2' && $imgSelect === 3) ? ' class="showItem"' : ''; ?>><?php echo (isset($item) && $item->tipo === '2') ? '<img src="http://i.ytimg.com/vi/' . $item->url . '/3.jpg" height="100" />' : ''; ?></li>
                        </ul>
                        <span class="btnNav btnNavPrev" title="Thumb anterior"><</span>
                        <span class="btnNav btnNavNext" title="Próximo thumb">></span>                        
                        <input type="hidden" name="inputThumbVideo" id="inputThumbVideo" value="<?php echo (isset($item) && $item->tipo === '2') ? $item->thumb : ''; ?>" />
                    </div>
                    <br clear="all" />
                </div>
                <div class="item">
                     <input type="checkbox" name="statusVideo" id="statusVideo" value="1" class="check" <?php echo (isset($item) && $item->tipo === '1' && $item->status === '0') ? '' : 'checked="checked"'; ?> />
                     <label for="statusVideo" class="lblStatus">Ativo</label>
                     <br clear="all" />
                </div>
                <div class="contLoading contLoadingGaler">
                    <div class="boxLoading"><img src="/admin/img/loading2.gif" alt="Carregando" /></div>
                </div>
                <input type="submit" id="submitVideo" value="<?php echo isset($item) && $item->tipo === '1' ? 'Salvar' : 'Cadastrar'; ?>" />
                <br clear="all" />
                <div class="boxMsg">
                    <p class="error">Preencha os campos corretamente.</p>
                    <p class="errorServerPdf">Erro: Arquivo inválido.</p>
                    <p class="errorServer">Ocorreu um erro durante o cadastramento. Tente novamente.</p>
                </div>      
            </div>
        </div>
    </div>
    <div class="contGaleriaRight">
        <div class="item itemCateg">
            <label for="categoria">*Categoria</label>
            <br clear="all" />
            <div class="listCateg">
               <?php
                    $arrayCateg = Categoria::get_list_categ();                
                    if(isset($arrayCateg)) : ?>
                 <ul>
                    <?php foreach($arrayCateg as $categ){ ?>
                       <li>
                          <input type="radio" name="categoria[]" id="categoria<?php echo $categ->id; ?>" value="<?php echo $categ->id; ?>" class="check required itemRequired" <?php echo (isset($item->categorias) && Utility::in_array_recursive($categ->id, $item->categorias)) ? 'checked="checked"' : '' ?> />
                          <label for="categoria<?php echo $categ->id; ?>"><?php echo stripslashes($categ->titulo); ?></label>&nbsp;&nbsp;
                          <a href="#<?php echo $categ->id ?>" class="excluirCateg">[Excluir]</a>
                          <br clear="all" />
                       </li>
                    <?php } ?>
                 </ul>
                <?php endif; ?>
            </div>
            <input type="text" id="categoriaTemp" class="form" />
            <a id="btnAddCateg" href="#">Adicionar</a>
        </div>
        <div class="item itemTag">
            <label for="tags">Tags</label>
            <select id="tags" name="tags">
               <?php
                  if(isset($item->tags)){
                     foreach($item->tags as $tag){
                        echo '<option value="' . stripslashes($tag['titulo']) . '" class="selected">' . $tag['titulo'] . '</option>';
                     }
                  }
               ?>
            </select>
            <p>- Separe as tags com 'Enter'.</p>
         </div>
    </div>
    <br clear="all" />
    
    <div id="msgLabel">&nbsp;</div>
    <?php if(isset($_GET['id']) && is_numeric($_GET['id'])) : ?>
        <input type="hidden" name="id" id="idRelease" value="<?php echo $_GET['id']; ?>" />
    <?php endif; ?>
 </form>