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
         case "inclusao": $nperm = "INCLUIR CADASTRO DE VEICULOS";break;
         case "alteracao": $nperm = "ALTERAR CADASTRO DE VEICULOS";break;
         case "exclusao": $nperm = "EXCLUIR CADASTRO DE VEICULOS";break;
   }
   
   $perm = testarPermissao($nperm);
   
   if ($perm != 'S') {
         http_response_code(401);
         echo "Sem permissão: " . $nperm . ". Solicite ao administrador a liberação.";
         return;
   }
   
   // acao        
   $id = tratarChave($_POST['id']);
   $placa = tratarTexto($_POST['placa']);
   $municipio_placa = tratarTexto($_POST['municipio_placa']);
   $uf_placa = tratarTexto($_POST['uf_placa']);
   $descricao = tratarTexto($_POST['descricao']);
   $renavan = tratarTexto($_POST['renavan']);
   $chassi = tratarTexto($_POST['chassi']);
   $marca = tratarTexto($_POST['marca']);
   $modelo = tratarTexto($_POST['modelo']);
   $ano_modelo = tratarNumero($_POST['ano_modelo']);
   $ano_fabricacao = tratarNumero($_POST['ano_fabricacao']);
   $cor = tratarTexto($_POST['cor']);
   $combustivel = tratarTexto($_POST['combustivel']);
   $tipo = tratarTexto($_POST['tipo']);
   $observacoes = tratarTexto($_POST['observacoes']);
   $_action = $_POST['_action'];
   
   if ($_action != "exclusao") {

         // validar campos
         if (empty($descricao)) {
	         http_response_code(400);
	         echo "Informe a descricao.";
	         return;  
         }
         
         if( $placa > "" && (strlen($placa)) < 8){
	         http_response_code(400);
	         echo "Placa inválida.";
	         return;
         }
         
         if((strlen($uf_placa)) < 2 && $uf_placa > ""){
	         http_response_code(400);
	         echo "UF inválida.";
	         return;
         }
         
         $year = date('Y');
         if ($ano_fabricacao < 1970 && $ano_fabricacao != 0)  {
	         http_response_code(400);
	         echo "Ano de Fabricação Inválido.";
	         return;  
         }
         
         if (($ano_modelo < 1970 && $ano_modelo != 0) || $ano_modelo > ($year + 1))  {
	         http_response_code(400);
	         echo "Ano do Modelo Inválido.";
	         return;  
         }
         
         if ($ano_modelo < $ano_fabricacao)  {
	         http_response_code(400);
	         echo "Ano do modelo não pode ser menor que Ano de fabricação.";
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
         $sql = "insert into veiculos (placa, municipio_placa, uf_placa, descricao, renavan, chassi, marca, modelo, ano_modelo, ano_fabricacao, cor, combustivel, tipo, observacoes) values ('" . $placa . "', '" . $municipio_placa . "', '" . $uf_placa . "', '" . $descricao . "', '" . $renavan . "', '" . $chassi . "', '" . $marca . "', '" . $modelo . "', " . $ano_modelo . ", " . $ano_fabricacao . ", '" . $cor . "', '" . $combustivel . "', '" . $tipo . "', '" . $observacoes . "');";
         $msg1 = "incluir";
         $msg2 = "inclusão";
   }
   
   if ($_action == "alteracao") {
         $sql = "update veiculos set municipio_placa='" . $municipio_placa . "',uf_placa='" . $uf_placa . "',descricao='" . $descricao . "',renavan='" . $renavan . "',chassi='" . $chassi . "',marca='" . $marca . "',modelo='" . $modelo . "',ano_modelo='" . $ano_modelo . "',ano_fabricacao='" . $ano_fabricacao . "',cor='" . $cor . "',combustivel='" . $combustivel . "',tipo='" . $tipo . "',observacoes='" . $observacoes . "' where id=" . $id;
         $msg1 = "alterar";
         $msg2 = "alterado";
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from veiculos where id=" . $id;
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
