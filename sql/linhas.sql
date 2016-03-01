/* criar tabela linhas */
create table if not exists linhas (
	id serial not null,
	linha varchar(30) not null,
	constraint PK_LINHAS primary key (id) 
);

/* TRIGGER DA LOG */
create function gravar_log_linhas() returns trigger as $gravar_log_linhas$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.linhas select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.linhas select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.linhas select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.linhas select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_linhas$ language plpgsql;

create trigger gravar_log_linhas after insert or update or delete on linhas
	for each row execute procedure gravar_log_linhas();

