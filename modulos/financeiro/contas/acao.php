<?php

   require_once BASE_DIR . '/util/conexao.php';
   require_once BASE_DIR . '/util/permissao.php';
   require_once BASE_DIR . '/util/util.php';
   require_once BASE_DIR . '/util/sessao.php';
   
   // validar sessao
   validarSessao();
   
   // retornar cadastro em JSON
   if ($_POST['_action'] == "consultar") {
         $id = $_POST['id'];
         
         $conexao = new Conexao();
         $sql = "select * from contas_movimento_financeiro where id=" . $id;
         echo json_encode(pg_fetch_all($conexao->query($sql)));
         return;
   }
   
   // testar permissao
   $nperm = "";
   switch($_POST['_action']) {
         case "inclusao": $nperm = "INCLUIR CADASTRO DE CONTAS DO MOVIMENTO FINANCEIRO";break;
         case "alteracao": $nperm = "ALTERAR CADASTRO DE CONTAS DO MOVIMENTO FINANCEIRO";break;
         case "exclusao": $nperm = "EXCLUIR CADASTRO DE CONTAS DO MOVIMENTO FINANCEIRO";break;
   }
   
   $perm = testarPermissao($nperm);
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao
   $id = tratarChave($_POST['id']);
   $loja = (int)$_POST['loja'];
   $nome = tratarTexto($_POST['nome']);
   $tipo = tratarTexto($_POST['tipo']);
   $categoria = tratarTexto($_POST['categoria']);
   $transfere_saldo = tratarTexto($_POST['transfere_saldo']);   
   $banco = (int)$_POST['banco'];
   $agencia = tratarTexto($_POST['agencia']);   
   $conta_corrente = tratarTexto($_POST['conta_corrente']);      
   $lancamento_manual = tratarTexto($_POST['lancamento_manual']);
   $codigo_banco = tratarTexto($_POST['codigo_banco']);
               
   if ($_action != "exclusao") {
         // validar campos
         if (empty($loja)) {
	         http_response_code(400);
        	   echo "Informe a loja da conta.";
	         return;  
         }
         
         if (empty($nome)) {
	         http_response_code(400);
        	   echo "Informe o nome da conta.";
	         return;  
         }

         if (empty($tipo)) {
	         http_response_code(400);
        	   echo "Informe o tipo da conta.";
	         return;  
         }   
         
         if (empty($categoria)) {
	         http_response_code(400);
        	   echo "Informe a categoria da conta.";
	         return;  
         }
         
         if (empty($transfere_saldo)) {
	         http_response_code(400);
        	   echo "Informe se a conta permite transferencia de saldo.";
	         return;  
         }                  

         if (empty($banco)) {
	         http_response_code(400);
        	   echo "Informe o banco da conta.";
	         return;  
         }
         
         if (empty($agencia)) {
	         http_response_code(400);
        	   echo "Informe a agência da conta.";
	         return;  
         }
         
         if (empty($conta_corrente)) {
	         http_response_code(400);
        	   echo "Informe a conta corrente.";
	         return;  
         }
         
         if (empty($lancamento_manual)) {
	         http_response_code(400);
        	   echo "Informe se a conta aceita lançamentos manuais.";
	         return;  
         }
         
         if (empty($codigo_banco)) {
	         http_response_code(400);
        	   echo "Informe o código do banco.";
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
         $sql = "insert into contas_movimento_financeiro (loja, nome, tipo, categoria, transfere_saldo, banco, agencia, conta_corrente, lancamento_manual, codigo_banco) values ('" . $loja . "', '" . $nome . "', '" . $tipo . "', '" . $categoria . "', '" . $transfere_saldo . "', '" . $banco . "', '" . $agencia . "', '" . $conta_corrente . "', '" . $lancamento_manual . "', " . $codigo_banco . ');';
         $msg1 = "incluir";
         $msg2 = "inclusão";
   }
   
   if ($_action == "alteracao") {
         $sql = "update contas_movimento_financeiro set loja='" . $loja . "',nome='" . $nome . "',tipo='" . $tipo . "',categoria='" . $categoria . "',transfere_saldo='" . $transfere_saldo . "',banco='" . $banco . "',agencia='" . $agencia . "',conta_corrente='" . $conta_corrente . "',lancamento_manual='" . $lancamento_manual . "',conta_banco=" . $conta_banco . " where id=" . $id;
         $msg1 = "alterar";
         $msg2 = "alterado";
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from contas_movimento_financeiro where id=" . $id;
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
