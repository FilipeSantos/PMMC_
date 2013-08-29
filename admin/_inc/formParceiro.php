<form id="formCadastrarParceiro" class="boxForm" method="post" enctype="multipart/form-data" action="/admin/_func/parceiro.php">
    <div class="boxContainerGal">
        <div class="item">
           <label for="nome">*Nome</label>
           <input type="text" name="nome" id="nome" class="form required" autocomplete="off" value="<?php echo isset($item) ? $item->nome : ''; ?>" />
           <br clear="all" />
        </div>
        <div class="item">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" class="form" cols="30" rows="10"><?php echo isset($item) ? $item->descricao : ''; ?></textarea>
            <br clear="all" />
        </div>
        <div class="item">
           <label for="desconto">Desconto</label>
           <input type="text" maxlength="50" name="desconto" id="desconto" class="form form2" autocomplete="off" value="<?php echo isset($item) ? $item->desconto : ''; ?>" />
           <span class="exemplo">Exemplos: 30%, R$ 20,00</span>
           <br clear="all" />
        </div>
        <div class="item">
           <label for="endereco">Endereço</label>
           <input type="text" name="endereco" id="endereco" class="form form4" autocomplete="off" value="<?php echo isset($item) ? $item->endereco : ''; ?>" />
           <br clear="all" />
           <span class="exemplo exemploSpace">Digite o endereço completo</span>
           <br clear="all" />
        </div>
        <div class="item">
           <label for="telefone">Telefone</label>
           <input type="text" name="telefone" id="telefone" class="form form2 tel" autocomplete="off" value="<?php echo isset($item) ? $item->telefone : ''; ?>" />
           <br clear="all" />
        </div>
        <div class="item">
           <label for="email">E-mail</label>
           <input type="text" name="email" id="email" class="form email" autocomplete="off" value="<?php echo isset($item) ? $item->email : ''; ?>" />
           <br clear="all" />
        </div>
        <div class="item">
           <label for="site">Site</label>
           <input type="text" name="site" id="site" class="form url" autocomplete="off" value="<?php echo isset($item) ? $item->site : ''; ?>" />
           <br clear="all" />
        </div>
        <div class="item">
            <input type="checkbox" name="status" id="status" value="1" class="check" <?php echo (isset($item) && $item->status === '0') ? '' : 'checked="checked"'; ?> />
            <label for="status" class="lblStatus">Ativo</label>
            <br clear="all" />
       </div>
    </div>
    <div class="contGaleriaRight">
        <div class="item itemCateg">
            <label for="servico">*Serviços</label>
            <br clear="all" />
            <div class="listCateg">
               <?php
                    $serv = Parceiro::get_servicos();
                    if($serv && is_array($serv)) :
                ?>
                 <ul>
                    <?php foreach($serv as $categ){ ?>
                       <li>
                          <input type="checkbox" name="servico[]" id="servico<?php echo $categ->id; ?>" value="<?php echo $categ->id; ?>" class="check required itemRequired" <?php echo (isset($item->servicos) && Utility::in_array_recursive($categ->id, $item->servicos)) ? 'checked="checked"' : '' ?> />
                          <label for="servico<?php echo $categ->id; ?>"><?php echo stripslashes($categ->titulo); ?></label>&nbsp;&nbsp;
                          <a href="#<?php echo $categ->id ?>" class="excluirCateg excluirServico">[Excluir]</a>
                          <br clear="all" />
                       </li>
                    <?php } ?>
                 </ul>
                <?php endif; ?>
            </div>
            <input type="text" id="categoriaTemp" class="form" />
            <a id="btnAddCateg" class="addServico" href="#">Adicionar</a>
        </div>
    </div>
    <br clear="all" />
    <div class="contLoading contLoadingParceiro">
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
    <?php if(isset($item)) : ?>
        <input type="hidden" name="idParceiro" id="idParceiro" value="<?php echo isset($item) ? $item->id : ''; ?>" />
    <?php endif; ?> 
</form>