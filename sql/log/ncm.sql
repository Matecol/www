/* criar tabela de ncm */
create table if not exists log.ncm (
	/* campos originais da tabela */
    id serial not null,
	ncm char(8) not null,
	descricao varchar(20) not null,
	monofasico char(1) not null,	
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_NCM primary key (log_seq)
);