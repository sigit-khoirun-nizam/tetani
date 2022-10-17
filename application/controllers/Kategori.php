<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index($url)
	{
		$this->load->view('headv2');
		$this->load->view('kategori',["url"=>$url]);
		$this->load->view('footv2');
	}
}