<?php
class Usuario{

    private $_db;

    function __construct($db){    
    	$this->_db = $db;
    }
//Usuarios
	public function listar_usuarios($orden, $cuantos, $pagina, $division,$tipo,$dir){
		Define("DIR",$dir);
		$resto="";
		$inicio = ($pagina - 1)*$cuantos;
		if($pagina != 0){
		$resto = "&pagina=".$pagina;
		}
			
		$inicio = ($pagina - 1)*$cuantos;
		$orden = array("","username desc","username asc","nombre desc","nombre asc");
		try {
			if($tipo == 1){ //administradores
			$stmt = $this->_db->prepare('SELECT memberID, username, nombre,email, puesto FROM members where nivel="YWRtaW5ZV1J0YVc0PQ==" order by '.$orden[$division].' limit '.$inicio.', '.$cuantos.'');				
			}
			if($tipo == 2){ //franquiciatarios
			$stmt = $this->_db->prepare('SELECT memberID, username, nombre,email, puesto, nivel FROM members order by '.$orden[$division].' limit '.$inicio.', '.$cuantos.'');				
			}

			$stmt->execute(array('inicio' => $inicio,'cuantos' => $cuantos));
			$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo '<table class="w-full whitespace-no-wrap"><thead>
					<tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
					<th>ID</th>
                        <th>Usuario <a href="?division=1'.$resto.'"><i class="fa fa-chevron-up"></i></a> <a href="?division=2'.$resto.'"><i class="fa fa-chevron-down"></i></a></th>
                        <th>Nombre <a href="?division=3'.$resto.'"><i class="fa fa-chevron-up"></i></a> <a href="?division=4'.$resto.'"><i class="fa fa-chevron-down"></i></a></th>
                        <th>Email Usuario</th>
						<th>Cargo</th>
						<th>Permisos</th>
                        <th>Acciones</th>
                    </tr>
                   </thead>
                   <tbody
                    class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
                  >';
			foreach($row as $dato){
				if($dato["nivel"] != null or !empty($dato["nivel"])){
					$niveles = json_decode($dato["nivel"],true);
				}else{
					$niveles = array();
				}
				
				$permisos ="";
				if (is_array($niveles) || is_object($niveles))
{
				foreach($niveles as $nivel){
					$permisos .= "<span
					class=\"px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100\"
				  >".$nivel."</span>";
				}
			}
				echo '<tr class="text-gray-700 dark:text-gray-400">';
				echo "<td class='px-4 py-3 text-sm'>".$dato['memberID']."</td><td class='px-4 py-3 text-sm'>".$dato['username']."</td><td class='px-4 py-3 text-sm'>".$dato['nombre']."</td><td class='px-4 py-3 text-sm'>".$dato['email']."</td><td class='px-4 py-3 text-sm'>".$dato['puesto']."</td><td class='px-4 py-3 text-sm'>".$permisos."</td><td class='px-4 py-3 text-sm'><a href='".DIR."perfil.php?id=".$dato['memberID']."' class='btn btn-primary btn-sm'>Editar</a> - <a href='".DIR."borrarusuario.php?id=".$dato['memberID']."' class='btn btn-primary btn-sm'>Eliminar</a></td>";
				echo '</tr>';	
			}
		    echo '</tbody>
                  </table>';				
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
public function paginar_usuarios($orden, $cuantos, $pagina, $division,$tipo){	
		$resto="";
		$inicio = ($pagina - 1)*$cuantos;
		$resto = "&division=".$division;
		if($inicio < 1){ $inicio = 0; }
		try {
			if($tipo == 2){
			$stmt = $this->_db->prepare('SELECT count(*) as cuantos FROM members');				
			}
			if($tipo == 1){
			$stmt = $this->_db->prepare('SELECT count(*) as cuantos FROM members where nivel="ZnJhbnF1aWNpYXRhcmlvWm5KaGJuRjFhV05wWVhSaGNtbHY="');				
			}
			
			
			$stmt->execute(array('inicio' => $inicio,'cuantos' => $cuantos));
			$row = $stmt->fetch();
			$iniciopag = (($pagina - 1)*$cuantos)+1;
			$finpag = $pagina*$cuantos;
			if($finpag >$row['cuantos']){
				$finpag = $row['cuantos'];
			}
			echo '<div
			class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800"
		  >
			<span class="flex items-center col-span-3">
			  MOSTRANDO '.$iniciopag.'-'.$finpag.' de '.$row['cuantos'].'
			</span>
			<span class="col-span-2"></span>
			<!-- Pagination -->
			<span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
			  <nav aria-label="Table navigation">
				<ul class="inline-flex items-center">';
			for($x=1; $x<= ($row['cuantos']/$cuantos)+1; $x++){
				if($x == $pagina){
				echo "<li><span
				class=\"px-3 py-1 text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600 rounded-md focus:outline-none focus:shadow-outline-purple\"
			  >
			  ".$x."
			  </span></li>";
				}else{
				echo '<li><a href="?pagina='.$x.$resto.'"><span
				class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple"
			  >
			  '.$x.'
			  </span></a></li>';
				}
			}
			echo '</ul>
			</nav>
		  </span>
		</div>';
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
// borrar usuario	
public function borrar_usuario($id){
		try {
			$stmt = $this->_db->prepare('DELETE FROM members WHERE memberID = :id');
			$stmt->execute(array( 'id' => $id));
			/*$stmt1 = $this->_db->prepare('DELETE FROM datos WHERE IDUSUARIO = :id');
			$stmt1->execute(array( 'id' => $id));
			$stmt2 = $this->_db->prepare('DELETE FROM empresa WHERE IDUSUARIO = :id');
			$stmt2->execute(array( 'id' => $id));*/
		
			return true;
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}	
//Nombre de usuario
	public function nombre($user_id){	

		try {
			$stmt = $this->_db->prepare('SELECT APELLIDOPATERNO, APELLIDOMATERNO, NOMBRE FROM datos WHERE IDUSUARIO = :user_id');
			$stmt->execute(array('user_id' => $user_id));
			$row = $stmt->fetch();
			$nombre = $row['APELLIDOPATERNO']." ".$row['APELLIDOMATERNO']." ".$row['NOMBRE'];
			return ($nombre);
			$stmt = null;
		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	
}


?>