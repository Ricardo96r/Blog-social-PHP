<?php

/*
Mysql -> tabla -> verificado {
	0 = No verificado
	1 = Se edito
	2 = Verificado
	}
*/

$explorar = mysql_query("
	SELECT cuentas.idcuenta, cuentas.cuenta, cuentas.nombres, cuentas.apellidos, cuentas.imagen_perfil, cuentas.imagen_perfil_fondo, publicaciones.idpublicacion, publicaciones.publicacion, publicaciones.tiempo_de_creacion 
	FROM publicaciones
	INNER JOIN cuentas
	ON cuentas.idcuenta = publicaciones.cuentas_idcuenta
	WHERE publicaciones.verificado = 2
	ORDER BY `idpublicacion` DESC", $conn) or die(mysql_error());
			
while ($explora = mysql_fetch_array($explorar)) {
			post($explora);
			}
?>