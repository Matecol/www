/* criar tabela vendedores */
create table if not exists vendedores (
	id serial not null, 
	nome varchar(50) not null,
	empresa int not null,
	tipo char(1) not null,
	comissao_pecas numeric(5,2),
	comissao_servicos numeric(5,2),
	situacao char(1) not null default 'I',
	constraint PK_VENDEDORES primary key (id) 
);

/* TRIGGER DA LOG */
create function gravar_log_vendedores() returns trigger as $gravar_log_vendedores$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.vendedores select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.vendedores select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.vendedores select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.vendedores select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_vendedores$ language plpgsql;

create trigger gravar_log_vendedores after insert or update or delete on vendedores
	for each row execute procedure gravar_log_vendedores();

