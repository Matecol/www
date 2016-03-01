/* criar tabela de locais de envio de mensagens */
create table if not exists envio_mensagens (
	usuario int not null,
	nome_mensagem varchar(20) not null,
	descricao varchar(40),
	valor char(1),
	constraint PK_ENVIO_MENSAGENS primary key (usuario, nome_mensagem),
	constraint FK_ENVIO_MENSAGENS_USUARIOS foreign key (usuario) references usuarios(id)
);

/* TRIGGER DA LOG */
create function gravar_log_envio_mensagens() returns trigger as $gravar_log_envio_mensagens$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.envio_mensagens select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.envio_mensagens select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.envio_mensagens select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.envio_mensagens select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_envio_mensagens$ language plpgsql;

create trigger gravar_log_envio_mensagens after insert or update or delete on envio_mensagens
	for each row execute procedure gravar_log_envio_mensagens();
