<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Mobileapi extends CI_Controller {

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

	public function __construct(){
		parent::__construct();

		$set = $this->func->getSetting("semua");
		$production = (strpos($set->midtrans_snap,"sandbox") == true) ? false : true;
		\Midtrans\Config::$serverKey = $set->midtrans_server;
		\Midtrans\Config::$isProduction = $production;
		\Midtrans\Config::$isSanitized = true;
		\Midtrans\Config::$is3ds = true;

		/*if($this->func->maintenis() == TRUE) {
			include(APPPATH.'views/maintenis.php');

			die();
		}*/
	}

	public function index(){
		//$this->load->view('welcome_message');
	}
	
	public function getsessiontoken(){		
		$token = md5(date("YmdHis"));		
		$this->db->insert("token",array("token"=>$token,"tgl"=>date("Y-m-d H:i:s")));
		
		echo json_encode(array("success"=>true,"token"=>$token,"usrid"=>0));
	}
	
	// CHAT
	public function chatnotif(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
				}
				
				$this->db->where("tujuan",$r->usrid);
				$this->db->where("baca",0);
				$db = $this->db->get("pesan");
				
				echo json_encode(array("success"=>true,"notif"=>$db->num_rows(),"id"=>$r->usrid));
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function chat(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
				
				$this->db->where("(tujuan = '".$r->usrid."' OR dari = '".$r->usrid."') AND baca = '0'");
				$this->db->update("pesan",["baca"=>1]);
				
				$this->db->where("tujuan",$r->usrid);
				$this->db->or_where("dari",$r->usrid);
				$this->db->limit(50,($page-1)*50);
				$db = $this->db->get("pesan");
				
				if($db->num_rows() > 0){
					$currdate = false;
					foreach($db->result() as $r){
						$letak = ($r->tujuan == 0) ? "kanan" : "kiri";
						
						if($this->func->ubahTgl("d-m-Y",$r->tgl) != $currdate){
							$data[] = array(
								"pesan"	=> $this->func->ubahTgl("d M Y",$r->tgl),
								"letak"	=> "tengah",
								"waktu"	=> $this->func->ubahTgl("H:i",$r->tgl),
								"baca"	=> $r->baca
							);
							$currdate = $this->func->ubahTgl("d-m-Y",$r->tgl);
						}
						
						$data[] = array(
							"pesan"	=> $r->isipesan,
							"letak"	=> $letak,
							"waktu"	=> $this->func->ubahTgl("H:i",$r->tgl),
							"baca"	=> $r->baca
						);
					}
					echo json_encode(array("success"=>true,"result"=>$data));
				}else{
					echo json_encode(array("success"=>false,"sesihabis"=>false));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function kirimpesan(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$inputJSON = file_get_contents('php://input');
			$input = json_decode($inputJSON, TRUE);
			
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
					$usr = $this->func->getUser($r->usrid,"semua");*/
					
					$isi = array(
						"tujuan"=> 0,
						"dari"	=> $r->usrid,
						"isipesan"	=> $input['pesan'],
						"tgl"	=> date("Y-m-d H:i:s"),
						"baca"	=> 0
					);
					
					$this->db->insert("pesan",$isi);
					echo json_encode(array("success"=>true,"result"=>$isi));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>false));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	
	public function slider(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
				}
				
				$this->db->where("tgl <= '".date("Y-m-d H:i:s")."' AND tgl_selesai >= '".date("Y-m-d H:i:s")."' AND jenis = '1'");
				$this->db->order_by("id","DESC");
				$db = $this->db->get("promo");
				if($db->num_rows() > 0){
					foreach($db->result() as $r){
						$data[] = array(
							"foto"	=> base_url("cdn/promo/".$r->gambar),
							"link"	=> $r->link
						);
					}
					echo json_encode(array("success"=>true,"result"=>$data));
				}else{
					echo json_encode(array("success"=>true,"sesihabis"=>false,"result"=>[]));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	
	public function kategori(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				/*foreach($db->result() as $r){
					$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
				}*/
				
				$this->db->where("parent",0);
				$this->db->order_by("id","DESC");
				$db = $this->db->get("kategori");
				if($db->num_rows() > 0){
					foreach($db->result() as $r){
						$data[] = array(
							"foto"	=> base_url("cdn/kategori/".$r->icon),
							"url"	=> $r->url,
							"nama"	=> $r->nama,
							"id"	=> $r->id
						);
					}
					echo json_encode(array("success"=>true,"result"=>$data));
				}else{
					echo json_encode(array("success"=>false,"sesihabis"=>false));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	
	// LACAK KIRIMAN
	public function lacakiriman(){
		if(isset($_GET["trx"])){
			$trx = $this->func->getTransaksi($_GET["trx"],"semua","idbayar");

			$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/waybill",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "waybill=".$trx->resi."&courier=".$trx->kurir,
			CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded",
				"key: 1cb6ca038ddb281f174dbc4264474df0"
			),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				echo "<span class='cl1'>terjadi kendala saat menghubungi pihak ekspedisi, cobalah beberapa saat lagi</span>";
			}else{
				$response = json_decode($response);
				//print_r();
				if($response->rajaongkir->status->code == "200"){
					$respon = $response->rajaongkir->result->manifest;
					if($response->rajaongkir->result->delivered == true){
						$paket = array(
							"penerima" 	=> strtoupper(strtolower($response->rajaongkir->result->delivery_status->pod_receiver)),
							"tgl"		=> $this->func->ubahTgl("d M Y H:i",$response->rajaongkir->result->delivery_status->pod_date." ".$response->rajaongkir->result->delivery_status->pod_time),
							"status"	=> 2,
							"resi"		=> $trx->resi
						);
					}else{
						$paket = array(
							"penerima" 	=> "",
							"tgl"		=> $this->func->ubahTgl("d M Y H:i",date("Y-m-d H:i:s")),
							"status"	=> 1,
							"resi"		=> $trx->resi
						);
					}
					if($response->rajaongkir->result->delivered == true AND $response->rajaongkir->query->courier != "jne"){
						$proses[] = array(
							"tgl" 	=> $this->func->ubahTgl("d/m/Y H:i",$response->rajaongkir->result->delivery_status->pod_date." ".$response->rajaongkir->result->delivery_status->pod_time),
							"desc"	=> "Diterima oleh ".strtoupper(strtolower($response->rajaongkir->result->delivery_status->pod_receiver)),
							"status"=> 2
						);
					}

					for($i=0; $i<count($respon); $i++){
						//print_r($respon[$i])."<p/>";
						$proses[] = array(
							"tgl" 	=> $this->func->ubahTgl("d/m/Y H:i",$respon[$i]->manifest_date." ".$respon[$i]->manifest_time),
							"desc"	=> $respon[$i]->manifest_description,
							"city"	=> $respon[$i]->city_name,
							"status"=> 1
						);
					}
					
					$paket["success"] = true;
					$paket["proses"] = $proses;
					echo json_encode($paket);
				}else{
					echo json_encode(
						array(
						"success"	=> false,
						"tgl"		=> $this->func->ubahTgl("d M Y H:i",date("Y-m-d H:i:s")),
						"msg"		=> "Nomor Resi tidak ditemukan, coba ulangi beberapa jam lagi sampai resi sudah update di sistem pihak ekspedisi"
						)
					);
				}
			}
		}else{
			echo json_encode(array("success"=>false,"tgl"=>$this->func->ubahTgl("d M Y H:i",date("Y-m-d H:i:s")),"msg"=>"terjadi kesalahan sistem, silahkan ualngi beberapa saat lagi"));
		}
	}
	
	// KERANJANG
	public function hapuskeranjang(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$inputJSON = file_get_contents('php://input');
			$input = json_decode($inputJSON, TRUE);
			
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
					$usr = $this->func->getUser($r->usrid,"semua");*/
				}
				
				$this->db->where("id",$input['pid']);
				$this->db->delete("transaksiproduk");
				
				echo json_encode(array("success"=>true));
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function tambahkeranjang(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$inputJSON = file_get_contents('php://input');
			$input = json_decode($inputJSON, TRUE);
			
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));*/
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				
				if(isset($input)){		
					$prod = $this->func->getProduk($input["idproduk"],"semua");
					$level = isset($usr->level) ? $usr->level : 0;
					if($level == 5){
						$harga = $prod->hargadistri;
					}elseif($level == 4){
						$harga = $prod->hargaagensp;
					}elseif($level == 3){
						$harga = $prod->hargaagen;
					}elseif($level == 2){
						$harga = $prod->hargareseller;
					}else{
						$harga = $prod->harga;
					}

					$keterangan = (isset($input["keterangan"])) ? $input["keterangan"] : "";
					$variasi = (isset($input["variasi"])) ? $input["variasi"] : 0;
					if($variasi != 0){
						$var = $this->func->getVariasi($variasi,"semua");
						if($level == 5){
							$harga = $var->hargadistri;
						}elseif($level == 4){
							$harga = $var->hargaagensp;
						}elseif($level == 3){
							$harga = $var->hargaagen;
						}elseif($level == 2){
							$harga = $var->hargareseller;
						}else{
							$harga = $var->harga;
						}
					}
					$stok = ($variasi != 0) ? $var->stok : $prod->stok;
					if($stok >= $input["jumlah"]){
						$data = array(
							"usrid"		=> $r->usrid,
							"idproduk"	=> $input["idproduk"],
							"tgl"		=> date("Y-m-d H:i:s"),
							"jumlah"	=> $input["jumlah"],
							"harga"		=> $harga,
							"keterangan"=> $keterangan,
							"variasi"	=> $variasi,
							"idtransaksi"	=> 0
						);
						if($this->db->insert("transaksiproduk",$data)){
							echo json_encode(array("success"=>true,"result"=>$data));
						}else{
							echo json_encode(array("success"=>false));
						}
					}else{
						echo json_encode(array("success"=>false,"msg"=>"stok habis"));
					}
				}else{
					echo json_encode(array("success"=>false,"sesihabis"=>true));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function keranjang(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));*/
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				
				
				$this->db->where("idtransaksi",0);
				$this->db->where("idpo",0);
				$this->db->where("usrid",$r->usrid);
				$this->db->order_by("id","DESC");
				$this->db->limit(10);
				$db = $this->db->get("transaksiproduk");
				if($db->num_rows() > 0){
					foreach($db->result() as $r){
						$harga = $r->harga;
						$produk = $this->func->getProduk($r->idproduk,"semua");
						$var = $this->func->getVariasi($r->variasi,"semua");
						$stok = ($r->variasi != 0) ? $var->stok : $produk->stok;
						if($var != null){
							$war = $this->func->getWarna($var->warna,"nama");
							$zar = $this->func->getSize($var->size,"nama");
							$variasea = ($r->variasi != 0) ? $produk->variasi." ".$war." ".$produk->subvariasi." ".$zar : "";
						}else{
							$variasea = "";
						}
						$data[] = array(
							"foto"	=> $this->func->getFoto($r->idproduk,"utama"),
							"harga"	=> "IDR ".$this->func->formUang($harga),
							"nama"	=> $produk->nama,
							"jumlah"=> $r->jumlah,
							"id"	=> $r->id,
							"po"	=> $r->idpo,
							"stok"	=> $stok,
							"variasi"	=> $variasea
						);
					}
					echo json_encode(array("success"=>true,"result"=>$data));
				}else{
					echo json_encode(array("success"=>false,"sesihabis"=>false));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function bayarpesanan(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));*/
					$usrid = $r->usrid;
				}
				
				
				$this->db->where("idtransaksi",0);
				$this->db->where("idpo",0);
				$this->db->where("usrid",$r->usrid);
				$this->db->order_by("id","DESC");
				//$this->db->limit(10);
				$db = $this->db->get("transaksiproduk");
				
				$totalharga = 0;
				$berat = 0;
				
				if($db->num_rows() > 0){
					foreach($db->result() as $r){
						$harga = $r->harga;
						$totalharga += $harga*$r->jumlah;
						$pro = $this->func->getProduk($r->idproduk,"semua");
						$var = $this->func->getVariasi($r->variasi,"semua");
						$stok = ($r->variasi != 0) ? $var->stok : $pro->stok;
						if($var != null){
							$war = $this->func->getWarna($var->warna,"nama");
							$zar = $this->func->getSize($var->size,"nama");
							$berat += $pro->berat * $r->jumlah;
							$variasea = ($r->variasi != 0) ? $pro->variasi." ".$war." ".$pro->subvariasi." ".$zar : "";
						}else{
							$variasea = "";
						}
						
						if($stok >= $r->jumlah){
							$produk[] = array(
								"foto"	=> $this->func->getFoto($r->idproduk,"utama"),
								"harga"	=> "Rp ".$this->func->formUang($harga),
								"nama"	=> $pro->nama,
								"jumlah"=> $r->jumlah,
								"id"	=> $r->id,
								"po"	=> $r->idpo,
								"variasi"	=> $variasea
							);
						}
					}
					
					if(count($produk) > 0){
						echo json_encode(
							array(
								"success"=>true,
								"produk"=>$produk,
								"totalharga"=>$totalharga,
								"berat"=>$berat,
								"saldo"=>$this->func->getSaldo($usrid,"saldo")
							)
						);
					}else{
						echo json_encode(array("success"=>false,"sesihabis"=>false));
					}
				}else{
					echo json_encode(array("success"=>false,"sesihabis"=>false));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	function terimapesanan(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$inputJSON = file_get_contents('php://input');
			$input = json_decode($inputJSON, TRUE);
			
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));*/
					$usr = $this->func->getUser($r->usrid,"semua");
				}

				$this->db->where("id",$input["id"]);
				if($this->db->update("transaksi",array("status"=>3,"selesai"=>date("Y-m-d H:i:s")))){
					echo json_encode(array("success"=>true,"message"=>"Success!"));
				}else{
					echo json_encode(array("success"=>false,"message"=>"Forbidden Access"));
				}
			}
		}
	}
	function cekout(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$inputJSON = file_get_contents('php://input');
			$input = json_decode($inputJSON, TRUE);
			
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));*/
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				
				$idbayar = 0;
				$kodebayar = rand(100,999);
				$input["total"] = intval($input["total"]) - intval($input["diskon"]);
				$transfer = intval($input["total"]) - intval($input["saldo"]);
				if($transfer > 0){
					$total = $kodebayar + intval($input["total"]);
				}else{
					$total = $input["total"];
					$kodebayar = 0;
				}

				$status = 0;
				$seli = intval($input["saldo"])-intval($input["total"]);
				$status = $seli >= 0 ? 1 : 0;
				$status = (strtolower($input["kurir"]) == "bayar") ? 1 : $status;
				$idalamat = $input["alamat"];

				//$voucher = $this->func->getVoucher($input["kodevoucher"],"id","kode");
				$bayar = array(
					"usrid"	=> $r->usrid,
					"tgl"	=> date("Y-m-d H:i:s"),
					"total"	=> $total,
					"saldo"	=> $input["saldo"],
					"kodebayar"	=> $kodebayar,
					"transfer"	=> $transfer,
					//"voucher"	=> $voucher,
					"metode"	=> $input["metode"],
					"diskon"	=> $input["diskon"],
					"status"	=> $status,
					"kadaluarsa"	=> date('Y-m-d H:i:s', strtotime("+2 days"))
				);
				$this->db->insert("pembayaran",$bayar);
				$idbayar = $this->db->insert_id();

				if($input["metode"] == 2){
					$saldoawal = $this->func->getSaldo($r->usrid,"saldo","usrid");
					$saldoakhir = $saldoawal - intval($input["saldo"]);
					$this->db->where("usrid",$r->usrid);
					$this->db->update("saldo",array("saldo"=>$saldoakhir,"apdet"=>date("Y-m-d H:i:s")));

					$sh = array(
						"tgl"	=> date("Y-m-d H:i:s"),
						"usrid"	=> $r->usrid,
						"jenis"	=> 2,
						"jumlah"	=> $input["saldo"],
						"darike"	=> 3,
						"sambung"	=> $idbayar,
						"saldoawal"	=> $saldoawal,
						"saldoakhir"	=> $saldoakhir
					);
					$this->db->insert("saldohistory",$sh);
				}

				$this->db->where("id",$idbayar);
				$this->db->update("pembayaran",array("invoice"=>date("Ymd").$idbayar.$kodebayar));
				$invoice = "#".date("Ymd").$idbayar.$kodebayar;

				$transaksi = array(
					"orderid"	=> "TRX".date("YmdHis"),
					"tgl"	=> date("Y-m-d H:i:s"),
					"kadaluarsa"	=> date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s"). ' + 2 days')),
					"usrid"	=> $r->usrid,
					"alamat"	=> $idalamat,
					"berat"	=> $input["berat"],
					"ongkir"	=> $input["ongkir"],
					"kurir"	=> strtolower($input["kurir"]),
					"paket"	=> $input["paket"],
					"dari"	=> $input["dari"],
					"tujuan"	=> $input["tujuan"],
					"status"	=> $status,
					"idbayar"	=> $idbayar
				);
				if($input["dropship"] != ""){
					$transaksi["dropship"] = $input["dropship"];
					$transaksi["dropshipnomer"] = $input["dropshipnomer"];
					$transaksi["dropshipalamat"] = $input["dropshipalamat"];
				}
				$this->db->insert("transaksi",$transaksi);
				$idtransaksi = $this->db->insert_id();
				
				$idproduk = explode("|",$input['idproduk']);
				for($i=0; $i<count($idproduk); $i++){
					$this->db->where("id",$idproduk[$i]);
					$this->db->update("transaksiproduk",array("idtransaksi"=>$idtransaksi));
				}
					
				// UPDATE STOK PRODUK
				$this->db->where("idtransaksi",$idtransaksi);
				$db = $this->db->get("transaksiproduk");
				foreach($db->result() as $r){
					if($r->variasi != 0){
						$var = $this->func->getVariasi($r->variasi,"semua","id");
						if($r->jumlah > $var->stok){
							echo json_encode(array("success"=>false,"message"=>"stok produk tidak mencukupi"));
							$stok = 0;
							exit;
						}else{
							$stok = $var->stok - $r->jumlah;
						}
						$variasi[] = $r->variasi;
						$stock[] = $stok;
						$stokawal[] = $var->stok;
						$jml[] = $r->jumlah;

						for($i=0; $i<count($variasi); $i++){
							$this->db->where("id",$variasi[$i]);
							$this->db->update("produkvariasi",["stok"=>$stock[$i],"tgl"=>date("Y-m-d H:i:s")]);
							
							$data = array(
								"usrid"	=> $r->usrid,
								"stokawal" => $stokawal[$i],
								"stokakhir" => $stock[$i],
								"variasi" => $variasi[$i],
								"jumlah" => $jml[$i],
								"tgl"	=> date("Y-m-d H:i:s"),
								"idtransaksi" => $idtransaksi
							);
							$this->db->insert("historystok",$data);
						}
					}else{
						$pro = $this->func->getProduk($r->idproduk,"semua");
						if($r->jumlah > $pro->stok){
							echo json_encode(array("success"=>false,"message"=>"stok produk tidak mencukupi"));
							$stok = 0;
							exit;
						}
						$stok = $pro->stok - $r->jumlah;
						$this->db->where("id",$r->idproduk);
						$this->db->update("produk",["stok"=>$stok,"tglupdate"=>date("Y-m-d H:i:s")]);

						$data = array(
							"usrid"	=> $usr->id,
							"stokawal" => $pro->stok,
							"stokakhir" => $stok,
							"variasi" => 0,
							"jumlah" => $r->jumlah,
							"tgl"	=> date("Y-m-d H:i:s"),
							"idtransaksi" => $idtransaksi
						);
						$this->db->insert("historystok",$data);
					}
				}

				$idbayaran = $idbayar;
				$idbayar = $this->func->arrEnc(array("idbayar"=>$idbayar),"encode");

				$usrid = $this->func->getUser($r->usrid,"semua");
				$alamat = $this->func->getAlamat($idalamat,"semua");
				$toko = $this->func->getSetting("semua");
				$diskon = $input["diskon"] != 0 ? "Diskon: <b>Rp ".$this->func->formUang(intval($input["diskon"]))."</b><br/>" : "";
				$diskonwa = $input["diskon"] != 0 ? "Diskon: *Rp ".$this->func->formUang(intval($input["diskon"]))."*\n" : "";
				$pesan = "
					Halo <b>".$usrid->nama."</b><br/>".
					"Terimakasih sudah membeli produk kami.<br/>".
					"Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>".
					"No Invoice: <b>".$invoice."</b><br/> <br/>".
					"Total Pesanan: <b>Rp ".$this->func->formUang($total)."</b><br/>".
					"Ongkos Kirim: <b>Rp ".$this->func->formUang(intval($input["ongkir"]))."</b><br/>".$diskon.
					"Kurir Pengiriman: <b>".strtoupper($input["kurir"]." ".$input["paket"])."</b><br/> <br/>".
					"Detail Pengiriman <br/>".
					"Penerima: <b>".$alamat->nama."</b>".
					"No HP: <b>".$alamat->nohp."</b>".
					"Alamat: <b>".$alamat->alamat."</b>".
					"<br/> <br/>".
					"Untuk pembayaran silahkan langsung klik link berikut:<br/>".
					"<a href='".site_url("home/invoice")."?inv=".$idbayar."'>Bayar Pesanan Sekarang &raquo;</a>
				";
				$this->func->sendEmail($usrid->username,$toko->nama." - Pesanan",$pesan,"Pesanan");
				$pesan = "
					Halo *".$usrid->nama."*\n".
					"Terimakasih sudah membeli produk kami.\n".
					"Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \n".
					"No Invoice: *".$invoice."*\n".
					"Total Pesanan: *Rp ".$this->func->formUang($total)."*\n".
					"Ongkos Kirim: *Rp ".$this->func->formUang(intval($input["ongkir"]))."*\n".$diskonwa.
					"Kurir Pengiriman: *".strtoupper($input["kurir"]." ".$input["paket"])."*\n \n".
					"Detail Pengiriman \n".
					"Penerima: *".$alamat->nama."*\n".
					"No HP: *".$alamat->nohp."*\n".
					"Alamat: *".$alamat->alamat."*\n \n".
					"Untuk pembayaran silahkan langsung klik link berikut\n".site_url("home/invoice")."?inv=".$idbayar."
				";
				$this->func->sendWA($this->func->getProfil($usrid->id,"nohp","usrid"),$pesan);
				$pesan = "
					<h3>Pesanan Baru</h3><br/>
					<b>".strtoupper(strtolower($usrid->nama))."</b> telah membuat pesanan baru dengan total pembayaran 
					<b>Rp. ".$this->func->formUang($total)."</b> Invoice ID: <b>".$invoice."</b>
					<br/>&nbsp;<br/>&nbsp;<br/>
					Cek Pesanan Pembeli di Dashboard Admin ".$toko->nama."<br/>
					<a href='".site_url("cdn")."'>Klik Disini</a>
				";
				$this->func->sendEmail($toko->email,$toko->nama." - Pesanan Baru",$pesan,"Pesanan Baru di ".$toko->nama);
				$pesan = "
					*Pesanan Baru*\n".
					"*".strtoupper(strtolower($usrid->nama))."* telah membuat pesanan baru dengan detail:\n".
					"Total Pembayaran: *Rp. ".$this->func->formUang($total)."*\n".
					"Invoice ID: *".$invoice."*".
					"\n \n".
					"Cek Pesanan Pembeli di *Dashboard Admin ".$toko->nama."*
					"; 
				$this->func->sendWA($toko->wasap,$pesan);

				//$url = $status == 0 ? site_url("home/invoice")."?inv=".$idbayar : site_url("manage/pesanan");
				echo json_encode(array("success"=>true,"status"=>$status,"inv"=>$idbayaran));
			/*}else{
				echo json_encode(array("success"=>false,"idbayar"=>0));
			}*/
			}else{
				echo json_encode(array("success"=>false,"message"=>"forbidden"));
			}
		}else{
			echo json_encode(array("success"=>false,"message"=>"forbidden"));
		}
	}
	
	// PESANAN
	public function pesanan(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				$page = (isset($_GET["page"]) AND intval($_GET["page"]) > 0) ? $_GET["page"] : 1;
				$perpage = (isset($_GET["perpage"]) AND intval($_GET["perpage"]) > 0) ? $_GET["perpage"] : 6;
				
				$this->db->where("usrid",$r->usrid);
				$rows = $this->db->get("transaksi");
				$rows = $rows->num_rows();

				$this->db->from('transaksi');
				$this->db->where("usrid",$r->usrid);
				$this->db->order_by("status ASC,tgl DESC");
				$this->db->limit($perpage,($page-1)*$perpage);
				$pro = $this->db->get();
				
				$maxPage = ceil($rows/$perpage);
		
				$hasil = array();
				foreach($pro->result() as $r){
					$bayar = $this->func->getBayar($r->idbayar,"semua");
					$trxproduk = $this->func->getTransaksiProduk($r->id,"semua","idtransaksi");
					$produk = $this->func->getProduk($trxproduk->idproduk,"semua");
					$variasi = $this->func->getVariasi($trxproduk->variasi,"semua");
					//$variasinama = ($trxproduk->variasi != 0) ? $variasi->nama : "";
					$variasistok = (is_object($variasi)) ? $variasi->stok : $produk->stok;
					//print_r($variasi); exit;
					$total = $bayar->total - $bayar->kodebayar;
					
					if(is_object($produk)){
						//print_r($produk);
						$hasil[] = array(
							"id"	=> $r->id,
							"idbayar"	=> $r->idbayar,
							"orderid"	=> $r->orderid,
							"tgl"	=> $this->func->ubahTgl("d-m-Y H:i",$r->tgl),
							"status"=> $r->status,
							"stok"	=> $variasistok,
							"total"	=> $total,
							"foto"	=> $this->func->getFoto($trxproduk->idproduk,"utama"),
							"nama"	=> $produk->nama,
							//"variasi"	=> $variasinama,
							"jml"	=> $trxproduk->jumlah
						);
					}else{
						if($r->status == 0){
							//$this->db->where("usrid",$usr->id);
							$this->db->where("id",$trxproduk->id);
							$this->db->delete("transaksiproduk");
							
							$this->db->where("id",$bayar->id);
							$this->db->delete("pembayaran");
							
							$this->db->where("id",$r->id);
							$this->db->delete("transaksi");
						}
					}
				}
				
				echo json_encode(array("success"=>true,"maxPage"=>$maxPage,"page"=>$page,"data"=>$hasil));
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>false));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>true));
		}
	}
	public function pesanansingle($id=null){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				
				$trx = $this->func->getTransaksi($id,"semua");
				if($id != null AND $trx != null){
					$data['paket'] = $trx->paket;
					$data['kurir'] = $trx->kurir;
					$data['ongkir'] = $trx->ongkir;
					$data['status'] = $trx->status;
					
					$this->db->where("id",$trx->alamat);
					$pro = $this->db->get("alamat");
					foreach($pro->result() as $r){
						$kec = $this->func->getKec($r->idkec,"semua");
						$kab = $this->func->getKab($kec->idkab,"nama");
						$data['alamat'][] = array(
							"kab"	=>	$kab,
							"kec"	=>	$kec->nama,
							"judul"	=> $r->judul,
							"alamat"	=> $r->alamat,
							"kodepos"	=> $r->kodepos,
							"nama"	=> $r->nama,
							"nohp"	=> $r->nohp,
							"id"	=> $r->id
						);
					}
					
					$this->db->where("idtransaksi",$id);
					$pro = $this->db->get("transaksiproduk");
					$data['harga'] = 0;
					foreach($pro->result() as $rs){
						$prod = $this->func->getProduk($rs->idproduk,"semua");
						$var = $this->func->getVariasi($rs->variasi,"semua");
						if($rs->variasi > 0){
							$war = $this->func->getWarna($var->warna,"nama");
							$zar = $this->func->getSize($var->size,"nama");
							$variasea = ($rs->variasi != 0) ? $prod->variasi." ".$war." ".$prod->subvariasi." ".$zar : "";
						}else{
							$variasea = "";
						}
						
						$produk[] = array(
							"foto"	=> $this->func->getFoto($rs->idproduk,"utama"),
							"harga"	=> "IDR ".$this->func->formUang($rs->harga),
							"nama"	=> $prod->nama,
							"jumlah"=> $rs->jumlah,
							"po"	=> $rs->idpo,
							"variasi"	=> $variasea
						);
						$data['harga'] = $data['harga'] + ($rs->harga*$rs->jumlah);
					}
					$data['total'] = $this->func->formUang( $data['harga'] + $data['ongkir'] );
					$data['harga'] = $this->func->formUang($data['harga']);
					$data['ongkir'] = $this->func->formUang($data['ongkir']);
					
					echo json_encode(array("success"=>true,"data"=>$data,"produk"=>$produk));
				}else{
					echo json_encode(array("success"=>false,"sesihabis"=>false));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>false));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>true));
		}
	}
	public function hapuspesanan($id=0){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$inputJSON = file_get_contents('php://input');
			$input = json_decode($inputJSON, TRUE);
			
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				
				$trx = $this->func->getTransaksi(intval($input['pid']),"semua","idbayar");
				
				if($trx->status == 0){
					$this->func->notifbatal(intval($input['pid']),2);

					$variasi = [];
					$this->db->where("idtransaksi",$trx->id);
					$db = $this->db->get("transaksiproduk");
					foreach($db->result() as $r){
						if($r->variasi > 0){
							$var = $this->func->getVariasi($r->variasi,"semua","id");
							if(isset($var->stok)){
								$stok = $var->stok + $r->jumlah;
								$variasi[] = $r->variasi;
								$stock[] = $stok;
								$stokawal[] = $var->stok;
								$jml[] = $r->jumlah;
							}
						}else{
							$pro = $this->func->getProduk($r->idproduk,"semua");
							$stok = $pro->stok + $r->jumlah;
							$this->db->where("id",$r->idproduk);
							$this->db->update("produk",["stok"=>$stok,"tglupdate"=>date("Y-m-d H:i:s")]);

							$data = array(
								"usrid"	=> $usr->id,
								"stokawal" => $pro->stok,
								"stokakhir" => $stok,
								"variasi" => 0,
								"jumlah" => $r->jumlah,
								"tgl"	=> date("Y-m-d H:i:s"),
								"idtransaksi" => $trx->id
							);
							$this->db->insert("historystok",$data);
						}
					}
					for($i=0; $i<count($variasi); $i++){
						$this->db->where("id",$variasi[$i]);
						$this->db->update("produkvariasi",["stok"=>$stock[$i],"tgl"=>date("Y-m-d H:i:s")]);
						
						$data = array(
							"usrid"	=> $usr->id,
							"stokawal" => $stokawal[$i],
							"stokakhir" => $stock[$i],
							"variasi" => $variasi[$i],
							"jumlah" => $jml[$i],
							"tgl"	=> date("Y-m-d H:i:s"),
							"idtransaksi" => $trx->id
						);
						$this->db->insert("historystok",$data);
					}
					
					$this->db->where("id",intval($input['pid']));
					$this->db->update("pembayaran",["status"=>3,"tglupdate"=>date("Y-m-d H:i:s")]);
				
					$this->db->where("idbayar",intval($input['pid']));
					$this->db->update("transaksi",["status"=>4]);
				}else{
					$this->db->where("id",intval($input['pid']));
					$this->db->delete("pembayaran");
					$this->db->where("idbayar",intval($input['pid']));
					$trx = $this->db->get("transaksi");
					foreach($trx->result() as $r){
						$this->db->where("idtransaksi",$r->id);
						$this->db->delete("transaksiproduk");
					}
					$this->db->where("idbayar",intval($input['pid']));
					$this->db->delete("transaksi");
				}
				
				echo json_encode(array("success"=>true));
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>false));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>true));
		}
	}
	public function pembayaran($id=0){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
					$usr = $this->func->getUser($r->usrid,"semua");
				}

				$set = $this->func->getSetting("semua");
				$this->db->from('pembayaran');
				$this->db->where("id",$id);
				$this->db->limit(1);
				$pro = $this->db->get();
				$hasil = array();
				foreach($pro->result() as $r){
					$hasilcek = ($r->midtrans_id != "") ? $this->cekmidtrans($r->id) : null;
					$hasil = array(
						"id"	=> $r->id,
						"tgl"	=> $this->func->ubahTgl("d-m-Y H:i",$r->tgl),
						"status"=> $r->status,
						"total"	=> $r->total,
						"ipaymu_tipe" => $r->ipaymu_tipe,
						"ipaymu_kode" => $r->ipaymu_kode,
						"ipaymu_channel" => $r->ipaymu_channel,
						"ipaymu_nama" => $r->ipaymu_nama,
						"payment_transfer"	=> $set->payment_transfer,
						"payment_ipaymu"	=> $set->payment_ipaymu,
						"payment_midtrans"	=> $set->payment_midtrans,
						"midtrans_id"	=> $r->midtrans_id,
						"midtrans_cek"	=> $hasilcek
					);
				}
				
				$this->db->where("usrid",0);
				$rek = $this->db->get("rekening");
				foreach($rek->result() as $rx){
					$hasil['rekening'][] = array(
						"norek"	=> $rx->norek,
						"atasnama"	=> $rx->atasnama,
						"kcp"	=> $rx->kcp,
						"bank"	=> $this->func->getBank($rx->idbank,"nama")
					);
				}
				
				echo json_encode(array("success"=>true,"data"=>$hasil));
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>false));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>true));
		}
	}
	function midtransSukses($bayar){
		$bayar = $this->func->getBayar($bayar,"semua");
		$trx = $this->func->getTransaksi($bayar->id,"semua","idbayar");
		$alamat = $this->func->getAlamat($trx->alamat,"semua");
		$usr = $this->func->getUser($bayar->usrid,"semua");
		$diskon = $bayar->diskon != 0 ? "Diskon: <b>Rp ".$this->func->formUang($bayar->diskon)."</b><br/>" : "";
		$diskonwa = $bayar->diskon != 0 ? "Diskon: *Rp ".$this->func->formUang($bayar->diskon)."*\n" : "";
		$toko = $this->func->getSetting("semua");

		$this->db->where("id",$bayar->id);
		$this->db->update("pembayaran",["status"=>1,"tglupdate"=>date("Y-m-d H:i:s")]);
			
		$this->db->where("idbayar",$bayar->id);
		$this->db->update("transaksi",["status"=>1]);
		
		$pesan = "
			Halo <b>".$usr->nama."</b><br/>".
			"Terimakasih, pembayaran untuk pesananmu sudah kami terima.<br/>".
			"Mohon ditunggu, admin kami akan segera memproses pesananmu<br/>".
			"<b>Detail Pesanan</b><br/>".
			"No Invoice: <b>#".$bayar->invoice."</b><br/>".
			"Total Pesanan: <b>Rp ".$this->func->formUang($bayar->total)."</b><br/>".
			"Ongkos Kirim: <b>Rp ".$this->func->formUang($trx->ongkir)."</b><br/>".$diskon.
			"Kurir Pengiriman: <b>".strtoupper($trx->kurir." ".$trx->paket)."</b><br/> <br/>".
			"Detail Pengiriman <br/>".
			"Penerima: <b>".$alamat->nama."</b> <br/>".
			"No HP: <b>".$alamat->nohp."</b> <br/>".
			"Alamat: <b>".$alamat->alamat."</b>".
			"<br/> <br/>".
			"Cek Status pesananmu langsung di menu:<br/>".
			"<a href='".site_url("manage/pesanan")."'>PESANANKU &raquo;</a>
		";
		$this->func->sendEmail($usr->username,$toko->nama." - Pesanan",$pesan,"Pesanan");
		$pesan = "
			Halo *".$usr->nama."* \n".
			"Terimakasih, pembayaran untuk pesananmu sudah kami terima. \n".
			"Mohon ditunggu, admin kami akan segera memproses pesananmu \n".
			"*Detail Pesanan* \n".
			"No Invoice: *#".$bayar->invoice."* \n".
			"Total Pesanan: *Rp ".$this->func->formUang($bayar->total)."* \n".
			"Ongkos Kirim: *Rp ".$this->func->formUang($trx->ongkir)."* \n".$diskon.
			"Kurir Pengiriman: *".strtoupper($trx->kurir." ".$trx->paket)."* \n  \n".
			"Detail Pengiriman  \n".
			"Penerima: *".$alamat->nama."* \n".
			"No HP: *".$alamat->nohp."* \n".
			"Alamat: *".$alamat->alamat."*".
			" \n  \n".
			"Cek Status pesananmu langsung di menu: \n".
			"*PESANANKU*
		";
		$this->func->sendWA($this->func->getProfil($usr->id,"nohp","usrid"),$pesan);
	}
	public function cekmidtrans($bayar){
		if(isset($bayar)){
			$bayar = $this->func->getBayar($bayar,"semua");
			$orderId = $bayar->midtrans_id;
			$status = \Midtrans\Transaction::status($orderId);
			/*
			print_r($status);
			if($status->payment_type == "cstore"){
				$tipe = "Convenience Store";
				$store = $status->store;
				$kode = $status->payment_code;
			}elseif($status->payment_type == "credit_card"){
				$tipe = "Kartu Kredit";
				$store = $status->bank;
				$kode = $status->masked_card;
			}elseif($status->payment_type == "gopay"){
				$tipe = "E-Channel";
				$store = "Gopay";
				$kode = "";
			}else{
				$tipe = "";
				$store = "";
				$kode = "";
			}	*/
			if($status->payment_type == "cstore"){
				$tipe = "Convenience Store";
				$store = $status->pdf_url;
				$kode = $status->payment_code;
				$cara = "";
			}elseif($status->payment_type == "bank_transfer"){
				if($bayar->midtrans_pdf != ""){
					$cara = "
						<div class='row mb10'>
							<div class='col4'><small>Petunjuk Pembayaran</small></div>
							<div class='col8 text-uppercase font-weight-bold'>: <a href='".$bayar->midtrans_pdf."' target='_blank'><b><i>Lihat Petunjuk &raquo;</i></b></a></div>
						</div>";
				}else{
					$cara = "";
				}
				$tipe = "Virtual Account";
				if(isset($status->va_numbers)){
					$store = $status->va_numbers[0]->bank;
					$kode = $status->va_numbers[0]->va_number;
				}elseif(isset($status->permata_va_number)){
					$store = "Bank Permata";
					$kode = $status->permata_va_number;
				}else{
					$kode = $status->payment_code;
					$store = "Bank";
				}
			}elseif($status->payment_type == "credit_card"){
				$tipe = "Kartu Kredit";
				$store = $status->bank;
				$kode = $status->masked_card;
				$cara = "";
			}elseif($status->payment_type == "echannel"){
				$tipe = "E-Channel";
				$store = "Multi Payment";
				$kode = $status->biller_code." - ".$status->bill_key;
				if($bayar->midtrans_pdf != ""){
					$cara = "
						<div class='row mb10'>
							<div class='col4'><small>Petunjuk Pembayaran</small></div>
							<div class='col8 text-uppercase font-weight-bold'>: <a href='".$bayar->midtrans_pdf."' target='_blank'><b><i>Lihat Petunjuk &raquo;</i></b></a></div>
						</div>";
				}else{
					$cara = "";
				}
			}elseif($status->payment_type == "gopay"){
				$tipe = "E-Channel";
				$store = "Gopay";
				$kode = "";
				$cara = "";
			}else{
				$tipe = "";
				$store = "";
				$kode = "";
				$cara = "";
			}
			
			$hasil["status"] = $status->transaction_status;
			$sukses = array("success","settlement","capture");
			if(in_array($status->transaction_status,$sukses)){
				$this->midtransSukses($bayar->id);
				$hasil["data"] = "
					<div class='row mb10'>
						<div class='col4'>Status</div>
						<div class='col8 text-uppercase text-success font-weight-bold'>: BERHASIL</div>
					</div>
					<div class='row mb10'>
						<div class='col4'><small>Metode Pembayaran</small></div>
						<div class='col8 text-uppercase font-weight-bold'>: ".$tipe."</div>
					</div>
					<div class='row mb10'>
						<div class='col4'>Merchant</div>
						<div class='col8 text-uppercase font-weight-bold'>: ".$store."</div>
					</div>
					<div class='row mb10'>
						<div class='col4'>Kode Bayar</div>
						<div class='col8 text-uppercase font-weight-bold'>: ".$kode."</div>
					</div>
					<div class='row mb10'>
						<div class='col4'><small>Jumlah Bayar</small></div>
						<div class='col8 text-uppercase font-weight-bold'>: Rp. ".$this->func->formUang($status->gross_amount)."</div>
					</div>
				";
			}else{
				$hasil["data"] = "
					<div class='row mb10'>
						<div class='col4'>Status</div>
						<div class='col8 text-uppercase text-danger font-weight-bold'>: ".$status->transaction_status."</div>
					</div>
					<div class='row mb10'>
						<div class='col4'><small>Metode Pembayaran</small></div>
						<div class='col8 text-uppercase font-weight-bold'>: ".$tipe."</div>
					</div>
					<div class='row mb10'>
						<div class='col4'>Merchant</div>
						<div class='col8 text-uppercase font-weight-bold'>: ".$store."</div>
					</div>
					<div class='row mb10'>
						<div class='col4'>Kode Bayar</div>
						<div class='col8 text-uppercase font-weight-bold'>: ".$kode."</div>
					</div>
					<div class='row mb10'>
						<div class='col4'><small>Jumlah Bayar</small></div>
						<div class='col8 text-uppercase font-weight-bold'>: Rp. ".$this->func->formUang($status->gross_amount)."</div>
					</div>".
					$cara."
				";
			}
		}else{
			$hasil = ["status"=>"failed","data"=>null];
		}

		return $hasil;
	}
	
	// Rekening
	public function rekening(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				$page = (isset($_GET["page"]) AND intval($_GET["page"]) > 0) ? $_GET["page"] : 1;
				$perpage = (isset($_GET["perpage"]) AND intval($_GET["perpage"]) > 0) ? $_GET["perpage"] : 6;
				
				$rows = $this->db->get("rekening");
				$this->db->where("usrid",$r->usrid);
				$rows = $rows->num_rows();

				$this->db->from('rekening');
				$this->db->where("usrid",$r->usrid);
				$this->db->order_by("id DESC");
				$this->db->limit($perpage,($page-1)*$perpage);
				$pro = $this->db->get();
				
				$maxPage = ceil($rows/$perpage);
		
				$alamat = array();
				foreach($pro->result() as $r){
					$bank = $this->func->getBank($r->idbank,"nama");
					$alamat[] = array(
						"id"	=> $r->id,
						"atasnama"	=> $r->atasnama,
						"idbank"	=> $r->idbank,
						"bank"	=> $bank,
						"norek"	=> $r->norek,
						"kcp"	=> $r->kcp,
					);
				}
				
				echo json_encode(array("success"=>true,"maxPage"=>$maxPage,"page"=>$page,"data"=>$alamat));
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>false));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>true));
		}
	}
	public function getrekening($id){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $rx){}
				$this->db->where("id",$id);
				$this->db->where("usrid",$rx->usrid);
				$db = $this->db->get("rekening");
				$reg = 0;
				
				$alamat = array();
				foreach($db->result() as $r){
					$bank = $this->func->getBank($r->idbank,"nama");
					$alamat = array(
						"id"	=> $r->id,
						"atasnama"	=> $r->atasnama,
						"idbank"	=> $r->idbank,
						"bank"	=> $bank,
						"norek"	=> $r->norek,
						"kcp"	=> $r->kcp
					);
				}
				
				echo json_encode($alamat);
			}else{
				echo json_encode(array(
						"atasnama"	=> "",
						"idbank"	=> "",
						"bank"	=> "",
						"norek"	=> "",
						"kcp"	=> ""
					));
			}
		}else{
			echo json_encode(array(
					"atasnama"	=> "",
					"idbank"	=> "",
					"bank"	=> "",
					"norek"	=> "",
					"kcp"	=> ""
				));
		}
	}
	public function tambahrekening(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$inputJSON = file_get_contents('php://input');
			$input = json_decode($inputJSON, TRUE);
			
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));*/
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				
				if(isset($input)){
					$dt = $input["data"];
					$data = array(
						"usrid"	=> $r->usrid,
						"idbank"=> $dt['idbank'],
						"atasnama"	=> $dt['atasnama'],
						"norek"	=> $dt['norek'],
						"kcp"	=> $dt['kcp'],
						"tgl"	=> date("Y-m-d H:i:s")
					);
					
					if($input['id'] > 0){
						$this->db->where("id",$input['id']);
						$this->db->update("rekening",$data);
					}else{
						$this->db->insert("rekening",$data);
					}
					
					echo json_encode(array("success"=>true));
				}else{
					echo json_encode(array("success"=>false,"sesihabis"=>false));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function hapusrekening($id=0){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$inputJSON = file_get_contents('php://input');
			$input = json_decode($inputJSON, TRUE);
			
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				
				$this->db->where("id",intval($input['pid']));
				$this->db->delete("rekening");
				
				echo json_encode(array("success"=>true));
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>false));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>true));
		}
	}
	
	// ALAMAT
	public function alamat(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				$page = (isset($_GET["page"]) AND intval($_GET["page"]) > 0) ? $_GET["page"] : 1;
				$perpage = (isset($_GET["perpage"]) AND intval($_GET["perpage"]) > 0) ? $_GET["perpage"] : 6;
				
				$rows = $this->db->get("alamat");
				$this->db->where("usrid",$r->usrid);
				$rows = $rows->num_rows();

				$this->db->from('alamat');
				$this->db->where("usrid",$r->usrid);
				$this->db->order_by("status DESC");
				$this->db->limit($perpage,($page-1)*$perpage);
				$pro = $this->db->get();
				
				$maxPage = ceil($rows/$perpage);
		
				$alamat = array();
				foreach($pro->result() as $r){
					$kec = $this->func->getKec($r->idkec,"semua");
					$kab = $this->func->getKab($kec->idkab,"nama");
					$alamat[] = array(
						"kab"	=>	$kab,
						"kec"	=>	$kec->nama,
						"judul"	=> $r->judul,
						"alamat"	=> $r->alamat,
						"kodepos"	=> $r->kodepos,
						"nama"	=> $r->nama,
						"nohp"	=> $r->nohp,
						"id"	=> $r->id,
						"status"	=> $r->status,
						"dari"	=> $this->func->globalset("kota")
					);
				}
				
				echo json_encode(array("success"=>true,"maxPage"=>$maxPage,"page"=>$page,"data"=>$alamat));
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>false));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>true));
		}
	}
	public function getalamat($id,$berat=1000){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $rx){}
				if($id != "utama"){
					$this->db->where("id",$id);
				}else{
					$this->db->where("status",1);
				}
				$this->db->where("usrid",$rx->usrid);
				$db = $this->db->get("alamat");
				$reg = 0;
				$alamat = array();
				$berat = ($berat > 0) ? $berat : 1000;
				foreach($db->result() as $r){
					$seting = $this->func->getSetting("semua");
					$kurir = $seting->kurir;
					$kurir = explode("|",$kurir);
					$this->db->where_in("id",$kurir);
					$this->db->order_by("id","ASC");
					$db = $this->db->get("kurir");
					
					//$paketkurir[] = "cod - cod";
					//$paketkurir[] = "toko - toko";
					//$hasil[] = $this->cekOngkir($seting->kota,$berat,$r->idkec,"cod","cod");
					//$hasil[] = $this->cekOngkir($seting->kota,$berat,$r->idkec,"toko","toko");
					//print_r($hasil);
					$hasil = array();
					$paketkurir = array();
					
					foreach($db->result() as $rs){
						$this->db->where("idkurir",$rs->id);
						$x = $this->db->get("paket");
						foreach($x->result() as $re){
							$res = $this->cekOngkir($seting->kota,$berat,$r->idkec,$rs->rajaongkir,$re->rajaongkir);
							//if($rs->rajaongkir == "jne" AND $re->rajaongkir == "REG"){ $reg = $res['harga']; }
							if($res['success']){
								$paketkurir[] = $rs->rajaongkir." - ".$re->rajaongkir;
								$res['kurir'] = strtoupper($res['kurir']);
								$hasil[] = $res;
							}
						}
						//print_r($x->result());
					}
					$kab = $this->func->getKec($r->idkec,"idkab");
					$prov = $this->func->getKab($kab,"idprov");
				
					$alamat = array(
						"idkec"	=>	$r->idkec,
						"idprov"=>	$prov,
						"idkab"	=>	$kab,
						"judul"	=> $r->judul,
						"alamat"	=> $r->alamat,
						"kodepos"	=> $r->kodepos,
						"nama"	=> $r->nama,
						"nohp"	=> $r->nohp,
						"id"	=> $r->id,
						"dari"	=> $this->func->globalset("kota"),
						"ongkir"=> $hasil,
						"paku"=> $paketkurir,
						"reg"	=> $reg
					);
				}
				
				echo json_encode($alamat);
			}else{
				echo json_encode(array(
						"idkec"	=>	0,
						"idprov"=>	0,
						"idkab"	=>	0,
						"judul"	=> "Tidak Ditemukan",
						"alamat"	=> "",
						"kodepos"	=> 0,
						"nama"	=> "",
						"nohp"	=> "",
						"id"	=> 0,
						"dari"	=> $this->func->globalset("kota"),
						"ongkir"=> false,
						"reg"	=> 0
					));
			}
		}else{
			echo json_encode(array(
					"idkec"	=>	0,
					"idprov"=>	0,
					"idkab"	=>	0,
					"judul"	=> "Tidak Ditemukan",
					"alamat"	=> "",
					"kodepos"	=> 0,
					"nama"	=> "",
					"nohp"	=> "",
					"id"	=> 0,
					"dari"	=> $this->func->globalset("kota")
				));
		}
	}
	public function pilihanongkir(){
		$kurir = $this->func->getSetting("kurir");
		
		$db = $this->db->get("kurir");
		foreach($db->result() as $r){
			$res = $this->cekOngkir($_GET["dari"],$_GET["berat"],$_GET['tujuan'],$r->rajaongkir,"");
			//$cek = json_decode($res);
			//if($cek['success'] == true){
				$hasil[] = $res;
			//}
		}
		print("<pre>".print_r($hasil,true)."</pre>");
	}
	public function tambahalamat($ide=0){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$inputJSON = file_get_contents('php://input');
			$input = json_decode($inputJSON, TRUE);
			
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));*/
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				
				if(isset($input)){
					$dt = $input["data"];
					if($ide != 0){
						$data = array(
							"status"=>1
						);
						$this->db->where("id !=",$input['id']);
						$this->db->where("usrid",$usr->id);
						$this->db->where("status",1);
						$this->db->update("alamat",["status"=>0]);
					}else{
						$data = array(
							"usrid"	=> $r->usrid,
							"idkec"	=> $dt['idkec'],
							"judul"	=> $dt['judul'],
							"alamat"	=> $dt['alamat'],
							"nama"	=> $dt['nama'],
							"kodepos"	=> $dt['kodepos'],
							"nohp"	=> $dt['nohp']
						);
					}
					
					if($input['id'] > 0){
						$this->db->where("id",$input['id']);
						$this->db->update("alamat",$data);
					}else{
						$this->db->insert("alamat",$data);
					}
					
					echo json_encode(array("success"=>true));
				}else{
					echo json_encode(array("success"=>false,"sesihabis"=>false));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function hapusalamat($id=0){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$inputJSON = file_get_contents('php://input');
			$input = json_decode($inputJSON, TRUE);
			
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				
				$this->db->where("id",intval($input['pid']));
				$this->db->delete("alamat");
				
				echo json_encode(array("success"=>true));
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>false));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>true));
		}
	}

	//WASAP
	function getwhatsapp(){
		echo json_encode(array("wasap"=>$this->func->getRandomWasap()));
	}
	
	// ALAMAT PROV KAB KEC
	public function getprov(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){		
				$data = array();
				$this->db->order_by("nama");
				$db = $this->db->get("prov");
				foreach($db->result() as $r){
					$data[] = array(
						"id"	=> $r->id,
						"nama"	=> $r->nama
					);
				}
				echo json_encode(array("success"=>true,"data"=>$data));
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}		
	public function getkab($id=0){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){		
				$data = array();
				$this->db->where("idprov",$id);
				$this->db->order_by("tipe,nama");
				$db = $this->db->get("kab");
				foreach($db->result() as $r){
					$data[] = array(
						"id"	=> $r->id,
						"nama"	=> $r->tipe." ".$r->nama
					);
				}
				echo json_encode(array("success"=>true,"data"=>$data));
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
		
	}		
	public function getkec($id=0){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){		
				$data = array();
				$this->db->where("idkab",$id);
				$this->db->order_by("nama");
				$db = $this->db->get("kec");
				foreach($db->result() as $r){
					$data[] = array(
						"id"	=> $r->id,
						"nama"	=> $r->nama
					);
				}
				echo json_encode(array("success"=>true,"data"=>$data));
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
		
	}
	public function getbank(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $t){		
					$data = array();
					$this->db->order_by("nama");
					$db = $this->db->get("rekeningbank");
					foreach($db->result() as $r){
						$data[] = array(
							"id"	=> $r->id,
							"nama"	=> $r->nama
						);
					}
					echo json_encode(array("success"=>true,"data"=>$data));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
		
	}
	
	// PRODUK
	public function produk(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));*/
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				$kategori = $this->func->getKategori($_GET["catid"],"nama");
				
				$this->db->where("idcat",$_GET["catid"]);
				$this->db->order_by("preorder ASC, id DESC");
				//$this->db->limit(10);
				$db = $this->db->get("produk");
				if($db->num_rows() > 0){
					$data = array();
					foreach($db->result() as $r){
						$this->db->where("idproduk",$r->id);
						$dba = $this->db->get("produkvariasi");
						$stok = 0;
						if($dba->num_rows() == 0){ $stok = $r->stok; }
						foreach($dba->result() as $rs){
							$stok += $rs->stok;
						}
						if($r->preorder > 0){
							$this->db->where("idproduk",$r->id);
							$dbb = $this->db->get("preorder");
							$preo = 0;
							foreach($dbb->result() as $re){
								$preo += $re->jumlah;
							}
							$stok = $stok - $preo;
						}
						
						if(is_object($usr)){
							if($usr->level == 5){
								$harga = $r->hargadistri;
							}else
							if($usr->level == 4){
								$harga = $r->hargaagensp;
							}elseif($usr->level == 3){
								$harga = $r->hargaagen;
							}elseif($usr->level == 2){
								$harga = $r->hargareseller;
							}else{
								$harga = $r->harga;
							}
						}else{
							$harga = $r->harga;
						}

						$this->db->where("idproduk",$r->id);
						$dba = $this->db->get("produkvariasi");
						$stok = 0;
						$hargo = array();
						if($dba->num_rows() == 0){ $stok = $r->stok; }
						foreach($dba->result() as $rs){
							$stok += $rs->stok;
							if(is_object($usr)){
								if($usr->level == 5){
									$hargo[] = $rs->hargadistri;
								}elseif($usr->level == 4){
									$hargo[] = $rs->hargaagensp;
								}elseif($usr->level == 3){
									$hargo[] = $rs->hargaagen;
								}elseif($usr->level == 2){
									$hargo[] = $rs->hargareseller;
								}else{
									$hargo[] = $rs->harga;
								}
							}else{
								$hargo[] = $rs->harga;
							}
						}
						if($dba->num_rows() > 0){ $harga = min($hargo); }
						$ulasan = $this->func->getReviewProduk($r->id);
						if($stok > 0){
							$data[] = array(
								"foto"	=> $this->func->getFoto($r->id,"utama"),
								"harga"	=> "IDR ".$this->func->formUang($harga),
								"nama"	=> $r->nama,
								"id"	=> $r->id,
								"po"	=> $r->preorder,
								"ulasan"=> $ulasan["ulasan"],
								"nilai"	=> $ulasan["nilai"],
							);
						}
					}
					echo json_encode(array("success"=>true,"kategori"=>$kategori,"result"=>$data));
				}else{
					echo json_encode(array("success"=>false,"kategori"=>$kategori,"sesihabis"=>false));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function cariproduk(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$inputJSON = file_get_contents('php://input');
			$input = json_decode($inputJSON, TRUE);

			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));*/
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				$cari = (isset($input["cari"])) ? $input["cari"] : "";
				
				$where = "nama LIKE '%".$cari."%' OR kode LIKE '%".$cari."%' OR url LIKE '%".$cari."%' OR deskripsi LIKE '%".$cari."%' OR berat LIKE '%".$cari."%' OR harga LIKE '%".$cari."%' OR hargareseller LIKE '%".$cari."%' OR hargaagen LIKE '%".$cari."%' OR hargaagensp LIKE '%".$cari."%' OR hargadistri LIKE '%".$cari."%' OR stok LIKE '%".$cari."%'";
				$this->db->where($where);
				$this->db->order_by("preorder ASC, id DESC");
				//$this->db->limit(10);
				$db = $this->db->get("produk");
				if($db->num_rows() > 0){
					$data = array();
					foreach($db->result() as $r){
						$this->db->where("idproduk",$r->id);
						$dba = $this->db->get("produkvariasi");
						$stok = 0;
						if($dba->num_rows() == 0){ $stok = $r->stok; }
						foreach($dba->result() as $rs){
							$stok += $rs->stok;
						}
						if($r->preorder > 0){
							$this->db->where("idproduk",$r->id);
							$dbb = $this->db->get("preorder");
							$preo = 0;
							foreach($dbb->result() as $re){
								$preo += $re->jumlah;
							}
							$stok = $stok - $preo;
						}
						
						if(is_object($usr)){
							if($usr->level == 5){
								$harga = $r->hargadistri;
							}else
							if($usr->level == 4){
								$harga = $r->hargaagensp;
							}elseif($usr->level == 3){
								$harga = $r->hargaagen;
							}elseif($usr->level == 2){
								$harga = $r->hargareseller;
							}else{
								$harga = $r->harga;
							}
						}else{
							$harga = $r->harga;
						}

						$this->db->where("idproduk",$r->id);
						$dba = $this->db->get("produkvariasi");
						$stok = 0;
						$hargo = array();
						if($dba->num_rows() == 0){ $stok = $r->stok; }
						foreach($dba->result() as $rs){
							$stok += $rs->stok;
							if(is_object($usr)){
								if($usr->level == 5){
									$hargo[] = $rs->hargadistri;
								}elseif($usr->level == 4){
									$hargo[] = $rs->hargaagensp;
								}elseif($usr->level == 3){
									$hargo[] = $rs->hargaagen;
								}elseif($usr->level == 2){
									$hargo[] = $rs->hargareseller;
								}else{
									$hargo[] = $rs->harga;
								}
							}else{
								$hargo[] = $rs->harga;
							}
						}
						if($dba->num_rows() > 0){ $harga = min($hargo); }
						$ulasan = $this->func->getReviewProduk($r->id);
						if($stok > 0){
							$data[] = array(
								"foto"	=> $this->func->getFoto($r->id,"utama"),
								"harga"	=> "IDR ".$this->func->formUang($harga),
								"nama"	=> $r->nama,
								"id"	=> $r->id,
								"po"	=> $r->preorder,
								"ulasan"=> $ulasan["ulasan"],
								"nilai"	=> $ulasan["nilai"],
							);
						}
					}
					echo json_encode(array("success"=>true,"result"=>$data));
				}else{
					echo json_encode(array("success"=>true,"result"=>[]));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function produkterbaru(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));*/
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				$data = array();
				
				$this->db->order_by("preorder ASC, tglupdate DESC");
				$this->db->limit(10);
				$db = $this->db->get("produk");
				if($db->num_rows() > 0){
					foreach($db->result() as $r){
						if(is_object($usr)){
							if($usr->level == 5){
								$harga = $r->hargadistri;
							}elseif($usr->level == 4){
								$harga = $r->hargaagensp;
							}elseif($usr->level == 3){
								$harga = $r->hargaagen;
							}elseif($usr->level == 2){
								$harga = $r->hargareseller;
							}else{
								$harga = $r->harga;
							}
						}else{
							$harga = $r->harga;
						}

						$this->db->where("idproduk",$r->id);
						$dba = $this->db->get("produkvariasi");
						$stok = 0;
						$hargo = array();
						if($dba->num_rows() == 0){ $stok = $r->stok; }
						foreach($dba->result() as $rs){
							$stok += $rs->stok;
							if(is_object($usr)){
								if($usr->level == 5){
									$hargo[] = $rs->hargadistri;
								}elseif($usr->level == 4){
									$hargo[] = $rs->hargaagensp;
								}elseif($usr->level == 3){
									$hargo[] = $rs->hargaagen;
								}elseif($usr->level == 2){
									$hargo[] = $rs->hargareseller;
								}else{
									$hargo[] = $rs->harga;
								}
							}else{
								$hargo[] = $rs->harga;
							}
						}
						if($dba->num_rows() > 0){ $harga = min($hargo); }
						if($r->preorder > 0){
							$this->db->where("idproduk",$r->id);
							$dbb = $this->db->get("preorder");
							$preo = 0;
							foreach($dbb->result() as $re){
								$preo += $re->jumlah;
							}
							$stok = $stok - $preo;
						}
						
						$ulasan = $this->func->getReviewProduk($r->id);
						if($stok > 0){
							$data[] = array(
								"foto"	=> $this->func->getFoto($r->id,"utama"),
								"harga"	=> "IDR ".$this->func->formUang($harga),
								"nama"	=> $this->func->potong($r->nama,40),
								"id"	=> $r->id,
								"po"	=> $r->preorder,
								"ulasan"=> $ulasan["ulasan"],
								"nilai"	=> $ulasan["nilai"],
							);
						}
					}
					echo json_encode(array("success"=>true,"result"=>$data));
				}else{
					echo json_encode(array("success"=>false,"sesihabis"=>false));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function produksingle(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));*/
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				
				
				$this->db->where("id",$_GET["pid"]);
				$db = $this->db->get("produk");
				if($db->num_rows() > 0){
					foreach($db->result() as $r){
						if(is_object($usr)){
							if($usr->level == 5){
								$harga = $r->hargadistri;
							}elseif($usr->level == 4){
								$harga = $r->hargaagensp;
							}elseif($usr->level == 3){
								$harga = $r->hargaagen;
							}elseif($usr->level == 2){
								$harga = $r->hargareseller;
							}else{
								$harga = $r->harga;
							}
						}else{
							$harga = $r->harga;
						}
						$this->db->where("idproduk",$_GET["pid"]);
						$this->db->order_by("jenis","DESC");
						$dbs = $this->db->get("upload");
						$foto = array();
						foreach($dbs->result() as $rs){
							$foto[]["foto"] = base_url("cdn/uploads/".$rs->nama);
						}
						$this->db->where("idproduk",$_GET["pid"]);
						//$this->db->group_by("warna");
						$dbs = $this->db->get("produkvariasi");
						$warnafix = array();
						$stoky = $r->stok;
						$variasiproduk = 0; 
						$hargos = 0;
						$hargo = array();
						if($dbs->num_rows() > 0){
							$warna = array();
							$stoky = 0;
							foreach($dbs->result() as $rs){
								$variasiproduk = 1;
								$stoky += $rs->stok;
								
								$this->db->where("variasi",$rs->id);
								$dbf = $this->db->get("preorder");
								$totalpre = 0;
								foreach($dbf->result() as $rf){
									$totalpre += $rf->jumlah;
								}
								
								//$warna[] = $this->func->getWarna($rs->warna,"nama");
								$warnaid[] = $rs->warna;
								$variasi[$rs->warna][] = $rs->id;
								$sizeid[$rs->warna][] = $rs->size;
								$har[$rs->warna][$rs->size] = $rs->harga;
								$harreseller[$rs->warna][$rs->size] = $rs->hargareseller;
								$haragen[$rs->warna][$rs->size] = $rs->hargaagen;
								$haragensp[$rs->warna][$rs->size] = $rs->hargaagensp;
								$hardistri[$rs->warna][$rs->size] = $rs->hargadistri;
								if(isset($stoks[$rs->warna])){
									$stoks[$rs->warna] += ($r->preorder == 0) ? $rs->stok : $rs->stok - $totalpre;
								}else{
									$stoks[$rs->warna] = ($r->preorder == 0) ? $rs->stok : $rs->stok - $totalpre;
								}
								$stok[$rs->warna][] = ($r->preorder == 0) ? $rs->stok : $rs->stok - $totalpre;
								//$size[$rs->warna][] = $this->func->getSize($rs->size,"nama");
							}
							$warnaid = array_unique($warnaid);
							$warnaid = array_values($warnaid);
							for($i=0; $i<count($warnaid); $i++){
								if($stoks[$warnaid[$i]] > 0){
									$warnafix[] = array(
										"id"	=> $warnaid[$i],
										"nama" 	=> $this->func->getWarna($warnaid[$i],"nama")
									);
									
									for($a=0; $a<count($sizeid[$warnaid[$i]]); $a++){
										if(is_object($usr)){
											if($usr->level == 5){
												$hargo[] = intval($hardistri[$warnaid[$i]][$sizeid[$warnaid[$i]][$a]]);
											}elseif($usr->level == 4){
												$hargo[] = intval($haragensp[$warnaid[$i]][$sizeid[$warnaid[$i]][$a]]);
											}elseif($usr->level == 3){
												$hargo[] = intval($haragen[$warnaid[$i]][$sizeid[$warnaid[$i]][$a]]);
											}elseif($usr->level == 2){
												$hargo[] = intval($harreseller[$warnaid[$i]][$sizeid[$warnaid[$i]][$a]]);
											}else{
												$hargo[] = intval($har[$warnaid[$i]][$sizeid[$warnaid[$i]][$a]]);
											}
										}else{
											$hargo[] = intval($har[$warnaid[$i]][$sizeid[$warnaid[$i]][$a]]);
										}
										$hargos += intval($har[$warnaid[$i]][$sizeid[$warnaid[$i]][$a]]);
									}
								}
							}
						}
						$this->db->where("idproduk",$_GET["pid"]);
						$rev = $this->db->get("review");
						$ulasan = [];
						$nilai = 0;
						foreach($rev->result() as $u){
							$ulasan[] = array(
								"nama"	=> $this->func->getProfil($u->usrid,"nama","usrid"),
								"tgl"	=> $this->func->ubahTgl("d/m/Y",$u->tgl),
								"keterangan"=> $u->keterangan,
								"nilai"	=> $u->nilai
							);
							$nilai += $u->nilai;
						}
						$nilai = $nilai != 0 ? round($nilai/$rev->num_rows(),1) : 0;
						//echo "<h1>".min($hargo)."</h1>";
						$harga = ($hargos > 0) ? max($hargo) : $harga;
						$harga = ($hargos > 0 AND min($hargo) != max($hargo)) ? "Rp. ".$this->func->formUang(min($hargo))." - ".$this->func->formUang(max($hargo)) : "Rp. ".$this->func->formUang($harga);
						$data = array(
							"success"=>true,
							"warna"	=> $warnafix,
							"stok"	=> $stoky,
							"foto"	=> $foto,
							"harga"	=> $harga,
							"nama"	=> $r->nama,
							"deskripsi"	=> $r->deskripsi,
							"id"	=> $r->id,
							"variasiproduk"	=> $variasiproduk,
							"po"	=> $r->preorder,
							"totulasan"=> $rev->num_rows(),
							"ulasan"=> $ulasan,
							"nilai"=> $nilai
						);
					}
					echo json_encode($data);
				}else{
					echo json_encode(array("success"=>false,"sesihabis"=>false));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function size(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));*/
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				
				
				$this->db->where("idproduk",$_GET["proid"]);
				$this->db->where("warna",$_GET["pid"]);
				$db = $this->db->get("produkvariasi");
				if($db->num_rows() > 0){
					foreach($db->result() as $r){
						if($r->stok > 0){
							$size[] = array(
								"id"=> $r->id,
								"stok"=> $r->stok,
								"nama"=> $this->func->getSize($r->size,"nama")
							);
						}
					}
					echo json_encode(array("success"=>true,"size"=>$size));
				}else{
					echo json_encode(array("success"=>false,"sesihabis"=>false));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	
	// PROFIL
	public function userdetail(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));*/
					$usr = $this->func->getUser($r->usrid,"semua");
					$result = array(
						"success"	=>true,
						"usrid"		=>$r->usrid,
						"level"		=>$usr->level,
						"nama"		=>$this->func->getProfil($r->usrid,"nama","usrid"),
						"saldo"		=>$this->func->getSaldo($r->usrid,"saldo","usrid"),
						"token"		=>$r->token
					);
					echo json_encode($result);
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function profil(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));*/
					$usr = $this->func->getUser($r->usrid,"semua");
				}
				
				$this->db->where("usrid",$usr->id);
				$db = $this->db->get("profil");
				if($db->num_rows() > 0){
					foreach($db->result() as $r){
						$data = array(
							"id"=> $r->id,
							"nohp"=> $r->nohp,
							"kelamin"=> $r->kelamin,
							"nama"=> $r->nama,
							"email"=> $this->func->getUser($usr->id,"username")
						);
					}
					echo json_encode(array("success"=>true,"data"=>$data));
				}else{
					echo json_encode(array("success"=>false,"sesihabis"=>false));
				}
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function simpanprofil(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$inputJSON = file_get_contents('php://input');
			$input = json_decode($inputJSON, TRUE);
			
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
					$usr = $this->func->getUser($r->usrid,"semua");*/
					
					$this->db->where("usrid",$r->usrid);
					$this->db->update("profil",array("nama"=>$input['nama'],"nohp"=>$input['nohp'],"kelamin"=>$input['kelamin']));
					$this->db->where("id",$r->usrid);
					$this->db->update("userdata",array("username"=>$input['email']));
				}
				
				echo json_encode(array("success"=>true));
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function simpanpassword(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$inputJSON = file_get_contents('php://input');
			$input = json_decode($inputJSON, TRUE);
			
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->limit(1);
			$db = $this->db->get("token");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					/*$this->db->where("id",$r->id);
					$this->db->update("token",array("last_access"=>date("Y-m-d H:i:s")));
					$usr = $this->func->getUser($r->usrid,"semua");*/
					
					$this->db->where("id",$r->usrid);
					$this->db->update("userdata",array("password"=>$this->encrypt->encode($input['email'])));
				}
				
				echo json_encode(array("success"=>true));
			}else{
				echo json_encode(array("success"=>false,"sesihabis"=>true));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	
	//LOGIN LOGOUT REGISTER
	public function login(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$inputJSON = file_get_contents('php://input');
			$input = json_decode($inputJSON, TRUE);
			
			$this->db->where("nohp",$input["email"]);
			$this->db->or_where("username",$input["email"]);
			$this->db->limit(1);
			$db = $this->db->get("userdata");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					$token = md5(date("YmdHis").$r->id);
					if($this->func->decode($r->password) === $input["password"]){
						//DESTROY OLD TOKEN SESSION
							$this->db->where("usrid",$r->id);
							$this->db->where("status",0);
							$this->db->update("token",array("status"=>2));
						//CREATE NEW TOKEN SESSION
						$data = array(
							"usrid"=>$r->id,
							"tgl"=>date("Y-m-d H:i:s"),
							"token"=>$token,
							"status"=>1
						);
						$this->db->insert("token",$data);
						
						echo json_encode(array("success"=>true,"level"=>$r->level,"usrid"=>$r->id,"nama"=>$this->func->getProfil($r->id,"nama","usrid"),"saldo"=>$this->func->getSaldo($r->id,"saldo","usrid"),"token"=>$token));
					}else{
						echo json_encode(array("success"=>false,"message"=>"Gagal masuk, Email/No HP/Password salah"));
					}
				}
			}else{
				echo json_encode(array("success"=>false,"message"=>"Gagal masuk, Pengguna tidak ditemukan"));
			}
		}else{
			echo json_encode(array("success"=>false,"sesihabis"=>false));
		}
	}
	public function logout(){
		if(isset($_SERVER['HTTP_AUTHORIZATION'])){
			$this->db->where("token",$_SERVER['HTTP_AUTHORIZATION']);
			$this->db->where("status",1);
			$this->db->update("token",array("status"=>2,"last_access"=>date("Y-m-d H:i:s")));
			
			$token = md5(date("YmdHis"));
			$this->db->insert("token",array("token"=>$token,"tgl"=>date("Y-m-d H:i:s")));
			echo json_encode(array("success"=>true,"token"=>$token,"message"=>"Berhasil keluar aplikasi, silahkan masuk/daftar untuk menggunakan Aplikasi"));
		}else{
			echo json_encode(array("success"=>false,"message"=>"Gagal logout! ulangi beberapa saat lagi"));
		}
	}
	public function register(){
		$inputJSON = file_get_contents('php://input');
		$input = json_decode($inputJSON, TRUE);
		if(isset($input["nohp"])){
			$users = $this->func->getUser($input["email"],"semua","username");
			if($input["nohp"] == null OR $input["nama"] == null OR $input["password"] == null){
				echo json_encode(array("success"=>false,"message"=>"Formulir belum lengkap, mohon lengkapi dahulu sesuai format yg disediakan"));
				exit;
			}
			if($users != null){
				echo json_encode(array("success"=>false,"message"=>"Alamat email sudah terdaftar!"));
				exit;
			}
			$data = array(
				"username"	=> $input["email"],
				"nama"	=> $input["nama"],
				"nohp"	=> $input["nohp"],
				"level"	=> 1,
				"password"	=> $this->func->encode($input["password"])
			);
			$this->db->insert("userdata",$data);
			$usrid = $this->db->insert_id();

			$data = array(
				"usrid"	=> $usrid,
				"nama"	=> $input["nama"],
				"nohp"	=> $input["nohp"]
			);
			$this->db->insert("profil",$data);
			
			/*$pesan = "Terimakasih telah bergabung menjadi mitra OKE Kasir dan salam OKE pasti SUKSES!";
			$this->func->sendEmail($input["email"],"Pendaftaran OKE Kasir",$pesan,"Aplikasi OKE Kasir");*/
			$this->func->verifEmail($usrid);
			$this->func->verifWA($usrid);
			
			echo json_encode(array("success"=>true,"message"=>"berhasil"));
			
		}else{
			echo json_encode(array("success"=>false,"message"=>"Akses ditolak, silahkan masukkan data dengan benar"));
		}
	}
	public function lupa(){
		$inputJSON = file_get_contents('php://input');
		$input = json_decode($inputJSON, TRUE);
		
		if(isset($input["email"])){
			$this->db->where("username",$input["email"]);
			$this->db->or_where("nohp",$input["email"]);
			$this->db->limit(1);
			$db = $this->db->get("userdata");
			$nama = $this->func->getSetting("nama");
			if($db->num_rows() > 0){
				foreach($db->result() as $r){
					//$this->func->sendEmail($r->email,"Reset password ".$nama,"Reset password","Aplikasi ".$nama);
					$this->func->resetPass($r->username);
					echo json_encode(array("success"=>true,"message"=>"Berhasil mereset password, silahkan cek email anda untuk detail password yang baru"));
				}
			}else{
				echo json_encode(array("success"=>false,"message"=>"Alamat Email atau No Handphone tidak terdaftar!"));
			}
		}else{
			echo json_encode(array("success"=>false,"message"=>"Masukkan alamat email/nomor handphone"));
		}
	}
	
	
	// CEK ONGKIR
	public function tesOngkir($dari,$berat,$tujuan,$kurir,$services){
		print_r($this->cekOngkir($dari,$berat,$tujuan,$kurir,$services));
	}
	public function ceksongkir(){
		if($_GET){
			$dari = (isset($_GET["dari"])) ? $_GET["dari"] : 0;
			$tujuan = (isset($_GET["tujuan"])) ? $_GET["tujuan"] : 0;
			$berat = (isset($_GET["berat"])) ? $_GET["berat"] : 0;
			$berat = ($berat == 0) ? 1000 : $berat;
			$kurir = (isset($_GET["kurir"])) ? $_GET["kurir"] : "jne";
			if($kurir == "jne"){$srvdefault="REG";}
			//elseif($kurir=="pos"){$srvdefault="Paket Kilat Khusus";}
			elseif($kurir=="tiki"){$srvdefault="REG";}
			else{$srvdefault="";}
			$service = (isset($_GET["service"])) ? $_GET["service"] : $srvdefault;
			
			//COD
			if($kurir == "cod"){
				$hasil = array(
					"success"	=> true,
					"dari"		=> $dari,
					"tujuan"	=> $tujuan,
					"kurir"		=> $kurir,
					"service"	=> $service,
					"harga"		=> 0,
					"update"	=> date("Y-m-d H:i:s"),
					"hargaperkg"=> 0
				);
				echo json_encode($hasil);
				exit;
			}
			
			echo json_encode($this->cekOngkir($dari,$berat,$tujuan,$kurir,$services));
		}
	}
	public function cekOngkir($dari,$berat,$tujuan,$kurir,$services){
			if($kurir == "jne"){$srvdefault="REG";}
			//elseif($kurir=="pos"){$srvdefault="Paket Kilat Khusus";}
			elseif($kurir=="tiki"){$srvdefault="REG";}
			else{$srvdefault="";}
			$service = (isset($services)) ? $services : "";
			
			//COD
			if($kurir == "cod"){
				$biayacod = $this->func->getSetting("biaya_cod");
				$hasil = array(
					"success"	=> true,
					"dari"		=> $dari,
					"tujuan"	=> $tujuan,
					"kurir"		=> "Bayar",
					"service"	=> "Ditempat",
					"harga"		=> $biayacod,
					"update"	=> date("Y-m-d H:i:s"),
					"hargaperkg"=> $biayacod,
					"etd"		=> 1
				);
				//echo json_encode($hasil);
				//exit;
			}
			if($kurir == "toko"){
				$biayakurir = $this->func->getSetting("biaya_kurir");
				$hasil = array(
					"success"	=> true,
					"dari"		=> $dari,
					"tujuan"	=> $tujuan,
					"kurir"		=> "Kurir",
					"service"	=> "Toko",
					"harga"		=> $biayakurir,
					"update"	=> date("Y-m-d H:i:s"),
					"hargaperkg"=> $biayakurir,
					"etd"		=> 1
				);
				//echo json_encode($hasil);
				//exit;
			}
			
			//RAJAONGKIR CONVERT KAB
			$dari = $this->func->getKab($dari,"rajaongkir");
			$datakec = $this->func->getKec($tujuan,"semua");
			$tujuan = $datakec->rajaongkir;

			$usrid = (isset($_SESSION["usrid"])) ? $_SESSION["usrid"] : 0;
			if($datakec->idkab == $dari AND $kurir == "jne"){
				if($_GET["service"] == "REG"){ $service = "CTC"; }
				elseif($_GET["service"] == "YES"){ $service = "CTCYES"; }
			}

			$beratkg = ($berat < 1000) ? 1 : round(intval($berat) / 1000,0,PHP_ROUND_HALF_DOWN);
			if($kurir == "jne"){
				$selisih = $berat - ($beratkg * 1000);
				if($selisih > 300){
					$beratkg = $beratkg + 1;
				}
			}elseif($kurir == "pos"){
				$selisih = $berat - ($beratkg * 1000);
				if($selisih > 200){
					$beratkg = $beratkg + 1;
				}
			}elseif($kurir == "tiki"){
				$selisih = $berat - ($beratkg * 1000);
				if($selisih > 299){
					$beratkg = $beratkg + 1;
				}
			}else{
				$selisih = $berat - ($beratkg * 1000);
				if($selisih > 0){
					$beratkg = $beratkg + 1;
				}
			}
			$beratkg = $beratkg < 1 ? 1 : $beratkg;

			if(isset($hasil)){
				return $hasil;
			}else{
				$this->db->where("dari",$dari);
				$this->db->where("tujuan",$tujuan);
				$this->db->where("kurir",strtolower($kurir));
				//$this->db->where("service",$service);
				//$this->db->limit(1);
				$this->db->order_by("id","DESC");
				$results = $this->db->get("historyongkir");
				if($results->num_rows() > 0){
					foreach($results->result() as $res){
						if($res->harga <= 0){
							$just = true;
							return $this->reqOngkir($dari,$berat,$tujuan,$kurir,$service);
							exit;
						}else{
							if(strcasecmp($service,$res->service) == 0){
								$harga = $res->harga * $beratkg;
								$etd = $res->etd != "" ? $res->etd : "-";
								$array = array(
									"success"	=> true,
									"dari"		=> $res->dari,
									"tujuan"	=> $res->tujuan,
									"kurir"		=> $res->kurir,
									"service"	=> $res->service,
									"etd"		=> $etd,
									"harga"		=> $harga,
									"update"	=> $res->update
								);
								return $array;
								$just = true;
							}
						}
					}
				}else{
					return $this->reqOngkir($dari,$berat,$tujuan,$kurir,$service);
				}
			}
	}
	private function reqOngkir($dari,$berat,$tujuan,$kurir,$services){
			$usrid = (isset($_SESSION["usrid"])) ? $_SESSION["usrid"] : 0;

			$beratkg = round(intval($berat) / 1000,0,PHP_ROUND_HALF_DOWN);
			if($kurir == "jne"){
				$selisih = $berat - ($beratkg * 1000);
				if($selisih > 300){
					$beratkg = $beratkg + 1;
				}
			}elseif($kurir == "pos"){
				$selisih = $berat - ($beratkg * 1000);
				if($selisih > 200){
					$beratkg = $beratkg + 1;
				}
			}elseif($kurir == "tiki"){
				$selisih = $berat - ($beratkg * 1000);
				if($selisih > 299){
					$beratkg = $beratkg + 1;
				}
			}else{
				$selisih = $berat - ($beratkg * 1000);
				if($selisih > 0){
					$beratkg = $beratkg + 1;
				}
			}
			$beratkg = $beratkg < 1 ? 1 : $beratkg;

				$curl = curl_init();
				curl_setopt_array($curl, array(
				  CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => "origin=".$dari."&originType=city&destination=".$tujuan."&destinationType=subdistrict&weight=".$berat."&courier=".$kurir,
				  CURLOPT_HTTPHEADER => array(
					"content-type: application/x-www-form-urlencoded",
					"key: 1cb6ca038ddb281f174dbc4264474df0"
				  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
				  return "cURL Error #:" . $err;
				} else {
					$arr = json_decode($response);
					//print_r($response);
					//exit;
					//print_r($arr->rajaongkir->results[0]->costs[0]->cost[0]->value);
					$hasil = array("success"=>false,"response"=>"daerah tidak terjangkau!","harga"=>0);

					if($arr->rajaongkir->status->code == "200"){
						$hasil = array("success"=>false,"response"=>"daerah tidak terjangkau!","message"=>"service code tidak ada data","harga"=>0,"kurir"=>$kurir,"paket"=>$services,"origin"=>$arr->rajaongkir);
						for($i=0; $i<count($arr->rajaongkir->results[0]->costs); $i++){
							$harga = $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value / $beratkg;
							$service = $arr->rajaongkir->results[0]->costs[$i]->service;
							$etd = $arr->rajaongkir->results[0]->costs[$i]->cost[0]->etd;
							$etd = $etd != "" ? $etd : "-";
							$array = array(
								"dari"		=> $dari,
								"tujuan"	=> $tujuan,
								"kurir"		=> $kurir,
								"service"	=> $service,
								"harga"		=> $harga,
								"etd"		=> $etd,
								"update"	=> date("Y-m-d H:i:s"),
								"usrid"		=> $usrid
							);
							//print_r(json_encode($array)."<p/>");
							$idhistory = $this->func->getHistoryOngkir(array("dari"=>$dari,"tujuan"=>$tujuan,"kurir"=>$kurir,"service"=>$service),"id");
							if($idhistory > 0){
								$this->db->where("id",$idhistory);
								$this->db->update("historyongkir",$array);
							}else{
								if($harga > 0){ $this->db->insert("historyongkir",$array); }
							}

							if($services != ""){
								if(strcasecmp($service,$services) == 0){
									$hasil = array(
										"success"	=> true,
										"dari"		=> $dari,
										"tujuan"	=> $tujuan,
										"kurir"		=> $kurir,
										"service"	=> $service,
										"harga"		=> $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value,
										"etd"		=> $etd,
										"update"	=> date("Y-m-d H:i:s"),
										"hargaperkg"=> $harga
									);
								}else{
									if($kurir == "jne"){
										if($services == "REG"){
											if(strcasecmp($service,"CTC") == 0){
												$hasil = array(
													"success"	=> true,
													"dari"		=> $dari,
													"tujuan"	=> $tujuan,
													"kurir"		=> $kurir,
													"service"	=> $service,
													"harga"		=> $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value,
													"etd"		=> $etd,
													"update"	=> date("Y-m-d H:i:s"),
													"hargaperkg"=> $harga
												);
											}
										}elseif($services == "YES"){
											if(strcasecmp($service,"CTCYES") == 0){
												$hasil = array(
													"success"	=> true,
													"dari"		=> $dari,
													"tujuan"	=> $tujuan,
													"kurir"		=> $kurir,
													"service"	=> $service,
													"harga"		=> $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value,
													"etd"		=> $etd,
													"update"	=> date("Y-m-d H:i:s"),
													"hargaperkg"=> $harga
												);
											}
										}else{
											
										}
									}
								}
							}else{
								$etd = $arr->rajaongkir->results[0]->costs[$i]->cost[0]->etd;
								$etd = $etd != "" ? $etd : "-";
								$hasil = array(
									"success"	=> true,
									"dari"		=> $dari,
									"tujuan"	=> $tujuan,
									"kurir"		=> $kurir,
									"service"	=> $arr->rajaongkir->results[0]->costs[$i]->service,
									"harga"		=> $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value,
									"etd"		=> $etd,
									"update"	=> date("Y-m-d H:i:s"),
									"hargaperkg"=> $harga
								);
							}
						}
					}
					//echo "dari: ".$dari.", tujuan: ".$tujuan.", berat: ".$berat.", kurir: ".$kurir."<br/>&nbsp;<br/>";
					return $hasil;
				}
	}
}
