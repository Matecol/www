/* criar tabela parâmetros_sistema */
create table if not exists parametros_sistema (
	chave varchar(20) not null,
	empresa int not null,
	usuario int not null,
	valor text not null,
	observacoes text,
	constraint PK_PARAMETROS_SISTEMA primary key (chave, empresa, usuario)
);

/* TRIGGER DA LOG */
create function gravar_log_parametros_sistema() returns trigger as $gravar_log_parametros_sistema$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.parametros_sistema select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.parametros_sistema select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.parametros_sistema select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.parametros_sistema select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_parametros_sistema$ language plpgsql;

create trigger gravar_log_parametros_sistema after insert or update or delete on parametros_sistema
	for each row execute procedure gravar_log_parametros_sistema();

