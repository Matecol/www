/* criar tabela de tipos_documento */
create table if not exists log.tipos_documento (
	/* campos originais da tabela */
	id int not null,
	grupo varchar(30) not null,
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_TIPOS_DOCUMENTO primary key (log_seq)
);