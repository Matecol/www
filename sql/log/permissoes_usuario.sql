/* criar tabela de permissões do usuário */
create table if not exists log.permissoes_usuario (
	/* campos originais da tabela */
	usuario int not null,
	permissao int not null,
	valor varchar(20),
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_PERMISSOES_USUARIO primary key (log_seq)
);
