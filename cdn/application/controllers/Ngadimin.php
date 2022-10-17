<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ngadimin extends CI_Controller {

	public function index(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}else{
			$this->load->view('admin/head',["menu"=>1]);
			$this->load->view('admin/home');
			$this->load->view('admin/foot');
		}
	}
	
	public function off404(){
		$this->load->view('404notfound');
	}
	
	/*
	public function keytes(){
		echo $this->func->decode("da9a630587e6a7805e9a31481ef4d8bd138204792e5601b8027bdc600a3adb5ea2a4ebc03abd37127ad3793d12c383526f094ed246aa97dcbc70974e8ea97631W1A+93/KKav7n+Ft03koP3J0f9PTnpD74ohYZaA+WiM=");
	}
	public function updatenow(){
		$this->db->update("produk",["stok"=>100]);
	}
	*/
	
	/* AGEN */
	public function agen(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			$res = $this->load->view('admin/agenlist',"",true);
			echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
		}else{
			$this->load->view('admin/head',["menu"=>9]);
			$this->load->view('admin/agen');
			$this->load->view('admin/foot');
		}
	}
	
	/* PRE ORDER */
	public function preorder(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			$res = $this->load->view('admin/preorderlist',"",true);
			echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
		}else{
			$this->load->view('admin/head',["menu"=>13]);
			$this->load->view('admin/preorder');
			$this->load->view('admin/foot');
		}
	}
	
	/* Halaman */
	public function halaman(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			$res = $this->load->view('admin/halamanlist',"",true);
			echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
		}else{
			$this->load->view('admin/head',["menu"=>11,"tiny"=>true]);
			$this->load->view('admin/halaman');
			$this->load->view('admin/foot');
		}
	}
	
	/* LAPORAN */
	public function laporantransaksi(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			$res = $this->load->view('admin/laporantransaksilist',"",true);
			echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
		}else{
			$this->load->view('admin/head',["menu"=>14]);
			$this->load->view('admin/laporantransaksi');
			$this->load->view('admin/foot');
		}
	}
	public function laporanuser(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			$res = $this->load->view('admin/laporanuserlist',"",true);
			echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
		}else{
			$this->load->view('admin/head',["menu"=>15]);
			$this->load->view('admin/laporanuser');
			$this->load->view('admin/foot');
		}
	}

	/* BLOG POST */
	public function blog(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			$res = $this->load->view('admin/bloglist',"",true);
			echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
		}else{
			$this->load->view('admin/head',["menu"=>17,"tiny"=>true]);
			$this->load->view('admin/blog');
			$this->load->view('admin/foot');
		}
	}
	public function editblog($id=0){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["nama"])){
			$foto  = isset($_SESSION["fotoPage"]) ? $_SESSION["fotoPage"] : "no-image.png";
			$string = $this->clean($_POST["nama"]);
			$this->db->where("url",$string);
			$dbs = $this->db->get("blog");
			if($dbs->num_rows() > 0){
				$string = $string."-".date("His");
			}
			
			$data = [
				"tgl"	=> date("Y-m-d H:i:s"),
				"judul"	=> $_POST["nama"],
				"img"	=> $foto,
				"konten"=> $_POST["konten"],
				"url"	=> $string
			];
			if($id > 0){
				unset($data["url"]);
				unset($data["img"]);
				$this->db->where("id",$id);
				$this->db->update("blog",$data);
				
				//print_r($thumb);
				redirect("ngadimin/blog");
			}else{
				$this->db->insert("blog",$data);
				$insertid = $this->db->insert_id();
				
				redirect("ngadimin/blog");
			}
		}else{
			$this->load->view("admin/head",["menu"=>17,"tiny"=>true]);
			$this->load->view("admin/blogform",["id"=>$id]);
			$this->load->view("admin/foot");
		}
	}
	
	/* USER MANAGER */
	public function usermanager(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			//$this->load->view('admin/pesanmasuk');
		}else{
			$this->load->view('admin/head',["menu"=>10]);
			$this->load->view('admin/usermanager');
			$this->load->view('admin/foot');
		}
	}
	
	/* PENGATURAN */
	public function pengaturan(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}

		if(!isset($_SESSION["level"]) OR (isset($_SESSION["level"]) AND $_SESSION['level'] != 2)){
			show_404();
			exit;
		}
		
		if(isset($_GET["load"])){
			//$this->load->view('admin/pesanmasuk');
		}else{
			$this->load->view('admin/head',["menu"=>12]);
			$this->load->view('admin/pengaturan');
			$this->load->view('admin/foot');
		}
	}
	
	/* SLIDER */
	public function slider(){
		if(!isset($_SESSION["isMasok"]) OR $_SESSION["isMasok"] != true){
			$this->logout();
			exit;
		}
		$this->load->view('admin/head',array("menu"=>5));
		$this->load->view('admin/slider');
		$this->load->view('admin/foot');
	}
	public function sliderform($id=0){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(!isset($_POST["tgl"])){
			$this->load->view('admin/head',array("menu"=>5));
			$this->load->view('admin/sliderform',["id"=>$id]);
			$this->load->view('admin/foot');
		}else{
			if($_POST["id"] == 0){
				$config['upload_path'] = './promo/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name'] = "promo_upl".date("YmdHis");
	
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('gambar')){
					$error = array('error' => $this->upload->display_errors());
					print_r($error);
				}else{
					$uploadData = $this->upload->data();
					/*$this->load->library('image_lib');
					/*$config_resize['image_library'] = 'gd2';
					$config_resize['maintain_ratio'] = TRUE;
					$config_resize['master_dim'] = 'height';
					$config_resize['quality'] = "100%";
					$config_resize['source_image'] = $config['upload_path'].$uploadData["file_name"];
	
					$config_resize['width'] = 1440;
					$config_resize['height'] = 1440;
					$this->image_lib->initialize($config_resize);
					$this->image_lib->resize();*/

					$data = [
						"usrid"=> $_SESSION["usrid"],
						"caption"=> $_POST["caption"],
						"jenis"=> $_POST["jenis"],
						"link"=> $_POST["link"],
						//"linktext"=> $_POST["linktext"],
						"status"=> $_POST["status"],
						"tgl"=> $_POST["tgl"],
						"tgl_selesai"=> $_POST["tgl_selesai"],
						"gambar"=> $uploadData["file_name"]
					];
					$this->db->insert("promo",$data);

					redirect("ngadimin/slider");
				}
			}else{
				$data = [
					"usrid"=> $_SESSION["usrid"],
					"caption"=> $_POST["caption"],
					"jenis"=> $_POST["jenis"],
					"link"=> $_POST["link"],
					//"linktext"=> $_POST["linktext"],
					"status"=> $_POST["status"],
					"tgl"=> $_POST["tgl"],
					"tgl_selesai"=> $_POST["tgl_selesai"]
				];
				$this->db->where("id",$_POST["id"]);
				$this->db->update("promo",$data);

				redirect("ngadimin/slider");
			}
		}
	}
	function hapusslider(){
		if(!isset($_SESSION["isMasok"]) OR $_SESSION["isMasok"] != true){
			$this->logout();
			exit;
		}

		if(isset($_POST["pro"])){
			$_POST["pro"] = $this->func->clean($_POST["pro"]);
			$this->db->where("id",$_POST["pro"]);
			$dbs = $this->db->get("promo");
			foreach($dbs->result() as $R){
				if(file_exists("promo/".$R->gambar)){
					unlink("promo/".$R->gambar);
				}
			}
			$this->db->where("id",$_POST["pro"]);
			$this->db->delete("promo");
			
			echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"msg"=>"promo tidak ditemukan","token"=> $this->security->get_csrf_hash()));
		}
	}
	
	/* VOUCHER */
	public function voucher(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			//$this->load->view('admin/pesanmasuk');
		}else{
			$this->load->view('admin/head',["menu"=>3]);
			$this->load->view('admin/voucher');
			$this->load->view('admin/foot');
		}
	}

	/* TESTISMONEY */
	public function testimoni(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		$this->load->view('admin/head',["menu"=>18]);
		$this->load->view('admin/testimoni');
		$this->load->view('admin/foot');
	}

	/* BOOSTER */
	public function booster(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		$this->load->view('admin/head',["menu"=>16]);
		$this->load->view('admin/booster');
		$this->load->view('admin/foot');
	}
	
	/* PESAN */
	public function pesan(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			$res = $this->load->view('admin/pesanmasuk',"",true);
			echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
		}else{
			$this->load->view('admin/head',["menu"=>4]);
			$this->load->view('admin/pesan');
			$this->load->view('admin/foot');
		}
	}
	
	/* PESANAN */
	public function pesanan(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		$this->load->view('admin/head',["menu"=>2]);
		$this->load->view('admin/pesanan');
		$this->load->view('admin/foot');
	}
	
	/* PRODUK */
	public function produk(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET['load']) AND $_GET['load'] == "true"){
			$res = $this->load->view('admin/produklist',"",true);
			echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
		}else{
			$this->load->view('admin/head',["menu"=>6]);
			$this->load->view('admin/produk');
			$this->load->view('admin/foot');
		}
	}
	public function produkform($id=0){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["nama"])){
			$_POST["id"] = $this->func->clean($_POST["id"]);
			$data = [
				"tgl"	=> date("Y-m-d H:i:s"),
				"nama"	=> $_POST["nama"],
				"deskripsi"=> $_POST["deskripsi"],
			];
			if($_POST["id"] > 0){
				$this->db->where("id",$_POST["id"]);
				$this->db->update("produk",$data);
				
				//print_r($thumb);
				redirect("ngadimin/produk");
			}else{
				$this->db->insert("produk",$data);
				$insertid = $this->db->insert_id();
				
				redirect("ngadimin/produk");
			}
		}else{
			$this->load->view("admin/head",["menu"=>6,"tiny"=>true]);
			$this->load->view("admin/produkform",["id"=>$id]);
			$this->load->view("admin/foot");
		}
	}
	
	// KATEGORI
	public function kategori(){
		if(!isset($_SESSION["isMasok"]) OR $_SESSION["isMasok"] != true){
			$this->logout();
			exit;
		}
		$this->load->view('admin/head',array("menu"=>7));
		$this->load->view('admin/kategori');
		$this->load->view('admin/foot');
	}
	public function kategoriform($id=0){
		if(!isset($_SESSION["isMasok"]) OR $_SESSION["isMasok"] != true){
			$this->logout();
			exit;
		}

		if(!isset($_POST["nama"])){
			$this->load->view('admin/head',array("menu"=>7));
			$this->load->view('admin/kategoriform',["id"=>$id]);
			$this->load->view('admin/foot');
		}else{
			if(isset($_FILES['icon']) AND $_FILES['icon']['size'] != 0 AND $_FILES['icon']['error'] == 0){
				$config['upload_path'] = './kategori/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name'] = "cat_".date("YmdHis");
	
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('icon')){
					$error = array('error' => $this->upload->display_errors());
					print_r($error);
					exit;
				}else{
					$uploadData = $this->upload->data();
					/*
					$this->load->library('image_lib');
					$config_resize['image_library'] = 'gd2';
					$config_resize['maintain_ratio'] = TRUE;
					$config_resize['master_dim'] = 'height';
					$config_resize['quality'] = "100%";
					$config_resize['source_image'] = $config['upload_path'].$uploadData["file_name"];
	
					$config_resize['width'] = 1024;
					$config_resize['height'] = 1024;
					$this->image_lib->initialize($config_resize);
					$this->image_lib->resize();
					*/
					
					$data["icon"] = $uploadData["file_name"];
				}
			}

			$data["nama"] = $_POST["nama"];
			$data["url"] = $this->clean($_POST["nama"]);
			
			if($_POST["id"] == 0){
				$this->db->insert("kategori",$data);
			}else{
				$this->db->where("id",$_POST["id"]);
				$this->db->update("kategori",$data);
			}
			
			redirect("ngadimin/kategori");
		}
	}
	function hapuskategori(){
		if(!isset($_SESSION["isMasok"]) OR $_SESSION["isMasok"] != true){
			$this->logout();
			exit;
		}

		if(isset($_POST["pro"])){
			$_POST["pro"] = $this->func->clean($_POST["pro"]);
			$this->db->where("id",$_POST["pro"]);
			$dbs = $this->db->get("kategori");
			foreach($dbs->result() as $R){
				unlink("kategori/".$R->icon);
			}
			$this->db->where("id",$_POST["pro"]);
			$this->db->delete("kategori");
			
			echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"msg"=>"produk tidak ditemukan","token"=> $this->security->get_csrf_hash()));
		}
	}
	
	/* VARIASI PRODUK*/
	public function variasi(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET['load']) AND $_GET['load'] == "warna"){
			$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
			$perpage = (isset($_GET["perpage"]) AND $_GET["perpage"] != "") ? $_GET["perpage"] : 10;
			$cari = (isset($_GET["cari"]) AND $_GET["cari"] != "") ? $_GET["cari"] : "";
			
			$where = "nama LIKE '%$cari%'";
			$this->db->where($where);
			$row = $this->db->get("variasiwarna");
			
			$this->db->where($where);
			$this->db->limit($perpage,($page-1)*$perpage);
			$this->db->order_by("id","DESC");
			$db = $this->db->get("variasiwarna");
			
			echo "
				<table class='table'>
					<tr>
						<th>No</th>
						<th>Nama</th>
						<th>Aksi</th>
					</tr>
			";
			if($row->num_rows() == 0){
				echo "
						<tr>
							<th class='text-center text-danger' colspan=4>Belum ada variasi.</th>
						</tr>
				";
			}
			$no = 1 + (($page-1)*$perpage);
			foreach($db->result() as $r){
				echo "
					<tr>
						<td>$no</td>
						<td>".strtoupper($r->nama)."</td>
						<td>
							<a href='javascript:void(0)' onclick='editWarna(".$r->id.",\"".$r->nama."\")' class='btn btn-primary btn-xs'><i class='fas fa-pencil-alt'></i></a>
							<a href='javascript:void(0)' onclick='hapusWarna(".$r->id.")' class='btn btn-danger btn-xs'><i class='fas fa-trash'></i></a>
						</td>
					</tr>
				";
				$no++;
			}
			echo "
				</table>
			";
			echo $this->func->createPagination($row->num_rows(),$page,$perpage,"refreshWarna");
			
		}elseif(isset($_GET['load']) AND $_GET['load'] == "size"){
			$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
			$perpage = (isset($_GET["perpage"]) AND $_GET["perpage"] != "") ? $_GET["perpage"] : 10;
			$cari = (isset($_GET["cari"]) AND $_GET["cari"] != "") ? $_GET["cari"] : "";
			
			$where = "nama LIKE '%$cari%'";
			$this->db->where($where);
			$row = $this->db->get("variasisize");
			
			$this->db->where($where);
			$this->db->limit($perpage,($page-1)*$perpage);
			$this->db->order_by("id","DESC");
			$db = $this->db->get("variasisize");
			
			echo "
				<table class='table'>
					<tr>
						<th>No</th>
						<th>Nama</th>
						<th>Aksi</th>
					</tr>
			";
			if($row->num_rows() == 0){
				echo "
						<tr>
							<th class='text-center text-danger' colspan=4>Belum ada variasi.</th>
						</tr>
				";
			}
			$no = 1 + (($page-1)*$perpage);
			foreach($db->result() as $r){
				echo "
					<tr>
						<td>$no</td>
						<td>".strtoupper($r->nama)."</td>
						<td>
							<a href='javascript:void(0)' onclick='editSize(".$r->id.",\"".$r->nama."\")' class='btn btn-primary btn-xs'><i class='fas fa-pencil-alt'></i></a>
							<a href='javascript:void(0)' onclick='hapusSize(".$r->id.")' class='btn btn-danger btn-xs'><i class='fas fa-trash'></i></a>
						</td>
					</tr>
				";
				$no++;
			}
			echo "
				</table>
			";
			echo $this->func->createPagination($row->num_rows(),$page,$perpage,"refreshSize");
		}else{
			$this->load->view('admin/head',["menu"=>8]);
			$this->load->view('admin/variasi');
			$this->load->view('admin/foot');
		}
	}
	public function tambahvariasi($id=0){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		$_POST["id"] = isset($_POST["id"]) ? $this->clean($_POST["id"]) : 0;
		if(isset($_POST["jenis"]) AND $_POST["jenis"] == "warna"){
			$data = [
				"tgl"	=> date("Y-m-d H:i:s"),
				"nama"	=> $_POST["nama"],
				"usrid"	=> $_SESSION["usrid"]
			];
			
			if($_POST["id"] > 0){
				$this->db->where("id",$_POST["id"]);
				$this->db->update("variasiwarna",$data);
			}else{
				$this->db->insert("variasiwarna",$data);
				$insertid = $this->db->insert_id();
			}
			echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
		}elseif(isset($_POST["jenis"]) AND $_POST["jenis"] == "size"){
			$data = [
				"tgl"	=> date("Y-m-d H:i:s"),
				"nama"	=> $_POST["nama"],
				"usrid"	=> $_SESSION["usrid"]
			];
			
			if($_POST["id"] > 0){
				$this->db->where("id",$_POST["id"]);
				$this->db->update("variasisize",$data);
			}else{
				$this->db->insert("variasisize",$data);
				$insertid = $this->db->insert_id();
			}
				
			echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
		}else{
			echo json_encode(["success"=>false,"token"=> $this->security->get_csrf_hash()]);
		}
	}
	public function hapusvariasi(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["theid"])){
			$_POST["theid"] = $this->clean($_POST["theid"]);
			$this->db->where("id",$_POST["theid"]);
			if($this->db->delete("produkvariasi")){
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}else{
				echo json_encode(["success"=>false,"token"=> $this->security->get_csrf_hash()]);
			}
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	public function hapuswarna(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["theid"])){
			$_POST["theid"] = $this->clean($_POST["theid"]);
			$this->db->where("id",$_POST["theid"]);
			if($this->db->delete("variasiwarna")){
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}else{
				echo json_encode(["success"=>false,"token"=> $this->security->get_csrf_hash()]);
			}
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	public function hapussize(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["theid"])){
			$_POST["theid"] = $this->clean($_POST["theid"]);
			$this->db->where("id",$_POST["theid"]);
			if($this->db->delete("variasisize")){
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}else{
				echo json_encode(["success"=>false,"token"=> $this->security->get_csrf_hash()]);
			}
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	
	/* USABLE FUNCTION */
	private function clean($string) {
		$string = str_replace(' ', '-', $string);
		$string = preg_replace('/[^A-Za-z0-9\-]/', '-', $string);

		return preg_replace('/-+/', '-', $string);
	}
	
	public function login(){
		if(isset($_POST["username"])){
			redirect("404_nf");
		}else{
			$this->load->view("admin/login");
		}
	}
	public function auth(){
		if(isset($_POST["username"])){
			//$this->session->sess_destroy();

			$this->db->where("username",$_POST["username"]);
			$db = $this->db->get("admin");
			
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					if($_POST["pass"] == $this->func->decode($r->password)){
						$this->session->set_userdata("isMasok",true);
						$this->session->set_userdata("usrid",$r->id);
						$this->session->set_userdata("level",$r->level);
						
						echo json_encode(array("success"=>true,"name"=>$_POST["username"],"token"=> $this->security->get_csrf_hash()));
					}else{
						echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
					}
				}
			}else{
				echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false));
		}
	}
	public function logout(){
		$this->session->sess_destroy();
		redirect("ngadimin/login");
	}
}
