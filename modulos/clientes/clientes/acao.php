<?php
   require_once BASE_DIR . '/util/conexao.php';
   require_once BASE_DIR . '/util/permissao.php';
   require_once BASE_DIR . '/util/util.php';
   require_once BASE_DIR . '/util/sessao.php';
   require_once BASE_DIR . '/util/criptografia.php';

   // validar sessao
   validarSessao();
   
   // Acao
   $_action = descriptografar($_POST['_action']);
   
   // retornar cadastro em JSON
   if ($_POST['_action'] == "consultar") {
         $cnpj = $_POST['cnpj'];
         
         $conexao = new Conexao();
         $sql = "select A.*, B.municipio || ' / ' || B.uf as nome_municipio_entrega, C.municipio || ' / ' || C.uf as nome_municipio_cobranca from clientes A join municipios B on A.municipio_entrega = B.id join municipios C on A.municipio_cobranca = C.id where A.cnpj='" . $cnpj . "';";
         echo json_encode(pg_fetch_all($conexao->query($sql)));
         return;
   }
   
   // testar permissao
   $nperm = "";
   switch($_POST['_action']) {
         case "inclusao": $nperm = "INCLUIR CADASTRO DE CLIENTES";break;
         case "inclusaodup": $nperm = "INCLUIR CADASTRO DE CLIENTES COM CPF/CNPJ DUPLICADO";break;
         case "alteracao": $nperm = "ALTERAR CADASTRO DE CLIENTES";break;
         case "exclusao": $nperm = "EXCLUIR CADASTRO DE CLIENTES";break;
   }
   
   $perm = testarPermissao($nperm);
   $cnpjPerm = testarPermissao("ALTERAR CNPJ DO CADASTRO DE CLIENTES");
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao
   $id = descriptografar($_POST['id']);
   $cnpj = tratarTexto($_POST['cnpj']);
   $ie = tratarTexto($_POST['ie']);
   $im = tratarTexto($_POST['im']);
   $razaosocial = tratarTexto($_POST['razaosocial']);
   $nomefantasia = tratarTexto($_POST['nomefantasia']);
   $endereco_entrega = tratarTexto($_POST['endereco_entrega']);
   $bairro_entrega  = tratarTexto($_POST['bairro_entrega']);
   $cep_entrega = tratarNumero($_POST['cep_entrega']);
   $municipio_entrega = tratarChave($_POST['municipio_entrega']);
   $telefone_entrega = tratarNumero($_POST['telefone_entrega']);
   $celular_entrega = tratarNumero($_POST['celular_entrega']);
   $endereco_cobranca = tratarTexto($_POST['endereco_cobranca']);
   $bairro_cobranca  = tratarTexto($_POST['bairro_cobranca']);
   $cep_cobranca = tratarNumero($_POST['cep_cobranca']);
   $municipio_cobranca = tratarChave($_POST['municipio_cobranca']);
   $telefone_cobranca = tratarNumero($_POST['telefone_cobranca']);
   $celular_cobranca = tratarNumero($_POST['celular_cobranca']);
   $email01 = tratarTextoSimples($_POST['email01']);
   $email02 = tratarTextoSimples($_POST['email02']);
   $autorizado_comprar = tratarTexto($_POST['autorizado_comprar']);
   $observacoes = tratarTexto($_POST['observacoes']);
   
   if ($_action != "exclusao") {
         
         // CPF/CNPJ
         if (empty($cnpj)) {
               http_response_code(400);
               echo "Informe o CPF/CNPJ.~cnpj";
               return;
         }
         
         if ($cnpj != "ISENTO") {
               $cnpj = tratarNumero($cnpj);
               if (! validarCpfCnpj($cnpj)) {
                     http_response_code(400);
                     echo "CPF/CNPJ inválido.~cnpj";
                     return;
               }
         }
         
         // INSCRICAO ESTADUAL
         if (empty($ie)) {
               http_response_code(400);
               echo "Informe a Inscrição Estadual / RG.~ie";
               return;
         }
         
         // validar campos
         if (empty($razaosocial)) {
	         http_response_code(400);
	         echo "Informe a Razão Social.~razaosocial";
	         return;  
         }
         
         if( $telefone_entrega > 0 && ((strlen($telefone_entrega)) < 10)){
	         http_response_code(400);
	         echo "Telefone de entrega inválido.";
	         return;
         }
   
         if( $telefone_cobranca > 0 && (strlen($telefone_cobranca)) < 10){
	         http_response_code(400);
	         echo "Telefone de cobrança inválido.";
	         return;
         }
         
         if(!validaEmail($email1)){
               http_response_code(400);
               echo "E-mail principal inválido!!!";
               return;  
         }
         
         if(!validaEmail($email2)){
               http_response_code(400);
               echo "E-mail secundário inválido!!!";
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
   
   if ($_action == "inclusao" || $_action == "inclusaodup") {
         $sql = "insert into clientes (cnpj, ie, im, razaosocial, nomefantasia, endereco_entrega, bairro_entrega, cep_entrega, municipio_entrega, telefone_entrega, celular_entrega, endereco_cobranca, bairro_cobranca, cep_cobranca, municipio_cobranca, telefone_cobranca, celular_cobranca, email01, email02, autorizado_comprar, observacoes) values ('" . $cnpj . "', '" . $ie . "', '" . $im . "', '" . $razaosocial . "', '" . $nomefantasia . "', '" . $endereco_entrega . "', '" . $bairro_entrega . "', '" . $cep_entrega . "', " . $municipio_entrega . ", '" . $telefone_entrega . "', '" . $celular_entrega . "', '" . $endereco_cobranca . "', '" . $bairro_cobranca . "', '" . $cep_cobranca . "', " . $municipio_cobranca . ", '" . $telefone_cobranca . "', '" . $celular_cobranca . "', '" . $email01 . "', '" . $email02 . "', '" . $autorizado_comprar . "', '" . $observacoes . "');";
         $msg1 = "incluir";
         $msg2 = "inclusão";
                
   }
   
   if ($_action == "alteracao") {
         if ($cnpjPerm != 'S') {
            $sql = "update clientes set ie='" . $ie . "',im='" . $im . "',razaosocial='" . $razaosocial . "',nomefantasia='" . $nomefantasia . "',endereco_entrega='" . $endereco_entrega . "',bairro_entrega='" . $bairro_entrega . "',cep_entrega='" . $cep_entrega . "',municipio_entrega=" .      $municipio_entrega . ",telefone_entrega='" . $telefone_entrega . "',celular_entrega='" . $celular_entrega . "',endereco_cobranca='" . $endereco_cobranca . "',bairro_cobranca='" . $bairro_cobranca . "',cep_cobranca='" . $cep_cobranca . "',municipio_cobranca=" . $municipio_cobranca . ",telefone_cobranca='" . $telefone_cobranca . "',celular_cobranca='" . $celular_cobranca . "',email01='" . $email01 . "',email02='" . $email02 . "',autorizado_comprar='" . $autorizado_comprar . "',observacoes='" . $observacoes . "' where id=" . $id;
         } else {
            $sql = "update clientes set cnpj='" . $cnpj . "',ie='" . $ie . "',im='" . $im . "',razaosocial='" . $razaosocial . "',nomefantasia='" . $nomefantasia . "',endereco_entrega='" . $endereco_entrega . "',bairro_entrega='" . $bairro_entrega . "',cep_entrega='" . $cep_entrega . "',municipio_entrega=" .      $municipio_entrega . ",telefone_entrega='" . $telefone_entrega . "',celular_entrega='" . $celular_entrega . "',endereco_cobranca='" . $endereco_cobranca . "',bairro_cobranca='" . $bairro_cobranca . "',cep_cobranca='" . $cep_cobranca . "',municipio_cobranca=" . $municipio_cobranca . ",telefone_cobranca='" . $telefone_cobranca . "',celular_cobranca='" . $celular_cobranca . "',email01='" . $email01 . "',email02='" . $email02 . "',autorizado_comprar='" . $autorizado_comprar . "',observacoes='" . $observacoes . "' where id=" . $id;
         }
         $msg1 = "alterar";
         $msg2 = "alterado";
         
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from clientes where id=" . $id;
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
         echo "Falha ao " . $msg1 . " registro. Tente novamente mais tarde ou contate o suporte.{" . $id . "}";
         return;
   }

   echo "Registro " . $msg2 . " com sucesso.";
   
?>
