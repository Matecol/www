/* criar tabela de unidades_medida */
create table if not exists log.unidades_medida (
	/* campos originais da tabela */
	id serial not null,
	unidade char(2) not null,
	descricao varchar(20) not null,
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_UNIDADES_MEDIDA primary key (log_seq) 
);