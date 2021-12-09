<?php 
require('config.php'); 
include('classes/perfil.php');
$link_activo="crearusuario.php";
$perfil = new Perfil($db); 
$perfilactual = $perfil->obtener_perfil($_SESSION['userid']);
//checamos si está logueado

if(!$user->is_logged_in()){ header('Location: salir.php'); } 
if($perfil->is_admin($_SESSION['userid'],"Administrador")){}else{ header('Location: salir.php'); }
//$user->checartoken($_SESSION['token'],$_SESSION['username']);
if(isset($_POST['submit'])){

	//very basic validation
	if(strlen($_POST['username']) < 3){
		$error[] = 'El usuario es muy corto.';
	} else {
		$stmt = $db->prepare('SELECT username FROM members WHERE username = :username');
		$stmt->execute(array(':username' => $_POST['username']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['username'])){
			$error[] = 'El usuario elegido se encuentra ocupado.';
		}
			
	}

	if(strlen($_POST['password']) < 3){
		$error[] = 'Password demasiado corto.';
	}

	if(strlen($_POST['passwordConfirm']) < 3){
		$error[] = 'Confirmación de password demasiado corta.';
	}

	if($_POST['password'] != $_POST['passwordConfirm']){
		$error[] = 'Los Passwords no son iguales.';
	}

	//email validation
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Ingresa un email válido';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $_POST['email']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['email'])){
			$error[] = 'El email ingresado ya está en uso.';
		}
			
	}


	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

		//create the activasion code
		$activasion = md5(uniqid(rand(),true));

		try {

			//insert into database with a prepared statement
			$stmt = $db->prepare('INSERT INTO members (username,password,email,active,nombre,puesto) VALUES (:username, :password, :email, :active,:nombre, :puesto)');
			$stmt->execute(array(
				':username' => $_POST['username'],
				':password' => $hashedpassword,
				':email' => $_POST['email'],
				':nombre' => $_POST['nombre'],
				':puesto' => $_POST['puesto'],								
				':active' => 'Yes'
//opcion de activación				':active' => $activasion
			));
			$id = $db->lastInsertId('memberID');
			$checar = true;
/* por si se necesita enviar email	/////////////////		
			//send email
			$_SESSION['to'] = $to = $_POST['email'];
			$_SESSION['subject'] =$subject = "Confirmación de registro";
			$_SESSION['body'] = $body = "Gracias por registrarte en SCAP.\n\n Para activar tu cuenta, da click en el siguiente link:\n\n ".DIR."activate.php?x=$id&y=$activasion\n\n Saludos \n\n";
			$_SESSION['to'] = $additionalheaders = "From: <".SITEEMAIL.">\r\n";
			$_SESSION['to'] .= $additionalheaders .= "Reply-To: $".SITEEMAIL."";
			mail($to, $subject, $body, $additionalheaders);
///////////////////			*/
			
			//redirect to index page
			//header('Location: perfil.php?id='.$id.'&creado='.$_POST['username'].'');
			//exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

//define page title
$titulo = 'Crear usuario';
$customheader = '';
//include header template
include('template/header.php'); 
//print_r($_SESSION);
?>
<main class="h-full pb-16 overflow-y-auto">
          <!-- Remove everything INSIDE this div to a really blank page -->
          <div class="container px-6 mx-auto grid">
            <h2
              class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
            >
              Crear usuarios
            </h2>
    <!--crear usuarios-->
	<div      class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800"
            >
          <?php if(isset($checar) and $checar == true){ ?>
     <div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>¡Bien hecho!</strong> se ha creado el usuario <?php echo $_POST['username']; ?>.
     </div>
     <?php } 
	 				if(isset($error)){
					foreach($error as $error){
						echo '<div class="min-w-0 p-4 text-gray-700 bg-red-100 rounded-lg shadow-xs"><button type="button" class="close" data-dismiss="alert">×</button>'.$error.'</div>';
					}
				}
	  ?>
                      <form action="" method="post" accept-charset="utf-8" class='form' style='margin-bottom: 0;'>
			   <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Usuario (usuario utilizado para iniciar sesión)</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  type="text" name="username" id="username" placeholder="Usuario" value="<?php if(isset($error)){ echo $_POST['username']; } ?>"
                />
              </label>   
			  <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Email</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  type="email" name="email" id="email" placeholder="Email" value="<?php if(isset($error)){ echo $_POST['email']; } ?>"
                />
              </label>   
			  <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Password</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  type="password" name="password" id="password" placeholder="Password"
                />
              </label>   
			  <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Confirmar Password</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  type="password" name="passwordConfirm" id="passwordConfirm" placeholder="Confirmar Password"
                />
              </label>
			  <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Nombre</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  type="text" name="nombre" id="nombre" placeholder="Nombre completo" value="<?php if(isset($error)){ echo $_POST['nombre']; } ?>"
                />
              </label> 
			  <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">Cargo</span>
                <input
                  class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                  type="text" name="puesto" id="puesto" placeholder="Cargo" value="<?php if(isset($error)){ echo $_POST['puesto']; } ?>"
                />
              </label>   
					  
                        <div class='form-actions form-actions-padding' style='margin-bottom: 0;'>
                          <div class='text-right'>
                          <button type="submit" name="submit" class="px-10 py-4 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"><i class='icon-plus'></i> Crear Usuario</button>
                          </div>
                        </div>
                      </form>
			</div>
    
    <!-- /crear usuarios-->
			</div>
			</main>
<?php 
//include header template
require('template/footer.php'); 
?>