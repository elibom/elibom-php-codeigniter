<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms extends CI_Controller {

	public function __construct() {
        parent::__construct();
		$this->load->library('elibom');
    }
	public function index()
	{
		$sms = $this->elibom->sendMessage('numero de celular', 'texto del mensaje');
		echo '<pre>';print_r($sms['deliveryToken']);echo '</pre>';
	}

	
}