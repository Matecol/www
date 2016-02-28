/* criar tabela de programas do usuário */
create table if not exists programas_usuario (
	usuario int not null,
	programa int not null,
	valor varchar(20) default 'S',
	constraint PK_PROGRAMAS_USUARIO primary key (usuario, programa),
	constraint FK_PROGRAMAS_USUARIO_USUARIOS foreign key (usuario) references usuarios(id),
	constraint FK_PROGRAMAS_USUARIO_PROGRAMAS foreign key (programa) references programas(id)
);

/* TRIGGER DA LOG */
create function gravar_log_programas_usuario() returns trigger as $gravar_log_programas_usuario$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.programas_usuario select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.programas_usuario select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.programas_usuario select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.programas_usuario select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_programas_usuario$ language plpgsql;

create trigger gravar_log_programas_usuario after insert or update or delete on programas_usuario
	for each row execute procedure gravar_log_programas_usuario();
