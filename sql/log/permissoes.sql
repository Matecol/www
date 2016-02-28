/* criar tabela de permissoes */
create table if not exists log.permissoes (
	/* campos originais da tabela */
	id int not null,
	descricao varchar(80),
	nivel int not null default 0,
	observacao text,
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_PERMISSOES primary key (log_seq)
);
