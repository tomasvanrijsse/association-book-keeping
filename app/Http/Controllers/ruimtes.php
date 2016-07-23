<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ruimtes extends CI_Controller {

	public function index()
	{
            $data = array();
            
            $this->load->view('ruimtes/index', $data);
	}

}