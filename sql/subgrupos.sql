/* criar tabela subgrupos */
create table if not exists subgrupos (
	id serial not null,
	subgrupo varchar(30) not null,
	grupo int not null,
	constraint PK_SUBGRUPOS primary key (id),
	constraint FK_SUBGRUPOS_GRUPOS foreign key (grupo) references grupos(id)
	 
);

/* TRIGGER DA LOG */
create function gravar_log_subgrupos() returns trigger as $gravar_log_subgrupos$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.subgrupos select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.subgrupos select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.subgrupos select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.subgrupos select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_subgrupos$ language plpgsql;

create trigger gravar_log_subgrupos after insert or update or delete on subgrupos
	for each row execute procedure gravar_log_subgrupos();

