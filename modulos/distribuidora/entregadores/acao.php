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
         case "inclusao": $nperm = "INCLUIR CADASTRO DE ENTREGADORES";break;
         case "alteracao": $nperm = "ALTERAR CADASTRO DE ENTREGADORES";break;
         case "exclusao": $nperm = "EXCLUIR CADASTRO DE ENTREGADORES";break;
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
   $cpf = tratarNumero($_POST['cpf']);
   $telefone = tratarNumero($_POST['telefone']);
   $cnh = tratarTexto($_POST['cnh']);
   $categoria_cnh = tratarTexto($_POST['categoria_cnh']);
   $comissao = tratarTexto($_POST['comissao']);
   $_action = $_POST['_action'];
   
   if ($_action != "exclusao") {

         // validar campos
         if (empty($nome)) {
	         http_response_code(400);
	         echo "Informe o nome do entregador.";
	         return;  
         }
         
         if (empty($cpf)) {
	         http_response_code(400);
	         echo "Informe o cpf do entregador.";
	         return;  
         }
              
         if( $telefone > 0 && ((strlen($telefone)) < 10)){
	         http_response_code(400);
	         echo "Telefone inválido.";
	         return;
         }
         
         if( $cnh > 0 && ((strlen($cnh)) < 11)){
	         http_response_code(400);
	         echo "CNH inválida.";
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
         $sql = "insert into entregadores (nome, cpf, telefone, cnh, categoria_cnh, comissao) values ('" . $nome . "', '" . $cpf . "', '" . $telefone . "', '" . $cnh . "', '" . $categoria_cnh . "', '" . $comissao . "' );";
         $msg1 = "incluir";
         $msg2 = "inclusão";
   }
   
   if ($_action == "alteracao") {
         $sql = "update entregadores set nome='" . $nome . "',cpf='" . $cpf . "',telefone='" . $telefone . "',cnh='" . $cnh . "',categoria_cnh='" . $categoria_cnh . "',comissao='" . $comissao . "' where id=" . $id;
         $msg1 = "alterar";
         $msg2 = "alterado";
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from entregadores where id=" . $id;
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
