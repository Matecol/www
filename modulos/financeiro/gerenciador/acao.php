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
         case "inclusao": $nperm = "INCLUIR CADASTRO DE GERENCIADOR";break;
         case "alteracao": $nperm = "ALTERAR CADASTRO DE GERENCIADOR";break;
         case "exclusao": $nperm = "EXCLUIR CADASTRO DE GERENCIADOR";break;
   }
   
   $perm = testarPermissao($nperm);
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao        
   $id = tratarChave($_POST['id']);
   $empresa = tratarChave($_POST['empresa']);
   $conta = tratarChave($_POST['conta']);
   $data = tratarData($_POST['data']);
   $saldo_inicial = tratarValor($_POST['saldo_inicial']);
   $saldo_abertura = tratarValor($_POST['saldo_abertura']);
   $valor_entrada = tratarValor($_POST['valor_entrada']);
   $saldo_encerramento = tratarValor($_POST['saldo_encerramento']);
   $usuario_aprovado = tratarChave($_POST['usuario_aprovado']);
   $_action = $_POST['_action'];
   
   if ($_action != "exclusao") {
         // validar campos
         if (empty($empresa)) {
	         http_response_code(400);
	         echo "Informe a empresa.";
	         return;  
         }
         
         if (empty($conta)) {
	         http_response_code(400);
	         echo "Informe a conta.";
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
         $sql = "insert into gerenciador (empresa, conta, data, saldo_inicial, saldo_abertura, valor_entrada, valor_saida, saldo_encerramento) values (" . $empresa . ", '" . $conta  . "', '" . $data . "', '" . $saldo_inicial  . "', '" . $saldo_abertura  . "', '" . $valor_entrada  . "', '" . $valor_saida  . "', '" . $saldo_encerramento  . "');";
         $msg1 = "incluir";
         $msg2 = "inclusão";
   }
   
   if ($_action == "alteracao") {
         $sql = "update gerenciador set empresa='" . $empresa . "', conta= '" . $conta . "', data= '" . $data . "', saldo_inicial= '" . $saldo_inicial . "', saldo_abertura= '" . $saldo_abertura . "', valor_entrada= '" . $valor_entrada . "', valor_saida= '" . $valor_saida . "', saldo_encerramento= '" . $saldo_encerramento . "', where id=" . $id;
         $msg1 = "alterar";
         $msg2 = "alterado";
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from gerenciador where id=" . $id;
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