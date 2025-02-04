SELECT * FROM ccp_usuario;

SELECT * FROM ccp_persona;

SELECT * FROM ccp_email;

SELECT * FROM ccp_menu;

SELECT * FROM ccp_rol;

SELECT * FROM ccp_telefono;

SELECT * FROM ccp_rol_permiso;

SELECT * FROM ccp_usuario_rol;

SELECT * FROM ccp_tipo_identificacion;

SELECT * FROM ccp_persona_identificacion;













-- ------------------------------------------

SELECT * FROM ccp_menu WHERE estado = 1;

SELECT `ccp_menu`.`ruta` FROM `ccp_menu` INNER JOIN `ccp_rol_permiso` ON `ccp_menu`.`id` = `ccp_rol_permiso`.`menu_id` WHERE `ccp_rol_permiso`.`rol_id` IN ('1','2') AND `ccp_menu`.`ruta` = '/colaborador' AND `ccp_menu`.`estado` = 1