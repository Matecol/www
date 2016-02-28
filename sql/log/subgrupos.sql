/* criar tabela de subgrupos */
create table if not exists log.subgrupos (
	/* campos originais da tabela */
	id int not null,
	subgrupo varchar(30) not null,
	grupo int not null,
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_SUBGRUPOS primary key (log_seq)
);