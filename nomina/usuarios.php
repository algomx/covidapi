<?php 
require('config.php'); 
include('classes/perfil.php');
include('classes/usuarios.php');
$usuarios = new Usuario($db); 
$perfil = new Perfil($db); 
$perfilactual = $perfil->obtener_perfil($_SESSION['userid']);
//checamos si está logueado
$link_activo="usuarios.php";
if(!$user->is_logged_in() or $_SESSION['userid'] != 1 ){ header('Location: salir.php'); } 
if($perfil->is_admin($_SESSION['userid'],"Administrador")){}else{ header('Location: salir.php'); }
//$user->checartoken($_SESSION['token'],$_SESSION['username']);
//define page title
$titulo = 'Usuarios';
$mensajeinfos = "Bienvenido ".$_SESSION['username']." desde aquí puedes ingresar a los modulos asignados.";
$customheader = '';
//include header template
require('template/header.php'); 
//print_r($_SESSION);
?>
<main class="h-full pb-16 overflow-y-auto">
          <div class="container grid px-6 mx-auto">
            <h2
              class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
            >
              Listado de usuarios
            </h2>
    <!--usuarios-->
	<div class="w-full overflow-x-auto">
<?php 
if(isset($_GET['tipo']) and is_numeric($_GET['tipo'])){
$tipodeusuario = $_GET['tipo'];
}else{ $tipodeusuario = 2; }

$orden = 1;
$cuantos = 10;
$tipo = $tipodeusuario;
if(isset($_GET['pagina']) and is_numeric($_GET['pagina'])){
	$pagina = $_GET['pagina']; }else{ $pagina = 1; }
if(isset($_GET['division']) and is_numeric($_GET['division']) and $_GET['division'] < 5 and $_GET['division'] != 0){	$division = $_GET['division']; }else{ $division = 1; }
$usuarios->listar_usuarios($orden, $cuantos, $pagina, $division,$tipo,PATH); 
$usuarios->paginar_usuarios($orden, $cuantos, $pagina, $division,$tipo); 
?>
</div>
    <!--/usuarios-->
</div>
</main>
<?php 
//include header template
require('template/footer.php'); 
?>