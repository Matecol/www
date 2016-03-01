/* criar tabela grupos */
create table if not exists grupos (
	id serial not null,
	grupo varchar(30) not null,
	constraint PK_GRUPOS primary key (id)
);

/* TRIGGER DA LOG */
create function gravar_log_grupos() returns trigger as $gravar_log_grupos$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.grupos select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.grupos select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.grupos select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.grupos select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_grupos$ language plpgsql;

create trigger gravar_log_grupos after insert or update or delete on grupos
	for each row execute procedure gravar_log_grupos();

