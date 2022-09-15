USE `twoshopm_rifas`;


DROP TABLE IF EXISTS cat_rifas;
CREATE TABLE IF NOT EXISTS cat_rifas
(
	idu_rifa INT AUTO_INCREMENT PRIMARY KEY,
	des_rifa VARCHAR(25) NOT NULL DEFAULT '',
	num_boletos INT NOT NULL DEFAULT 0,
	num_costo_boleto INT NOT NULL DEFAULT 0,
	opc_liberada SMALLINT NOT NULL DEFAULT 0,
	fec_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS cat_premios;
CREATE TABLE IF NOT EXISTS cat_premios
(
	idu_premio INT NOT NULL DEFAULT 0,
	idu_rifa int NOT NULL DEFAULT 0,
	des_premio VARCHAR(50) NOT NULL DEFAULT '', 
	nom_img_premio VARCHAR(50) NOT NULL DEFAULT ''
);

DROP TABLE IF EXISTS cat_usuarios;
CREATE TABLE IF NOT EXISTS cat_usuarios
(
	idu_usuario INT AUTO_INCREMENT PRIMARY KEY,
	idu_rifa int NOT NULL DEFAULT 0,
	nom_usuario VARCHAR(50) NOT NULL DEFAULT '',
	opc_pagado SMALLINT NOT NULL DEFAULT 0,
	fec_selecciona TIMESTAMP null
);

DROP TABLE IF EXISTS ctl_numeros_usuarios;
CREATE TABLE IF NOT EXISTS ctl_numeros_usuarios
(
	idu_rifa int NOT NULL DEFAULT 0,
	num_rifa int NOT NULL DEFAULT 0,
	idu_usuario int NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS ctl_numeros_premio;
CREATE TABLE IF NOT EXISTS ctl_numeros_premio
(
	idu_rifa int NOT NULL DEFAULT 0,
	num_rifa int NOT NULL DEFAULT 0,
	idu_premio int NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS ctl_codigos;
CREATE TABLE IF NOT EXISTS ctl_codigos
(
	idu_rifa int NOT NULL DEFAULT 0,
	des_codigo VARCHAR(6) NOT NULL DEFAULT '',
	idu_usuario int NOT NULL DEFAULT 0
);

DELIMITER //
CREATE TRIGGER Codigo_beforeInsert
  BEFORE INSERT ON ctl_codigos
  FOR EACH ROW
  BEGIN
    SET @idu_rifa = 1;
    WHILE (@idu_rifa IS NOT NULL) DO 
      SET NEW.des_codigo = LEFT(MD5(RANDOM_BYTES(10)), 6);
      SET @idu_rifa = (SELECT idu_rifa FROM ctl_codigos WHERE des_codigo = NEW.des_codigo);
    END WHILE;
  END;//
  
INSERT INTO cat_usuarios (idu_rifa, idu_usuario, nom_usuario)
VALUES ( 0, 1, 'Miriam Valdez' );

INSERT INTO cat_usuarios (idu_rifa, idu_usuario, nom_usuario)
VALUES ( 0, 2, 'Arnoldo Ibarra' );

INSERT INTO ctl_codigos (idu_rifa, idu_usuario)
VALUES ( 0, 1 ); 

UPDATE ctl_codigos
SET des_codigo = 'MlVh5' 
WHERE idu_usuario = 1;

INSERT INTO ctl_codigos (idu_rifa, idu_usuario)
VALUES ( 0, 2 );

UPDATE ctl_codigos
SET des_codigo = 'AhIi5' 
WHERE idu_usuario = 2;
