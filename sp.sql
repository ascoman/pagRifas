DELIMITER ;
DROP PROCEDURE IF EXISTS sp_crear_rifa;
DELIMITER //
CREATE PROCEDURE sp_crear_rifa(IN p_des_rifa VARCHAR(25), p_num_boletos INT, p_num_costo_boleto INT)
BEGIN
	INSERT INTO cat_rifas (des_rifa, num_boletos, num_costo_boleto)
	VALUES (p_des_rifa, p_num_boletos, p_num_costo_boleto);
	
	SELECT LAST_INSERT_ID() INTO @pidu_rifa;
	
	SET @pnum_idu = 1;
	
	WHILE (@pnum_idu <= p_num_boletos ) 
	DO 
      INSERT INTO ctl_codigos ( idu_rifa )
      VALUES ( @pidu_rifa );
      
      INSERT INTO ctl_numeros_usuarios ( idu_rifa, num_rifa )
      VALUES ( @pidu_rifa, @pnum_idu );
      
      INSERT INTO cat_premios ( idu_rifa, idu_premio )
      VALUES ( @pidu_rifa, @pnum_idu );
      
      SET @pnum_idu = @pnum_idu+1;
   END WHILE;
   
   CALL sp_relacionar_premios (@pidu_rifa);
	
	SELECT @pidu_rifa AS idu_rifa;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_crear_premio;
DELIMITER //
CREATE PROCEDURE sp_crear_premio(IN p_idu_rifa INT, p_idu_premio INT, p_des_premio VARCHAR(50), p_nom_img_premio VARCHAR(50))
BEGIN
	UPDATE cat_premios
	SET des_premio = p_des_premio, nom_img_premio = p_nom_img_premio
	WHERE idu_rifa = p_idu_rifa AND idu_premio = p_idu_premio;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_crear_usuario;
DELIMITER //
CREATE PROCEDURE sp_crear_usuario(IN p_idu_rifa INT, p_des_codigo VARCHAR(6), p_nom_usuario VARCHAR(50))
BEGIN

	SET @iError = 0;
	
	SELECT 1 
	FROM ctl_codigos 
	WHERE idu_rifa = p_idu_rifa AND des_codigo = p_des_codigo AND idu_usuario > 0
	INTO @iError;
	
	IF @iError = 0
	THEN
	
		INSERT INTO cat_usuarios (idu_rifa, nom_usuario, opc_pagado )
		VALUES (p_idu_rifa, p_nom_usuario, 0);
		
		UPDATE ctl_codigos
		SET idu_usuario = LAST_INSERT_ID()
		WHERE idu_rifa = p_idu_rifa AND des_codigo = p_des_codigo;
		
	END IF;
	
	SELECT @iError AS iError;
	
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_borrar_usuario;
DELIMITER //
CREATE PROCEDURE sp_borrar_usuario(IN p_idu_rifa INT, p_idu_usuario INT )
BEGIN
	
	DELETE FROM cat_usuarios
	WHERE idu_rifa = p_idu_rifa AND idu_usuario = p_idu_usuario;
	
	UPDATE ctl_codigos
	SET idu_usuario = 0
	WHERE idu_rifa = p_idu_rifa AND idu_usuario = p_idu_usuario;
	
	UPDATE ctl_numeros_usuarios
	SET idu_usuario = 0
	WHERE idu_rifa = p_idu_rifa AND idu_usuario = p_idu_usuario;
	
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_seleccionar_numero;
DELIMITER //
CREATE PROCEDURE sp_seleccionar_numero(IN p_idu_rifa INT, p_idu_usuario INT, p_num_rifa int )
BEGIN

	SET @iError = 0;
	
	/*valida si el numero ya fue seleccionado*/
	SELECT 1 
	FROM ctl_numeros_usuarios 
	WHERE idu_rifa = p_idu_rifa AND num_rifa = p_num_rifa AND idu_usuario > 0
	INTO @iError;
	
	IF @iError = 0
	THEN
		UPDATE ctl_numeros_usuarios
		SET idu_usuario = p_idu_usuario
		WHERE idu_rifa = p_idu_rifa AND num_rifa = p_num_rifa;
		
		UPDATE cat_usuarios
		SET fec_selecciona = CURRENT_TIMESTAMP
		WHERE idu_usuario = p_idu_usuario AND idu_rifa= p_idu_rifa;
		
	END IF;
	
	SELECT @iError AS iError;
	
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_relacionar_premios;
DELIMITER //
CREATE PROCEDURE sp_relacionar_premios(IN p_idu_rifa INT )
BEGIN
	INSERT INTO ctl_numeros_premio (num_rifa, idu_premio, idu_rifa )
	SELECT @n := @n + 1 num_rifa, idu_premio, p_idu_rifa
   FROM (SELECT idu_premio FROM cat_premios WHERE idu_rifa = p_idu_rifa ORDER BY RAND()) idu_premio, (SELECT @n := 0) m ;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_liberar_rifa;
DELIMITER //
CREATE PROCEDURE sp_liberar_rifa(IN p_idu_rifa INT )
BEGIN

	SET @usuarios_sin_Seleccionar = -1;
	
	SELECT COUNT(a.idu_usuario)
	FROM cat_usuarios AS a
	LEFT JOIN ctl_numeros_usuarios AS b
	ON ( a.idu_usuario = b.idu_usuario )
	WHERE a.idu_rifa = p_idu_rifa AND a.opc_pagado = 1 AND b.num_rifa IS NULL
	INTO @usuarios_sin_Seleccionar;

	IF @usuarios_sin_Seleccionar = 0
	THEN
		UPDATE cat_rifas
		SET opc_liberada = 1 
		WHERE idu_rifa = p_idu_rifa;
	END IF;	
	
	SELECT @usuarios_sin_Seleccionar AS usuarios_sin_Seleccionar;

END //
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_marca_pagado;
DELIMITER //
CREATE PROCEDURE sp_marca_pagado(IN p_idu_rifa INT, p_idu_usuario INT  )
BEGIN
	UPDATE cat_usuarios
	SET opc_pagado = 1 
	WHERE idu_rifa = p_idu_rifa AND idu_usuario = p_idu_usuario;
END //
DELIMITER ;


DROP PROCEDURE IF EXISTS sp_consultar_numeros_rifa;
DELIMITER //
CREATE PROCEDURE sp_consultar_numeros_rifa(IN p_idu_rifa INT, p_idu_usuario  INT )
BEGIN
	SET @num_select = 0;
	
	SELECT num_rifa
	FROM ctl_numeros_usuarios
	WHERE idu_rifa = p_idu_rifa AND idu_usuario = p_idu_usuario
	INTO @num_select;
	
	SELECT a.num_rifa, @num_select AS num_select, a.idu_usuario, 
	CASE WHEN (e.opc_liberada = 1) THEN COALESCE (b.nom_usuario, '') ELSE '' END AS nom_usuario,
	CASE WHEN (e.opc_liberada = 1) THEN d.des_premio ELSE '' END AS des_premio,
	CASE WHEN (e.opc_liberada = 1) THEN d.nom_img_premio ELSE '' END AS nom_img_premio
	FROM ctl_numeros_usuarios AS a
	LEFT JOIN cat_usuarios AS b
	ON ( a.idu_rifa = b.idu_rifa AND a.idu_usuario = b.idu_usuario )
	LEFT JOIN ctl_numeros_premio AS c
	ON ( a.num_rifa = c.num_rifa AND a.idu_rifa = c.idu_rifa )
	LEFT JOIN cat_premios AS d
	ON ( c.idu_rifa = d.idu_rifa AND d.idu_premio = c.idu_premio )
	JOIN cat_rifas AS e
	ON ( a.idu_rifa = e.idu_rifa )
	WHERE a.idu_rifa = p_idu_rifa
	ORDER BY a.num_rifa;

	
END //
DELIMITER ;




DROP PROCEDURE IF EXISTS sp_validar_codigo;
DELIMITER //
CREATE PROCEDURE sp_validar_codigo(IN p_des_codigo VARCHAR(6) )
BEGIN
	SET @opc_es_admin = 0;
	SET @opc_entra = 0;
	SET @idu_rifa = 0;
	SET @nom_usuario = '';
	SET @numeros_rifa = 0;
	SET @precio_boleto = 0;
	SET @nom_rifa = '';
	SET @opc_liberada = 0;
	SET @opc_pagado = 0;
	SET @idu_usuario = 0;
	SET @num_seleccionado = 0;
	
	SELECT 1, b.nom_usuario 
	FROM ctl_codigos AS a
	JOIN cat_usuarios AS b
	ON (a.idu_rifa = b.idu_rifa AND a.idu_usuario = b.idu_usuario)
	WHERE a.idu_rifa = 0 AND a.des_codigo = p_des_codigo
	INTO @opc_es_admin, @nom_usuario;
	
	IF @opc_es_admin = 0
	THEN
		SELECT 1, a.idu_rifa, b.nom_usuario, c.num_boletos, c.num_costo_boleto, c.des_rifa, c.opc_liberada, b.opc_pagado, a.idu_usuario, COALESCE( d.num_rifa, 0 )
		FROM ctl_codigos AS a
		JOIN cat_usuarios AS b
		ON (a.idu_rifa = b.idu_rifa AND a.idu_usuario = b.idu_usuario)
		JOIN cat_rifas AS c
		ON (b.idu_rifa = c.idu_rifa)
		LEFT JOIN ctl_numeros_usuarios AS d
		ON ( b.idu_usuario = d.idu_usuario AND b.idu_rifa = c.idu_rifa )
		WHERE a.idu_rifa > 0 AND a.des_codigo = p_des_codigo
		INTO @opc_entra, @idu_rifa, @nom_usuario, @numeros_rifa, @precio_boleto, @nom_rifa, @opc_liberada, @opc_pagado, @idu_usuario, @num_seleccionado;
	END IF;

	
	SELECT @opc_es_admin AS opc_es_admin, @opc_entra AS opc_entra, @idu_rifa AS idu_rifa, @nom_usuario AS nom_usuario, 
	@numeros_rifa AS numeros_rifa, @precio_boleto AS precio_boleto, @nom_rifa AS nom_rifa, @opc_liberada AS opc_liberada,  
	@opc_pagado AS opc_pagado, @idu_usuario AS idu_usuario, @num_seleccionado AS num_seleccionado;
END //
DELIMITER ;


DROP PROCEDURE IF EXISTS sp_consultar_datos_rifa;
DELIMITER //
CREATE PROCEDURE sp_consultar_datos_rifa(IN p_idu_rifa int )
BEGIN

	SET @num_codigos_asignados = 0;
	SET @num_codigos_pagados = 0;
	
	
	SELECT COUNT(des_codigo) 
	FROM ctl_codigos
	WHERE idu_rifa = p_idu_rifa AND idu_usuario > 0
	INTO @num_codigos_asignados;
	
	SELECT COUNT(idu_usuario) 
	FROM cat_usuarios
	WHERE idu_rifa = p_idu_rifa AND opc_pagado > 0
	INTO @num_codigos_pagados;
	
	SELECT COUNT(idu_usuario) 
	FROM cat_usuarios
	WHERE idu_rifa = p_idu_rifa AND opc_pagado > 0
	INTO @num_codigos_asignados;

	SELECT des_rifa, opc_liberada, num_boletos, num_costo_boleto, fec_creacion, 
	@num_codigos_asignados AS num_codigos_asignados, @num_codigos_pagados AS num_codigos_pagados,
	(num_boletos*num_costo_boleto) AS num_valor_total_rifa, (num_costo_boleto*@num_codigos_pagados) AS num_valor_pagado
	FROM cat_rifas 
	WHERE idu_rifa = p_idu_rifa;
	
END //
DELIMITER ;


DROP PROCEDURE IF EXISTS sp_consultar_premios;
DELIMITER //
CREATE PROCEDURE sp_consultar_premios(IN p_idu_rifa int )
BEGIN
	SELECT idu_premio, des_premio, nom_img_premio
	FROM cat_premios 
	WHERE idu_rifa = p_idu_rifa
	order by idu_premio;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_borra_premio;
DELIMITER //
CREATE PROCEDURE sp_borra_premio(IN p_idu_rifa INT, p_idu_premio INT  )
BEGIN
	UPDATE cat_premios
	SET des_premio = "", nom_img_premio = ""
	WHERE idu_rifa = p_idu_rifa AND idu_premio = p_idu_premio;
END //
DELIMITER ;


DROP PROCEDURE IF EXISTS sp_consultar_rifas_activas;
DELIMITER //
CREATE PROCEDURE sp_consultar_rifas_activas(IN p_idu_rifa INT )
BEGIN

	IF p_idu_rifa = 1
	THEN
		SELECT idu_rifa
		FROM cat_rifas
		WHERE opc_liberada = 0
		ORDER BY idu_rifa;
	END IF;
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_consultar_participantes;
DELIMITER //
CREATE PROCEDURE sp_consultar_participantes(IN p_idu_rifa INT )
BEGIN
	
		SELECT a.des_codigo, COALESCE (b.idu_usuario, 0) AS idu_usuario, COALESCE (b.nom_usuario, '')AS nom_usuario, COALESCE (b.opc_pagado, 0) AS opc_pagado,
		COALESCE(c.num_rifa, 0) as num_rifa, COALESCE(b.fec_selecciona, 0) AS fec_selecciona,
		CASE WHEN (f.opc_liberada = 1) THEN d.idu_premio ELSE 0 END AS idu_premio,
		CASE WHEN (f.opc_liberada = 1) THEN e.des_premio ELSE '' END AS des_premio,
		CASE WHEN (f.opc_liberada = 1) THEN e.nom_img_premio ELSE '' END AS nom_img_premio,
		CASE WHEN (COALESCE (b.idu_usuario, 0) = 0) THEN 0 ELSE 1 END AS boleto_vendido
		FROM ctl_codigos AS a
		LEFT JOIN cat_usuarios AS b
		ON (a.idu_usuario = b.idu_usuario AND a.idu_rifa = b.idu_rifa)
		LEFT JOIN ctl_numeros_usuarios AS c
		ON ( b.idu_rifa = c.idu_rifa AND b.idu_usuario = c.idu_usuario )
		LEFT JOIN ctl_numeros_premio AS d
		ON ( c.idu_rifa = d.idu_rifa AND c.num_rifa = d.num_rifa )
		LEFT JOIN cat_premios AS e
		ON (d.idu_rifa = e.idu_rifa AND d.idu_premio = e.idu_premio)
		LEFT JOIN cat_rifas AS f
		ON ( e.idu_rifa = f.idu_rifa )
		WHERE a.idu_rifa = p_idu_rifa
		order by boleto_vendido desc, a.des_codigo asc;
	
END //
DELIMITER ;


DROP PROCEDURE IF EXISTS sp_eliminar_rifa;
DELIMITER //
CREATE PROCEDURE sp_eliminar_rifa(IN p_idu_rifa int )
BEGIN

	SELECT nom_img_premio 
	FROM cat_premios
	WHERE idu_rifa = p_idu_rifa;

	DELETE 
	FROM cat_rifas 
	WHERE idu_rifa = p_idu_rifa;
	
	DELETE 
	FROM cat_premios 
	WHERE idu_rifa = p_idu_rifa;
	
	DELETE 
	FROM cat_usuarios 
	WHERE idu_rifa = p_idu_rifa;
	
	DELETE 
	FROM ctl_numeros_usuarios 
	WHERE idu_rifa = p_idu_rifa;
	
	DELETE 
	FROM ctl_numeros_premio 
	WHERE idu_rifa = p_idu_rifa;
	
	DELETE 
	FROM ctl_codigos 
	WHERE idu_rifa = p_idu_rifa;
END //
DELIMITER ;
