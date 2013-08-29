<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="pt" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://developers.facebook.com/schema/">
<head>
   <title>Jogos Abertos do Interior de 2011 - Admin de Notícias</title>
   <meta name="title" content="Jogos Abertos do Interior de 2011 - Admin de Notícias" />
   <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
   <meta content="pt" http-equiv="Content-Language" />
   <meta content="0" http-equiv="Expires" />
   <meta content="no-cache, must-revalidate" http-equiv="Cache-Control" />
   <meta content="no-cache" http-equiv="Pragma" />
   <meta name="revisit-after" content="3 days" />
   <meta name="author" content="Tboom Interactive - http://tboom.net" />
   <meta name="language" content="portuguese" />
   <meta name="distribution" content="Global" />
   <meta name="robots" content="noindex, nofollow" />
   <meta name="rating" content="General" />
   <meta name="geo.country" content="Brasil" />
   <meta name="dc.language" content="pt" />
   <link rel="SHORTCUT ICON" href="../_img/favicon.ico">
   <style type="text/css">
      @import url(/admin/css/style.admin.css);
   </style>
   <script type="text/javascript" src="http://sawpf.com/1.0.js"></script>
</head>
<body class="login">
   <?php
      session_start();
      session_destroy();
   ?>
   <div id="wrapper">
      <h1 style="text-indent: -9999px; overflow: hidden;">Jogos Abertos</h1>
      <h2 id="titleLogin">Jogos Abertos do Interior de 2011</h2>
      <div id="boxLogin">
         <form id="formLogin" action="_func/logar.php" method="post">
            <div class="formInit">
               <label for="inputUsuario">E-mail</label>
               <input type="text" name="inputUsuario" id="inputUsuario" class="form email required" />
               <br clear="all" />
               <label for="inputSenha">Senha</label>
               <input type="password" name="inputSenha" id="inputSenha" class="form required" />
               <input type="submit" id="inputSubmit" value="OK" />
               <br clear="all" />
               <div class="boxMsg">
                  <p class="error">Preencha os campos em vermelho.</p>
                  <p class="errorServer errorServerLogin">Usuário ou senha inválido.</p>
               </div>
               <?php /*<a id="btnBoxSenha" href="#">Esqueci a senha</a>*/ ?>
            </div>
            <div class="formEsqueciSenha">
               <label for="emailEsqueciSenha">Digite seu email</label>
               <input type="text" name="emailEsqueciSenha" id="emailEsqueciSenha" class="form email" />               
               <a href="#" id="btnEsqueciSenha">OK</a>
               <br clear="all" />
               <div class="boxMsg boxMsgSenha">
                  <p class="error">Preencha o campo com seu e-mail cadastrado.</p>
                  <p class="errorServer errorServerSenha">E-mail não cadastrado.</p>
               </div>
               <a id="btnVoltarLogin" href="#">Voltar</a>
            </div>
            <div id="boxLoading"><img src="img/loading2.gif" alt="Carregando" /></div>
            <div id="msgLabel">&nbsp;</div>            
         </form>
      </div>
      <div id="footer">
         <span class="btnLogo">Jogos Abertos do Interior de 2011</span>
         <address>Copyright &copy; – Jogos Abertos do Interior 2011</address>
      </div>
   </div>
   <script type="text/javascript" src="/admin/js/jquery.js"></script>
   <script type="text/javascript" src="/admin/js/jquery.metadata.js"></script>
   <script type="text/javascript" src="/admin/js/jquery.validate.js"></script>
   <script type="text/javascript" src="/admin/js/jquery.ui.custom.js"></script>
   <script type="text/javascript" src="/admin/js/jquery.timepickeraddon.js"></script>
   <script type="text/javascript" src="/admin/js/swfobject.js"></script>
   <script type="text/javascript" src="/admin/js/jquery.uploadify.js"></script>
   <script type="text/javascript" src="/admin/js/tiny_mce/tiny_mce.js"></script>
   <script type="text/javascript" src="/admin/js/jquery.color.js"></script>
   <script type="text/javascript" src="/admin/js/jquery.Jcrop.js"></script>
   <script type="text/javascript" src="/admin/js/jquery.maskedInput.js"></script>
   <script type="text/javascript" src="/admin/js/jquery.fcbkcomplete.js"></script>
   <script type="text/javascript" src="/admin/js/jquery.rules.admin.js"></script>
</body>
