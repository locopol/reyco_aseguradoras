<?php

class Model_login extends CI_Model {

var $details;

	function __construct()
	{
		// Llamar al constructor de CI_Model
		parent::__construct();
	
	}



	function validate_user( $user, $password ) 
	{
    	// Build a query to retrieve the user's details
    	// based on the received username and password
    	$this->db->from('user');
    	$this->db->where('user',$user );
    	$this->db->where( 'password', sha1($password) );
    	$login = $this->db->get()->result();

    	// The results of the query are stored in $login.
    	// If a value exists, then the user account exists and is validated
    	if ( is_array($login) && count($login) == 1 ) {
        // Set the users details into the $details property of this class
		$this->details = $login[0];

	// Establecer niveles de acceso por compañia

		$this->db->select('company');
	    	$this->db->from('user_company');
    		$this->db->where('idUser', $this->details->id);

		$company = array();
		
		foreach ( $this->db->get()->result() as $row )
		 array_push($company, $row->company);
		
		$this->details2 = $company;
		
        // Call set_session to set the user's session vars via CodeIgniter
        $this->set_session();
        return true;
	}

    	return false;
	}


	function set_session() 
	{
    	// session->set_userdata is a CodeIgniter function that
    	// stores data in a cookie in the user's browser.  Some of the values are built in
    	// to CodeIgniter, others are added (like the IP address).  See CodeIgniter's documentation for details.
    	$this->session->set_userdata( array(
            'id'=>$this->details->id,
	    'user'=>$this->details->user,
	    'proveedor'=>$this->details->company,
	    'titulo'=>$this->details->title,
	    'company' => $this->details2,
            'email'=>$this->details->email,
           // 'avatar'=>$this->details->avatar,
           // 'tagline'=>$this->details->tagline,
           // 'isAdmin'=>$this->details->isAdmin,
           // 'teamId'=>$this->details->teamId,
            'isLoggedIn'=>true
        ));
	
	}

	function kill_session()
	{
		$this->session->sess_destroy();
		$this->session->set_userdata(array('id' => '', 'user' => '', 'isLoggedIn' => '', 'proveedor' => '', 'titulo' => '', 'company' => ''));
	return 0;

	}

}

?>
