/* criar tabela de locais de cobrança */
create table if not exists log.locais_cobranca (
	/* campos originais da tabela */
	codigo_banco int not null,
	complemento varchar(20) not null,
	descricao varchar(30),
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_LOCAIS_COBRANCA primary key (log_seq)
);
