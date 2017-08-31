<?php

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url', 'file'));
		$this->load->config('custom_config');
		$this->load->library('session');
	      	$this->load->model('model_login');
		$this->load->database();
	}

	function genpw($clave) 
	{
		echo sha1($clave);
	}

	function index() 
	{
    		if( $this->session->userdata('isLoggedIn') ) {
        	redirect(base_url() . 'base/search');
    		} else {
        	$this->show_login(2);
		}
	}

	function show_login( $show_error = false ) 
	{
		switch ($show_error) {
			case 0:
				$data = array('estado' => '', 'error' => '', 'success' => '');
				break;
			case 1:
				$data = array('estado' => '', 'error' => 'Error de autentificaci&oacute;n, usuario o contrase&ntilde;a incorrectos.', 'success' => '');
				break;
			case 2:
				$data = array('estado' => '', 'success' => 'Sesi&oacute;n expirada, ingrese sus credenciales nuevamente.', 'error' => '');
				break;
		}

    		$this->load->helper('form','url');
		$this->load->view('login',$data);
	}

	function login_user() 
	{

	// Grab the email and password from the form POST
      	$user = $this->input->post('user');
      	$pass  = $this->input->post('password');

      	//Ensure values exist for email and pass, and validate the user's credentials
      	if( $user && $pass && $this->model_login->validate_user($user,$pass)) {
          // If the user is valid, redirect to the main view
          redirect(base_url() . 'base/search/1');
      	} else {
          // Otherwise show the login screen with an error message.
          $this->show_login(1);
      	}
	
	}

	function logout_user()
	{
		/* eliminar pid de carga de datos, en caso de existir */
		if(file_exists('./application/logs/cargaarchivo.pid')) {
		 $pid = read_file('./application/logs/cargaarchivo.pid');
 		  if ($pid == $this->session->userdata('session_id'))
		   unlink('./application/logs/cargaarchivo.pid');
		}

		$this->model_login->kill_session();
		$this->show_login(0);
	}

}

?>
