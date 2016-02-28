/* criar tabela de entregadores */
create table if not exists log.entregadores (
	/* campos originais da tabela */
	id serial not null,
	nome varchar(50) not null,
	cpf char(11) not null,
	telefone varchar(20),
	cnh varchar(20),
	categoria_cnh varchar(2),
	comissao numeric(5,2),
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_ENTREGADORES primary key (log_seq)
);