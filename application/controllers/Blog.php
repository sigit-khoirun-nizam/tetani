<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {
	public function __construct(){
		parent::__construct();

		/*\Midtrans\Config::$serverKey = $this->func->getSetting("midtrans_server");
		\Midtrans\Config::$isProduction = false;
		\Midtrans\Config::$isSanitized = true;
		\Midtrans\Config::$is3ds = true;

		if($this->func->maintenis() == TRUE) {
			include(APPPATH.'views/maintenis.php');

			die();
		}*/
    }

    function index(){
        $this->load->view("headv2");
        $this->load->view("main/blog");
        $this->load->view("footv2");
    }

    function single($url=null){
        $this->load->view("headv2");
        $this->load->view("main/blog-single",["url"=>$url]);
        $this->load->view("footv2");
    }
}