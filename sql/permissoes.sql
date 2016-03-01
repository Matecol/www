/* criar tabela de permissoes */
create table if not exists permissoes (
	id serial not null,
	descricao varchar(80),
	nivel int not null default 0,
	observacao text,
	constraint PK_PERMISSOES primary key (id)
);

/* TRIGGER DA LOG */
create function gravar_log_permissoes() returns trigger as $gravar_log_permissoes$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.permissoes select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.permissoes select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.permissoes select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.permissoes select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_permissoes$ language plpgsql;

create trigger gravar_log_permissoes after insert or update or delete on permissoes
	for each row execute procedure gravar_log_permissoes();
