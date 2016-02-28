/* criar tabela de parâmetros_sistema */
create table if not exists log.parametros_sistema (
	/* campos originais da tabela */
	chave varchar(20) not null,
	empresa int not null,
	usuario int not null,
	valor text not null,
	observacoes text,
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_PARAMETROS_SISTEMA primary key (log_seq) 
);