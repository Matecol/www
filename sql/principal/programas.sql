/* criar tabela de programas */
create table if not exists programas (
	id serial not null,
	nome varchar(40) not null,
	modulo int not null,
	pasta varchar(40) not null,
	agrupamento varchar(40),
	indice int not null default 0,
	nivel int default 0,
	constraint PK_PROGRAMAS primary key (id),
	constraint FK_PROGRAMAS_MODULOS  foreign key (modulo) references modulos (id)
);

/* TRIGGER DA LOG */
create function gravar_log_programas() returns trigger as $gravar_log_programas$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.programas select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.programas select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.programas select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.programas select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_programas$ language plpgsql;

create trigger gravar_log_programas after insert or update or delete on programas
	for each row execute procedure gravar_log_programas();
