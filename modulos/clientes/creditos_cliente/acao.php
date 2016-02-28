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
         case "inclusao": $nperm = "INCLUIR SOLICITACAO DE CREDITO";break;
         case "alteracao": $nperm = "ALTERAR SOLICITACAO DE CREDITO";break;
         case "aprovacao": $nperm = "APROVAR SOLICITACAO DE CREDITO";break;
         case "revogacao": $nperm = "REVOGAR SOLICITACAO DE CREDITO";break;
         case "cancelamento": $nperm = "CANCELAR SOLICITACAO DE CREDITO";break;
   }
   
   $perm = testarPermissao($nperm);
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao
   $id = (int)$_POST['id'];
   $cliente = (int)$_POST['cliente'];         
   $valor = (float)$_POST['valor'];
   $tipo = tratarTexto($_POST['tipo']);   
   $observacoes = tratarTexto($_POST['observacoes']);
   $_action = $_POST['_action'];
   
   if ($_action != "revogacao" && $_action != "cancelamento") {
         // validar campos
         if ($valor <= 0) {
	         http_response_code(400);
        	   echo "Informe o valor do crédito.";
	         return;  
         }
         
         if (empty($observacoes)) {
	         http_response_code(400);
        	   echo "Informe a observação.";
	         return;  
         }
   }
   
   if (empty($_action) || $cliente == 0) {
        http_response_code(400);
        echo "Falha nos parâmetros da solicitação.";
        return;
   }
   
   // Abrir conexao
   $conexao = new Conexao();
   
   // Testar acao
   $sql = "";
   
   if ($_action == "inclusao") {
         $sql = "insert into creditos_cliente (cliente, tipo, valor, observacoes, status, usuario_solicitacao) values (" . $cliente . ", '" . $tipo . "', " . $valor . ", '" . $observacoes . "', 'S', " . $_SESSION['id'] . ");";
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