/* criar tabela contas_movimento_financeiro */
create table if not exists contas_movimento_financeiro (
	id serial not null,
	loja int not null,
	nome varchar(30) not null,
	tipo char(1) not null,       
	categoria char(1) not null, 
	transfere_saldo char(1) not null,
	banco int not null,
	agencia varchar(10) not null,
	conta_corrente varchar(20) not null,
	lancamento_manual char(1) not null,
	codigo_banco int not null,
	constraint PK_CONTAS_MOVIMENTO_FINANCEIRO primary key (id) 
);

-- DESCRICAO DOS CAMPOS
-- loja - loja onde ficará a conta do movimento financeiro
-- nome - nome da conta do movimento financeiro
-- tipo - C-Crédito D-Débito
-- categoria - categoria da conta (1-Recebe lançamentos  2-Faz Lançamentos)
-- transfere_saldo - transfere saldo automaticamente entre movimentos
-- banco - código do banco
-- agencia - agência do banco
-- conta corrente - numero da conta corrente
-- lancamento_manual - permite lancamento manual
-- codigo_banco - codigo do banco no cadastro de clientes/fornecedores

/* TRIGGER DA LOG */
create function gravar_log_contas_movimento_financeiron() returns trigger as $gravar_log_contas_movimento_financeiron$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.contas_movimento_financeiron select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.contas_movimento_financeiron select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.contas_movimento_financeiron select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.contas_movimento_financeiron select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_contas_movimento_financeiron$ language plpgsql;

create trigger gravar_log_contas_movimento_financeiron after insert or update or delete on contas_movimento_financeiron
	for each row execute procedure gravar_log_contas_movimento_financeiron();

