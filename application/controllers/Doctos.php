<?php

class Doctos extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->config->load('custom_config');
		$this->load->helper(array('form', 'url','file'));
		$this->load->library(array('session','ftp','table'));
		$this->load->model(array('model_doctos','dbmaint'));
		$tmpl=array('table_open' => '<table class="display datatable">');
		$this->table->set_template($tmpl);
	}


	function procesa_doctos_siniestro($id, $idDocto = 0,$idSiniestro = 0)
	{
		if($idDocto == 0 && $idSiniestro == 0)
			redirect('/');

		if($this->model_doctos->check_reg($idSiniestro))
			redirect(base_url() . $this->model_doctos->doc_path($idSiniestro));
			
			//redirect(base_url() . 'base/historial/' . $id . '/S/success/-1');


		 $docresult = $this->model_doctos->get_doctos($idDocto, $idSiniestro);
		 $this->db->reconnect();
		 
		 if ($docresult == 1)
		 {
			 $this->model_doctos->insert_reg($idDocto, $idSiniestro);
 		 	 redirect(base_url() . $this->model_doctos->doc_path($idSiniestro));
		 }
		 if ($docresult == 0)
		 {
			 redirect(base_url() . 'base/historial/' . $id . '/S/error/' . $docresult);
		 }

		 if ($docresult == 128)
		 {
			 redirect(base_url() . 'base/historial/' . $id . '/S/error/' . $docresult);
		 }

		 if ($docresult == 255)
		 {
			 redirect(base_url() . 'base/historial/' . $id . '/S/error/' . $docresult);
		 }

	}


}
