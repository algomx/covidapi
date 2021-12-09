<?php require('config.php'); 
include('classes/perfil.php');
include('classes/usuarios.php');
$usuarios = new Usuario($db); 
$perfil = new Perfil($db); 
$link_activo="borrarusuario.php";
if(!$user->is_logged_in()){ header('Location: salir.php'); } 
if(!$perfil->is_admin($_SESSION['userid'],"Administrador")){ header('Location: salir.php'); } 
if(isset($_GET['id']) and is_numeric($_GET['id']) and $_SESSION['userid'] == 1 and $_GET['id']!= 1){
	$id = $_GET['id']; 
}else{
	$id = $_SESSION['userid'];
}
$perfilactual = $perfil->obtener_perfil($id);
//checamos si está logueado
if(isset($_POST['submit'])){
	if($id==1){$error = 'Por seguridad no puedes borrar esta cuenta, si es necesario puedes solicitarlo a sistemas.';}else{
			$checar = $usuarios->borrar_usuario($id);
	}
}
//$user->checartoken($_SESSION['token'],$_SESSION['username']);
//define page title
$titulo = 'Borrar Usuario.';
$mensajeinfos = "Bienvenido ".$_SESSION['username']." desde aquí puedes ingresar a los modulos asignados.";
$customheader = '';
//include header template
require('template/header.php'); 
//print_r($_SESSION);
?>
<main class="h-full pb-16 overflow-y-auto">
          <!-- Remove everything INSIDE this div to a really blank page -->
          <div class="container px-6 mx-auto grid">
            <h2
              class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
            >
              Eliminar Usuario
            </h2>
          <?php if(isset($error)){ ?>
               <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?php echo $error; ?>.
     </div>

			<?php  } ?>
          <?php if(isset($checar) and $checar == true){ ?>
     <div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>¡Correcto!</strong> se ha eliminado el usuario <?php echo $perfilactual['username']; ?>.
     </div>
     <?php } else{		   ?>
     <?php if(isset($checar)){ ?>
     <div class="alert alert-dismissible alert-alert">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?php echo $checar; ?>.
     </div>
     <?php } ?>  
  <!--borrar usuario-->

                      <form action="" method="post" accept-charset="utf-8" class='form' style='margin-bottom: 0;'>
                        <fieldset>
                          <div class='col-sm-12'>
                            <div class='lead'>
                              <i class='icon-remove text-contrast'></i> Seguro que quieres borrar el usuario <?php echo $perfilactual['username']; ?>
                            </div>
                            <small class='text-muted'>Presiona el boton borrar para eliminar el usuario mencionado.</small>
                          </div>
                          <div class='col-sm-7 col-sm-offset-1'>
                        </fieldset>
                        <div class='form-actions form-actions-padding' style='margin-bottom: 0;'>
                          <div class='text-right'>
                          <button type="submit" name="submit" class="px-10 py-4 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"><i class='icon-remove'></i> Borrar</button>
                          </div>
                        </div>
                      </form>
                      <?php } ?>
    <!--/borrar usuario-->
     </div>
     </main>
<?php 
//include header template
require('template/footer.php'); 
?>