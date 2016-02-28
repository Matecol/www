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
         $sql = "select * from municipios where id=" . $id;
         echo json_encode(pg_fetch_all($conexao->query($sql)));
         return;
   }
   
   // testar permissao
   $nperm = "";
   switch($_POST['_action']) {
         case "inclusao": $nperm = "INCLUIR CADASTRO DE MUNICIPIOS";break;
         case "alteracao": $nperm = "ALTERAR CADASTRO DE MUNICIPIOS";break;
         case "exclusao": $nperm = "EXCLUIR CADASTRO DE MUNICIPIOS";break;
   }
   
   $perm = testarPermissao($nperm);
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao
   $id = tratarChave($_POST['id']);
   $municipio = tratarTexto($_POST['municipio']);
   $uf = tratarTexto($_POST['uf']);
   $ibge = (int)$_POST['ibge'];
   $_action = $_POST['_action'];
   
   if ($_action != "exclusao") {
         // validar campos
         if (empty($municipio)) {
	         http_response_code(400);
        	   echo "Informe o nome do município.";
	         return;  
         }
   
         if (empty($ibge)) {
	         http_response_code(400);
	         echo "Informe o código IBGE.";
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
         $sql = "insert into municipios (municipio, uf, ibge) values ('" . $municipio . "', '" . $uf . "', " . $ibge . ');';
         $msg1 = "incluir";
         $msg2 = "inclusão";
   }
   
   if ($_action == "alteracao") {
         $sql = "update municipios set municipio='" . $municipio . "',uf='" . $uf . "',ibge=" . $ibge . " where id=" . $id;
         $msg1 = "alterar";
         $msg2 = "alterado";
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from municipios where id=" . $id;
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
