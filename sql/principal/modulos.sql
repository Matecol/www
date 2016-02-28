/* criar tabela modulos */
create table if not exists modulos (
	id serial not null,
	nome varchar(40) not null,
	pasta varchar(40) not null,
	indice int not null default 0,
	nivel int default 0,
	constraint PK_MODULOS primary key (id) 
);

/* TRIGGER DA LOG */
create function gravar_log_modulos() returns trigger as $gravar_log_modulos$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.modulos select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.modulos select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.modulos select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.modulos select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_modulos$ language plpgsql;

create trigger gravar_log_modulos after insert or update or delete on modulos
	for each row execute procedure gravar_log_modulos();

