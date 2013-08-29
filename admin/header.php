   <?php
      $paginas = explode('/', strtolower($_SERVER['PHP_SELF']));
      $paginas = array_splice($paginas, 2);
   
      $txt = 'pg';
      foreach($paginas as $pagina){
         $pagina = str_replace('.php', '', $pagina);
         $pagina = str_replace('-', '_', $pagina);
         $txt = $txt . "_$pagina";
         $url = "$txt";
         $$url = TRUE;
      }
   ?>
   <h1 class="home"><a href="/admin/index.php">Jogos Abertos</a></h1>
   <div class="infoTop">
      <div class="infoLog">
         <?php if(isset($excluir) && $excluir) : ?>
            <p class="noticiaExcluida">Notícia excluída com sucesso!</p>
         <?php endif; ?>
         <p>Olá <strong><?php echo $_SESSION['user']; ?></strong></p>
      </div>
      <div class="infoLogRight">
         <a class="btnSair" href="/admin/login.php?logout=true" title="Sair">[sair]</a>
         <a href="/admin/usuario/perfil.php" title="Ver perfil">[Meus Dados]</a>
         <?php if($_SESSION['capability'] === '1') : ?>
            <a class="btnRelat" href="#">[Relatórios]</a>
            <a class="btnUsers<?php echo isset($pg_usuarios) || isset($pg_usuario) ? ' hover' : ''; ?>" href="/admin/usuarios.php">[Usuários]</a>
            <div class="boxInfoRelatorios">
               <a class="btnPlanImprensa" href="/admin/planilha.php?tipo=imprensa">Imprensa</a>
               <a href="/admin/planilha.php?tipo=voluntarios">Voluntários</a>
               <br clear="all" />
            </div>
         <?php endif; ?>
      </div>
   </div>
   <br clear="all" />
   <ul id="menuTop">
      <li><a <?php echo isset($pg_noticias) || isset($pg_noticia) ? 'class="hover"' : ''; ?> href="/admin/noticias.php">Notícias</a></li>
      <li><a <?php echo isset($pg_releases) || isset($pg_release) ? 'class="hover"' : ''; ?> href="/admin/releases.php">Relases</a></li>
      <li><a <?php echo isset($pg_banco_de_arquivos) ? 'class="hover"' : ''; ?> href="/admin/banco-de-arquivos.php">Banco de Arquivos</a></li>
      <?php if($_SESSION['capability'] === '1') : ?>
         <li><a <?php echo isset($pg_parceiros_e_descontos) ? 'class="hover"' : ''; ?> href="/admin/parceiros-e-descontos.php">Parceiros e Descontos</a></li>
         <li><a <?php echo isset($pg_programacao) ? 'class="hover"' : ''; ?> href="/admin/programacao.php">Programação</a></li>
         <li><a <?php echo isset($pg_boletins) || isset($pg_boletim) ? 'class="hover"' : ''; ?> href="/admin/boletins.php">Boletins</a></li>
         <li><a <?php echo isset($pg_classificacao) ? 'class="hover"' : ''; ?> href="/admin/classificacao.php">Classificação</a></li>
         <li><a <?php echo isset($pg_quadro_de_medalhas) ? 'class="hover"' : ''; ?> href="/admin/quadro-de-medalhas.php">Quadro de Medalhas</a></li>
      <?php endif; ?>
      <li class="last"><a <?php echo isset($pg_galeria_multimidia) ? 'class="hover"' : ''; ?> href="/admin/galeria-multimidia.php">Galeria Multimidia</a></li>
   </ul>
   <br clear="all" />