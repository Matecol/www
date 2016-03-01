/* criar tabela unidades_medida */
create table if not exists unidades_medida (
	id serial not null,
	unidade char(2) not null,
	descricao varchar(20) not null,
	constraint PK_UNIDADES_MEDIDA primary key (id) 
);

/* TRIGGER DA LOG */
create function gravar_log_unidades_medida() returns trigger as $gravar_log_unidades_medida$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.unidades_medida select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.unidades_medida select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.unidades_medida select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.unidades_medida select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_unidades_medida$ language plpgsql;

create trigger gravar_log_unidades_medida after insert or update or delete on unidades_medida
	for each row execute procedure gravar_log_unidades_medida();

