<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {
	/*public function __construct(){
		parent::__construct();

		if($this->func->maintenis() == TRUE) {
			include(APPPATH.'views/maintenis.php');

			die();
		}
	}*/

	public function index($url=null){
		if(isset($url) AND $url != null){
			$this->session->set_userdata("url",site_url('produk/'.$url));
			//$db = $this->func->getProduk($url,"semua","url");
			$this->db->where("url",$url);
			$this->db->limit(1);
			$res = $this->db->get("produk");
			$db = array();
			if($res->num_rows() > 0){
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$db = $result[0];
			}else{
				redirect("404_notfound");
				exit;
			}
			//REVIEWPRODUK
			$this->db->where("idproduk",$db->id);
			$review = $this->db->get("review");

			$this->load->view("headv2",array("titel"=>"Jual ".$db->nama,"desc"=>strip_tags($db->deskripsi),"img"=>$this->func->getFoto($db->id,"utama"),"url"=>site_url("produk/".$db->url)));
			$this->load->view("main/produks",array("data"=>$db,"review"=>$review));
			$this->load->view("footv2");
		}else{
			redirect("404_index");
		}
	}
}
