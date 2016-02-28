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
         case "inclusao": $nperm = "INCLUIR CADASTRO DE EMPRESAS";break;
         case "alteracao": $nperm = "ALTERAR CADASTRO DE EMPRESAS";break;
         case "exclusao": $nperm = "EXCLUIR CADASTRO DE EMPRESAS";break;
   }
   
   $perm = testarPermissao($nperm);
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao         
   $id = tratarChave($_POST['id']);
   $cnpj = tratarNumero($_POST['cnpj']);
   $ie = tratarNumero($_POST['ie']);   
   $im = tratarTexto($_POST['im']);   
   $razaosocial = tratarTexto($_POST['razaosocial']);   
   $nomefantasia = tratarTexto($_POST['nomefantasia']);   
   $endereco = tratarTexto($_POST['endereco']);   
   $bairro = tratarTexto($_POST['bairro']);   
   $cep = tratarNumero($_POST['cep']);   
   $municipio = tratarChave($_POST['municipio']);
   $telefone = tratarNumero($_POST['telefone']);   
   $fax = tratarNumero($_POST['fax']);
   $email = tratarTextoMinusculo($_POST['email']);   
   
   $_action = $_POST['_action'];
   
   if ($_action != "exclusao") {
         // validar campos
         if (empty($cnpj)) {
	         http_response_code(400);
        	   echo "Informe o CNPJ da empresa.";
	         return;  
         }
         
         if (empty($ie)) {
	         http_response_code(400);
        	   echo "Informe o Inscrição Estadual da empresa.";
	         return;  
         }
         
         if (empty($razaosocial)) {
	         http_response_code(400);
        	   echo "Informe o razão social da empresa.";
	         return;  
         }         
   
         if (empty($nomefantasia)) {
	         http_response_code(400);
        	   echo "Informe o nome fantasia da empresa.";
	         return;  
         }
       
         if (empty($endereco)) {
	         http_response_code(400);
        	   echo "Informe o endereço da empresa.";
	         return;  
         }

         if (empty($bairro)) {
	         http_response_code(400);
        	   echo "Informe o bairro da empresa.";
	         return;  
         }
   
         if (empty($cep)) {
	         http_response_code(400);
        	   echo "Informe o CEP da empresa.";
	         return;  
         }
         
          if (empty($municipio)) {
	         http_response_code(400);
        	   echo "Informe o município da empresa.";
	         return;  
         }
       
         if (empty($telefone)) {
	         http_response_code(400);
        	   echo "Informe o telefone da empresa.";
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
         $sql = "insert into empresas (cnpj, ie, im, razaosocial, nomefantasia, endereco, bairro, cep, municipio, telefone, fax, email) values ('" . $cnpj . "', '" . $ie . "', '" . $im . "', '" . $razaosocial . "', '" . $nomefantasia . "', '" . $endereco . "', '" . $bairro . "', '" . $cep . "', " . $municipio . ", '" . $telefone . "', '"  . $fax . "', '" . $email . "');";
         $msg1 = "incluir";
         $msg2 = "inclusão";
   }
   
   if ($_action == "alteracao") {
         $sql = "update empresas set cnpj=" . $cnpj . ",ie=" . $ie . ",im=" . $im . ",razaosocial='" . $razaosocial . "',nomefantasia='" . $nomefantasia . "',endereco='" . $endereco . "',bairro='" . $bairro . "',cep=" . $cep . ",municipio=" . $municipio . ",telefone=" . $telefone . ",fax=" . $fax . ",email='" . $email . "' where id=" . $id;
         $msg1 = "alterar";
         $msg2 = "alterado";
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from empresas where id=" . $id;
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
