/* criar tabela de contas_movimento_financeiro */
create table if not exists log.contas_movimento_financeiro (
	/* campos originais da tabela */
    id serial not null,
	loja int not null,
	nome varchar(30) not null,
	tipo char(1) not null,       
	categoria char(1) not null, 
	transfere_saldo char(1) not null,
	banco int not null,
	agencia varchar(10) not null,
	conta_corrente varchar(20) not null,
	lancamento_manual char(1) not null,
	codigo_banco int not null,
	/* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_CONTAS_MOVIMENTO_FINANCEIRO primary key (log_seq)
);