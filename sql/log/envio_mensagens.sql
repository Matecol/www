/* criar tabela de envio de mensagens */
create table if not exists log.envio_mensagens (
	/* campos originais da tabela */
    usuario varchar(20) not null,
	nome_mensagem varchar(20) not null,
	descricao varchar(40),
	valor char(1),
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_ENVIO_MENSAGENS primary key (log_seq)
);
