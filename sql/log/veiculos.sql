/* criar tabela de veiculos */
create table if not exists log.veiculos (
	/* campos originais da tabela */
    id serial not null,
	placa char(8),
	municipio_placa varchar(50),
	uf_placa char(2),
	descricao varchar(50) not null,
	renavan varchar(30),
	chassi varchar(30),
	marca varchar(30),
	modelo varchar(30),
	ano_modelo int,
	ano_fabricacao int,
	cor varchar(20),
	combustivel varchar(20),
	tipo char(1),	
	observacoes text,
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_VEICULOS primary key (log_seq)
);