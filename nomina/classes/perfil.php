<?php
class Perfil{

    private $_db;

    function __construct($db){    
    	$this->_db = $db;
    }
//Perfil
	public function obtener_perfil($user_id){	

		try {
			$stmt = $this->_db->prepare('SELECT * FROM members WHERE memberID = :user_id');
			$stmt->execute(array('user_id' => $user_id));
			$row = $stmt->fetch();
			return ($row);
			$stmt = null;
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
//Logo
	public function cambiar_logo($user_id,$avatar){	

		try {
			$stmt = $this->_db->prepare('INSERT INTO members (memberID, logo) VALUES (:user_id,:avatar) ON DUPLICATE KEY UPDATE memberID = VALUES(memberID), logo= VALUES(logo)');
			$stmt->execute(array('user_id' => $user_id,'avatar' => $avatar));
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
//Avatar
	public function cambiar_avatar($user_id,$avatar){
//		echo $user_id."------------".$avatar;	

		try {
			$stmt = $this->_db->prepare('INSERT INTO members (memberID, avatar) VALUES (:user_id,:avatar) ON DUPLICATE KEY UPDATE memberID = VALUES(memberID), avatar= VALUES(avatar)');
			$stmt->execute(array('user_id' => $user_id,'avatar' => $avatar));
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

//obtener datos personales
public function mis_datos($id)
{
		try {
			$stmt = $this->_db->prepare('SELECT * FROM datos WHERE IDUSUARIO = :user_id');
			$stmt->execute(array('user_id' => $id));
			$row = $stmt->fetch();
			return ($row);
			$stmt = null;
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	
}

//obtener datos	
public function mi_empresa($id)
{
		try {
			$stmt = $this->_db->prepare('SELECT * FROM empresa WHERE IDUSUARIO = :user_id');
			$stmt->execute(array('user_id' => $id));
			$row = $stmt->fetch();
			return ($row);
			$stmt = null;
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	
}
//si es administrador
public function is_admin($id,$nivel)
{
		try {
			$stmt = $this->_db->prepare('SELECT count(*) as cuantos, nivel FROM members where memberID = :user_id');
			$stmt->execute(array('user_id' => $id));
			$row = $stmt->fetch();
			if($row['cuantos'] == 1){
				$proyectosarray = json_decode($row['nivel'],true);
				if(in_array($nivel, $proyectosarray, true)){
					return true;
				   }else{
					   return false;
				   }
				
			}else{
				return false;
			}
			$stmt = null;
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	
}

//avisos
	public function avisos($idusuario, $titulo, $aviso){	

		try {
			$stmt = $this->_db->prepare('INSERT INTO avisos (idusuario, titulo, aviso) VALUES (:user_id,:titulo, :aviso) ON DUPLICATE KEY UPDATE idusuario = VALUES(idusuario), titulo= VALUES(titulo), aviso= VALUES(aviso)');
			$stmt->execute(array('user_id' => $idusuario,'titulo' => $titulo,'aviso' => $aviso));
			return true;
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
// ver avisos
	public function listar_avisos(){	
		try {
			$stmt = $this->_db->prepare('SELECT * FROM avisos order by fecha desc');				
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $row;
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

//Perfil
	public function obtener_aviso($user_id){	

		try {
			$stmt = $this->_db->prepare('SELECT * FROM avisos WHERE id = :user_id');
			$stmt->execute(array('user_id' => $user_id));
			$row = $stmt->fetch();
			return ($row);
			$stmt = null;
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
//Borrar aviso
public function borrar_aviso($id){
		try {
			$stmt = $this->_db->prepare('DELETE FROM avisos WHERE id = :id');
			$stmt->execute(array( 'id' => $id));		
			return true;
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}	
//Checar si existe familia
	public function checar_llenadof($user_id,$tabla,$campo){	

		try {
			$stmt = $this->_db->prepare('SELECT count(*) as cuantos FROM '.$tabla.' WHERE IDUSUARIO = :user_id and tipo = :tipo');				
			$stmt->execute(array('user_id' => $user_id, 'tipo' => $campo));
			$row = $stmt->fetch();
			if($row['cuantos']>0){
				return true;
			}else{return false;}
			$stmt = null;
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
		//insertar itaviso //id, título, texto, tabla
		public function insertar_itaviso($idusuario, $titulo, $aviso,$niveles,$tabla){	

			try {
				$stmt = $this->_db->prepare('INSERT INTO '.$tabla.' (idusuario, titulo, aviso,niveles) VALUES (:user_id,:titulo, :aviso,:niveles) ON DUPLICATE KEY UPDATE idusuario = VALUES(idusuario), titulo= VALUES(titulo), aviso= VALUES(aviso), niveles= VALUES(niveles)');
				$stmt->execute(array('user_id' => $idusuario,'titulo' => $titulo,'aviso' => $aviso,'niveles'=>$niveles));
				return true;
			} catch(PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
			}
		}
	//insertar ita //id, título, texto, tabla
	public function insertar_ita($idusuario, $titulo, $aviso,$tabla){	

		try {
			$stmt = $this->_db->prepare('INSERT INTO '.$tabla.' (idusuario, titulo, aviso) VALUES (:user_id,:titulo, :aviso) ON DUPLICATE KEY UPDATE idusuario = VALUES(idusuario), titulo= VALUES(titulo), aviso= VALUES(aviso)');
			$stmt->execute(array('user_id' => $idusuario,'titulo' => $titulo,'aviso' => $aviso));
			return true;
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	// ver t // tabla
	public function ver_t($tabla,$orden,$campo){	
		try {
			$stmt = $this->_db->prepare('SELECT * FROM '.$tabla.' order by '.$campo.' '.$orden.'');				
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $row;
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
//obtener it //id tabla para elemento único
public function obtener_it($user_id,$tabla){	

	try {
		$stmt = $this->_db->prepare('SELECT * FROM '.$tabla.' WHERE id = :user_id');
		$stmt->execute(array('user_id' => $user_id));
		$row = $stmt->fetch();
		return ($row);
		$stmt = null;
	} catch(PDOException $e) {
		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	}
}
public function borrar_it($id,$tabla){
	try {
		$stmt = $this->_db->prepare('DELETE FROM '.$tabla.' WHERE id = :id');
		$stmt->execute(array( 'id' => $id));		
		return true;
	} catch(PDOException $e) {
		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	}
}
//insertar categoria //id, título, texto, icono, color
public function insertar_categorias($idusuario, $titulo, $aviso,$icono, $color){	

	try {
		$stmt = $this->_db->prepare('INSERT INTO categorias (idusuario, titulo, aviso,icono, color) VALUES (:user_id,:titulo, :aviso, :icono, :color) ON DUPLICATE KEY UPDATE idusuario = VALUES(idusuario), titulo= VALUES(titulo), aviso= VALUES(aviso)');
		$stmt->execute(array('user_id' => $idusuario,'titulo' => $titulo,'aviso' => $aviso, 'icono' => $icono, 'color' => $color));
		return true;
	} catch(PDOException $e) {
		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	}
}
//editar categoria //id, título, texto, icono, color
public function editar_categorias($id,$idusuario, $titulo, $aviso,$icono, $color){	

	try {
		$stmt = $this->_db->prepare('UPDATE categorias set idusuario = :user_id , titulo = :titulo , aviso = :aviso ,icono = :icono, color = :color where id = :id');
		$stmt->execute(array('id'=> $id,'user_id' => $idusuario,'titulo' => $titulo,'aviso' => $aviso, 'icono' => $icono, 'color' => $color));
		return true;
	} catch(PDOException $e) {
		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	}
}

//actualizar perfil
public function actualiza_perfil($userid,$nombre,$cargo,$proyectos,$niveles,$estadisticas,$estadisticasfb){	

	try {
		$stmt = $this->_db->prepare('INSERT INTO members (memberID,nombre,puesto, proyectos,nivel,estadisticas,estadisticasfb) VALUES (:user_id,:nombre,:cargo, :proyectos,:nivel,:estadisticas,:estadisticasfb)
ON DUPLICATE KEY UPDATE memberID = VALUES(memberID),nombre = VALUES(nombre),puesto = VALUES(puesto), proyectos = VALUES(proyectos), nivel = VALUES(nivel), estadisticas = VALUES(estadisticas), estadisticasfb = VALUES(estadisticasfb)');
		$stmt->execute(array('user_id'=>$userid,'nombre'=>$nombre,'cargo'=>$cargo,'proyectos'=>$proyectos,'nivel'=>$niveles,'estadisticas'=>$estadisticas,'estadisticasfb'=>$estadisticasfb));
		return true;
		} catch(PDOException $e) {
	    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
			//insertar trámite //id, título, texto, tabla
			public function insertar_tarea($id, $idusuario, $titulo, $categoria,  $asignado, $tiempo,$aviso){
				try {
					$stmt = $this->_db->prepare('INSERT INTO tareas (id, creador, tarea, proyecto,asignado,fecha, descripcion, estado ) VALUES (:id, :creador, :tarea, :proyecto,:asignado,:fecha, :descripcion, "1") ON DUPLICATE KEY UPDATE creador = VALUES(creador), tarea= VALUES(tarea),  proyecto= VALUES(proyecto), asignado= VALUES(asignado), fecha= VALUES(fecha), descripcion= VALUES(descripcion),estado= VALUES(estado)');
					$stmt->execute(array('id' => $id,'creador' => $idusuario,'tarea' => $titulo,'proyecto' => $categoria,'asignado'=>$asignado,'fecha'=>$tiempo,'descripcion'=>$aviso ));
					return true;
				} catch(PDOException $e) {
					echo '<p class="bg-danger">'.$e->getMessage().'</p>';
				}
			}
//termina archivo
}


?>