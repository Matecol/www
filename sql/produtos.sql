/* criar tabela de produtos */
create table if not exists produtos (
	id serial not null,
	nome varchar(40) not null,
	aplicacao varchar(50),
	codigo_referencia varchar(20),
	codigo_fabrica varchar(20),
	codigo_serie varchar(20),
	codigo_barras varchar(20),
	linha int not null,
	grupo int not null,
	subgrupo int not null,
	ncm int,
	unidade_medida int not null,
	marca int not null,
	situacao char(1),
	qtd_embalagem numeric(14,4),
	peso_liquido numeric(10,4),
	peso_bruto numeric(10,4),
	preco_custo numeric(14,4),
	preco_venda numeric(14,4),
	observacoes text,
	constraint PK_PRODUTOS primary key (id),
	constraint FK_PRODUTOS_LINHAS foreign key (linha) references linhas(id),
    constraint FK_PRODUTOS_GRUPOS foreign key (grupo) references grupos(id),
	constraint FK_PRODUTOS_SUBGRUPOS foreign key (subgrupo) references subgrupos(id),
	constraint FK_PRODUTOS_NCM foreign key (ncm) references ncm(id),
	constraint FK_PRODUTOS_UNIDADES_MEDIDA foreign key (unidade_medida) references unidades_medida(id),
	constraint FK_PRODUTOS_MARCAS foreign key (marca) references marcas(id)
);

/* TRIGGER DA LOG */
create function gravar_log_produtos() returns trigger as $gravar_log_produtos$
begin
	/* INCLUSAO */
	if (TG_OP = 'INSERT') then
		insert into log.produtos select NEW.*, 'I', current_setting('sistemaweb.usuario'),  current_setting('sistemaweb.pagina');
		return NEW; 
	end if;
	/* ALTERACAO */
	if (TG_OP = 'UPDATE') then
		insert into log.produtos select OLD.*, 'A', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		insert into log.produtos select NEW.*, 'D', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return NEW;
	end if;
	/* EXCLUSAO */
	if (TG_OP = 'DELETE') then
		insert into log.produtos select OLD.*, 'E', current_setting('sistemaweb.usuario'), current_setting('sistemaweb.pagina');
		return OLD;
	end if;
	
	return NULL;
end;

$gravar_log_produtos$ language plpgsql;

create trigger gravar_log_produtos after insert or update or delete on produtos
	for each row execute procedure gravar_log_produtos();

