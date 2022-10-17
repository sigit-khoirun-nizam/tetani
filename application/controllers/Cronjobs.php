<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjobs extends CI_Controller {

	public function __construct() 
	{
		parent::__construct();
	}

	public function index(){
		redirect("home/_404");
	}

	public function update_notif(){
		$this->db->where("status",0);
		$this->db->limit(10);
		$this->db->order_by("id ASC");
		$db = $this->db->get("notifikasi");

		$result = array();
		foreach($db->result() as $r){
			if($r->jenis == 1){
				$hasil = $this->func->sendEmailOK($r->tujuan,$r->judul,$r->pesan,$r->subyek,$r->pengirim);
				$result[] = array("id"=>$r->id,"sukses"=>$hasil);
				if($hasil == true){
					$this->db->where("id",$r->id);
					$this->db->update("notifikasi",["proses"=>date("Y-m-d H:i:s"),"status"=>1]);
				}else{
					$this->db->where("id",$r->id);
					$this->db->update("notifikasi",["proses"=>date("Y-m-d H:i:s"),"status"=>2]);
				}
			}elseif($r->jenis == 2){
				$hasil = $this->func->sendWAOK($r->tujuan,$r->pesan);
				$result[] = array("id"=>$r->id,"sukses"=>$hasil);
				if($hasil == true){
					$this->db->where("id",$r->id);
					$this->db->update("notifikasi",["proses"=>date("Y-m-d H:i:s"),"status"=>1]);
				}else{
					$this->db->where("id",$r->id);
					$this->db->update("notifikasi",["proses"=>date("Y-m-d H:i:s"),"status"=>2]);
				}
			}
		}

		echo json_encode($result);
	}
}