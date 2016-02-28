/* criar tabela de programas */
create table if not exists log.programas (
	/* campos originais da tabela */
	id int not null,
	nome varchar(40) not null,
	modulo int not null,
	pasta varchar(40) not null,
	agrupamento varchar(40),
	indice int not null,
	nivel int,
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_PROGRAMAS primary key (log_seq)
);