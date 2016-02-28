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
         case "inclusao": $nperm = "INCLUIR CADASTRO DE UNIDADE DE MEDIDA";break;
         case "alteracao": $nperm = "ALTERAR CADASTRO DE UNIDADE DE MEDIDA";break;
         case "exclusao": $nperm = "EXCLUIR CADASTRO DE UNIDADE DE MEDIDA";break;
   }
   
   $perm = testarPermissao($nperm);
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao        
   $id = tratarChave($_POST['id']);
   $unidade = tratarTexto($_POST['unidade']);
   $descricao = tratarTexto($_POST['descricao']);
   $_action = $_POST['_action'];
   
   if ($_action != "exclusao") {

         // validar campos
         if (empty($unidade)) {
	         http_response_code(400);
	         echo "Informe a sigla da unidade de medida.";
	         return;  
         }
   
         if (empty($descricao)) {
	         http_response_code(400);
	         echo "Informe a descrição.";
       	   return;  
         }
         
         if((strlen($unidade)) < 2){
	         http_response_code(400);
	         echo "Sigla da unidade de medida inválida.";
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
         $sql = "insert into unidades_medida (unidade, descricao) values ('" . $unidade . "', '" . $descricao . "');";
         $msg1 = "incluir";
         $msg2 = "inclusão";
   }
   
   if ($_action == "alteracao") {
         $sql = "update unidades_medida set unidade='" . $unidade . "',descricao='" . $descricao . "' where id=" . $id;
         $msg1 = "alterar";
         $msg2 = "alterado";
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from unidades_medida where id=" . $id;
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
