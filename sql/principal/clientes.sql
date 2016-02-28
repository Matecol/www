/* criar tabela de clientes/fornecedores */
create table if not exists clientes (
	id serial not null,
	cnpj varchar(14) not null,
	ie varchar(20),
	im varchar(20),
	razaosocial varchar(80) not null,
	nomefantasia varchar(60),
	/* endereco de entrega */
	endereco_entrega varchar(80),
	bairro_entrega varchar(50),
	cep_entrega char(8),
	municipio_entrega int,
	telefone_entrega varchar(11),
	celular_entrega varchar(11),
	/* endereco de cobranca */
	endereco_cobranca varchar(80),
	bairro_cobranca varchar(50),
	cep_cobranca char(8),
	municipio_cobranca int,
	telefone_cobranca varchar(11),
	celular_cobranca varchar(11),
	/* outros */
	email01 varchar(80),
	email02 varchar(80),
	situacao char(1),
	autorizado_comprar varchar(60),
	data_cadastro timestamp default current_timestamp,
	usuario_cadastro text default current_setting('sistemaweb.usuario'),
	data_alteracao timestamp default current_timestamp,
	usuario_alteracao text default current_setting('sistemaweb.usuario'),
	observacoes text,
	constraint PK_CLIENTES primary key (id),
	constraint FK_CLIENTES_MUNICIPIOS_ENTREGA foreign key (municipio_entrega) references municipios(id),
	constraint FK_CLIENTES_MUNICIPIOS_COBRANCA foreign key (municipio_cobranca) references municipios(id)
);

/* TRIGGER DE ATUALIZACAO DA DATA DE ALTERACAO */
create function atualizar_clientes() returns trigger as $atualizar_clientes$
begin
	NEW.data_alteracao := current_timestamp;
	NEW.usuario_alteracao := current_setting('sistemaweb.usuario');
	
	return NEW;
end;

$atualizar_clientes$ LANGUAGE plpgsql;

create trigger atualizar_clientes before update on clientes for each row execute procedure atualizar_clientes();

/* TRIGGER DA LOG */
create function gravar_log_clientes() returns trigger as $gravar_log_clientes$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.clientes select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.clientes select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.clientes select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.clientes select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_clientes$ language plpgsql;

create trigger gravar_log_clientes after insert or update or delete on clientes
	for each row execute procedure gravar_log_clientes();
