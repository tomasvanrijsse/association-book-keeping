<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class gedeeldeposten extends CI_Controller {

	public function index()
	{
            $data = array();
            
            $this->load->view('gedeeldeposten/index', $data);
	}

}