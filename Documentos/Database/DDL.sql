Use siasb

CREATE TABLE tblogs_sistema(
IDLog		int	auto_increment primary key,
usuario		int,
tabela		varchar(200),
old_value	varchar(255),
new_value	varchar(255),
dataHora	datetime,
FOREIGN KEY(usuario) REFERENCES TBUsuario(IDUsuario)
)

Drop table tblogs_sistema

SHOW CREATE TABLE TBPessoa

CREATE TABLE TBLogsGerais(
id			int		not null	auto_increment	primary key
alteracao	varchar(300)
),

CREATE TABLE TBprioridade(
	ID		int	primary key,
    descricao	varchar(50)
)

ALTER TABLE TBChamados
Add column prioridade	int

ALTER TABLE TBChamados
ADD CONSTRAINT FOREIGN KEY(prioridade) REFERENCES TBPrioridade(ID)

CREATE TABLE TBImagens(
IDImagem		INT AUTO_INCREMENT PRIMARY KEY,
imagem			longblob,
referencia		int
)


select * from usuario_token

CREATE TABLE tbLocal (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(255) NOT NULL,
    endereco VARCHAR(255),
    ativo BIT NOT NULL
);

CREATE TABLE LocalSetorSecao (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    localID INT,
    setorSecaoID INT,
    FOREIGN KEY (localID) REFERENCES tbLocal(ID),
    FOREIGN KEY (setorSecaoID) REFERENCES tbSetor_Secao(ID)
);

CREATE TABLE TBNotificacoes(
IDNotificacao		int		primary key	auto_increment,
mensagem			varchar(100),
visualizado			bit,
excluido			bit,
destino				int,	
chamadoReferencia	int,
data				datetime,
FOREIGN KEY(destino) REFERENCES TBUsuario(idusuario),
FOREIGN KEY(chamadoReferencia) REFERENCES TBChamados(idchamado)
)
alter table tbnotificacoes
change mensagem status varchar(100)

SHOW CREATE TABLE TBChamados
SHOW CREATE TABLE TBPessoa(
	IDPessoa	int		primary key auto_increment,
	nomeCompleto	varchar(100),
	cpf				varchar(11),
	matricula		varchar(45), #verificar posteriormente
	setor			int,
	secao			int,
	email			varchar(100)
);
-- deveria ser sem o auto_increment..


SHOW CREATE TABLE TBSetor(
	IDSetor		int		primary key,
	descricao	varchar(45),
	gerente		int,
	FOREIGN KEY (IDSetor) REFERENCES TBPessoa(IDPessoa)
);
ALTER TABLE TBSetor
CHANGE COLUMN descricao descricao_setor varchar(45)

ALTER TABLE tbsetor
ADD CONSTRAINT tbsetor_ibfk_1 FOREIGN KEY (gerente) REFERENCES TBPessoa(IDPessoa);


ALTER TABLE tbsecao DROP FOREIGN KEY `tbsecao_ibfk_1`;
ALTER TABLE `tbsecao` ADD `gerente` INT(11) DEFAULT NULL;
ALTER TABLE `tbsecao` ADD CONSTRAINT `tbsecao_ibfk_1` FOREIGN KEY (`gerente`) REFERENCES `tbpessoa` (`IDPessoa`);


SHOW CREATE TABLE TBSecao(
	IDSecao		int		primary key,
	descricao	varchar(45),
	gerente		int,
	FOREIGN KEY (IDSecao) REFERENCES TBPessoa(IDPessoa)
);

ALTER TABLE TBSecao
ADD COLUMN setor int,
ADD FOREIGN KEY (setor) REFERENCES TBSetor(IDSetor);

ALTER TABLE TBSecao
CHANGE COLUMN descricao descricao_secao VARCHAR(45);


CREATE TABLE TBUsuario(
	IDUsuario			int		primary key,
	nome			varchar(45),
	senha			varchar(100), #mudar para SHA-256
	FOREIGN KEY (IDUsuario) REFERENCES TBPessoa(IDPessoa)
);

CREATE TABLE TBAdministrador(
	IDAdministrador		int		primary key,
	nome			varchar(45),
	senha			varchar(45),
	FOREIGN KEY (IDAdministrador) REFERENCES TBPessoa(IDPessoa)
);

CREATE TABLE TBStatus_Chamado(
	IDStatus	INT 	PRIMARY KEY AUTO_INCREMENT,
	descricao	VARCHAR(50)
);
ALTER TABLE TBStatus_Chamado MODIFY IDStatus INT NOT NULL; 
#tirei o auto_increment.

CREATE TABLE TBTipo_Equipamentos(
	IDTipo		 	INT		PRIMARY KEY,
	descricao		VARCHAR(45)
);

CREATE TABLE TBEquipamentos(
	sti_ID		int		primary key,
	descricao	varchar(50),
	ip			varchar(30),
	tipo		int,
	usuario		int,
	setor		int,
	secao		int,
	FOREIGN KEY(tipo) REFERENCES TBTipo_Equipamento(IDTipo),
	FOREIGN KEY(usuario) REFERENCES TBPessoa(IDPessoa),
	FOREIGN KEY(setor) REFERENCES TBSetor(IDSetor),
	FOREIGN KEY(secao) REFERENCES TBSecao(IDSecao)
);

CREATE TABLE TBChamados (
	IDChamado INT NOT NULL PRIMARY KEY,
	assunto VARCHAR(50) NOT NULL,
	descricao VARCHAR(300) NOT NULL,
	dataAbertura DATETIME NOT NULL,
	status_chamado INT, #FK
	responsavel INT, 
	autor INT,
	equipamento		int,
	FOREIGN KEY(status_chamado) REFERENCES TBStatus_Chamado(IDStatus),
	FOREIGN KEY(responsavel) REFERENCES TBAdministrador(IDAdministrador),
	FOREIGN KEY(autor) REFERENCES TBUsuario(IDUsuario),
	FOREIGN KEY(equipamento) REFERENCES TBEquipamentos(sti_ID)
);

ALTER TABLE `tbchamados` ADD CONSTRAINT `tbchamados_ibfk_5` FOREIGN KEY (`responsavel`) 
REFERENCES `tbusuario`(`IDUsuario`) ON DELETE RESTRICT ON UPDATE RESTRICT;

SHOW CREATE TABLE TBLog_Chamado(
	IDLog	int		primary key,
	mensagem	varchar(200),
	dataAlteracao	datetime,
	responsavel		int,
	status			int,
	FOREIGN KEY (status) REFERENCES TBStatus_Chamado(IDStatus),
	FOREIGN KEY (responsavel) REFERENCES TBAdministrador(IDAdministrador),
);
ALTER TABLE TBLog_Chamado
ADD FOREIGN KEY (referencia)	REFERENCES TBLigacaoChamados_Log(CodChamado)
ADD COLUMN     referencia			int,
#
ALTER TABLE TBLog_Chamado
DROP FOREIGN KEY tblog_chamado_ibfk_3

SHOW CREATE TABLE TBLigacaoChamados_Log (
    IDChamado INT,
    CodChamado INT,
    FOREIGN KEY (IDChamado) REFERENCES TBChamados(IDChamado),
    FOREIGN KEY (CodChamado) REFERENCES TBLog_Chamado(referencia)
);
DROP TABLE TBLigacaoChamados_log

CREATE TABLE TBAgenda(
ID			int		not null primary key,
mensagem	varchar(200) not null,
data		date	not null,
usuario		int		not null,
foreign key (usuario) references TBusuario(IDUsuario)
)
