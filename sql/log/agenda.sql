/* criar tabela de Agenda */
create table if not exists log.agenda (
    /* campos originais da tabela */
    id int not null,
    razaosocial varchar(60) not null,
    endereco varchar(60),
    bairro varchar(40),
    cep char( 8 ),
    municipio int,
    telefone varchar(13),
    celular varchar(13),
    contato varchar(13),
    email varchar(60),
    observacoes text,
    /* campos da log */
    log_tipo char(1) not null,
    log_usuario text not null,
    log_pagina text,
    log_data timestamp default current_timestamp,
    log_seq serial not null,
	constraint PK_LOG_AGENDA primary key (log_seq)
);
