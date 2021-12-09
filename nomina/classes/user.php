<?php
require('classes/password.php');
class User extends Password{

    private $_db;

    function __construct($db){
    	parent::__construct();
    
    	$this->_db = $db;
    }

	private function get_user_hash($username){	

		try {
			$stmt = $this->_db->prepare('SELECT password,memberID FROM members WHERE username = :username AND active="Yes" ');
			$stmt->execute(array('username' => $username));
			
			$row = $stmt->fetch();
			if (!$row){ return false; }else{
			return array ($row['password'],$row['memberID']);}
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function login($username,$password){
		list($hashed,$userid) = $this->get_user_hash($username);
		
		if($this->password_verify($password,$hashed) == 1){
//token
		$token = md5(uniqid(rand(),true));

		try {

			$stmt = $this->_db->prepare("UPDATE members SET token = :token WHERE username = :email");
			$stmt->execute(array(
				':email' => $username,
				':token' => $token
			));

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}
//token		    
			
		    $_SESSION['loggedin81si'] = true;
		    $_SESSION['userid'] = $userid;			
		    $_SESSION['username'] = $username;
			$_SESSION['token'] = $token;
		    return true;
		} 	
	}
//permisos de usuario
	public function permisos_usuario($permiso){
		    $usuperm = $_SESSION['userid'];
		try {			
			$stmt = $this->_db->prepare('SELECT perm_id FROM `role_perm`,user_role where role_perm.role_id = user_role.role_id and user_role.user_id = :username  and role_perm.perm_id = :permiso');
			$stmt->execute(array('username' => $usuperm,'permiso' =>$permiso));
			
			$row = $stmt->fetch();
			if($row != ""){
				return true;
			}else{
				
			}
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}			
	}
		
	public function logout(){
		session_destroy();
	}

	public function is_logged_in(){
		if(isset($_SESSION['loggedin81si']) && $_SESSION['loggedin81si'] == true){

		try {
			$stmt = $this->_db->prepare('SELECT token FROM members WHERE memberID = :memberID AND active="Yes" ');
			$stmt->execute(array('memberID' => $_SESSION['userid']));
			
			$row = $stmt->fetch();
			if($row['token']==$_SESSION['token']){
			return true;				
			}
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
		}		
	}

public function borrar_test($id){
		try {
			$stmt = $this->_db->prepare('DELETE FROM test WHERE id = :id');
			$stmt->execute(array('id' => $id));
			header('Location: '.$_SERVER['HTTP_REFERER'].'');
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	
}


?>