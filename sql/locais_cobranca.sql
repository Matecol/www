/* criar tabela de locais de cobrança */
create table if not exists locais_cobranca (
	codigo_banco int not null,
	complemento varchar(20) not null,
	descricao varchar(30),
	constraint PK_LOCAIS_COBRANCA primary key (codigo_banco, complemento)
);

/* TRIGGER DA LOG */
create function gravar_log_locais_cobranca() returns trigger as $gravar_log_locais_cobranca$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.locais_cobranca select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.locais_cobranca select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.locais_cobranca select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.locais_cobranca select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_locais_cobranca$ language plpgsql;

create trigger gravar_log_locais_cobranca after insert or update or delete on locais_cobranca
	for each row execute procedure gravar_log_locais_cobranca();
