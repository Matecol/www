/* criar tabela de creditos do cliente */
create table if not exists creditos_cliente (
	id serial not null,
	cliente int not null,
	tipo char(1) not null,
	valor numeric(12, 2) not null,
	status char(1) not null, /* S=SOLICITADO, R=REVOGADO, A=APROVADO, C=CANCELADO */
	dt_solicitacao timestamp default current_timestamp,
	usuario_solicitacao int,
	dt_revogacao timestamp,
	usuario_revogacao int,
	dt_aprovacao timestamp,
	usuario_aprovacao int, 
	dt_cancelamento timestamp,
	usuario_cancelamento int,
	observacoes text,
	constraint PK_CREDITOS_CLIENTE primary key (id,cliente),
	constraint FK_CREDITOS_CLIENTE_CLIENTES foreign key (cliente) references clientes (id),
	constraint FK_CREDITOS_CLIENTE_USUARIO_SOLICITACAO foreign key (usuario_solicitacao) references usuarios (id),
	constraint FK_CREDITOS_CLIENTE_USUARIO_REVOGACAO foreign key (usuario_revogacao) references usuarios (id),
	constraint FK_CREDITOS_CLIENTE_USUARIO_APROVACAO foreign key (usuario_aprovacao) references usuarios (id),
	constraint FK_CREDITOS_CLIENTE_USUARIO_CANCELAMENTO foreign key (usuario_cancelament) references usuarios (id)
);

/* TRIGGER DA LOG */
create function gravar_log_creditos_cliente() returns trigger as $gravar_log_creditos_cliente$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.creditos_cliente select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.creditos_cliente select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.creditos_cliente select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.creditos_cliente select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_creditos_cliente$ language plpgsql;

create trigger gravar_log_creditos_cliente after insert or update or delete on creditos_cliente
	for each row execute procedure gravar_log_creditos_cliente();
