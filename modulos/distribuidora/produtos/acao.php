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
         case "inclusao": $nperm = "INCLUIR CADASTRO DE PRODUTOS";break;
         case "alteracao": $nperm = "ALTERAR CADASTRO DE PRODUTOS";break;
         case "exclusao": $nperm = "EXCLUIR CADASTRO DE PRODUTOS";break;
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
   $codigo_referencia = tratarTexto($_POST['codigo_referencia']);
   $codigo_fabrica = tratarTexto($_POST['codigo_fabrica']);
   $codigo_barras = tratarTexto($_POST['codigo_barras']);
   $grupo = tratarChave($_POST['grupo']);
   $ncm = tratarChave($_POST['ncm']);
   $unidade_medida = tratarChave($_POST['unidade_medida']);
   $marca = tratarChave($_POST['marca']);
   $situacao = tratarTexto($_POST['situacao']);
   $qtd_embalagem = tratarNumero($_POST['qtd_embalagem']);
   $peso_liquido = tratarNumero($_POST['peso_liquido']);
   $peso_bruto = tratarNumero($_POST['peso_bruto']);
   $preco_custo = tratarNumero($_POST['preco_custo']);
   $preco_venda = tratarNumero($_POST['preco_venda']);
   $observacoes = tratarTexto($_POST['observacoes']);
   $_action = $_POST['_action'];
   
   if ($_action != "exclusao") {

         // validar campos
         if (empty($nome)) {
	         http_response_code(400);
	         echo "Informe o nome do produto.";
	         return;  
         }
         
         if (empty($grupo)) {
	         http_response_code(400);
	         echo "Informe o grupo do produto.";
	         return;  
         }
                  
         if (empty($marca)) {
	         http_response_code(400);
	         echo "Informe o marca do produto.";
	         return;  
         }
         
         if ($peso_bruto < $peso_liquido) {
	         http_response_code(400);
	         echo "Peso Bruto não pode ser menor que Peso Líquido.";
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
         $sql = "insert into produtos (nome, codigo_referencia, codigo_fabrica, codigo_barras, grupo, ncm, unidade_medida, marca, situacao, qtd_embalagem, peso_liquido, peso_bruto, preco_custo, preco_venda, observacoes ) values ('" . $nome . "', '" . $codigo_referencia . "', '" . $codigo_fabrica . "', '" . $codigo_barras . "', " . $grupo . ", " . $ncm . ", " . $unidade_medida . ", " . $marca . ", '" . $situacao  . "', " . $qtd_embalagem . ", '" . $peso_liquido . "', '" . $peso_bruto . "', " . $preco_custo . ", " . $preco_venda . ", '" . $observacoes . "');";
         $msg1 = "incluir";
         $msg2 = "inclusão";
   }
   
   if ($_action == "alteracao") {
         $sql = "update produtos set nome='" . $nome . "',codigo_referencia='" . $codigo_referencia . "',codigo_fabrica='" . $codigo_fabrica . "',codigo_barras='" . $codigo_barras . ",grupo=" . $grupo . ",ncm=" . $ncm . ",unidade_medida=" . $unidade_medida . ",marca=" . $marca . ",situacao='" . $situacao . "',qtd_embalagem=" . $qtd_embalagem . "',peso_liquido=" . $peso_liquido . "',peso_bruto=" . $peso_bruto . ",preco_custo=" . $preco_custo . ",preco_venda=" . $preco_venda . ",observacoes='" . $observacoes . "' where id=" . $id;
         $msg1 = "alterar";
         $msg2 = "alterado";
   }
   
   if ($_action == "exclusao") {
         $sql = "delete from produtos where id=" . $id;
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
