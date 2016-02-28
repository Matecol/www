/* criar tabela de usuários */
create table if not exists log.usuarios (
	/* campos originais da tabela */
	id int not null,
	login varchar(20) not null,
	senha char(40) not null,
	nome varchar(60) not null,
	modelo int default 0,
	empresa int default 0,
	nivel int not null,
	externo char(1) not null,
	mobile char(1) not null,
	telefone varchar(11),
	ramal varchar(3),
	email varchar(80) not null,
	bloqueado char(1) not null,
	foto bytea,
	observacoes text,
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_USUARIOS primary key (log_seq)
);
