create table if not exists log.creditos_cliente (
	/* campos originais da tabela */
	id int not null,
	cliente int not null,
	tipo char(1) not null,
	valor numeric(12, 2) not null,
	status char(1) not null,
	dt_solicitacao timestamp,
	usuario_solicitacao int,
	dt_revogacao timestamp,
	usuario_revogacao int,
	dt_aprovacao timestamp,
	usuario_aprovacao int, 
	dt_cancelamento timestamp,
	usuario_cancelamento int,
	observacoes text,
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_CREDITOS_CLIENTE primary key (log_seq)
);
