/* criar tabela de permissões do usuário */
create table if not exists permissoes_usuario (
	usuario int not null,
	permissao int not null,
	valor varchar(20) default 'S',
	constraint PK_PERMISSOES_USUARIO primary key (usuario, permissao),
	constraint FK_PERMISSOES_USUARIO_USUARIOS foreign key (usuario) references usuarios(id),
	constraint FK_PERMISSOES_USUARIO_PERMISSOES foreign key (permissao) references permissoes(id)
);

/* TRIGGER DA LOG */
create function gravar_log_permissoes_usuario() returns trigger as $gravar_log_permissoes_usuario$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.permissoes_usuario select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.permissoes_usuario select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.permissoes_usuario select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.permissoes_usuario select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_permissoes_usuario$ language plpgsql;

create trigger gravar_log_permissoes_usuario after insert or update or delete on permissoes_usuario
	for each row execute procedure gravar_log_permissoes_usuario();
