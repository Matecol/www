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
         case "inclusao": $nperm = "INCLUIR CONTATO NA AGENDA";break;
         case "alteracao": $nperm = "ALTERAR CONTATO DA AGENDA";break;
         case "exclusao": $nperm = "EXCLUIR CONTATO DA AGENDA";break;
   }
   
   $perm = testarPermissao($nperm);
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao   
   $razaosocial = tratarTexto($_POST['razaosocial']);
   $id = tratarChave($_POST['id']);
   $endereco = tratarTexto($_POST['endereco']);
   $bairro = tratarTexto($_POST['bairro']);
   $cep = tratarNumero($_POST['cep']);
   $municipio = tratarChave($_POST['municipio']);
   $telefone = tratarNumero($_POST['telefone']);
   $celular = tratarNumero($_POST['celular']);
   $email = tratarTextoMinusculo($_POST['email']);
   $observacoes = tratarTexto($_POST['observacoes']);
   $_action = $_POST['_action'];
   
   if ($_action != "exclusao") {
         
         // validar campos
         if (empty($razaosocial)) {
	         http_response_code(400);
	         echo "Informe o nome do contato.";
	         return;  
         }
   
         if (empty($telefone)) {
	         http_response_code(400);
	         echo "Informe o telefone do contato.";
	         return;
         }
         
         if( $cep > 0 && ((strlen($cep)) < 8)){
	         http_response_code(400);
	         echo "CEP inválido.";
	         return;
         }
         
         if( $telefone > 0 && ((strlen($telefone)) < 10)){
	         http_response_code(400);
	         echo "Telefone inválido.";
	         return;
         }
         
         if( $telefone > 0 && ((strlen($telefone)) < 10)){
	         http_response_code(400);
	         echo "Telefone inválido.";
	         return;
         }
         
         if( $celular > 0 && (strlen($celular)) < 10){
	         http_response_code(400);
	         echo "Número de celular inválido.";
	         return;
         }
         
         if(!validaEmail($email)){
               http_response_code(400);
               echo "E-mail inválido!!!";
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
         $sql = "insert into agenda (razaosocial, endereco, bairro, cep, municipio, telefone, celular, email, observacoes) values ('" . $razaosocial . "', '" . $endereco . "', '" . $bairro . "', '" . $cep . "', " . $municipio . ", '" . $telefone . "', '" . $celular . "', '" . $email . "', '" . $observacoes . "');";
         $msg1 = "incluir";
         $msg2 = "inclusão";
                
   }
   
   if ($_action == "alteracao") {
         $sql = "update agenda set razaosocial='" . $razaosocial . "',endereco='" . $endereco . "',bairro='" . $bairro . "',cep='" . $cep . "',municipio=" . $municipio . ",telefone='" . $telefone . "',celular='" . $celular . "',email='" . $email . "',observacoes='" . $observacoes . "' where id=" . $id;
         $msg1 = "alterar";
         $msg2 = "alterado";
         
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from agenda where id=" . $id;
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
