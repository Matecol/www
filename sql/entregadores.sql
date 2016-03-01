/* criar tabela de entregadores */
create table if not exists entregadores (
	id serial not null,
	nome varchar(50) not null,
	cpf char(11) not null,
	telefone varchar(11),
	cnh char(11),
	categoria_cnh varchar(2),
	comissao numeric(5,2),
	constraint PK_ENTREGADORES primary key (id));

/* TRIGGER DA LOG */
create function gravar_log_entregadores() returns trigger as $gravar_log_entregadores$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.entregadores select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.entregadores select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.entregadores select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.entregadores select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_entregadores$ language plpgsql;

create trigger gravar_log_entregadores after insert or update or delete on entregadores
	for each row execute procedure gravar_log_entregadores();

