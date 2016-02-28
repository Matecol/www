/* criar tabela tipos_documento */
create table if not exists tipos_documento (
	id serial not null,
	descricao varchar(30) not null,
	constraint PK_TIPOS_DUCUMENTO primary key (id)
);

/* TRIGGER DA LOG */
create function gravar_log_tipos_documento() returns trigger as $gravar_log_tipos_documento$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.tipos_documento select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.tipos_documento select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.tipos_documento select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.tipos_documento select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_tipos_documento$ language plpgsql;

create trigger gravar_log_tipos_documento after insert or update or delete on tipos_documento
	for each row execute procedure gravar_log_tipos_documento();

