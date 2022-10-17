<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends CI_Controller {
	/*public function __construct(){
		parent::__construct();

		if($this->func->maintenis() == TRUE) {
			include(APPPATH.'views/maintenis.php');

			die();
		}
	}*/

	public function index(){
		if($this->func->cekLogin() == true){
			if($_SESSION["status"] == 1){
				$this->load->view("headv2",array("titel"=>"Akun Saya"));
				$this->load->view("admin/pengaturan");
				$this->load->view("footv2");
			}else{
				$this->load->view("headv2");
				$this->load->view("main/sukses_verifikasi",array("belumverif"=>true));
				$this->load->view("footv2");
			}
		}else{
			redirect("home/signin");
		}
	}

	// LACAK PESANAN
	public function lacakpaket($orderid=null){
		if($orderid != null){
			$this->load->view("headv2",array("titel"=>"Lacak Paket"));
			$this->load->view("admin/lacakpaket",array("orderid"=>$orderid,"transaksi"=>$this->func->getTransaksi($orderid,"semua","orderid")));
			$this->load->view("footv2");
		}else{
			redirect("404_notfound");
		}
	}

	// PRODUK
	public function produk(){
		if($this->func->cekLogin() == true){
			if($_SESSION["status"] == 1){
				$this->load->view("headv2",array("titel"=>"Daftar Produk"));
				$this->load->view("admin/daftarproduk");
				$this->load->view("footv2");
			}else{
				$this->load->view("headv2",array("titel"=>"Akun Belum Terverifikasi"));
				$this->load->view("main/sukses_verifikasi",array("belumverif"=>true));
				$this->load->view("footv2");
			}
		}else{
			redirect("home/signin");
		}
	}
	public function tambahproduk(){
		if($this->func->cekLogin() == true){
			if($_SESSION["status"] == 1){
				$this->load->view("headv2",array("titel"=>"Tambah Produk Baru"));
				$this->load->view("admin/tambahproduk");
				$this->load->view("footv2");
			}else{
				$this->load->view("headv2",array("titel"=>"Akun Belum Terverifikasi"));
				$this->load->view("main/sukses_verifikasi",array("belumverif"=>true));
				$this->load->view("footv2");
			}
		}else{
			redirect("home/signin");
		}
	}
	public function ubahproduk(){
		$idproduk = (isset($_GET["tokens"])) ? $_GET["tokens"] : "0-0";
		if($this->func->cekLogin() == true){
			if($_SESSION["status"] == 1){
				$idproduk = explode("-",$idproduk);
				$this->load->view("headv2",array("titel"=>"Ubah Produk"));
				$this->load->view("admin/ubahproduk",array("idproduk"=>$idproduk[1]));
				$this->load->view("footv2");
			}else{
				$this->load->view("headv2",array("titel"=>"Akun Belum Terverifikasi"));
				$this->load->view("main/sukses_verifikasi",array("belumverif"=>true));
				$this->load->view("footv2");
			}
		}else{
			redirect("home/signin");
		}
	}

	// TOKO
	public function pengaturantoko(){
		if($this->func->cekLogin() == true){
			if($_SESSION["status"] == 1){
				$this->load->view("headv2",array("titel"=>"Pengaturan Toko"));
				$this->load->view("admin/pengaturantoko");
				$this->load->view("footv2");
			}else{
				$this->load->view("headv2",array("titel"=>"Akun Belum Terverifikasi"));
				$this->load->view("main/sukses_verifikasi",array("belumverif"=>true));
				$this->load->view("footv2");
			}
		}else{
			redirect("home/signin");
		}
	}
	public function bukatoko(){
		if($this->func->cekLogin() == true){
			if($_SESSION["status"] == 1){
				$this->load->view("headv2",array("titel"=>"Mulai Berjualan di ".$this->func->globalset("nama")));
				$this->load->view("admin/tambahtoko");
				$this->load->view("footv2");
			}else{
				$this->load->view("headv2",array("titel"=>"Akun Belum Terverifikasi"));
				$this->load->view("main/sukses_verifikasi",array("belumverif"=>true));
				$this->load->view("footv2");
			}
		}else{
			redirect("home/signin");
		}
	}
	public function penjualan($konf="normal"){
		if($this->func->cekLogin() == true){
			if($_SESSION["status"] == 1){
				$push["konf"] = $konf;

				$this->load->view("headv2",array("titel"=>"Daftar Penjualan"));
				$this->load->view("admin/penjualan",$push);
				$this->load->view("footv2");
			}else{
				$this->load->view("headv2",array("titel"=>"Akun Belum Terverifikasi"));
				$this->load->view("main/sukses_verifikasi",array("belumverif"=>true));
				$this->load->view("footv2");
			}
		}else{
			redirect("home/signin");
		}
	}

	// UPDATE PROFIL TOKO

	public function uploadprofiltoko(){
		if($this->func->cekLogin() == true){
				$toko = $this->func->getToko("id",$_SESSION["idtoko"]);
				if($toko->foto != "default_toko.png" OR $toko->foto != "default_toko.jpg"){
					unlink("assets/img/toko/".$toko->foto);
				}

				$config['upload_path'] = './assets/img/toko/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['file_name'] = $_SESSION["usrid"].$_SESSION["idtoko"].date("YmdHis");

				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('foto')){
					$error = array('error' => $this->upload->display_errors());
					print_r($error);
					//redirect("404_notfound");
				}else{
					$upload_data = $this->upload->data();
					$this->load->library('image_lib');
					$config_resize['image_library'] = 'gd2';
					$config_resize['maintain_ratio'] = TRUE;
					$config_resize['master_dim'] = 'height';
					$config_resize['quality'] = "100%";
					$config_resize['source_image'] = $config['upload_path'].$uploadData["file_name"];
					$config_resize['width'] = 1024;
					$config_resize['height'] = 720;
					$this->image_lib->initialize($config_resize);
					$this->image_lib->resize();

					$filename = $upload_data['file_name'];
					$this->db->where("id",$_SESSION["idtoko"]);
					$this->db->update("toko",array("foto"=>$filename,"update"=>date("Y-m-d H:i:s")));

					redirect("manage/pengaturantoko");
				}
		}else{
			redirect("home/signin");
		}
	}

	// PEMBELI
	public function pesanan(){
		if($this->func->cekLogin() == true){
			if($_SESSION["status"] == 1){
				$this->load->view("headv2",array("titel"=>"Status Pesanan"));
				$this->load->view("admin/pesanan");
				$this->load->view("footv2");
			}else{
				$this->load->view("headv2",array("titel"=>"Akun Belum Terverifikasi"));
				$this->load->view("main/sukses_verifikasi",array("belumverif"=>true));
				$this->load->view("footv2");
			}
		}else{
			redirect("home/signin");
		}
	}
	public function detailpesanan(){
		if($this->func->cekLogin() == true){
			if($_SESSION["status"] == 1){
				if(isset($_GET["orderid"])){
					$transaksi = $this->func->getTransaksi($_GET["orderid"],"semua","orderid");
					$bayar = $this->func->getBayar($transaksi->idbayar,"semua");

					$this->load->view("headv2",array("titel"=>"Rincian Pesanan"));
					$this->load->view("admin/pesanandetail",array("transaksi"=>$transaksi,"bayar"=>$bayar));
					$this->load->view("footv2");
				}else{
					echo "
					<script>
						history.back();
					</script>
					";
				}
			}else{
				$this->load->view("headv2",array("titel"=>"Akun Belum Terverifikasi"));
				$this->load->view("main/sukses_verifikasi",array("belumverif"=>true));
				$this->load->view("footv2");
			}
		}else{
			redirect("home/signin");
		}
	}
	public function konfirmasi(){
		if($this->func->cekLogin() == true){
			if(isset($_POST["idbayar"])){
				$config['upload_path'] = './cdn/konfirmasi/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name'] = $_SESSION["usrid"].$_POST["idbayar"].date("YmdHis");

				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('bukti')){
					$error = $this->upload->display_errors();
					print_r($error);
					//redirect("404_notfound");
				}else{
					$upload_data = $this->upload->data();
					$this->load->library('image_lib');
					$config_resize['image_library'] = 'gd2';
					$config_resize['maintain_ratio'] = TRUE;
					$config_resize['master_dim'] = 'height';
					$config_resize['quality'] = "100%";
					$config_resize['source_image'] = $config['upload_path'].$upload_data["file_name"];
					$config_resize['width'] = 1024;
					$config_resize['height'] = 720;
					$this->image_lib->initialize($config_resize);
					$this->image_lib->resize();

					$filename = $upload_data['file_name'];
					$data = array(
						"tgl"		=> date("Y-m-d H:i:s"),
						"idbayar"	=> $_POST["idbayar"],
						"bukti"		=> $filename
					);
					$this->db->insert("konfirmasi",$data);

					redirect("manage/pesanan");
				}
			}else{
				if($_SESSION["status"] == 1){
					$push["idbayar"] = isset($_GET["sess"]) ? $_GET["sess"] : 0;

					$this->load->view("headv2",array("titel"=>"Konfirmasi Pembayaran"));
					$this->load->view("admin/konfirmasi",$push);
					$this->load->view("footv2");
				}else{
					$this->load->view("headv2",array("titel"=>"Akun Belum Terverifikasi"));
					$this->load->view("main/sukses_verifikasi",array("belumverif"=>true));
					$this->load->view("footv2");
				}
			}
		}else{
			redirect("home/signin");
		}
	}
	public function konfirmasipreorder(){
		if($this->func->cekLogin() == true){
			if(isset($_POST["idbayar"])){
				$config['upload_path'] = './cdn/konfirmasi/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png';
				$config['file_name'] = "pre_".$_SESSION["usrid"].$_POST["idbayar"].date("YmdHis");

				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('bukti')){
					$error = $this->upload->display_errors();
					print_r($error);
					//redirect("404_notfound");
				}else{
					$upload_data = $this->upload->data();
					$this->load->library('image_lib');
					$config_resize['image_library'] = 'gd2';
					$config_resize['maintain_ratio'] = TRUE;
					$config_resize['master_dim'] = 'height';
					$config_resize['quality'] = "100%";
					$config_resize['source_image'] = $config['upload_path'].$upload_data["file_name"];
					$config_resize['width'] = 1024;
					$config_resize['height'] = 720;
					$this->image_lib->initialize($config_resize);
					$this->image_lib->resize();

					$filename = $upload_data['file_name'];
					$data = array(
						"bukti"		=> $filename
					);
					$this->db->where("id",$_POST["idbayar"]);
					$this->db->update("preorder",$data);

					redirect("manage/pesanan");
				}
			}else{
				redirect("home/");
			}
		}else{
			redirect("home/signin");
		}
	}
	public function terimapesanan(){
		if($this->func->cekLogin() == true){
			if(isset($_POST["pengiriman"])){
				$this->db->where("id",$_POST["pengiriman"]);
				$this->db->update("pengiriman",array("status"=>2,"selesai"=>date("Y-m-d H:i:s")));

				$kirim = $this->func->getPengiriman($_POST["pengiriman"],"semua");
				$usertoko = $this->func->getUser($kirim->idtoko,"semua","idtoko");
				$saldoawal = $this->func->getSaldo($usertoko->id,"saldo","usrid");
				$total = 0;
				$this->db->where("pengiriman",$kirim->id);
				$db = $this->db->get("transaksi");
				foreach($db->result() as $res){ $total += $res->total; }
				$jumlah = $total + $kirim->ongkir;
				$saldoakhir = $jumlah + $saldoawal;
				$data = array(
					"tgl"		=> date("Y-m-d H:i:s"),
					"usrid"		=> $usertoko->id,
					"jenis"		=> 1,
					"jumlah"	=> $jumlah,
					"darike"	=> 1,
					"saldoawal"	=> $saldoawal,
					"saldoakhir"=> $saldoakhir,
					"sambung"	=> $kirim->id
				);
				$this->db->insert("saldohistory",$data);

				$this->db->where("usrid",$usertoko->id);
				$this->db->update("saldo",array("saldo"=>$saldoakhir,"apdet"=>date("Y-m-d H:i:s")));

				redirect("manage/pesanan");
			}else{
				redirect("manage/pesanan");
			}
		}else{
			redirect("home/signin");
		}
	}
	public function ulasan($orderid=0){
		if($this->func->cekLogin() == true){
			if($_SESSION["status"] == 1){
				$this->load->view("headv2",array("titel"=>"Ulasan Produk"));
				$this->load->view("admin/review",array("orderid"=>$orderid));
				$this->load->view("footv2");
			}else{
				$this->load->view("headv2",array("titel"=>"Akun Belum Terverifikasi"));
				$this->load->view("main/sukses_verifikasi",array("belumverif"=>true));
				$this->load->view("footv2");
			}
		}else{
			redirect("home/signin");
		}
	}
	public function saldo(){
		if($this->func->cekLogin() == true){
			if($_SESSION["status"] == 1){
				if(isset($_POST["idrek"]) AND isset($_POST["jumlah"])){
					$keterangan = (isset($_POST["keterangan"])) ? $_POST["keterangan"] : "";
					$saldoawal = $this->func->getSaldo($_SESSION["usrid"],"saldo","usrid");
					if($saldoawal >= intval($_POST["jumlah"])){
						$saldoakhir = $saldoawal - intval($_POST["jumlah"]);
						$data = array(
							"status"	=> 0,
							"usrid"		=> $_SESSION["usrid"],
							"idrek"		=> $_POST["idrek"],
							"total"		=> $_POST["jumlah"],
							"tgl"		=> date("Y-m-d H:i:s"),
							"keterangan"=> $keterangan
						);
						$this->db->insert("saldotarik",$data);
						$idtarik = $this->db->insert_id();

						$data = array(
							"tgl"		=> date("Y-m-d H:i:s"),
							"usrid"		=> $_SESSION["usrid"],
							"jenis"		=> 2,
							"jumlah"	=> $_POST["jumlah"],
							"darike"	=> 2,
							"saldoawal"	=> $saldoawal,
							"saldoakhir"=> $saldoakhir,
							"sambung"	=> $idtarik
						);
						$this->db->insert("saldohistory",$data);

						$this->db->where("usrid",$_SESSION["usrid"]);
						$this->db->update("saldo",array("saldo"=>$saldoakhir,"apdet"=>date("Y-m-d H:i:s")));

						echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
					}else{
						echo json_encode(array("success"=>false,"msg"=>"saldo tidak mencukupi","token"=> $this->security->get_csrf_hash()));
					}
				}else{
					//$this->load->view("head",array("titel"=>"Kotak Pesan"));
					//$this->load->view("admin/saldo");
					//$this->load->view("foot");
					echo json_encode(array("success"=>false,"msg"=>"formulir belum lengkap","token"=> $this->security->get_csrf_hash()));
				}
			}else{
				$this->load->view("headv2",array("titel"=>"Akun Belum Terverifikasi"));
				$this->load->view("main/sukses_verifikasi",array("belumverif"=>true));
				$this->load->view("footv2");
			}
		}else{
			redirect("home/signin");
		}
	}
}
