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
         case "inclusao": $nperm = "INCLUIR CADASTRO DE VENDEDORES";break;
         case "alteracao": $nperm = "ALTERAR CADASTRO DE VENDEDORES";break;
         case "exclusao": $nperm = "EXCLUIR CADASTRO DE VENDEDORES";break;
   }
   
   $perm = testarPermissao($nperm);
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao        
   $id = tratarChave($_POST['id']);
   $nome = tratarTexto($_POST['nome']);
   $empresa = (int)$_POST['empresa'];
   $tipo = tratarTexto($_POST['tipo']);
   $comissao_pecas = tratarNumero($_POST['comissao_pecas']);
   $comissao_servicos = tratarNumero($_POST['comissao_servicos']);
   $situacao = tratarTexto($_POST['situacao']);
   $_action = $_POST['_action'];
   
   if ($_action != "exclusao") {

         // validar campos
         if (empty($nome)) {
	         http_response_code(400);
	         echo "Informe o nome do vendedor.";
	         return;  
         }
         
         if (empty($empresa)) {
	         http_response_code(400);
	         echo "Informe a empresa do vendedor.";
	         return;  
         }
         
         if (empty($tipo)) {
	         http_response_code(400);
	         echo "Informe o tipo do vendedor.";
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
         $sql = "insert into vendedores (nome, empresa, tipo, comissao_pecas, comissao_servicos, situacao) values ('" . $nome . "', " . $empresa . ", '" . $tipo . "', '" . $comissao_pecas . "', '" . $comissao_servicos . "', '" . $situacao . "');";
         $msg1 = "incluir";
         $msg2 = "inclusão";
   }
   
   if ($_action == "alteracao") {
         $sql = "update vendedores set nome='" . $nome . "',empresa=" . $empresa . ",tipo='" . $tipo . "',comissao_pecas='" . $comissao_pecas . "',comissao_servicos='" . $comissao_servicos . "',situacao='" . $situacao . "' where id=" . $id;
         $msg1 = "alterar";
         $msg2 = "alterado";
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from nome where id=" . $id;
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
