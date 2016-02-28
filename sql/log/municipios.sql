/* criar tabela de municipios */
create table if not exists log.municipios (
	/* campos originais da tabela */
	id int not null,
   	ibge int not null,
   	municipio varchar(60) not null,
   	uf char(2) not null,
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_MUNICIPIOS primary key (log_seq)
);
