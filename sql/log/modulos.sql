/* criar tabela de modulos */
create table if not exists log.modulos (
	/* campos originais da tabela */
	id int not null,
	nome varchar(40) not null,
	pasta varchar(40) not null,
	indice int not null,
	nivel int ,
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_MODULOS primary key (log_seq) 
);