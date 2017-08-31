<?php

class Fotos2 extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->config->load('ftp');
		$this->load->helper(array('form', 'url','file'));
		$this->load->library(array('session','ftp','table'));
		$this->load->model(array('model_fotos','dbmaint'));
		$tmpl=array('table_open' => '<table class="display datatable">');
		$this->table->set_template($tmpl);
	}

	function test()
	{

	echo mime_content_type('fotos/VT-2051/vid.mp4') . "\n";
	}

	function procesa_fotos_pat($pat = 0,$id = 0)
	{
		if($pat == 0 && $id == 0)
			redirect('/');

		if($this->model_fotos->check_reg($pat))
			redirect(base_url() . 'base/historial/' . $id . '/S/success/-1');

		 $imgresult = $this->model_fotos->get_imgs($pat);
		 $this->db->reconnect();
		 
		 if ($imgresult == 1)
		 {
			 $this->model_fotos->insert_reg($pat);
			 redirect(base_url() . 'base/historial/' . $id . '/S/success/' . $imgresult);
		 }
		 if ($imgresult == 0)
		 {
			 redirect(base_url() . 'base/historial/' . $id . '/S/error/' . $imgresult);
		 }

		 if ($imgresult == 128)
		 {
			 redirect(base_url() . 'base/historial/' . $id . '/S/error/' . $imgresult);
		 }

		 if ($imgresult == 255)
		 {
			 redirect(base_url() . 'base/historial/' . $id . '/S/error/' . $imgresult);
		 }

	}

	function procesa_fotos() 
	{

		// llamar a funcion para obtener patentes a copiar
		
		$dbresult = $this->dbmaint->get_patentes_stock();

		// llamar a funcion para eliminar fotos de patentes vencidas

		$dbresult2 = $this->dbmaint->get_patentes_vencidas();

		// llamar a funcion para procesar patentes y traer archivos

		$fileok = 0; $filefail = 0; $deletes = 0;

		foreach ($dbresult->result() as $row) {

 		 $imgresult = $this->model_fotos->get_imgs($row->placa);

		 if ($imgresult == 0) {
			 $filefail++;
		 } 

		 if ($imgresult == 1) {

			 $this->model_fotos->insert_reg($row->placa);
			 $fileok++;
		 }

		 if ($imgresult == 255) {

			 $opcion = array('estado' => 'S', 'historial' => '', 'result' => '', 'error' => 'Error de comunicaci&oacute;n con el servidor central', 'success' => '');
			$this->load->view('base', $opcion);

			 return 0;
		 }

		}

		foreach ($dbresult2->result() as $row) {

			if($this->model_fotos->del_imgs($row->placa));
			 if($this->model_fotos->delete_reg($row->placa));
			  $deletes++;
		}

		if ($fileok == 0 && $filefail == 0)
			$opcion = array('estado' => 'S', 'historial' => '', 'result' => '', 'error' => '', 'success' => 'No se encontraron cambios en la sincronización.');

		if ($fileok == 0) {
			$result = "Se produjeron problemas al obtener fotos desde el servidor, patentes descartadas: " . $filefail . ", purgadas: " . $deletes;
			$opcion = array('estado' => 'S', 'historial' => '', 'result' => '', 'error' => $result, 'success' => '');
		} else {
			$result = "Fotos obtenidas correctamente, patentes importadas: " . $fileok . ", no importadas: " . $filefail . ", purgadas: " . $deletes;
			$opcion = array('estado' => 'S', 'historial' => '', 'result' => '', 'error' => '', 'success' => $result);
		}

		$this->load->view('base', $opcion);

	}

}
