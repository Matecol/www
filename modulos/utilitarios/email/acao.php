<?php
   require_once BASE_DIR . '/util/conexao.php';
   require_once BASE_DIR . '/util/permissao.php';
   require_once BASE_DIR . '/util/util.php';
   require_once BASE_DIR . '/util/sessao.php';
   require_once BASE_DIR . "/util/mail/mail.php";

   // validar sessao
   validarSessao();
   
   // testar permissao
   $nperm = "";
   switch($_POST['_action']) {
         case "inclusao": $nperm = "ENVIAR E-MAIL";break;
   }
   
   $perm = testarPermissao($nperm);
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao   
   $id = tratarNumero($_POST['id']);
   $emitente = tratarTextoMinusculo($_POST['emitente']);
   $senha_emitente = $_POST['senha_emitente'];
   $destinatario = tratarTextoMinusculo($_POST['destinatario']);
   $destinatariocc = tratarTextoMinusculo($_POST['destinatariocc']);
   $destinatariobcc = tratarTextoMinusculo($_POST['destinatariobcc']);
   $assunto = utf8_decode(tratarTextoSimples($_POST['assunto']));
   $corpo = utf8_decode(tratarTextoSimples($_POST['corpo']));
     
   // validar campos
   if (empty($destinatario)) {
      http_response_code(400);
	echo "Informe o(s) destinatário(s).";
	return;  
   }
   
   if (empty($assunto)) {
       http_response_code(400);
       echo "Informe o assunto do e-mail.";
       return;
   }
         
   if (empty($corpo)) {
       http_response_code(400);
       echo "Informe o corpo do e-mail.";
       return;
   }
   
   //Enviar E-Mail
   // Instanciando a Classe  
   $mail = new Mail();
   
   //Usuário e senha do emitente
   $mail->setUsuario($emitente, $senha_emitente);
      
   //Destinatarios
   $destinatarios="";
   $dest="";
   $destinatarios = explode(",", $destinatario);
   $cont = 0;
   
   //Verifica e-mail(s) informado(s) e os valida
   foreach ( $destinatarios as $dest ) {
      $cont++;
      if(!validaEmail(trim($dest))){
          http_response_code(400);
          echo $cont . "° e-mail inválido!!!";
          return;  
      }
   }
   
   //Adiciona destinatario(s)
   foreach ( $destinatarios as $dest ) {
      $mail->addDestinatario(trim($dest));
   }
   
   //Destinatarios CC
   if(!empty($destinatariocc)){
      $destinatarios="";
      $dest="";
      $destinatarios = explode(",", $destinatariocc);
      $cont = 0;
   
      //Verifica e-mail(s) informado(s) e os valida
      foreach ( $destinatarios as $dest ) {
            $cont++;
            if(!validaEmail(trim($dest))){
            http_response_code(400);
            echo $cont . "° e-mail CC inválido!!!";
            return;  
            }
      }
   
         //Adiciona destinatario(s)
      foreach ( $destinatarios as $dest ) {
            $mail->addCC(trim($dest));
         }
   }
 
   //Destinatarios BCC
   if(!empty($destinatariobcc)){
      $destinatarios="";
      $dest="";
      $destinatarios = explode(",", $destinatariobcc);
      $cont = 0;
   
      //Verifica e-mail(s) informado(s) e os valida
      foreach ( $destinatarios as $dest ) {
            $cont++;
            if(!validaEmail(trim($dest))){
                http_response_code(400);
                echo $cont . "° e-mail CCO inválido!!!";
            return;  
            }
      }
   
      //Adiciona destinatario(s)
      foreach ( $destinatarios as $dest ) {
            $mail->addBCC(trim($dest));
      }
   }

   //Assunto
   $mail->setAssunto($assunto);
   
   //Corpo
   $mail->setcorpo($corpo);
   
   //Enviar
   try{
      $mail->enviar();
      echo "E-mail enviado com sucesso.";
      echo "<script>redirecionar('cadastro.php',2);>";
   }catch (Exception $e) {
      echo "Erro ao enviar e-mail."; 
   }
   
?>
