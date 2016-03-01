/* criar tabela de usuarios */
create table if not exists usuarios (
	id serial not null,
	login varchar(20) not null,
	senha char(40) not null,
	nome varchar(60) not null,
	modelo int default 0,
	empresa int,
	nivel int not null default 0,
	externo char(1) not null default 'N',
	mobile char(1) not null default 'N',
	telefone varchar(11),
	ramal varchar(3),
	email varchar(80) not null,
	bloqueado char(1) not null default 'N',
	observacoes text,
	constraint PK_USUARIOS primary key (id),
	constraint FK_USUARIOS_EMPRESAS foreign key (empresa) references empresas(id)
);

/* TRIGGER DA LOG */
create function gravar_log_usuarios() returns trigger as $gravar_log_usuarios$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.usuarios select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.usuarios select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.usuarios select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.usuarios select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_usuarios$ language plpgsql;

create trigger gravar_log_usuarios after insert or update or delete on usuarios
	for each row execute procedure gravar_log_usuarios();

