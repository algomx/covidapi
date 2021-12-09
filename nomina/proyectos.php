  <?php 
require('config.php'); 
include('classes/perfil.php');
$perfil = new Perfil($db); 
//checamos si está logueado
$link_activo="proyectos.php";
if(!$user->is_logged_in()){ header('Location: salir.php');} 
if($perfil->is_admin($_SESSION['userid'],"Administrador")){}else{ header('Location: salir.php'); }
$perfilactual = $perfil->obtener_perfil($_SESSION['userid']);

//print_r($_POST);
//process login form if submitted
if(isset($_POST['Guardar'])){
	$_POST['Guardar'] = "1";
	foreach($_POST as $post){
		if(trim($post) == ""){$error[0] = "TODOS LOS CAMPOS SON OBLIGATORIOS";}			
	}
	if(!isset($error)){
        $agregado = $perfil->insertar_categorias($_SESSION['userid'], $_POST['TITULO'], $_POST['aviso'],$_POST['icono'],"pink");	}
}//end if submit
//define page title
$titulo = 'Proyectos.';
$customheader = "";
//include header template

include('template/header.php'); ?>
    <!-- agregar -->

    <main class="h-full pb-16 overflow-y-auto">
          <!-- Remove everything INSIDE this div to a really blank page -->
          <div class="container px-6 mx-auto grid">
            <h2
              class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
            >
              Información de proyectos
            </h2>
            <?php
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo '<div class="alert alert-danger">'.$error.'</div>';
					}
				}

				if(isset($agregado)){
						echo '<div class="alert alert-success">'.$agregado.' La cateopría se ha agregado correctamente, se puede corroborar al final de la pagina.</div>';
				}

        ?>  
                  <div class="card">
              
	                            <div class="card-content">
      
        
<form class="form form-horizontal" style="margin-bottom: 0;" method="post" action="#" enctype="application/x-www-form-urlencoded" accept-charset="UTF-8">    
                      <div class="form-group">
                        <label class="col-md-1 control-label" for="TITULO">Proyecto</label>
                        <div class="col-md-6">
                          <input class="form-control" name="TITULO" type="text" id="TITULO" value="<?php if(isset($error)){ echo $_POST['TITULO']; }?>" onchange="showUser(this.value)" >
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="col-md-1 control-label" for="TITULO">Icono</label>
                        <div class="col-md-6">
                          <input class="form-control" name="icono" type="text" id="icono" value="<?php if(isset($error)){ echo $_POST['icono']; }?>" onchange="showUser(this.value)" >
                        </div>
                        <a href="http://fontawesome.io/icons/" target="_blank";>Ver listado de iconos disponibles</a>
                        </div>
<div class="form-group">
                        <label class="col-md-1 control-label" for="TITULO">Descripción</label>
                        <div class="col-md-6">
<textarea class='form-control wysihtml5' id='wysiwyg2' name="aviso" rows='10'><?php if(isset($error)){ echo $_POST['aviso']; }?></textarea>  </div></div>  
                      <div class="form-actions" style="margin-bottom: 0;">
                        <div class="row">
                          <div class="col-md-9 col-md-offset-3">
                           
                              <button type="submit" name="Guardar" id="Guardar" class="btn btn-lg" ><i class='icon-save'></i>
                              Agregar Proyecto</button>
                          </div>
                        </div>
                      </div>
                      </form>
            </div>
            </div>

    <!-- /agregar -->
<!-- avisos anteriores -->
<div class="card">
	                            <div class="card-header" data-background-color="purple">
	                                <h4 class="title">Proyectos Existentes</h4>
	                                <p class="category">En este punto puedes eliminar o editar las Proyectos existentes.</p>
	                            </div>
	                            <div class="card-content table-responsive">

<table class="table"><thead class="text-primary">
					<tr>
                        <th>Proyecto</th>
                        <th>Descripción</th>
                        <th>Opciones</th>
                    </tr>
                   </thead>
                   <tbody>                  
                   <?php $avisos = $perfil->ver_t('categorias','desc','fecha');  
foreach($avisos as $aviso){
  
 ?>
                <tr><td><?php echo '<i class="fa fa-'.$aviso['icono'].'"> </i> '.$aviso['titulo']; ?></td><td><?php echo $aviso['aviso']; ?></td><td><?php echo "<a href='".PATH."editarproyecto.php?id=".$aviso['id']."' class='btn btn-info'><i class='material-icons'>mode_edit</i> Editar</a>"; ?><?php echo "<a href='".PATH."borrarproyecto.php?id=".$aviso['id']."' class='btn btn-danger'><i class='material-icons'>clear</i> Eliminar</a>"; ?></td></tr>
<?php    
}
 ?>
</tbody>
                  </table>
                  </div>
                  </div>
<!-- /avisos anteriores -->
</div>
</main>
<?php
include('template/footer.php'); 
?>