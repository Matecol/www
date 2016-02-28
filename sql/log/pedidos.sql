/* criar tabela de pedidos */
create table if not exists log.pedidos (
	/* campos originais da tabela */
        id serial not null,
        empresa int not null,
        cliente int not null,
        vendedor int not null,
        movimento_fianceiro int not null,
        forma_pagamento int not null,
        dt_emissao timestamp default current_timestamp,
        valor_mercadoria numeric(12, 2),
        valor_frete numeric(12, 2),
        valor_total numeric(12, 2) ,
        /* campos da log */
	log_tipo char(1) not null,
	log_usuario text not null,
	log_pagina text,
	log_data timestamp default current_timestamp,
	log_seq serial not null,
   	constraint PK_LOG_EMPRESAS primary key (log_seq)
);
