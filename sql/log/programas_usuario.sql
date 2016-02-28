/* criar tabela de programas_usuario */
create table if not exists log.programas_usuario (
	usuario int not null,
	programa int not null,
	valor varchar(20),
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_PROGRAMAS_USUARIO primary key (log_seq) 
);