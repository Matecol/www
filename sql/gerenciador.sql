/* criar tabela gerenciador caixa */
create table if not exists gerenciador (
	id serial not null, 
	empresa int not null,
	conta int not null,
	data date not null,
	saldo_inicial numeric(14,4),
    saldo_abertura numeric(14,4),
    valor_entrada numeric(14,4),
    valor_saida numeric(14,4),
    saldo_encerramento numeric(14,4),
    /*dados da aprovação*/
    usuario_aprovado int,
    data_aprovacao timestamp default current_timestamp,
	constraint PK_GERENCIADOR primary key (id),
    constraint FK_GERENCIADOR_EMPRESAS foreign key (empresa) references empresas(id),
    constraint FK_GERENCIADOR_CONTAS foreign key (conta) references contas(id),
    constraint FK_GERENCIADOR_USUARIOS foreign key (usuario_aprovado) references usuarios(id),
);

/* TRIGGER DA LOG */
create function gravar_log_gerenciador() returns trigger as $gravar_log_gerenciador$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.gerenciador select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.gerenciador select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.gerenciador select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.gerenciador select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_gerenciador$ language plpgsql;

create trigger gravar_log_gerenciador after insert or update or delete on gerenciador
	for each row execute procedure gravar_log_gerenciador();

