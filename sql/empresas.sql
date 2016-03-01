/* criar tabela de empresas */
create table if not exists empresas (
	id serial not null,
	cnpj char(14) not null,
	ie varchar(20),
	im varchar(20),
	razaosocial varchar(80) not null,
	nomefantasia varchar(60),
	endereco varchar(80),
	bairro varchar(50),
	cep char(8),
	municipio int,
	telefone varchar(11),
	fax varchar(11),
	email varchar(80),
    constraint PK_EMPRESAS primary key (id),
	constraint FK_EMPRESAS_MUNICIPIOS foreign key (municipio) references municipios(id)
);

/* TRIGGER DA LOG */
create function gravar_log_empresas() returns trigger as $gravar_log_empresas$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.empresas select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.empresas select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.empresas select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.empresas select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_empresas$ language plpgsql;

create trigger gravar_log_empresas after insert or update or delete on empresas
	for each row execute procedure gravar_log_empresas();

/* CADASTRO DA PEPSI */
insert into empresas (cnpj, ie, razaosocial, nomefantasia, endereco, bairro, cep, municipio, telefone) values
    ('02726752000160', '06.300.140-3', 'PEPSI-COLA INDUSTRIAL DA AMAZONIA LTDA', 'PEPSI', 'AV. AUTAZ MIRIM, 1383', 'DISTRITO INDUSTRIAL', '69075155', 8462, '9221292714');
