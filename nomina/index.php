<?php 
require('config.php'); 
include('classes/perfil.php');
$perfil = new Perfil($db); 
//checamos si está logueado
if($user->is_logged_in()){ header('Location: dashboard.php'); } 
//process login form if submitted
if(isset($_POST['submit'])){

	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if($user->login($username,$password)){ 
	
		header('Location: '.PATH.'dashboard.php');
		exit;
	
	} else {
		$error[] = 'Usuario o contraseña incorrectos, o la cuenta no ha sido activada.';
	}

}//end if submit
//print_r($_POST);
//define page title
$titulo = 'Ingreso.';
$customheader = '';
//include header template

?>



<!DOCTYPE html>
<html :class="{ 'theme-dark': light }" x-data="data()" lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ingreso - Nom Dashboard</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="<?php echo PATH; ?>assets/css/tailwind.output.css" />
    <script
      src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
      defer
    ></script>
    <script src="<?php echo PATH; ?>/assets/js/init-alpine.js"></script>
  </head>
  <body>
    <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
      <div
        class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800"
      >
        <div class="flex flex-col overflow-y-auto md:flex-row">
          <div class="h-32 md:h-auto md:w-1/2">
            <img
              aria-hidden="true"
              class="object-cover w-full h-full dark:hidden"
              src="<?php echo PATH; ?>/assets/img/login-office.jpeg"
              alt="Office"
            />
            <img
              aria-hidden="true"
              class="hidden object-cover w-full h-full dark:block"
              src="<?php echo PATH; ?>/assets/img/login-office-dark.jpeg"
              alt="Office"
            />
          </div>
          <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
            <div class="w-full">
              <h1
                class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200"
              >
                Ingreso
              </h1>
              <?php 
              if(isset($error)){
                foreach($error as $error){
                    echo '<div class="min-w-0 p-4 text-white bg-purple-600 rounded-lg shadow-xs">'.$error.'</div>';
                }
            }
            if(isset($_GET['m']) and base64_decode($_GET['m']) =="Se ha enviado un correo con la información necesaria para realizar el cambio de contraseña del sistema." ){
                echo '<div class="min-w-0 p-4 text-white bg-purple-600 rounded-lg shadow-xs">'.base64_decode($_GET['m']).'</div>';
            }
            if(isset($_GET['m']) and base64_decode($_GET['m']) =="Ingresa con el nuevo password generado." ){
                echo '<div class="min-w-0 p-4 text-white bg-purple-600 rounded-lg shadow-xs">'.base64_decode($_GET['m']).'</div>';
            }
            ?>
            <form method="post" action="">
              <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Email</span>
                <input type="text" name="username" 
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  placeholder="Usuario"
                />
              </label>
              <label class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">Password</span>
                <input type="password" name="password"
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  placeholder="***************"
                />
              </label>
              <button type="submit" name="submit" class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">Ingresar</button>
            </form>
              <hr class="my-8" />

              

              <p class="mt-4">
                <a
                  class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline"
                  href="./recuperar-pass.php"
                >
                  ¿Olvidaste tu contraseña?
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

