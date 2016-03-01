/* criar tabela marcas */
create table if not exists marcas (
	id serial not null,
	marca varchar(30) not null,
	constraint PK_MARCAS primary key (id) 
);

/* TRIGGER DA LOG */
create function gravar_log_marcas() returns trigger as $gravar_log_marcas$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.marcas select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.marcas select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.marcas select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.marcas select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_marcas$ language plpgsql;

create trigger gravar_log_marcas after insert or update or delete on marcas
	for each row execute procedure gravar_log_marcas();

