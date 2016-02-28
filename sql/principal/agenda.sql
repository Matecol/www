/* criar tabela de Agenda */
create table if not exists agenda (
     id serial not null,
     razaosocial varchar(60) not null,
     endereco varchar(60),
     bairro varchar(40),
     cep char(8),
     municipio int,
     telefone varchar(13),
     celular varchar(13),
     contato varchar(13),
     email varchar(60),
     observacoes text,
     constraint PK_AGENDA primary key (id),
     constraint FK_AGENDA_MUNICIPIO foreign key (municipio) references municipios(id)
);

/* TRIGGER DA LOG */
create function gravar_log_agenda() returns trigger as $gravar_log_agenda$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.agenda select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.agenda select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.agenda select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.agenda select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_agenda$ language plpgsql;

create trigger gravar_log_agenda after insert or update or delete on agenda
	for each row execute procedure gravar_log_agenda();


