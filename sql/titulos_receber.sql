/* criar tabela de titulos à receber */
create table if not exists titulos_receber (
	empresa int not null,
	cliente int not null,
	titulo varchar(30) not null,
	tipo_documento int not null,
	local_cobranca_codigo_banco int not null,
	local_cobranca_complemento varchar(20),
	dt_geracao timestamp not null default current_timestamp,
	dt_emissao timestamp not null,
	dt_vencimento timestamp not null,
	dt_quitacao timestamp not null,
	valor_original numeric(12, 2) not null default 0.0,
	valor_titulo numeric(12, 2) not null default 0.0,
	nosso_numero varchar(30),
	cheque_banco int,
	cheque_agencia varchar(10),
	cheque_conta varchar(10),
	cheque_numero int,
	cheque_dt_emissao timestamp,
	cheque_cpfcnpj varchar(14),
	cheque_nome varchar(50),
	cartao_numero_autorizacao varchar(10),
	origem char(10),
	situacao char(1),
	observacao text,
	constraint PK_TITULOS_RECEBER primary key (empresa, cliente, titulo),
	constraint FK_TITULOS_RECEBER_EMPRESA foreign key (empresa) references empresas(id),
	constraint FK_TITULOS_RECEBER_CLIENTE foreign key (cliente) references clientes(id),
	constraint FK_TITULOS_RECEBER_TIPO_DOCUMENTO foreign key (tipo_documento) references tipos_documento(id),
	constraint FK_TITULOS_RECEBER_LOCAL_COBRANCA foreign key (local_cobranca_codigo_banco, local_cobranca_complemento) references locais_cobranca(codigo_banco, complemento)
);

/* TRIGGER DA LOG */
create function gravar_log_titulos_receber() returns trigger as $gravar_log_titulos_receber$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.titulos_receber select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.titulos_receber select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.titulos_receber select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.titulos_receber select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_titulos_receber$ language plpgsql;

create trigger gravar_log_titulos_receber after insert or update or delete on titulos_receber
	for each row execute procedure gravar_log_titulos_receber();
