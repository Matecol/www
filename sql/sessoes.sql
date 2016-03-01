create table if not exists sessoes (
	id serial not null,
	usuario int not null,
	dt_criacao timestamp not null default current_timestamp,
	dt_atual timestamp not null,
	dt_vencimento timestamp not null,
	useragent text,
	ip varchar(20),
	checksum char(40),
	contador int default 0,
	constraint PK_SESSOES primary key (id),
	constraint FK_SESSOES_USUARIOS foreign key (usuario) references usuarios(id)
);

create function atualizar_sessao() returns trigger as $atualizar_sessao$
begin
	NEW.dt_atual := current_timestamp;
	NEW.dt_vencimento := current_timestamp + interval '10 minutes';
	
	return NEW;
end;

$atualizar_sessao$ LANGUAGE plpgsql;

create trigger atualizar_sessao before insert or update on sessoes for each row execute procedure atualizar_sessao();
