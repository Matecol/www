<?php

   require_once BASE_DIR . '/util/conexao.php';
   require_once BASE_DIR . '/util/permissao.php';
   require_once BASE_DIR . '/util/util.php';
   require_once BASE_DIR . '/util/sessao.php';
   
   // validar sessao
   validarSessao();
   
   // testar programa
   $nperm = "";
   switch($_POST['_action']) {
         case "inclusao": $nperm = "INCLUIR PROGRAMAS DO USUARIO";break;
         case "alteracao": $nperm = "ALTERAR PROGRAMAS DO USUARIO";break;
         case "exclusao": $nperm = "EXCLUIR PROGRAMAS DO USUARIO";break;
   }
   
   $perm = testarprograma($nperm);
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao         
   $id = tratarChave($_POST['id']);
   $usuario = tratarChave($_POST['usuario']);
   $programa = tratarChave($_POST['programa']);   
   $valor = tratarTexto($_POST['valor']);
   $_action = $_POST['_action'];
   
   if ($_action != "exclusao") {
         // validar campos
         if (empty($usuario)) {
	         http_response_code(400);
        	   echo "Informe o usuário.";
	         return;  
         }
         
         if (empty($valor)) {
	         http_response_code(400);
        	   echo "Informe o programa.";
	         return;  
         }
         
         
         if (empty($valor)) {
	         http_response_code(400);
        	   echo "Informe o valor.";
	         return;  
         }
      
         if (empty($_action)) {
	         http_response_code(400);
	         echo "Falha nos parâmetros da solicitação.";
               return;
         }
   }
   
   // Abrir conexao
   $conexao = new Conexao();

   if ($_action == "inclusao") {
      $sql = "";
   
      // Verifica se chave já existe
      $sql = "select * from programas_usuario where usuario=" . $usuario . " and permissao=" . $permissao;
      $result = $conexao->query($sql);
			
      // Abrir resultado
      $rows = pg_fetch_all($result);

      if ($rows != null) {
            http_response_code(400);
            echo "Chave já existe.";
            return;
      }
   }
                        
   // Testar acao
   $sql = "";
   
   if ($_action == "inclusao") {
         $sql = "insert into programas_usuario (usuario, programa, valor) values (" . $usuario . ", " . $programa . ", '" . $valor . "');";
         $msg1 = "incluir";
         $msg2 = "inclusão";
   }
   
   if ($_action == "alteracao") {
         $sql = "update programas_usuario set usuario=" . $usuario . ",programa=" . $programa . ",valor='" . $valor . "' where id=" . $id;
         $msg1 = "alterar";
         $msg2 = "alterado";
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from programas_usuario where id=" . $id;
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
