<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Shop extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('headv2');
		$this->load->view('shop');
		$this->load->view('footv2');
	}

	public function preorder()
	{
		$this->load->view('headv2');
		$this->load->view('preorder');
		$this->load->view('footv2');
	}
}
