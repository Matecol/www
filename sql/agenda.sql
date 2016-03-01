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
