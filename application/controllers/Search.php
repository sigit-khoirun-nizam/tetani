<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index(){
		if(isset($_SESSION["securesearch"]) AND isset($_GET['token'])){
			$data = $this->func->arrEnc($_GET['token'],"decode");
			$data['query'] = (isset($data['query'])) ? $data['query'] : "";
			$page = (isset($_GET["page"]) AND $_GET["page"] != "" AND intval($_GET["page"]) > 0) ? intval($_GET["page"]) : 1;
			$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "produk.id";
			$perpage = 20;

			if(isset($data['category']) AND $data['category'] != ""){ $this->db->where("idcat",$data['category']); }
			if(isset($data['query'])){
				$where = "(produk.nama LIKE '%".$data['query']."%' OR produk.deskripsi LIKE '%".$data['query']."%' OR produk.harga LIKE '%".$data['query']."%')";
				$this->db->where($where);
			}
			if(isset($data['addedquery'])){
				for($i=0; $i<count($data['addedquery']); $i++){
					$where = "(produk.nama LIKE '%".$data['addedquery'][$i]."%' OR produk.deskripsi LIKE '%".$data['addedquery'][$i]."%' OR produk.harga LIKE '%".$data['addedquery'][$i]."%')";
					if(isset($data['query'])){
						$this->db->or_where($where);
					}else{
						$this->db->where($where);
					}
				}
			}
			$rows = $this->db->get("produk");
			$rows = $rows->num_rows();

			$this->db->select(
				'*,
				produk.deskripsi as deskripsi,
				toko.nama as namatoko,
				toko.id as idtoko,
				produk.id as id,
				produk.url as url,
				produk.nama as nama'
			);
			$this->db->from('produk');
			$this->db->join('toko', 'toko.id = produk.idtoko');
			if(isset($data['category']) AND $data['category'] != ""){ $this->db->where("idcat",$data['category']); }
			if(isset($data['query'])){
				$where = "(produk.nama LIKE '%".$data['query']."%' OR produk.deskripsi LIKE '%".$data['query']."%' OR produk.harga LIKE '%".$data['query']."%')";
				$this->db->where($where);
			}
			if(isset($data['addedquery'])){
				for($i=0; $i<count($data['addedquery']); $i++){
					$where = "(produk.nama LIKE '%".$data['addedquery'][$i]."%' OR produk.deskripsi LIKE '%".$data['addedquery'][$i]."%' OR produk.harga LIKE '%".$data['addedquery'][$i]."%')";
					if(isset($data['query'])){
						$this->db->where($where);
					}else{
						$this->db->where($where);
					}
				}
			}

			$this->db->order_by($orderby,"asc");
			$this->db->limit($perpage,($page-1)*$perpage);
			$injek["produk"] = $this->db->get();
			$injek["rows"] = $rows;
			$injek["perpage"] = $perpage;
			$injek["page"] = $page;
			$injek["query"] = $data["query"];
			$injek["addedquery"] = (isset($data['addedquery'])) ? $data['addedquery'] : array();

			/*
			print_r($injek["produk"]);
			*/

			$this->load->view("headv2",array("titel"=>"Hasil pencarian untuk '".$data["query"]."'"));
			$this->load->view("main/search",$injek);
			$this->load->view("footv2");
		}else{
			redirect("404_notfound");
		}
	}
	public function secure(){
		if(isset($_POST)){
			$this->session->set_userdata("securesearch",true);

			echo json_encode(array("success"=>true,"token"=>$this->func->arrEnc($_POST,"encode")));
		}else{
			redirect("404_notfound");
		}
	}
}
