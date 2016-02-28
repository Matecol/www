<?php

   require_once BASE_DIR . '/util/conexao.php';
   require_once BASE_DIR . '/util/permissao.php';
   require_once BASE_DIR . '/util/util.php';
   require_once BASE_DIR . '/util/sessao.php';

   // validar sessao
   validarSessao();
   
   // testar permissao
   $nperm = "";
   switch($_POST['_action']) {
         case "inclusao": $nperm = "INCLUIR CADASTRO DE NCM";break;
         case "alteracao": $nperm = "ALTERAR CADASTRO DE NCM";break;
         case "exclusao": $nperm = "EXCLUIR CADASTRO DE NCM";break;
   }
   
   $perm = testarPermissao($nperm);
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao        
   $id = tratarChave($_POST['id']);
   $ncm = (int)$_POST['ncm'];
   $descricao = tratarTexto($_POST['descricao']);
   $monofasico = tratarTexto($_POST['monofasico']);
   $_action = $_POST['_action'];
   
   if ($_action != "exclusao") {

         // validar campos
         if (empty($ncm)) {
	         http_response_code(400);
	         echo "Informe o ncm.";
	         return;  
         }
   
         if (empty($descricao)) {
	         http_response_code(400);
	         echo "Informe a descricao.";
       	   return;  
         }
         
         if((strlen($ncm)) < 8){
	         http_response_code(400);
	         echo "O campo NCM deve conter 8 dígitos.";
	         return;
         }
      
   }
   
   if (empty($_action)) {
	   http_response_code(400);
	   echo "Falha nos parâmetros da solicitação.";
         return;
   }
   
   // Abrir conexao
   $conexao = new Conexao();
   
   // Testar acao
   $sql = "";
   
   if ($_action == "inclusao") {
         $sql = "insert into ncm (ncm, descricao, monofasico) values ('" . $ncm . "', '" . $descricao . "', '" . $monofasico . "');";
         $msg1 = "incluir";
         $msg2 = "inclusão";
   }
   
   if ($_action == "alteracao") {
         $sql = "update ncm set ncm='" . $ncm . "',descricao='" . $descricao . "',monofasico='" . $monofasico . "' where id=" . $id;
         $msg1 = "alterar";
         $msg2 = "alterado";
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from ncm where id=" . $id;
         $msg1 = "excluir";
         $msg2 = "excluído";
   }
   
   if (empty($sql)) {
         http_response_code(400);
	   echo "Falha nos parâmetros da solicitação. Tente novamente mais tarde ou contate o suporte.";
         return;
   }
   
   $flag = 0;
   $result = @pg_query($sql) or $flag = 1;
   
   if ($flag == 1) {
         http_response_code(400);
         echo "Falha ao " . $msg1 . " registro. Tente novamente mais tarde ou contate o suporte.";
         return;
   }

   echo "Registro " . $msg2 . " com sucesso.";
   
?>
