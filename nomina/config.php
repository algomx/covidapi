<?php 
ob_start();
session_start();
//set timezone
date_default_timezone_set('America/Mexico_City');

//database credentials
define('DBHOST','localhost');
define('DBUSER','root');
define('DBPASS','root');
define('DBNAME','nomina');

//Globals site
define("NOMBRE_SITIO", "ESTE ES EL NOMBRE DEL SITIO");
define("PATH","http://localhost/nomina/"); //ruta a los archivos completa utilizando // al inicio para soportar https
define("DESCRIPCION", "Esta descripción del sitio se cambia en configuración");
define("TW_USUARIO","twitter");
define("FB_USUARIO","FB");
define('SITEEMAIL','noreply@neodatta.net');

try {

	//create PDO connection 
	$db = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
	$db->exec("set names utf8");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
	//show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}

//include the user class, pass in the database connection
$nombre_fichero = 'classes/user.php';


if (file_exists($nombre_fichero)) {
    include('classes/user.php');
    $user = new User($db); 
} else{
//echo "<h1>Hola</h1>";
}


?>