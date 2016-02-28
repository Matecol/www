/* criar tabela de pedidos */
create table if not exists pedidos (
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
    constraint PK_PEDIDOS primary key (id),
	constraint FK_PEDIDOS_EMPRESAS foreign key (empresa) references empresas(id),
	constraint FK_PEDIDOS_CLIENTES foreign key (cliente) references clientes(id),
	constraint FK_PEDIDOS_EMPRESAS foreign key (vendedor) references vendedores(id),
	constraint FK_PEDIDOS_EMPRESAS foreign key (movimento_financeiro) references movimento_financeiro(id),
	constraint FK_PEDIDOS_EMPRESAS foreign key (forma_pagamento) references formas_pagamento(id)
);

/* TRIGGER DA LOG */
create function gravar_log_pedidos() returns trigger as $gravar_log_pedidos$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.pedidos select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.pedidos select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.pedidos select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.pedidos select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_pedidos$ language plpgsql;

create trigger gravar_log_pedidos after insert or update or delete on pedidos
	for each row execute procedure gravar_log_pedidos();

