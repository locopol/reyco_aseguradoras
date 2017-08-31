<?php

class Dbmaint extends CI_Model {

	//var $activo;

	function __construct()
	{
		// Llamar al constructor de CI_Model
		parent::__construct();
		// Version 1
		// $this->tcampos = 21;
		// Version 2
		// $tcampos = 22;
		// Version 3
		// $tcampos = 23;
		// Version 4
		   $this->tcampos = 30;
	
	}

	function carga_archivo() {

	    /* variable de cantidad de campos */
		// En constructor
	
	    /* validar existencia del archivo de datos (impedir errores de proceso) */

	    if (!read_file($this->config->item('filedata_dir') . $this->config->item('filedata_name')))
		    return 2;
	    
	    /* Generar PID y consultarlo (impedir duplicidad de informacion y ejecución) */

	    $datefileinfo = get_file_info('./application/logs/cargaarchivo.pid', array('date'));

	    error_log("archivo: " . (time() - $datefileinfo['date']));
	    if ( file_exists('./application/logs/cargaarchivo.pid') ) {
		    if ((time() - $datefileinfo['date']) <= 300)
			    return -1;
		    else
			    write_file('./application/logs/cargaarchivo.pid',$this->session->userdata('session_id'));


		    $pid = read_file('./application/logs/cargaarchivo.pid');

		     if ($pid != $this->session->userdata('session_id'))
		      return -1;
		} else {
			write_file('./application/logs/cargaarchivo.pid',$this->session->userdata('session_id'));
		}

		/* Abrir en formato CSV (version 4) */

	    	$linea = 0;
		$original = ini_get("auto_detect_line_endings");
		ini_set("auto_detect_line_endings", true);

		if (($gestor = fopen( $this->config->item('filedata_dir') . $this->config->item('filedata_name') , "r")) !== FALSE) {
			while (($datos = fgetcsv($gestor, 10000, "|")) !== FALSE) {
			$i = 0;
			$total = count($datos);
			
			if ($linea == 0 && $total != $this->tcampos ) {
				/* salir si cabeceras no corresponden al largo correcto */
				unlink('./application/logs/cargaarchivo.pid');
				fclose($gestor);
				ini_set("auto_detect_line_endings", $original);
     				return 1;
			} elseif ($linea == 0) {
			      	/* Eliminar Registros de Stock Actual */
				$this->db->empty_table('stocklist');
			
			//} elseif ($datos[0] == null) {
				 //nothing, corresponde al ultimo salto de linea puesto en el insumo (enviar corrección a mario)
			//	 $linea--;
			
			} else {
			
 	   	$folders = array();
		$texto = '';
		$fecha = date('Y-m-d H:i:s');

//Estado|Tipo Vehiculo|Marca|Modelo|Color|Inventario|Siniestro|Placa|Anno|Chasis|Motor|Dueño Ant.|Rut Dueño Ant.|Funcionando|Nº Remate|Nº Lote|Con Documento|Con Llave|Liquidador|Condicionado|Proveedor|Nº Liquidación|Fecha Liq.|Fecha Recep.|Ubicacion|Comentario Cia.|Fecha Remate|Monto Adjudicado|Monto Indemnizado|Monto Minimo
	$s = array(
	'created_at' => $fecha,
	'updated_at' => $fecha,
        'estado' => trim($datos[$i++]),
        'tipo' => trim($datos[$i++]),
        'marca' => trim($datos[$i++]),
        'modelo' => utf8_encode(trim($datos[$i++])),
        'color' => utf8_encode(trim($datos[$i++])),
        'inventario' => trim($datos[$i++]),
        'siniestro' => trim($datos[$i++]," \t\n\r\0\x0B\x2E"),
        'placa' => trim($datos[$i++]),
        'anno' => trim($datos[$i++]),
        'chassis' => trim($datos[$i++]),
	'motor' => trim($datos[$i++]),
	'duennoant' => utf8_encode(trim($datos[$i++])),
	'rutduennoant' => trim($datos[$i++]," \t\n\r\0\x0B\x2E"),
	'funciona' => trim($datos[$i++]),
	'numremate' => trim($datos[$i++]),
	'numlote' => trim($datos[$i++]),
	'condocumento' => trim($datos[$i++]),
	'conllave' => trim($datos[$i++]),
	'liquidador' => utf8_encode(trim($datos[$i++])),
	'condicionado' => trim($datos[$i++]),
	'proveedor' => trim($datos[$i++]),
	'numliq' => trim($datos[$i++]),
	'fechaliq' => $datos[$i] == '' ? null[$i++] : date("Y-m-d",strtotime(str_replace('/','-',$datos[$i++]))),
	'fecharecep' => $datos[$i] == '' ? null[$i++] : date("Y-m-d",strtotime(str_replace('/','-',$datos[$i++]))),
	'ubicac' => utf8_encode(trim($datos[$i++])),
	'comentario' => utf8_encode(trim($datos[$i++])),
	'fecharemate' => $datos[$i] == '' ? null[$i++] :  date("Y-m-d",strtotime(str_replace('/','-',$datos[$i++]))),
	'montoadj' => trim($datos[$i++]),
	'montoindem' => trim($datos[$i++]),
	'montomin' => trim($datos[$i])
	);

		if ($s['proveedor'] == 'BCI SEGUROS GENERALES S.A.')   $s['proveedor'] = 'BCI/ZENIT SEGUROS GENERALES S.A.';
		if ($s['proveedor'] == 'ZENIT SEGUROS GENERALES S.A.') $s['proveedor'] = 'BCI/ZENIT SEGUROS GENERALES S.A.';

	    	if ($s['comentario'] == '') $s['comentario'] = 'Sin Comentario';
	     	 preg_match_all("/-.$/", $s['placa'],$result);
		
		 if ($result[0] != array())
		  $s['placa'] = substr($s['placa'],0,strlen($s['placa']) - 2);

	    	 if ($s['ubicac'] != 'VEHICULOS ANULADO')
	      	  $this->db->insert('stocklist', $s); 
	    	 else
	     	  if ($s['estado'] == 'S' && $s['ubicac'] == 'VEHICULOS ANULADO')
    	      	   $this->db->insert('stocklist', $s); 

             	unset($s);
            }

	    $i=0;
	    $linea++;
			
	  }

      	   unlink('./application/logs/cargaarchivo.pid');
      	   ini_set("auto_detect_line_endings", $original);
      	   fclose($gestor);
      	   return 0;

    	 }

	}

	function get_lista($estado, $proveedor) {

		switch ($estado) {
		case 'V':
			$estado_array = array('V','R');
			break;
		case 'S':
			$estado_array = array('S');
			break;
		}

		    if ($proveedor == 'ALL') {
			$sql='select (CASE WHEN fecharecep is NULL THEN \'Sin fecha\' ELSE date_format(fecharecep, \'%d-%m-%Y\') END) as \'Recepci&oacute;n\', siniestro as Siniestro, placa as Patente, tipo as Tipo, marca as Marca, modelo as Modelo, anno as \'A&ntilde;o\', concat(\'<a href=\"' . base_url() . 'base\/historial\/\',id,\'\/' . $estado . '\/_\/_\" >Detalles<\/a>\') as Accion from stocklist WHERE estado in ?';
			$query=$this->db->query($sql, array($estado_array));
		    } else {

			$sql='select (CASE WHEN fecharecep is NULL THEN \'Sin fecha\' ELSE date_format(fecharecep, \'%d-%m-%Y\') END) as \'Recepci&oacute;n\', siniestro as Siniestro, placa as Patente, tipo as Tipo, marca as Marca, modelo as Modelo, anno as \'A&ntilde;o\', concat(\'<a href=\"' . base_url() . 'base\/historial\/\',id,\'\/' . $estado . '\/_\/_\" >Detalles<\/a>\') as Accion from stocklist WHERE estado in (?) and proveedor = ?';
			$query=$this->db->query($sql, array($estado, $proveedor));
		    }
		
		return $query;

	}


	function search_db($base,$pattern,$estado,$proveedor) {

		switch ($base) {
			case 'pat': $where='placa'; break;
			case 'sin': $where='siniestro'; break;
			case 'rem': $where='numremate'; break;
			case 'liq': $where='numliq'; break; 
		}

		$allwhere = '';
		$this->db->select('(CASE WHEN fecharecep is NULL THEN \'Sin fecha\' ELSE  date_format(fecharecep, \'%d-%m-%Y\') END) as \'Recepci&oacute;n\', siniestro as \'Siniestro\', placa as \'Patente\', tipo as \'Tipo\', marca as \'Marca\', modelo as \'Modelo\', anno as \'A&ntilde;o\', concat(\'<a href="' . base_url() . 'base\/historial\/\',id,\'\/' . $estado . '\/_\/_">Detalles<\/a>\') as \'Accion\'',FALSE);

// activar solo cuando se busquen por codigo de remate (historial)
//		if ($base == 'rem') { $allwhere = "{$where} = '" . $pattern . "' AND proveedor = '" . $proveedor . "'"; }
//		else { $allwhere = "proveedor='{$proveedor}' AND {$where} LIKE '%{$pattern}%'"; }
		$allwhere = "(proveedor='" . $proveedor . "' OR proveedor = 'ASISTENCIA') AND {$where} LIKE '%{$pattern}%'"; 	

		if ($proveedor == "ALL") $allwhere = "{$where} = '{$pattern}'";

//		if ($proveedor == "ALL") $allwhere = array($where => $pattern);

		$this->db->where($allwhere,NULL,FALSE);

		$query=$this->db->get('stocklist');

		   if ($this->db->affected_rows() == 0)
			return 0;

		return $query;

	}

	function search_db_excel($base,$pattern,$proveedor) {

		switch ($base) {
			case 'pat': $where='placa'; break;
			case 'sin': $where='siniestro'; break;
			case 'rem': $where='numremate'; break;
			case 'liq': $where='numliq'; break;
			case 'all': $where='estado'; break;
		}
		$allwhere = '';
		$this->db->select('siniestro as \'N° Siniestro\', tipo as \'Tipo\', marca as \'Marca\', modelo as \'Modelo\', anno as \'A&ntilde;o\',  placa as \'Patente\', (CASE WHEN fecharecep is NULL THEN \'Sin fecha\' ELSE  date_format(fecharecep, \'%d-%m-%Y\') END) as \'F. Ingreso\', ubicac as \'Ubicación\', comentario as \'Comentario a CIA\'',FALSE);

// activar solo cuando se busquen por codigo de remate (historial)		
//		if ($base == 'rem') { $allwhere = "{$where} = '" . $pattern . "' AND proveedor = '" . $proveedor . "'"; }
//		else { $allwhere = "proveedor='" . $proveedor . "' AND {$where} LIKE '%{$pattern}%'"; }
//
		$allwhere = "proveedor='" . $proveedor . "' AND {$where} LIKE '%{$pattern}%'"; 

		if ($proveedor == "ALL") $allwhere = "{$where} = '{$pattern}'";

		$this->db->order_by("fecharecep", "desc");

		$this->db->where($allwhere, NULL, FALSE);

		$query=$this->db->get('stocklist');

		   if ($this->db->affected_rows() == 0)
			return 0;

		return $query;

	}

	function get_value_of_col($id,$col) {


		$this->db->select($col);
		$this->db->where('id',$id);
		$query=$this->db->get('stocklist');
		$result=$query->result();
		if ($this->db->affected_rows() != 0)
		{

			if ($col == 'estado')
			{
				if ($result[0]->$col == 'V') 
					return 'Vendido';
				if ($result[0]->$col == 'S') 
					return 'Stock';
				if ($result[0]->$col == 'R') 
					return 'Reparado';
			}

			if ($col == 'funciona' || $col == 'condocumento' || $col == 'conllave' || $col == 'condicionado')
			{
				if($result[0]->$col == 'S')
					return 'Si';
				else
					return 'No';
			}

			if ($result[0]->$col == '')
				return "N/A";
		return $result[0]->$col;

		}
	return "NOPE";

	}

	function get_patentes_stock () {

		$sql='select placa from stocklist where placa not in (select placa from fotos) and estado = \'S\'';
			
		$query=$this->db->query($sql);

		return $query;

	}

	function get_patentes_vencidas () {

		$sql='select placa from fotos where placa not in (select b.placa from stocklist b where estado = \'S\')';
		$query=$this->db->query($sql);

		return $query;
	}


	function add_reg_update($values) {

		//Insertar directamente para mantener registro de cambios
	
      		$s = array(
        		'idInventario' => $values['idInventario'],
			'val_monto_indemnizado' => $values['val_monto_indemnizado'],
			'val_monto_minimo' => $values['val_monto_minimo'],
			'val_prox_remate' => $values['val_prox_remate'],
			'status_update' => false,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		);

		$this->db->insert('stocklist_feedback', $s); 
	
		 if ($this->db->affected_rows() == 0)
			return 0;

		 return 1;
	}

	function upd_reg_update($idInventario, $col, $value) {

		$sql = 'UPDATE stocklist_feedback SET ' . $col . ' = ?, updated_at = ? WHERE idInventario = ?';

		$query=$this->db->query($sql, array($value, date('Y-m-d H:i:s'), $idInventario));

		if ($this->db->affected_rows() == 0)
			 return 0;
	
		return 1;
	}

	function get_reg_update($idInventario, $col) {
		$this->db->select($col);
		$this->db->where('idInventario',$idInventario);
		$query=$this->db->get('stocklist_feedback');
		$result=$query->result();

		if (!$result)
			return 0;
		
		return $result[0]->$col;
	}

	function del_reg_update ($idInventario) {
		$sql = "DELETE FROM stocklist_feedback where idInventario = ?";

		$query=$this->db->query($sql, array($idInventario));

		if ($this->db->affected_rows() == 0)
		 return 0;
	
		return 1;

	}

	function upd_feedback () {

		$sql = "select a.id from stocklist_feedback a, stocklist b where a.idInventario = b.inventario and a.val_monto_indemnizado = b.montoindem and a.val_monto_minimo = b.montomin";

		$query=$this->db->query($sql);

		if ($this->db->affected_rows() == 0) {
			return 0;
		}
		else {
			$sql = "delete from stocklist_feedback where id in ?";
			$query=$this->db->query($sql, $query->result_array());

			if ($this->db->affected_rows() == 0) 
				return 0;

			return 1;
		}

	}

	function get_proveedores () {

		$sql = 'select proveedor from stocklist group by proveedor;';
		$query=$this->db->query($sql);

		if ($this->db->affected_rows() == 0)
			return 0;

			return $query->result_array();
	}


	function add_user($user, $passwd, $title, $company)
	{
		$sql = "INSERT INTO user VALUES  (0,?,?,?,?)";
		$this->db->query($sql, array($user,$passwd,$company, $title)); 

		if ($this->db->affected_rows() == 0)
			return 0;
		
		return 1;
	}

	function del_user($idUser) {
		$sql = "DELETE FROM user where id = ?";

		$query=$this->db->query($sql, array($idUser));

		if ($this->db->affected_rows() == 0)
		 return 0;
	
		return 1;

	}

	function pwd_user($idUser, $pwd) {

		$sql = 'UPDATE user SET password = ? WHERE id = ?';

		$query=$this->db->query($sql, array($pwd, $idUser));

		if ($this->db->affected_rows() == 0)
			 return 0;
	
		return 1;
	}

	function get_user($idUser) {
		$sql='select user from user where id = ?';
		$query=$this->db->query($sql, array($idUser));

	  	 if ($this->db->affected_rows() == 0)
			return 0;

		$result=$query->result();
		return $result[0]->user;

	}

	function get_users_del() {
		$sql='select user as Usuario, title as \'Descripci&oacute;n\', company as \'Compa&ntilde;ia\', concat(\'<button id=btn_del class=btn_del value=\', id, \'>Eliminar<\/button>\') as Accion from user WHERE user not in (\'admin\',\'lionheart\')';
		$query=$this->db->query($sql);

	  	 if ($this->db->affected_rows() == 0)
			return 0;

		return $query;

	}

	function get_users_pwd() {
		$sql='select user as Usuario, title as \'Descripci&oacute;n\', company as \'Compa&ntilde;ia\', concat(\'<button id=btn_pwd class=btn_pwd value=\', id, \'>Cambiar contrase&ntilde;a<\/button>\') as Accion from user';
		$query=$this->db->query($sql);

	  	 if ($this->db->affected_rows() == 0)
			return 0;

		return $query;

	}
}

?>
