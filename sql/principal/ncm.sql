/* criar tabela ncm */
create table if not exists ncm (
	id serial not null,
	ncm char(8) not null,
	descricao varchar(20) not null,
	monofasico char(1) not null default 'N',
	constraint PK_NCM primary key (id) 
);

/* TRIGGER DA LOG */
create function gravar_log_ncm() returns trigger as $gravar_log_ncm$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.ncm select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.ncm select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.ncm select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.ncm select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_ncm$ language plpgsql;

create trigger gravar_log_ncm after insert or update or delete on ncm
	for each row execute procedure gravar_log_ncm();

