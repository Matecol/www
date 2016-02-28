/* criar tabela de empresas */
create table if not exists log.empresas (
	/* campos originais da tabela */
	id int not null,
	cnpj char(14) not null,
	ie varchar(20),
	im varchar(20),
	razaosocial varchar(80) not null,
	nomefantasia varchar(60),
	endereco varchar(80),
	bairro varchar(50),
	cep char(8),
	municipio int,
	telefone varchar(11),
	fax varchar(11),
	email varchar(80),
    /* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_EMPRESAS primary key (log_seq)
);
