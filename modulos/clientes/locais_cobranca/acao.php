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
         case "inclusao": $nperm = "INCLUIR LOCAIS DE COBRANCA";break;
         case "alteracao": $nperm = "ALTERAR LOCAIS DE COBRANCA";break;
         case "exclusao": $nperm = "EXCLUIR LOCAIS DE COBRANCA";break;
   }
   
   $perm = testarPermissao($nperm);
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao         
   $codigo_banco = (int)$_POST['codigo_banco'];
   $complemento = tratarTexto($_POST['complemento']);   
   $descricao = tratarTexto($_POST['descricao']);
   $_action = $_POST['_action'];
   
   if ($_action != "exclusao") {
         // validar campos
         if ($codigo_banco <= 0) {
	         http_response_code(400);
        	   echo "Informe o código do banco.";
	         return;  
         }
         
         if (empty($complemento)) {
	         http_response_code(400);
        	   echo "Informe o complemento.";
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
         $sql = "insert into locais_cobranca (codigo_banco, complemento, descricao) values (" . $codigo_banco . ", '" . $complemento . "', '" . $descricao . "');";
         $msg1 = "incluir";
         $msg2 = "inclusão";
   }
   
   if ($_action == "alteracao") {
         $sql = "update locais_cobranca set descricao='" . $descricao . "' where codigo_banco=" . $codigo_banco . " and complemento='" . $complemento . "';";
         $msg1 = "alterar";
         $msg2 = "alterado";
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from locais_cobranca where codigo_banco=" . $codigo_banco . " and complemento='" . $complemento . "';";
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
