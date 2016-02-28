/* criar tabela de marcas */
create table if not exists log.marcas (
	/* campos originais da tabela */
	id serial not null,
	marca varchar(30) not null,
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_MARCAS primary key (log_seq) 
);