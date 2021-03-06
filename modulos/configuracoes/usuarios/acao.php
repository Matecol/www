<?php
   require_once BASE_DIR . '/util/conexao.php';
   require_once BASE_DIR . '/util/permissao.php';
   require_once BASE_DIR . '/util/util.php';
   require_once BASE_DIR . '/util/sessao.php';

   // validar sessao
   validarSessao();
   
   // retornar cadastro em JSON
   if ($_POST['_action'] == "consultar") {
         $usuario = $_POST['usuario'];
         if (empty($usuario)) {
               return;
         }
         
         $conexao = new Conexao();
         $sql = "select * from usuarios where id=" . $usuario;
         echo json_encode(pg_fetch_all($conexao->query($sql)));
         return;
   }
   
   // testar permissao
   $nperm = "";
   switch($_POST['_action']) {
         case "inclusao": $nperm = "INCLUIR CADASTRO DE USUARIOS";break;
         case "alteracao": $nperm = "ALTERAR CADASTRO DE USUARIOS";break;
         case "exclusao": $nperm = "EXCLUIR CADASTRO DE USUARIOS";break;
   }
   
   $perm = testarPermissao($nperm);
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao   
   $id = tratarChave($_POST['id']);
   $login = tratarTexto($_POST['login']);
   $senha = tratarTextoSimples($_POST['senha']);
   $confirmacao_senha = tratarTextoSimples($_POST['confirmacao_senha']);
   $nome = tratarTexto($_POST['nome']);
   $modelo = (int)$_POST['modelo'];
   $empresa = tratarChave($_POST['empresa']);
   $nivel = (int)$_POST['nivel'];
   $externo = tratarTexto($_POST['externo']);
   $mobile = tratarTexto($_POST['mobile']);
   $telefone = tratarNumero($_POST['telefone']);
   $email = tratarTextoMinusculo($_POST['email']);
   $ramal = tratarNumero($_POST['ramal']);
   $bloqueado = tratarTexto($_POST['bloqueado']);
   $observacoes = tratarTexto($_POST['observacoes']);
   $_action = $_POST['_action'];
   
    if ($_action != "exclusao") {
          // validar campos
          if (empty($nome)) {
	          http_response_code(400);
	          echo "Informe o nome do usuário.";
	          return;
          }
   
          if (empty($login)) {
	          http_response_code(400);
	          echo "Informe o login.";
	          return;  
          }
   
         if ($_action == "inclusao") {
               if((strlen($senha)) < 6){
	              http_response_code(400);
	               echo "O campo senha deve conter no mínimo 6 dígitos.";
      	         return;
               }
         
               if (empty($senha)) {
	              http_response_code(400);
	               echo "Informe a senha.";
      	         return;
               }
   
               if ($senha != $confirmacao_senha) {
      	         http_response_code(400);
      	         echo "Senhas não conferem.";
      	         return;
               }
               
               if( $telefone > 0 && ((strlen($telefone)) < 10)){
	             http_response_code(400);
	             echo "Telefone inválido.";
	             return;
               }
               
               if(!validaEmail($email)){
                   http_response_code(400);
                   echo "E-mail inválido!!!";
                   return;  
               }
         }
     
         if (empty($empresa)) {
	         http_response_code(400);
	         echo "Informe a empresa.";
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
   
   // Testar acao
   $sql = "";
   
   if ($_action == "inclusao") {
         $sql = "insert into usuarios (login, senha, nome, modelo, empresa, nivel, externo, mobile, telefone, ramal, email, bloqueado, observacoes) values ('" . $login . "', '" . sha1($senha) . "', '" . $nome . "', " . $modelo . ", " . $empresa . ", " . $nivel . ", '" . $externo . "', '" . $mobile . "', '" . $telefone . "', '" . $ramal . "', '" . $email . "', '" . $bloqueado . "', '" . $observacoes . "');";
         $msg1 = "incluir";
         $msg2 = "inclusão";
   }
   
   if ($_action == "alteracao") {
         $sql = "update usuarios set nome='" . $nome . "',modelo=" . $modelo . ",empresa=" . $empresa . ",nivel=" . $nivel . ",externo='" . $externo . "',mobile='" . $mobile . "',telefone='" . $telefone . "',ramal='" . $ramal . "',email='" . $email . "',bloqueado='" . $bloqueado . "',observacoes='" . $observacoes ."' where id=" . $id;
         $msg1 = "alterar";
         $msg2 = "alterado";
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from usuarios where id=" . $id;
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
         echo "Falha ao " . $msg1 . " registro. Tente novamente mais tarde ou contate o suporte." . $sql;
         return;
   }

   echo "Registro " . $msg2 . " com sucesso.";
   
?>
