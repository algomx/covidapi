<?php 
require('config.php'); 
include('classes/perfil.php');
$perfil = new Perfil($db); 
//checamos si está logueado
$link_activo="dashboard.php";
if(!$user->is_logged_in()){ header('Location: salir.php');
 } 
$perfilactual = $perfil->obtener_perfil($_SESSION['userid']);

//print_r($_POST);
//define page title
$titulo = 'Panel principal.';
$customheader = '';
//include header template

include('template/header.php'); 
if(isset($error)){
    foreach($error as $error){
        echo '<div class="alert alert-danger">'.$error.'</div>';
    }
}
if(isset($_GET['m']) and base64_decode($_GET['m']) =="Se ha enviado un correo con la información necesaria para realizar el cambio de contraseña del sistema." ){
    echo '<div class="alert alert-success">'.base64_decode($_GET['m']).'</div>';
}
if(isset($_GET['m']) and base64_decode($_GET['m']) =="Ingresa con el nuevo password generado." ){
    echo '<div class="alert alert-success">'.base64_decode($_GET['m']).'</div>';
}

?>
<!-- principal -->
<main class="h-full pb-16 overflow-y-auto">
          <!-- Remove everything INSIDE this div to a really blank page -->
          <div class="container px-6 mx-auto grid">
            <h2
              class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
            >
              Inicio
            </h2>
            <a
              class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple"
              href="<?php echo PATH; ?>perfil.php"
            >
              <div class="flex items-center">
                <svg
                  class="w-5 h-5 mr-2"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                  ></path>
                </svg>
                <span>Bienvenido <?php echo $perfilactual['nombre'] ?></span>
              </div>
              <span>Editar perfil &RightArrow;</span>
            </a>
              <?php //print_r($_SESSION); print_r($perfilactual); ?>

              <h4
              class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300"
            >
              Avisos
            </h4>
            <div class="grid gap-6 mb-8 md:grid-cols-2">
              <div
                class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
              >
                <h4 class="mb-4 font-semibold text-gray-600 dark:text-gray-300">
                  Comunicación interna
                </h4>
                <p class="text-gray-600 dark:text-gray-400">
                  Espacio para avisos generales
                </p>
              </div>
              <div
                class="min-w-0 p-4 text-white bg-purple-600 rounded-lg shadow-xs"
              >
                <h4 class="mb-4 font-semibold">
                  Festividades
                </h4>
                <p>
                  Próximos cumpleaños y fechas importantes por desarrollar
                </p>
              </div>
            </div>

          </div>
          
        </main>
<!-- /principal -->
<?php
include('template/footer.php'); 
?>
