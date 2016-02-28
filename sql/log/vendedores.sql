/* criar tabela de vendedores */
create table if not exists log.vendedores (
	/* campos originais da tabela */
	id serial not null, 
	nome varchar(50) not null,
	empresa int not null,
	tipo char(1) not null,
	comissao_pecas numeric(5,2),
	comissao_servicos numeric(5,2),
	situacao char(1) not null default 'I',
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_VENDEDORES primary key (log_seq)
);