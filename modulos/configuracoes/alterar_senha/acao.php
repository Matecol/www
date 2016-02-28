<?php

   require_once BASE_DIR . '/util/conexao.php';
   require_once BASE_DIR . '/util/permissao.php';
   require_once BASE_DIR . '/util/util.php';
   require_once BASE_DIR . '/util/sessao.php';

   // validar sessao
   validarSessao();
   
   // acao        
   $senha_atual = tratarTextoSimples($_POST['senha_atual']);
   $nova_senha = tratarTextoSimples($_POST['nova_senha']);
   $confirmacao_senha = tratarTextoSimples($_POST['confirmacao_senha']);
   $_action = $_POST['_action'];

   // validar campos
   if (empty($senha_atual)) {
         http_response_code(400);
         echo "Informe a senha atual.";
         return;  
   }
   
   if (empty($nova_senha)) {
         http_response_code(400);
         echo "Informe a nova senha.";
         return;  
   }
   
   if((strlen($nova_senha)) < 6){
         http_response_code(400);
	   echo "A senha deve conter no mínimo 6 dígitos.";
         return;
   }
   
   if ($nova_senha != $confirmacao_senha) {
         http_response_code(400);
	   echo "Senhas não conferem.";
	   return;
   }
   
   if (empty($_action)) {
	   http_response_code(400);
	   echo "Falha nos parâmetros da solicitação.";
         return;
   }
   
   // Abrir conexao
   $conexao = new Conexao();
   
   // Módulo do usuário
   $senha_bd = pg_fetch_all($conexao->query("select senha from usuarios where id=" . $_SESSION['id']))[0]['senha'];

   if (sha1($senha_atual) != $senha_bd) {
         http_response_code(400);
	   echo "Senha incorreta.";
	   return;
   }						
   
   // Testar acao
   $sql = "";
      
   $sql = "update usuarios set senha='" . sha1($nova_senha) . "' where id=" . $_SESSION['id'];
   $msg1 = "alterar";
   $msg2 = "alterado";
   
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
