<?php
   // Importar conteudo do arquivo
   function import($url) {
       require_once $url;
   }
   
   // Ler arquivo
   function lerArquivo($arquivo) {
       if (! file_exists($arquivo)) {
           return;
       }
       
       $arq = fopen($arquivo, "r");
       $conteudo = "";
       
       while (! feof($arq)) {
           $conteudo .= fgets($arq, 4096);
       }
       
       fclose($arq);
       
       return $conteudo;
   }
   
?>
