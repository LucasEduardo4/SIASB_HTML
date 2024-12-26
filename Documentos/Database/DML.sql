Use siasb;
-- -----------------------------------------------------------------
-- Trabalhando com os Logs
SELECT IDLog, u.nome as 'usuario', old_value, new_value, dataHora FROM tblogs_sistema LEFT JOIN tbusuario u on tblogs_sistema.usuario = u.IDUsuario WHERE tabela = 'tblocal';

SELECT localsetorsecao.ID, tblocal.descricao as 'Local', setorSecaoID FROM localsetorsecao
                LEFT JOIN tblocal ON localsetorsecao.localID = tblocal.ID
                WHERE setorSecaoID = 1
-- -----------------------------------------------------------------
SELECT * FROM TBUsuario WHERE nome = 'gabrielfernandes' OR nome = 'gabrieloliveira'

SELECT * FROM tbusuario WHERE nome = 'root'
SELECT distinct IDPessoa, nomeCompleto from TBPessoa where gestor =1

DELIMITER //
CREATE PROCEDURE buscaResponsavel(IN chamadoBusca INT)
BEGIN
SELECT responsavel, nome FROM (
    SELECT DISTINCT lc.responsavel as 'responsavel', u.nome as 'nome'
    FROM tblog_chamado lc 
    LEFT JOIN TBUsuario u on u.IDUsuario = lc.responsavel
    WHERE lc.referencia = chamadoBusca
    
    UNION
    
    SELECT DISTINCT c.responsavel as 'responsavel', u.nome as 'nome' 
    FROM TBChamados c
    LEFT JOIN TBUsuario u on u.IDUsuario = c.responsavel
    WHERE c.IDChamado = chamadoBusca
) AS combined_results;
END;
//
DELIMITER ;

UPDATE tbchamados SET prioridade = 2 WHERE IDChamado = 86;

SELECT DISTINCT sc.ID, sc.descricao, p.nomeCompleto, p.IDPessoa as 'responsavelID', l.descricao as 'local', l.ID as 'localID' from tbsetor_secao sc
        LEFT JOIN TBPessoa p on gerente = p.IDPessoa
        LEFT JOIN localsetorsecao lsc on sc.ID = lsc.setorSecaoID
        LEFT JOIN tblocal l on lsc.localID = l.ID
    
	$sql = "DELETE FROM tbsetor_secao WHERE ID = $ID";
    
    START TRANSACTION;
    DELETE FROM localsetorsecao where setorsecaoid = 4;
    DELETE FROM tbsetor_secao WHERE ID = 4;
    COMMIT



        SELECT * FROM localsetorsecao
        
        INSERT INTO TBLocal(descricao, endereco, ativo) VALUES ('TESTE', 'Rua 32, etc', 1)
        
        INSERT INTO localsetorsecao(localID, setorSecaoID) VALUES (7, 3)

        
        UPDATE tbsetor_secao SET descricao = 'whu', gerente = '2' WHERE ID = 4


SELECT * FROM TBUsuario u  
    LEFT JOIN TBPessoa p on p.IDPessoa = u.IDUsuario
    WHERE u.nome = 'gabriel_fernandes'
    
    UPDATE TBUsuario SET habilitado = 0 AND administrador = 0 WHERE IDUsuario = 'gabriel_fernandes'

SELECT * FROM tbimagens WHERE LOG IS NOT NULL AND referencia = 87
SELECT * FROM tbimagens WHERE LOG IS NOT NULL AND referencia = 89
SELECT * FROM tbimagens WHERE LOG IS NOT NULL AND referencia = 90


INSERT INTO TBprioridade VALUES (3, 'Alto')

select * from tbimagens where log is not null and referencia = 88;


SELECT IDLog, mensagem, dataAlteracao, u.nome as 'responsavel', sc.descricao as 'status', referencia FROM TBLog_chamado
                left join tbusuario u on responsavel = u.IDUsuario
                left join tbstatus_chamado sc on status = sc.IDStatus WHERE referencia = 88
                
INSERT INTO TBChamados(prioridade) VALUES (1) WHERE IDChamado <> 10;
start transaction
UPDATE TBChamados set prioridade = 1 WHERE status_chamado = 4
rollback
commit

SELECT IDChamado, dataAbertura, assunto, sc.descricao as 'status_chamado', p.nomeCompleto as 'autor', p2.nomeCompleto as 'responsavel', equipamento FROM TBChamados   left join tbstatus_chamado sc on status_chamado = sc.IDStatus
left join tbpessoa p on autor = p.IDPessoa
left join tbpessoa p2 on responsavel = p2.IDPessoa WHERE 1=1 AND dataAbertura BETWEEN '2023-02-06' AND '2023-09-14' order by assunto desc
 SELECT DISTINCT p.IDPessoa, p.nomeCompleto FROM tbchamados c left join tbusuario u on c.autor = u.IDUsuario left join tbpessoa p on u.IDUsuario = p.IDPessoa where p.setor_secao = 1

SELECT IDChamado, assunto, a.nome as 'autor', dataAbertura, dataFechamento, c.descricao, r.nome as 'responsavel', s.descricao as 'status_chamado' from tbchamados c
        LEFT JOIN tbusuario a on a.IDUsuario = c.autor
        LEFT JOIN tbusuario r on r.IDUsuario = c.responsavel
        LEFT JOIN tbstatus_chamado s on s.IDStatus = c.status_chamado

UPDATE tbchamados set equipamento = '111' where status_chamado = 1

show create table tbpessoa
INSERT INTO TBPessoa VALUES(
30,'Lucas O Cara','22232323','2323231','2','fera@bol.com',''
)

DELETE FROM TBCHamados where IDChamado = 36

alter table tbchamados drop column imagem

SELECT u.IDUsuario FROM tbpessoa p 
LEFT JOIN tbusuario u on u.idusuario = p.idpessoa
WHERE email = 'root@test.com'

SELECT c.IDChamado, c.assunto, c.descricao, c.dataAbertura, c.dataFechamento, sc.descricao as 'status_chamado', a.nome as 'responsavel', u.nome as 'autor', e.descricao as 'equipamento', c.imagem, te.descricao as 'TipoEquipamento', st.descricao as 'setor', st.ID
FROM TBChamados c 
LEFT JOIN TBStatus_Chamado sc ON c.status_chamado = sc.IDStatus 
LEFT JOIN TBUsuario a ON c.responsavel = a.IDUsuario 
LEFT JOIN TBUsuario u ON c.autor = u.IDUsuario 
LEFT JOIN TBEquipamentos e ON c.equipamento = e.sti_ID LEFT JOIN TBPessoa p on c.autor = p.idpessoa 
LEFT JOIN TBTipo_equipamentos as te on e.tipo = te.IDTipo 
LEFT JOIN tbsetor_secao st on p.setor_secao = st.ID 
WHERE 1=1 AND ID = '2' limit 10


SELECT IDNotificacao FROM TBNotificacoes
LEFT JOIN TBChamados c on chamadoReferencia = c.IDChamado
LEFT JOIN TBUsuario u on c.autor = u.IDUsuario
where u.nome = 'root' and visualizado = 1 and excluido <> 1;

ALTER TABLE tbchamados drop column categoria

SET @UltimoID = (SELECT MAX(IDChamado) FROM tbchamados);
        SET @UltimoID = IFNULL(@UltimoID, 0) + 1;
        INSERT INTO tbchamados (IDChamado, assunto, descricao, dataAbertura, status_chamado, responsavel, autor, equipamento, imagem)
        VALUES (@UltimoID, '1241', 'asdf', '2023-08-30 12:41:27', 1, null, '1', 22, '',);

-- Alterações para a tabela tblocal:
INSERT INTO tblocal (ID, nomeLocal, endereco, ativo) VALUES (1, 'PEreira', 'Rua whatever', 1);
SELECT * FROM TBLOCAL

show create table tbsetor_secao

ALTER TABLE tbsetor_secao ADD COLUMN local int
ALTER TABLE tbsetor_secao ADD CONSTRAINT tbsetor_secao_ibfk_2 FOREIGN KEY (local) references TBLocal(ID)

SELECT * FROM TBsetor_secao
update tbsetor_secao set local = 1 where ID=2

-- Alterações 28/08 pós feedback: (unificação setor_secao
DROP TABLE tbsecao
DROP FOREIGN KEY tbsecao_ibfk_1
show create table tbsecao

SELECT * FROM TBnotificacoes 
LEFT JOIN TBChamados c on c.IDChamado = chamadoReferencia 
order by chamadoReferencia

DELETE FROM TBNotificacoes where chamadoReferencia = 72

SELECT * FROM TBEquipamentos

DELETE FROM tbchamados WHERE equipamento = 22
SELECT * FROM tbchamados order by equipamento desc;

SELECT * FROM TBequipamentos where secao

DELETE FROM TBEquipamentos where sti_id = 2080

SELECT * from tbsecao
DELETE from tbsecao where idSecao = 3
DROP TABLE tbsecao
SET @ultimoID = (SELECT MAX(ID) FROM tblocal);
if(@ultimoID = NULL) @ultimoID=1;

ALTER TABLE TBEquipamentos change secao setor_secao int

ALTER TABLE TBPessoa drop column secao
alter table tbpessoa change setor setor_secao int

SHOW CREATE TABLE tbsetor

ALTER TABLE TBSetor change setor_secao descricao varchar(45)
ALTER TABLE tbsetor rename tbsetor_secao

SELECT * from tbsetor_secao
ALTER TABLE TBSetor_secao add column ativo bit

update tbsetor_Secao set ativo=1 WHERE ID = 3



Show create table tbequipamentos
ALTER TABLE TBEquipamentos ADD CONSTRAINT tbequipamentos_ibfk_3 FOREIGN KEY (setor_secao) references tbsetor_secao(ID)
SELECT * FROM TBEQUIPAMENTOS

ALTER TABLE TBEquipamentos ADD COLUMN local	int
ALTER TABLE TBEquipamentos ADD CONSTRAINT tbequipamentos_ibfk_4 FOREIGN KEY (local) references TBLocal(ID)

ALTER TABLE TBEquipamentos  DROP FOREIGN KEY tbequipamentos_ibfk_4 -- vacilei.. o equipamento ja esta vinculado com setor secao, e o setor secao está vinulado com o local
ALTER TABLE TBEquipamentos DROP COLUMN local


-- Fim das alterações
SET @ID = (SELECT IDUsuario FROM tbusuario WHERE nome = "ROOT")
        SELECT nomeCompleto from tbpessoa where IDPessoa = @ID
        
     SET @ID = (SELECT IDUsuario FROM tbusuario WHERE nome = "root"); SELECT nomeCompleto from tbpessoa where IDPessoa = @ID
     
     SELECT c.IDChamado, c.assunto, c.descricao, c.dataAbertura, c.dataFechamento, sc.descricao as 'status_chamado', a.nome as 'responsavel', u.nome as 'autor', e.descricao as 'equipamento', c.imagem, c.categoria FROM TBChamados c 
        LEFT JOIN TBStatus_Chamado sc ON c.status_chamado = sc.IDStatus 
        LEFT JOIN TBUsuario a ON c.responsavel = a.IDUsuario 
        LEFT JOIN TBUsuario u ON c.autor = u.IDUsuario 
        LEFT JOIN TBEquipamentos e ON c.equipamento = e.sti_ID WHERE 1=1 AND setor = '2' limit 10
        LEFT JOIN TBImagens i on c.IDChamado = i.referencia
        
SELECT c.IDChamado, c.assunto, c.descricao, c.dataAbertura, c.dataFechamento, 
                sc.descricao as 'status_chamado', a.nome as 'responsavel', u.nome as 'autor', 
                e.descricao as 'equipamento', c.imagem, c.categoria, te.descricao as 'TipoEquipamento', st.descricao_setor as 'setor'
                FROM TBChamados c
                LEFT JOIN TBStatus_Chamado sc ON c.status_chamado = sc.IDStatus
                LEFT JOIN TBUsuario a ON c.responsavel = a.IDUsuario
                LEFT JOIN TBUsuario u ON c.autor = u.IDUsuario    
                LEFT JOIN TBEquipamentos e ON c.equipamento = e.sti_ID
                LEFT JOIN TBPessoa p on c.autor = p.idpessoa
                LEFT JOIN TBTipo_equipamentos as te on e.tipo = te.IDTipo
                LEFT JOIN TBSetor st on p.setor = st.IDSetor
                WHERE 1=1
        
        
        select * from tbequipamentos
        
        
        

SELECT nomeCompleto FROM TBUSUARIO u
join TBPessoa p on p.IDPessoa = u.IDUsuario
WHERE nome = "gabriel_fernandes"

SET @UltimoID = (SELECT MAX(sti_ID) FROM tbequipamentos);
            SET @UltimoID = IFNULL(@UltimoID, 0) + 1;
            INSERT INTO TBequipamentos(sti_id, descricao,tipo, usuario) values(@UltimoID, 106, 1, 1);
            
SELECT e.sti_id, e.descricao, e.ip, te.descricao as 'tipo',p.nomeCompleto, e.secao FROM TBEquipamentos e
        join TBTipo_Equipamentos te on te.IDTipo = e.tipo
        join TBPessoa p on p.IDPessoa = e.usuario
        where e.tipo = 1

SELECT e.sti_id, e.descricao, e.ip, te.descricao as 'tipo',p.nomeCompleto, sc.descricao_secao FROM TBEquipamentos e
        join TBTipo_Equipamentos te on te.IDTipo = e.tipo
        join TBPessoa p on p.IDPessoa = e.usuario
        join TBSecao sc on e.secao = sc.IDSecao
        --

SELECT 
 
Cannot add or update a child row: a foreign key constraint fails (`siasb`.`tbequipamentos`, CONSTRAINT `tbequipamentos_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `tbpessoa` (`IDPessoa`))	0.000 sec


DELIMITER //
CREATE TRIGGER tr_insert_into_TBNotificacoes
AFTER UPDATE ON TBChamados
FOR EACH ROW
BEGIN
    IF NEW.status_chamado != OLD.status_chamado THEN
        INSERT INTO TBNotificacoes (status, visualizado, excluido, destino, chamadoReferencia, data)
        VALUES (NEW.status_chamado, 0, 0, NEW.autor, NEW.idchamado, NOW());
    END IF;
END;
//
DELIMITER ;



-- selects para relatorios > preview.php
SELECT u.IDUsuario, p.nomeCompleto FROM TBUsuario u
left join tbpessoa p on u.IDUsuario = p.IDPessoa
WHERE p.setor = 1;
GROUP BY p.nomeCompleto


SELECT DISTINCT u.idusuario, u.nome FROM tbchamados c
left join tbusuario u on c.autor = u.idusuario;

SELECT DISTINCT * FROM tbstatus_chamado;

SELECT DISTINCT * FROM tbtipo_equipamentos;

SELECT DISTINCT * FROM tbsetor
--
ALTER TABLE `tbequipamentos` DROP `setor`;
SELECT * FROM TBLog_chamado
    
DELIMITER //
CREATE TRIGGER tr_update_status_and_responsavel_on_log_insert
AFTER INSERT ON tblog_chamado
FOR EACH ROW
BEGIN
    -- Atualizar o status do chamado
    UPDATE tbchamados
    SET status_chamado = NEW.status
    WHERE IDChamado = NEW.referencia;

    -- Atualizar o responsavel do chamado
    UPDATE tbchamados
    SET responsavel = NEW.responsavel
    WHERE IDChamado = NEW.referencia;
END;
//
DELIMITER ;


DELIMITER //
CREATE TRIGGER tr_update_dataFechamento_on_tblog_chamado_insert
AFTER INSERT ON tblog_chamado
FOR EACH ROW
BEGIN
    IF NEW.status = 4 THEN
        UPDATE tbchamados
        SET dataFechamento = NEW.dataAlteracao
        WHERE IDChamado = NEW.referencia;
    END IF;
END;
//
DELIMITER ;

	SELECT IDLog, mensagem, dataAlteracao, u.nome as 'responsavel', sc.descricao as 'status', referencia FROM TBLog_chamado
    left join tbusuario u on responsavel = u.IDUsuario
    left join tbstatus_chamado sc on status = sc.IDStatus
	WHERE referencia = 1


	SELECT p.setor FROM TBUsuario u
    join tbpessoa p on p.IDPessoa = u.IDUsuario
    where u.nome = 'root'

	SELECT p.IDPessoa, p.nomeCompleto, p.cpf, p.matricula, st.descricao_setor, sc.descricao_secao, p.email, p.gestor, u.IDUsuario, u.administrador
			FROM TBPessoa p
			JOIN TBSetor st ON p.setor = st.IDSetor
			JOIN TBSecao sc ON p.secao = sc.IDSecao
			LEFT JOIN TBUsuario	u on p.IDPessoa = u.IDUsuario



    ALTER TABLE tblog_chamado
    DROP FOREIGN KEY tblog_chamado_ibfk_2
    
    show create table tblog_chamado
    show CREATE TABLE TBChamados 

	SET @UltimoID = (SELECT MAX(IDChamado) FROM tbchamados);
	SET @UltimoID = IFNULL(@UltimoID, 0) + 1;
	INSERT INTO tbchamados (IDChamado, assunto, descricao,		dataAbertura, 			status_chamado, responsavel, 	autor, equipamento, imagem ,	categoria)
	VALUES (				@UltimoID, 'teste', 'workbench',	'2023-07-07 16:02:45', 		1, 				null, 		'1', 	'105', 			'', 	'hardware')
    
    
	SET @UltimoID = (SELECT MAX(IDChamado) FROM tbchamados);
	SET @UltimoID = IFNULL(@UltimoID, 0) + 1;
	INSERT INTO tbchamados (IDChamado, assunto, descricao, dataAbertura, status_chamado, responsavel, autor, equipamento, imagem ,categoria)
	VALUES (@UltimoID, '$assunto', '$descricao', '$datetime', 1, null, '$userID', $equipamento, '', '$categoria')
	VALUES (@UltimoID, 'teste', 'workbench', '2023-07-07 16:02:45', 1, null, '1', '105', '', 'hardware')


SELECT IDUsuario FROM TBusuario WHERE nome like 'root'
	
	SELECT c.IDChamado, c.assunto, c.descricao, c.dataAbertura, sc.descricao as "status_chamado	", a.nome as "responsavel", u.nome as "autor", e.descricao as "equipamento", c.imagem, c.categoria
    FROM TBChamados c
	LEFT JOIN TBStatus_Chamado sc ON c.status_chamado = sc.IDStatus
    LEFT JOIN TBUsuario a on c.responsavel = a.IDUsuario
    LEFT JOIN TBUsuario u on c.autor = u.IDUsuario    
    LEFT JOIN TBEquipamentos e on c.equipamento = e.sti_ID    
    WHERE c.IDChamado = 13
    
    
    SELECT DISTINCT lc.IDLog, lc.mensagem, lc.dataAlteracao, u.nome as 'responsavel', lc.status FROM TBChamados c
    left join tbligacaochamados_log lcl on c.IDChamado = lcl.IDChamado
    left join tblog_chamado lc on lcl.CodChamado = lc.referencia
    left join tbusuario u on u.IDUsuario = lc.responsavel
    where c.IDChamado = 1

#equipamento
	SELECT c.IDChamado, e.descricao
    FROM TBChamados C
    JOIN TBEquipamentos e on c.equipamento = e.sti_ID
    
INSERT INTO TBChamados Values(
	6,
	"chamado teste 6",
	"impressora com defeito...",
	'2023-06-23 10:33:00',
	1,
	1,
	2,
	105
)

INSERT INTO TBStatus_Chamado Values(
	1,
	"Aberto"
)

INSERT INTO TBAdministrador Values(
1,
"ROOT",
"TOOR"
)

INSERT INTO TBSetor VALUES(
1,
"Tecnologia",
1
);
SELECT * FROM TBSetor

use siasb
SELECT * FROM TBpessoa
SELECT p.IDPessoa, p.nomeCompleto, p.cpf, p.matricula, st.descricao_setor, sc.descricao_secao, p.email
                FROM TBPessoa p
                JOIN TBSetor st ON p.setor = st.IDSetor
                JOIN TBSecao sc ON p.secao = sc.IDSecao

	delete from tbsetor where idsetor = 1
INSERT INTO TBSecao VALUES(
1,
"T.I",
1,
1
);
SELECT * FROM TBSECAO

SELECT s.IDSecao, s.descricao_secao, p.nomeCompleto, t.descricao_setor FROM TBSECAO s
join TBPessoa p on s.gerente = p.IDPessoa
join TBSetor t on s.setor = t.IDSetor

Select * from TBPESSOA
select * from tbsetor
Select IDSetor, descricao_setor, p.nomeCompleto from TBSetor
join TBPessoa p on gerente = p.IDPessoa

declare @setor
declare @secao
SELECT p.IDPessoa, p.nomeCompleto, p.cpf, p.matricula, @setor = st.descricao, @secao = sc.descricao, p.email

FROM TBPessoa p
join TBSetor st on p.setor = st.IDSetor
join TBSecao sc on p.secao = sc.IDSecao


SELECT p.IDPessoa, p.nomeCompleto, p.cpf, p.matricula, st.descricao AS setor, sc.descricao AS secao, p.email
FROM TBPessoa p
JOIN TBSetor st ON p.setor = st.IDSetor
JOIN TBSecao sc ON p.secao = sc.IDSecao
INTO @IDPessoa, @nomeCompleto, @cpf, @matricula, @setor, @secao, @email;


INSERT INTO TBPessoa VALUES(
2,
"user",
"12345678900",
"9999",
1,
1,
"user@test.com"
);

INSERT INTO TBUsuario VALUES(
2,
"USER",
"123"
);

SELECT * FROM TBEquipamentos

SELECT e.sti_id, e.descricao, e.ip, te.descricao as 'tipo',p.nomeCompleto, st.descricao_setor, sc.descricao_secao FROM TBEquipamentos e
join TBTipo_Equipamentos te on te.IDTipo = e.tipo
join TBPessoa p on p.IDPessoa = e.usuario
join TBSetor st on e.setor = st.IDSetor
join TBSecao sc on e.secao = sc.IDSecao

as
INSERT INTO TBEquipamentos VALUES(
	105,
	"Impressora teste",
	NULL,
	1,
	2,
	1,
	1

);

INSERT INTO TBTipo_Equipamentos VALUES(
	1,
	"teste"
);
SELECT * FROM TBChamados
SELECT * FROM TBLigacaoChamados_Log
SELECT * FROM TBLog_Chamado

Delete from TBLigacaoChamados_Log where IDChamado = 1 and CodChamado = 6

#chamados < ligacaoChamados_Log < LogChamado

SELECT DISTINCT c.IDChamado, c.assunto, c.descricao, c.dataAbertura, lc.IDLog, lc.mensagem, lc.dataAlteracao
FROM TBChamados c
LEFT JOIN TBLigacaoChamados_Log lcl on c.IDChamado = lcl.IDChamado
LEFT JOIN TBLog_Chamado lc on lcl.CodChamado = lc.referencia

INSERT INTO TBLog_Chamado VALUES(
	5,
	"resolvido",
	'2023-06-23 10:45:00',
	1,
	4,
    6
);

INSERT INTO TBLigacaoChamados_Log VALUES(
    6,
    6
);
#dados: