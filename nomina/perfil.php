<?php require('config.php'); 
include('classes/perfil.php');
$perfil = new Perfil($db); 
//checamos si está logueado
$link_activo="perfil.php";
if(!$user->is_logged_in()){ header('Location: salir.php'); } 
if(isset($_GET['id']) and is_numeric($_GET['id']) and $perfil->is_admin($_SESSION['userid'],"Administrador")){
	
	$id = $_GET['id']; 
}else{
	$id = $_SESSION['userid'];
}
/*necesario para cambiar avatar */
if(isset($_POST['cambiarpass'])){
	if(strlen($_POST['npassword']) < 3){
		$error[] = 'Password demasiado corto.';
	}
	if(strlen($_POST['passwordConfirm']) < 3){
		$error[] = 'Confirmación de password demasiado corto.';
	}
	if($_POST['npassword'] != $_POST['passwordConfirm']){
		$error[] = 'Los passwords no coinciden.';
	}
	//if no errors have been created carry on
	if(!isset($error)){
		//hash the password
		$hashedpassword = $user->password_hash($_POST['npassword'], PASSWORD_BCRYPT);
		try {
			$stmt = $db->prepare("UPDATE members SET password = :hashedpassword  WHERE memberID = :id");
			$stmt->execute(array(
				':hashedpassword' => $hashedpassword,
				//':password' => $hashedpassword1,
				':id' => $id
			));
			$checar = true;
		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}
	}
}
//print_r($_POST);
if(isset($_POST['nombre']) and $perfil->is_admin($_SESSION['userid'],"Administrador")){
  if(isset($_POST['proyectos'])){
    $proyectos = json_encode($_POST['proyectos']);
  }else{
    $proyectos = "";
  }
  if(isset($_POST['permisos'])){
  $niveles = json_encode($_POST['permisos']);}
  else{
$niveles ="";
  }
  $estadisticas = "";//json_encode($_POST['estadisticas']);
  $estadisticasfb = "";//json_encode($_POST['estadisticasfb']);
	if(!isset($error)){
		//funcion de captura
		$checar = $perfil->actualiza_perfil($id,$_POST['nombre'],$_POST['cargo'],$proyectos,$niveles,$estadisticas,$estadisticasfb);
    unset($_POST);
	}

}
if(isset($id)){
$perfilactual = $perfil->obtener_perfil($id);
}else{
$perfilactual = $perfil->obtener_perfil($_SESSION['userid']);	
}
//print_r($perfilactual);
//$user->checartoken($_SESSION['token'],$_SESSION['username']);
//define page title
$titulo = 'Perfil.';
$mensajeinfos = "Bienvenido ".$_SESSION['username']." desde aquí puedes ingresar a los modulos asignados.";
$customheader = '';
//include header template
require('template/header.php'); 
//print_r($_SESSION);
?>
<main class="h-full pb-16 overflow-y-auto">
<div class="container px-6 mx-auto grid">
            <h2
              class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
            >
              Inicio
            </h2>
<!-- perfil de usuario -->
<?php if(isset($checar) and $checar == true){ ?>
     <div class="min-w-0 p-4 text-black bg-green-100 rounded-lg shadow-xs">
                    <strong>¡Bien hecho!</strong> Se han actualizado los datos.
     </div>
     <?php }
	 				if(isset($error)){
					foreach($error as $error){
						echo '<div class="min-w-0 p-4 text-gray-700 bg-red-100 rounded-lg shadow-xs">'.$error.'</div>';
					}
				}
	  ?>
    <main class="h-full pb-16 overflow-y-auto">
    <h4
              class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300"
            >
              Datos Usuario
            </h4>
            <small>Aquí puedes cambiar los datos que aparecen en tu perfil, así como tu contraseña en caso de ser necesario.</small>
            <div      class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800"
            >
            <form action="" method="post" accept-charset="utf-8" class='form' style='margin-bottom: 0;'>
            <?php if($perfil->is_admin($_SESSION['userid'],"Administrador")){ ?>
            <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Nombre a mostrar</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  name="nombre" type="text" class="form-control required" id="nombre" value="<?php echo $perfilactual['nombre']; ?>"
                />
              </label>
              <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Puesto</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  type="text" name="cargo" id="cargo" class="form-control required" value="<?php echo $perfilactual['puesto']; ?>"
                />
              </label>
              <div class="mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                  Asignar niveles de usuario
                </span>
                <div class="mt-2">

                <?php $avisos1 = array(1=>"Administrador",2=>"Supervisor",3=>"Team",4=>"Cliente",5=>"Empleado");
                              //print_r($proyectosarray);
                              if($perfilactual['nivel']==""){
                                $permisosarray = array();  
                              }else{
                                $permisosarray = json_decode($perfilactual['nivel'],true);
                                if(empty($permisosarray)){
                                  $permisosarray = array();
                                }
                              }
                              foreach($avisos1 as $aviso1){
                              ?>
                              <label class="inline-flex items-center text-gray-600 dark:text-gray-400" >
                    <input
                      name="permisos[]" type="checkbox"
                      class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                      value="<?php echo $aviso1;?>" <?php if(in_array($aviso1, $permisosarray, true)){
                                 echo "checked";
                                }?>
                    />
                    <span class="ml-2"><?php echo $aviso1;?></span>
                  </label>
                              <?php    
                              }
                              ?>
                  
                </div>
              </div>

              <div class="mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                  Asignar proyectos
                </span>
                <div class="mt-2">

                <?php $avisos = $perfil->ver_t('categorias','desc','fecha');  
                                  if($perfilactual['proyectos']==""){
                                  $proyectosarray = array();  
                                  }else{
                                  $proyectosarray = json_decode($perfilactual['proyectos'],true);
                                  if(empty($proyectosarray)){
                                    $proyectosarray = array();
                                  }
                                  }
                              //print_r($proyectosarray);
                              foreach($avisos as $aviso){
                              ?>
                              <label class="inline-flex items-center text-gray-600 dark:text-gray-400" >
                    <input
                      name="proyectos[]" type="checkbox"
                      class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                      value="<?php echo $aviso['id'];?>" <?php if(in_array($aviso['id'], $proyectosarray, true)){
                                 echo "checked";
                                }?>
                    />
                    <span class="ml-2"><?php echo $aviso['titulo'];?></span>
                  </label>
                              <?php    
                              }
                              ?>
                  
                </div>
              </div>


              <?php }else{ ?>
                <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Nombre a mostrar</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  name="nombre" type="text" class="form-control required" id="nombre" value="<?php echo $perfilactual['nombre']; ?>"
                readonly />
              </label>
              <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Puesto</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  type="text" name="cargo" id="cargo" class="form-control required" value="<?php echo $perfilactual['puesto']; ?>"
                readonly />
              </label>
              <?php } ?>

              <div class="mt-4 text-sm">
                <div class="mt-2">
                <label class="inline-flex items-center text-gray-600 dark:text-gray-400" >
                <input class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                data-target='#change-password' data-toggle='collapse' name="cambiarpass" id='changepasswordcheck' type='checkbox' value='option1'
                    />
                    <span class="ml-2">Para cambiar tu contraseña marca esta casilla y posteriormente ingresa tu nueva contraseña en los siguientes campos</span>
                  </label>

                  <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Contraseña</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  name="npassword" id="npassword" placeholder='Password' type='password'
                />
              </label>
              <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Confirmar contraseña</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  name="passwordConfirm" id="passwordConfirm" placeholder='Confirmación Password' type='password' />
              </label>

              </div>
             </div>
                <br/>
             <button type="submit" class="px-10 py-4 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"><i class='icon-save'></i> Guardar Cambios</button>
            </form>

                          </div>
  </main>
  <!-- /perfil de usuario -->
<?php 
//include header template
require('template/footer.php'); 
?>