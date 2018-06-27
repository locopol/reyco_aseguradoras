<?php

class Model_fotos extends CI_Model {

	function __construct()
	{
		// Llamar al constructor de CI_Model
		parent::__construct();
	
	}

	function get_imgs($pat) {

		$error = 0;
		$retval = 0;
		$x = 0;$y = 0;
		$this->load->library('image_lib');
   		$config['hostname'] = $this->config->item('hostname');
        	$config['username'] = $this->config->item('username');
        	$config['password'] = $this->config->item('password');
        	$config['port']     = 21;  
        	$config['passive']  = FALSE;
        	$config['debug']    = $this->config->item('debug_ftp');
		$config['image_library'] = 'gd2';
		$config['width'] = 720;
		$config['height'] = 540;
		
		$err = $this->ftp->connect($config);

		if ($err == "")
			return 255;

		$list = $this->ftp->list_files($this->config->item('rdir') . $pat);

		if ($list[0])
		{

			if (!is_dir($this->config->item('ldir') . $pat))			
				mkdir($this->config->item('ldir') . $pat);

			foreach ($list as $rfile) {

				$lfile = str_replace($this->config->item('rdir') . $pat, '', $rfile);
				$efile = explode(".", strtolower($lfile));
			
		if(count($efile) == 2) {
			if(($efile[1] == 'jpg' || $efile[1] == 'jpeg' || $efile[1] == 'png' || $efile[1] == 'bmp') && $x <6)    
			{
				$retval = $this->ftp->download($rfile, $this->config->item('ldir') . $pat . $lfile, 'binary');
				$config['source_image'] = $this->config->item('ldir') . $pat . $lfile;
	
				$retval2 = $this->image_lib->initialize($config);

			  	$this->image_lib->resize();

			}

			if ($efile[1] == 'mp4' || $efile[1] == 'ogv' || $efile[1] == 'webm')
			{

				$retval = $this->ftp->download($rfile, $this->config->item('ldir') . $pat . $lfile, 'binary');
			}


		
		}

				if($retval)
					if ($retval2)
						$error = 1;
					else
						$error = 128;
				$x++;
			}

		}

		$this->ftp->close(); 

	 return $error;

	}

	function del_imgs($pat) {

		if (is_dir($this->config->item('ldir') . $pat)) {
		    	foreach (scandir($this->config->item('ldir') . $pat) as $item) {
		        	if ($item == '.' || $item == '..') continue;
				else unlink($this->config->item('ldir') . $pat . '/' . $item);
			}

			rmdir($this->config->item('ldir') . $pat);

		} else {
			return 0;
		}

	return 1;
			
	}

	function is_reg($pat) {

		$this->db->where('placa', $pat);
		$query=$this->db->select('placa');

   	  	if ($this->db->affected_rows() == 1)
			return 1;
		else
			return 0;

	}


	function insert_reg($pat) {

		$data = array(
			'idfoto' => 0,
			'placa' => $pat,
			'created_at' => date('Y-m-d H:i:s'),
		);

		$this->db->insert('fotos', $data); 

   	  	if ($this->db->affected_rows() == 0)
			return 1;
		else
			return 0;

	}

	function delete_reg($pat) {

		$this->db->delete('fotos', array('placa' => $pat)); 

   	  	if ($this->db->affected_rows() == 0)
			return 1;
		else
			return 0;

	}

	function delete_fotos_reg() {

		$this->db->empty_table('fotos'); 

   	  	if ($this->db->affected_rows() == 0)
			return 0;
		else
			return $this->db->affected_rows();

	}

	function delete_fotos_media() {
		
		$x = 0;

		if (is_dir($this->config->item('ldir'))) {
			foreach (scandir($this->config->item('ldir')) as $item)
				$x++;
		
			delete_files($this->config->item('ldir'), TRUE);		
		} else {
			return 0;
		}

	return ($x-2);
			
	}

	function check_reg($pat) {

		$sql = "SELECT idfoto FROM fotos WHERE placa = ?";

		$this->db->query($sql, array($pat)); 

		if ($this->db->affected_rows() == 0)
			return 0;
		else
			return 1;

	}

	function img_path($pat) {

		$sql='select placa from fotos where placa = \'' . $pat .  '\'';
		$query=$this->db->query($sql);

   	  	if ($this->db->affected_rows() == 1)
		{
			$retval = array(); $x = 0;

			foreach (scandir($this->config->item('ldir') . $pat) as $item) {
				$efile = explode(".", strtolower($item));
				if ($item == '.' || $item == '..' || $efile[1] == 'mp4' || $efile[1] == 'ogv' || $efile[1] == 'webm') continue;
			//	if ($item == '.' || $item == '..') continue;
				else $retval[$x] = $this->config->item('pdir') . $pat . '/' . $item;
				$x++;
			}
		
			if (count($retval) == 0)
			  $retval = 0;

		}
		
		else {
			return 0;
		}

	return $retval;
	
	}

	function check_video_path($pat) {

		$sql='select placa from fotos where placa = \'' . $pat .  '\'';
		$query=$this->db->query($sql);

   	  	if ($this->db->affected_rows() == 1)
		{
			$retval = 0; $x = 0;

		    	foreach (scandir($this->config->item('ldir') . $pat) as $item) {
				if ($item == '.' || $item == '..')  { continue; }
				else {
					$efile = explode(".", strtolower($item));
					if ($efile[1] == 'mp4' || $efile[1] == 'ogv' || $efile[1] == 'webm')
						$retval = $this->config->item('pdir') . $pat . '/' . $item;
				}
				$x++;
			}
		

		}
		else
		{
			return 0;
		}

	return $retval;

	}

	function get_msg($id) 
	{

		switch ($id)
		{

			case '-1':
				$retval = 'Las imagenes ya fueron procesadas, no se realiza ninguna acci&oacute;n.';
				break;
			case '1':
				$retval = 'Imagenes obtenidas correctamente desde el servidor.';
				break;
			case '0':
				$retval = 'No se encontraron imagenes disponibles para el vehiculo seleccionado.';
				break;
			case '128':
				$retval = 'Error de inicialiaci&oacute;n de libreria de imagenes GD, contactese con el administrador.';
				break;
			case '255':
				$retval = 'Error de comunicaci&oacute;n con el servidor de imagenes, contactese con el administrador.';
				break;
			default:
				$retval = 'No se realizo ninguna acccion';
		}

	 return $retval;
	}

}

?>
