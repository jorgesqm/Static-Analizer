<!DOCTYPE html>
<html>
	<head>
		<?php 
		 include "./include/Estilos.php";
		?>
	</head><br><br>
	<body> 
	<?php 
	include "./include/HeaderUsuario.php";
	?>
	<br>
	<div style="float: left;width: 300px;position:relative;left: 20px ">
		<ul class="collection">
    		<li class="collection-item avatar">
      		<img src="Imagenes/Fotos/perfil1.jpg" alt="" class="circle">
      		<span class="title">Administrador</span>
      		<p>Jorge Quiñonez<br>@jorgesqm
      		</p>
      		<a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
      		</li>
      	</ul>
      	<br>


      	<?php
      		$userAdmin = 'jorgesqm';

      		$foro = $_GET['id'];

      		$link = mysqli_connect('localhost', 'usuarios', '12345','BIBLIOTECA') or die ('No se pudo establecer conexion con la base de datos: ' . mysqli_error($link));

			$query = "SELECT U.username, U.nombre, U.apellido FROM USUARIOS U, INVITACIONES I WHERE I.id_foro = $foro AND U.username = I.participante AND I.estado = 1";

			$result = mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));

			while ($line = mysqli_fetch_ASSOC($result)) {

				$user = $line['username'];
				$nombre = $line['nombre'];
				$apellido = $line['apellido'];
				$foto = $user.'.jpg';
				//$source = "Imagenes/Fotos/".$user.".jpg";
				echo"<ul class='collection'>
						<li class='collection-item avatar'>
						<img src='Imagenes/Fotos/$foto' alt='' class='circle'>
						<span class='title'>$nombre $apellido</span>
						<p>@$user</p>
						</li>
					</ul>";	
			}	      		

	echo "</div>";

	
	echo "<div style='float: left; position:relative;left: 30px;'>
		<ul class='collapsible popout' data-collapsible='accordion'>";

		$dias = ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sábado'];

		$meses = ['','ENERO', 'FEBRERO', 'MARZO','ABRIL', 'MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];


		$queryEntradas = "SELECT* FROM ENTRADAS WHERE id_foro = $foro ORDER BY fecha";
		$resultEntradas = mysqli_query($link, $queryEntradas) or die('Query failed: '.mysqli_error($link));
		$entradas = mysqli_fetch_ASSOC($resultEntradas);

		if($entradas){
			echo "<ul class='collapsible popout' data-collapsible='accordion' style='width: 850px'>";
			while ($entradas) {
				$user = $entradas['username'];
				$fecha = $entradas['fecha'];
				$contenido = $entradas['contenido'];
				$id_entrada = $entradas['id'];
				
				echo"<li>
					<div class='collapsible-header'><i class='material-icons'>perm_identity</i>
					<b>El usuario @$user comento</b><br><p>$contenido</p>
					</div>";
					

				$queryRespuestas = "SELECT * FROM RESPUESTAS WHERE id_entrada = $id_entrada ORDER BY fecha";
				$resultRespuestas = mysqli_query($link, $queryRespuestas) or die('Query failed: ' . mysqli_error($link));

				$respuestas = mysqli_fetch_ASSOC($resultRespuestas);

				if($respuestas){
					while($respuestas){
					$userR = $respuestas['username'];
					$respuesta = $respuestas['contenido'];
					$fechaR =  strtotime($respuestas['fecha']);
					$date = $dias[date("w",$fechaR)]." ". date("d",$fechaR) . " de ". $meses[date("n",$fechaR)]. " del ". date("Y",$fechaR);

					$fotoR = $userR.'.jpg';

					//echo "<p>$date</p>";
						echo "<div class='collapsible-body'>
								<div class='chip'>
				    			<img src='Imagenes/Fotos/$fotoR' alt='Contact Person'>$userR
				  				</div><br>
				  				$date<p>$respuesta</p>
							</div>";

						//echo "<div class='collapsible-body'></div>";
						$respuestas = mysqli_fetch_ASSOC($resultRespuestas);
					}
				echo "</li>";
				}else{	
				echo "</li>";
				}

				/*echo "<div class='row'>
						<form class='col s12'>
							<button class='btn waves-effect indigo lighten-1' type='submit' id onclick='mostrarInput()'>Responder</button>
							<div class='row'>
								<div class='input-field col s12'>
					          		<textarea id='textarea1' class='materialize-textarea' style='visibility: hidden;'></textarea>	
					      		</div>
					      	</div>
					    </form>
					</div><br>";*/

					echo "<div style='position:relative;left: 675spx'>
						<button class='btn waves-effect indigo lighten-1' type='submit'>Responder</button>
						</div>";

			$entradas = mysqli_fetch_ASSOC($resultEntradas);	
			}
			echo "</ul>";

		}else{
			echo "<ul class='collapsible' data-collapsible='accordion' style='width: 875px'>
					<li>
					<div class='collapsible-header'><i class='material-icons'>highlight_off</i><h4>No hay entradas por el momento</h4></div>
					</li>
				</ul>";	
		}

		$valores = $userAdmin."-".$foro;
		echo"<div class='row'>
			<form class='col s12'method='POST' action='EntradaNueva.php'>
				<div class='row'>
					<div class='input-field col s12'>
					    <textarea id='textarea1' name='contenido' class='materialize-textarea'></textarea>
					    <label for='textarea1'>Agregar Entrada</label>
					</div> 
					<center>
		    		<button name = 'valores' value = $valores class='btn waves-effect waves-light' type='Submit'>Agregar
	    			<i class='material-icons right'>send</i>
	  				</button>	
		    		</center>
		    		<br>
				</div>
			</form>
		</div>";
		
	?>

		
	</div>

	<div style="float: right;position: relative;right: 20px">
		<ul class="collection with-header">
	    	<li class="collection-header"><h5>Agregar Participantes</h5></li>
	    	<?php
	    	$foro = $_GET['id'];
	    	$queryInvitaciones = "SELECT * FROM USUARIOS U WHERE U.username NOT IN (SELECT participante FROM INVITACIONES WHERE id_foro=$foro ) AND U.username != 'jorgesqm'";
			$resultInvitaciones = mysqli_query($link, $queryInvitaciones) or die('Query failed: '.mysqli_error($link));
			$invitaciones = mysqli_fetch_ASSOC($resultInvitaciones);

			if($invitaciones){
					while($invitaciones){
						$us = $invitaciones['username'];

					echo "<li class='collection-item'><div>$us<a href='NuevaInvitacion.php?foro=$foro&participante=$us' class='secondary-content'><i class='material-icons'>add_box</i></a></div></li>";
					$invitaciones = mysqli_fetch_ASSOC($resultInvitaciones);
					}
				
				}

	    	?>
	        
	    </ul>
	</div>
	
	  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script type="text/javascript" src="Materialize/js/materialize.min.js"></script>
      <script type="text/javascript">
      	$(document).ready(function(){
    	$('.collapsible').collapsible({
      	accordion : false 
    	});
  		});

  		function mostrarInput(){
		if (document.getElementById("textarea1")){
			document.getElementById("textarea1").style = "visibility:visible"  ;
			}
		}

      </script>
   </body>
</html>