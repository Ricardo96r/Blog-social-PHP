<div class="fondo" id="publicar">
	<div class="well-bl-1">
		<div class="row">
			<div class="col-md-12">
<?php
if(isset($_SESSION['username'])) {
	if(!isset($_POST['enviar_nota'])) {
		?>
			<form enctype="multipart/form-data" method="post" action="">
                <div id="publicar-form-file">
                    <input name="archivo" type="file" id="publicar-form-file-input">
                    <div id="publicar-form-file-text">
                    	Subir publicación
                    </div>
                </div>
                <script src="<?php echo "temas/".$prop['tema'];?>/js/publicar.js"></script>
				<textarea name="nota" class="form-control" maxlength="200" placeholder="Escribe algo..."></textarea>
				<input class="btn btn-warning form-control" type="submit" name="enviar_nota" value="Enviar publicación">
			</form>
        <?php
		} else {
			?><div id="publicar-form"><?php
			$idcuentap = mysql_query("SELECT idcuenta, email FROM cuentas WHERE email = '$_SESSION[username]'");
			$idcuentap2 = mysql_fetch_array($idcuentap);
			$idcuenta = $idcuentap2['idcuenta'];
			$nota = antiSqlInjection($_POST['nota']);
			$ruta = "static-content/publicaciones/";
			$name_m = mysql_query("SELECT idimagenes FROM imagenes");
			$name = (mysql_num_rows($name_m) + 1)."-".rand();
			
			/*
				Errores
			*/
			if(!isset($nota) and empty($nota)) {
				echo "Porfavor no deje campos vacios";
			} elseif(strlen($nota) < 20) {
				echo "La nota es muy corta, tiene que tener mas de 20 caracteres";
			} elseif(strlen($nota) > 200) {
				echo "La nota es muy larga, el máximo de caracteres es 200";
			} elseif($_FILES["archivo"]["size"] > 200000000) {
				echo "Foto invalida";
			} elseif($_FILES["archivo"]["error"] > 0) {
				echo $_FILES["archivo"]["error"]. "Error al subir la imagen!";
			} elseif(file_exists($ruta.$name)) {
				echo "Error anormal, reporte. Intente nuevamente.";
				
			/*
				Accion realizada cuando no se encuentran errores mencionados arriba
			*/
			} else {
			#Agrega la imagen a la base de datos en una tabla unica
			$enviar_imagen = mysql_query("INSERT INTO `imagenes` (`ruta`) VALUES ('".$name."')") or die (mysql_error());
			
			#Busca el idimagen de la tabla imagenes para luego usar el id en la tabla publicaciones
			$idimage_f = mysql_query("SELECT idimagenes, ruta FROM imagenes WHERE ruta = '$name'") or die (mysql_error()); 
			$idimagen = mysql_fetch_array($idimage_f);
			
			#Envio de la publicacion a la DB en la tabla publicaciones
			$enviar_nota = mysql_query("INSERT INTO `publicaciones` (`cuentas_idcuenta`, `publicacion`, `imagenes_idimagenes`) VALUES ('".$idcuenta."','".$nota."', '"."$idimagen[idimagenes]"."')") or die (mysql_error());
			move_uploaded_file($_FILES["archivo"]["tmp_name"], $ruta.$name);
			?>
            <div class="alert alert-warning alert-dismissable">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>¡Publicación enviada!</strong>
            </div>
			<?php
			echo "<img class='img-responsive' src=".$ruta.$name.">";
			echo $nota;
				}
				?></div><?php
			}
} else {
	header("Location: ?p=404");
	}
?>
            </div>
		</div>
	</div>
</div>