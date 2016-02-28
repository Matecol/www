<?php

   require_once BASE_DIR . "/util/mail/mail.php";
   
   $mail = new Mail();
   $mail->setUsuario("raphael_amorim@outlook.com", "g49lWj+8xpoUQKQYOqIhlPyxGGcBj9IyicHdIxjncxaWPfFAKO9F/a05ppBNPbn9TMOkwg1dK9LC1yIBS9UKiZWImck/PlCLv8Twr7DlgBWPWW5ADzSp9BbU6Qn6Eg1Z47mVEo3G1BetIMgdrRIxvhZa7msgshuZY83rj4KaSWKW0i+JYlyWzSADqBpLlH4MeApI2dizedY1/NjPLbqOkl7Lh9nd5Uvp2clHWM5FTgn+T2aann1RsNKvcnhfOa8n4h4ZYppdTzuEPy7X9yuUgIW5soP8Yu5ThMZaZVDwSR/EiZx3ovMAFmSUQF1HS3wlabDiMK5Wwijela1LJsEhhQ==");
   $mail->addDestinatario("thiago.pereira.ti@gmail.com");
   $mail->addCC("raphael_amorim@outlook.com.br");
   $mail->addBCC("thiago.pereira.ti@icloud.com");
   $mail->setAssunto("Teste de Envio!!!");
   $mail->setCorpo("Teste");
   $mail->addAnexo("/tmp/tabela_cfop.pdf", "tabela_cfop.pdf");
   $mail->addAnexo("/tmp/tabela_cst.pdf", "tabela_cst.pdf");
   $mail->enviar(); 
  
	
?>