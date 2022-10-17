<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assync extends CI_Controller {
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
		redirect("404_notfound");
	}
	
	public function newtoken(){
		echo json_encode(["name"=>$this->security->get_csrf_token_name(),"token"=>$this->security->get_csrf_hash()]);
	}
	public function bayarulangpesanan(){
		if(isset($_POST["bayar"])){
			$bayar = $this->func->getBayar($_POST["bayar"],"semua");
			$orderId = $bayar->midtrans_id;
			$status = \Midtrans\Transaction::cancel($orderId);
			
			if($status){
				echo json_encode(["success"=>true]);
			}else{
				echo json_encode(["success"=>false]);
			}
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	function midtrans_webhook(){
		$notif = new \Midtrans\Notification();

		$transaction = $notif->transaction_status;
		$transaction_id = $notif->transaction_id;
		$type = $notif->payment_type;
		$fraud = $notif->fraud_status;
		$order_id = $notif->order_id;
		$bayarid = $this->func->getBayar($notif->order_id,"id","invoice");

		if ($transaction == 'capture') {
			if ($type == 'credit_card'){
				if($fraud == 'challenge'){
					echo "Transaction order_id: " . $order_id ." is challenged by FDS";
				}else{
					$this->suksesTrxMidtrans($bayarid,$transaction_id);
					echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
				}
			}
		}
		else if ($transaction == 'settlement'){
			$this->suksesTrxMidtrans($bayarid,$transaction_id);
			echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
		}
		else if($transaction == 'pending'){
			echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
		}
		else if ($transaction == 'deny') {
			echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
		}
		else if ($transaction == 'expire') {
			echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
		}
		else if ($transaction == 'cancel') {
			echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
		}
	}
	function suksesTrxMidtrans($bayarid,$transaction_id){
		$this->db->where("id",$bayarid);
		$this->db->update("pembayaran",["status"=>1,"tglupdate"=>date("Y-m-d H:i:s"),"midtrans_id"=>$transaction_id]);
			
		$this->db->where("idbayar",$bayarid);
		$this->db->update("transaksi",["status"=>1]);
		
		$this->midtransSukses($bayarid);
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
			"Ongkos Kirim: *Rp ".$this->func->formUang($trx->ongkir)."* \n".$diskonwa.
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
	public function cekmidtrans(){
		if(isset($_GET["bayar"])){
			$bayar = $this->func->getBayar($_GET["bayar"],"semua");
			$orderId = $bayar->midtrans_id;
			$status = \Midtrans\Transaction::status($orderId);
			/*print_r($status);
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
				$cara = ($bayar->midtrans_pdf != "") ? $bayar->midtrans_pdf : "";
				$kode = $status->payment_code;
				$store = $status->store;
			}elseif($status->payment_type == "bank_transfer"){
				if($bayar->midtrans_pdf != ""){
					$cara = "
						<div class='row m-b-10'>
							<div class='col-4'>Petunjuk Pembayaran</div>
							<div class='col-8 text-uppercase font-weight-bold'>: <a href='".$bayar->midtrans_pdf."' target='_blank'><b><i>Lihat Petunjuk &raquo;</i></b></a></div>
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
						<div class='row m-b-10'>
							<div class='col-4'>Petunjuk Pembayaran</div>
							<div class='col-8 text-uppercase font-weight-bold'>: <a href='".$bayar->midtrans_pdf."' target='_blank'><b><i>Lihat Petunjuk &raquo;</i></b></a></div>
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
			
			$sukses = array("success","settlement","capture");
			if(in_array($status->transaction_status,$sukses)){
				echo "
					<div class='row m-b-10'>
						<div class='col-4'>Status</div>
						<div class='col-8 text-uppercase text-success font-weight-bold'>: BERHASIL</div>
					</div>
					<div class='row m-b-10'>
						<div class='col-4'>Metode Pembayaran</div>
						<div class='col-8 text-uppercase font-weight-bold'>: ".$tipe."</div>
					</div>
					<div class='row m-b-10'>
						<div class='col-4'>Merchant</div>
						<div class='col-8 text-uppercase font-weight-bold'>: ".$store."</div>
					</div>
					<div class='row m-b-10'>
						<div class='col-4'>Kode Bayar</div>
						<div class='col-8 text-uppercase font-weight-bold'>: ".$kode."</div>
					</div>
					<div class='row m-b-10'>
						<div class='col-4'>Jumlah Bayar</div>
						<div class='col-8 text-uppercase font-weight-bold'>: Rp. ".$this->func->formUang($status->gross_amount)."</div>
					</div>
				";
			}else{
				echo "
					<div class='row m-b-10'>
						<div class='col-4'>Status</div>
						<div class='col-8 text-uppercase text-danger font-weight-bold'>: ".$status->transaction_status."</div>
					</div>
					<div class='row m-b-10'>
						<div class='col-4'>Metode Pembayaran</div>
						<div class='col-8 text-uppercase font-weight-bold'>: ".$tipe."</div>
					</div>
					<div class='row m-b-10'>
						<div class='col-4'>Merchant</div>
						<div class='col-8 text-uppercase font-weight-bold'>: ".$store."</div>
					</div>
					<div class='row m-b-10'>
						<div class='col-4'>Kode Bayar</div>
						<div class='col-8 text-uppercase font-weight-bold'>: ".$kode."</div>
					</div>
					<div class='row m-b-10'>
						<div class='col-4'>Jumlah Bayar</div>
						<div class='col-8 text-uppercase font-weight-bold'>: Rp. ".$this->func->formUang($status->gross_amount)."</div>
					</div>".
					$cara."
				";
			}
		}else{
			echo "ID Transaksi Pembayaran tidak valid";
		}
	}


	public function automaticSystemUpdateForCronJobs(){
		$date = strtotime(date("Y-m-d H:i:s"));
		$batasbayar = strtotime("-2 day", $date);
		$batasbayar = date("Y-m-d H:i:s", $date);

		// BATALKAN PEMBAYARAN
		$this->db->where("status",0);
		$this->db->where("kadaluarsa <",date("Y-m-d H:i:s"));
		$db = $this->db->get("pembayaran");
		foreach($db->result() as $r){
			$this->db->where("idbayar",$r->id);
			$this->db->update("transaksi",array("status"=>4,"selesai"=>date("Y-m-d H:i:s"),"keterangan"=>"dibatalkan oleh sistem, karena melewati batas waktu pembayaran"));
		}
		$this->db->where("status",0);
		$this->db->where("kadaluarsa <",date("Y-m-d H:i:s"));
		$this->db->update("pembayaran",array("status"=>3,"tglupdate"=>date("Y-m-d H:i:s")));

		// BATALKAN RESPON PENJUAL
		/*	
			$this->db->where()
			$this->db->where("id",$_POST["data"]);
			$this->db->update("transaksi",array("status"=>4,"keterangan"=>"stok habis","selesai"=>date("Y-m-d H:i:s")));

			$trx = $this->func->getTransaksi($_POST["data"],"semua");

			// TOTAL SALDO
			$total = 0;
			$this->db->where("idtransaksi",$_POST["data"]);
			$dbs = $this->db->get("transaksiproduk");
			foreach($dbs->result() as $res){
				$total += $res->jumlah * $res->harga;
			}
			$saldojml = $total + $trx->ongkir;
			$usrid = $trx->usrid;
			$saldoawal = $this->func->getSaldo($usrid,"saldo","usrid");

			// UPDATE SALDO
			$saldo = $saldoawal + $saldojml;
			$this->db->where("usrid",$usrid);
			$this->db->update("saldo",array("saldo"=>$saldo));
			// SALDO DARI KE
			$data = array(
				"tgl"	=> date("Y-m-d H:i:s"),
				"usrid"	=> $usrid,
				"jenis"	=> 1,
				"jumlah"	=> $saldojml,
				"darike"	=> 4,
				"saldoawal"	=> $saldoawal,
				"saldoakhir"	=> $saldo,
				"sambung"	=> $_POST["data"]
			);
			$this->db->insert("saldohistory",$data);
		*/
		echo json_encode(array("success"=>true));
	}

	public function newprod(){ //HOME > PRODUK TERBARU
		$limit = (isset($_GET["display"])) ? intval($_GET["display"]) : 10;
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
		$this->db->limit($limit);
		$this->db->order_by("produk.id","desc");
		$query = $this->db->get();

		foreach($query->result() as $res){
			$kota = explode(" ",$this->func->getKab($res->idkab,"nama"),2);
			$kota = $kota[1];
			$judul = $this->func->potong($res->nama,35,'...');
			$namatoko = $this->func->potong(strtoupper(strtolower($res->namatoko)),20,'...');
			$label = $this->func->getLabelCOD($res->cod);

			echo "
				<div class='kotak'>
					<a href='".site_url('produk/'.$res->url)."' class='kotak-atas'>
						<div class='kotak-img' style='background-image:url(\"".$this->func->getFoto($res->id,'utama')."\")'>
							<!--<img src='".$this->func->getFoto($res->id,'utama')."' />-->
						</div>
						<div class='kotak-detail'>
							<div class='kotak-judul'>".$judul."</div>
							<p class='kotak-harga'> Rp. ".$this->func->formUang($res->harga)."</p>
							".$label."
						</div>
					</a>
					<div class='kotak-toko'>
						<span class='kotak-namatoko'>".$namatoko."</span><br/>
						<span class='kotak-alamat'><i class='fa fa-map-marker'></i> ".$kota."</span>
					</div>
				</div>
			";
		}
	}

	public function pilihanprod(){ // HOME > PRODUK PILIHAN
		$limit = (isset($_GET["display"])) ? intval($_GET["display"]) : 10;
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
		$this->db->limit($limit);
		$this->db->order_by('rand()');
		$query = $this->db->get();

		foreach($query->result() as $res){
			$kota = explode(" ",$this->func->getKab($res->idkab,"nama"),2);
			$kota = $kota[1];
			$judul = $this->func->potong($res->nama,35,'...');
			$namatoko = $this->func->potong(strtoupper(strtolower($res->namatoko)),20,'...');
			$label = $this->func->getLabelCOD($res->cod);

			echo "
				<div class='kotak'>
					<a href='".site_url('produk/'.$res->url)."' class='kotak-atas'>
						<div class='kotak-img' style='background-image:url(\"".$this->func->getFoto($res->id,'utama')."\")'>
							<!--<img src='".$this->func->getFoto($res->id,'utama')."' />-->
						</div>
						<div class='kotak-detail'>
							<div class='kotak-judul'>".$judul."</div>
							<p class='kotak-harga'> Rp. ".$this->func->formUang($res->harga)."</p>
							".$label."
						</div>
					</a>
					<div class='kotak-toko'>
						<span class='kotak-namatoko'>".$namatoko."</span><br/>
						<span class='kotak-alamat'><i class='fa fa-map-marker'></i> ".$kota."</span>
					</div>
				</div>
			";
		}
	}

	function slidepromo(){
		if(isset($_GET["token"])){
			$this->db->where("status",1);
			$this->db->where("(tgl_selesai >= '".date("Y-m-d H:i:s")."' AND tgl <= '".date("Y-m-d H:i:s")."')");
			$this->db->order_by("id","desc");
			$db = $this->db->get("promo");

			if($db->num_rows() > 0){
				echo "
						<ul id='slider' class='slider'>
					";

				foreach($db->result() as $res){
					echo "<li>
							<a href=\"#slide1\"><img class='full' src='".base_url("assets/img/promo/".$res->gambar)."' /></a>
							</li>";
				}

				echo "
						</ul>
					";

				echo "
					<script type='text/javascript'>
						$(function(){
							$('#slider').slippry()
						});
					</script>
				";
			}else{
				//echo "<img class='full' src='".base_url("assets/img/promo/default.jpg")."' />";
			}
		}else{
			redirect("404_notfound");
		}
	}

	function slidegambar(){
		if(isset($_GET["token"])){
			$id = $_GET["token"];
			$this->db->where("idproduk",$id);
			$this->db->order_by("jenis","desc");
			$db = $this->db->get("upload");

			if($db->num_rows() > 0){
				echo "
						<ul id='slider' class='slider'>
					";

				foreach($db->result() as $res){
					echo "<li>
							<a href=\"#slide1\"><img class='full' src='".base_url("assets/img/produk/".$res->nama)."' /></a>
							</li>";
				}

				echo "
						</ul>
					";

				echo "
					<script type='text/javascript'>
						$(function(){
							$('#slider').slippry()
						});
					</script>
				";
			}else{
				echo "<img class='full' src='".base_url("assets/img/produk/default.jpg")."' />";
			}
		}else{
			redirect("404_notfound");
		}
	}

	// BOOSTER
	public function booster(){
		$sebelum = (isset($_SESSION["boostr"])) ? $_SESSION["boostr"] : $this->session->set_userdata("boostr",1);
		$data = ["success"=>false,"token"=> $this->security->get_csrf_hash()];

		$this->db->order_by("RAND()");
		$this->db->limit(1);
		if($sebelum == 1){
			$db = $this->db->get("transaksiproduk");
			foreach($db->result() as $r){
				$nama = $this->func->getProduk($r->idproduk,"nama");
				if($nama != null){
					$data = array(
						"success"=> true,
						"foto"	=> $this->func->getFoto($r->idproduk),
						"user"	=> $this->func->getProfil($r->usrid,"nama","usrid"),
						"produk"=> $nama,
						"token"	=> $this->security->get_csrf_hash()
					);
				}
			}
			$_SESSION["boostr"] == 2;
		}else{
			$db = $this->db->get("booster");
			foreach($db->result() as $r){
				$nama = $this->func->getProduk($r->idproduk,"nama");
				if($nama != null){
					$data = array(
						"success"=> true,
						"foto"	=> $this->func->getFoto($r->idproduk),
						"user"	=> $this->func->getProfil($r->usrid,"nama","usrid"),
						"produk"=> $this->func->getProduk($r->idproduk,"nama"),
						"token"	=> $this->security->get_csrf_hash()
					);
				}
			}
			$_SESSION["boostr"] == 1;
		}
		echo json_encode($data);
	}
	
	// CEK VOUCHER
	public function cekvoucher(){
		if(isset($_POST["kode"])){
			$voc = $this->func->getVoucher($_POST["kode"],"semua","kode");
			
			if(is_object($voc)){
				$tgla = $this->func->ubahTgl("Ymd",$voc->mulai);
				$tglb = $this->func->ubahTgl("Ymd",$voc->selesai);
				if($tgla <= date("Ymd") AND $tglb >= date("Ymd")){
					$harga = intval($_POST["harga"]);
					$ongkir = intval($_POST["ongkir"]);
					if($voc->jenis == 1){
						if($voc->tipe == 2){
							$diskon = ($harga >= $voc->potonganmin) ? $voc->potongan : 0;
						}else{
							$diskon = ($harga >= $voc->potonganmin) ? $harga * ($voc->potongan/100) : 0;
						}
					}elseif($voc->jenis == 2){
						if($voc->tipe == 2){
							$diskon = ($harga >= $voc->potonganmin) ? $voc->potongan : 0;
						}else{
							$diskon = ($harga >= $voc->potonganmin) ? $ongkir * ($voc->potongan/100) : 0;
						}
						//echo $diskon."---".$ongkir;
					}else{
						$diskon = 0;
					}
					if($voc->potonganmaks != 0){
						$diskon = ($diskon < $voc->potonganmaks) ? $diskon : $voc->potonganmaks;
					}
					echo json_encode(["success"=>true,"diskon"=>$diskon,"token"=>$this->security->get_csrf_hash()]);
				}else{
					echo json_encode(["success"=>false,"token"=>$this->security->get_csrf_hash()]);
				}
			}else{
				echo json_encode(["success"=>false,"token"=>$this->security->get_csrf_hash()]);
			}
		}else{
			echo json_encode(["success"=>false,"token"=>$this->security->get_csrf_hash()]);
		}
	}

	// MANAJEMEN TOKO
	public function daftarProduk($idtoko=0,$onboard="none"){
		$idtoko = ($idtoko!=0) ? $idtoko : $_SESSION["idtoko"];
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;

		$this->db->where("idtoko",$idtoko);
		$rows = $this->db->get("produk");
		$rows = $rows->num_rows();

		$this->db->from('produk');
		$this->db->where("idtoko",$idtoko);
		$this->db->order_by($orderby,"desc");
		$this->db->limit($perpage,($page-1)*$perpage);
		$injek["produk"] = $this->db->get();
		$injek["rows"] = $rows;
		$injek["perpage"] = $perpage;
		$injek["page"] = $page;
		$injek["onb"] = $onboard;
		$injek["kotak4"] = $onboard == "onboard" ? " kotak4" : "";

		/*
		print_r($injek["produk"]);
		*/

		//$this->load->view("head");
		$this->load->view("admin/daftarprodukassync",$injek);
		//$this->load->view("foot");
	}
	public function penjualan(){
		if(isset($_GET["status"])){
			$injek["page"] = (isset($_GET["page"])) ? $_GET["page"] : 1;
			if($_GET["status"] == "konfirm"){
				$this->load->view("admin/penjualankonfirm",$injek);
			}elseif($_GET["status"] == "dikemas"){
				$this->load->view("admin/penjualandikemas",$injek);
			}elseif($_GET["status"] == "dikirim"){
				$this->load->view("admin/penjualandikirim",$injek);
			}elseif($_GET["status"] == "selesai"){
				$this->load->view("admin/penjualanselesai",$injek);
			}elseif($_GET["status"] == "batal"){
				$this->load->view("admin/penjualanbatal",$injek);
			}else{
				redirect("404_notfound");
			}
		}else{
			redirect("404_notfound");
		}
	}
	public function accPenjualan(){
		if(isset($_GET["p"])){
			if($_GET["p"] == "terima"){
				if(isset($_POST["data"]) AND $_POST["data"] > 0){
					$this->db->where("id",$_POST["data"]);
					$this->db->update("transaksi",array("status"=>2,"kadaluarsa"=>date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s"). ' + 2 days'))));
					echo json_encode(array("success"=>true));
				}else{
					echo json_encode(array("success"=>false,"msg"=>"tidak ada produk yang dipilih,
					apabila kendala ini terjadi berulang silahkan hubungi customer service kami"));
				}
			}elseif($_GET["p"] == "tolak"){
				if(isset($_POST["data"]) AND $_POST["data"] > 0){
					$this->db->where("id",$_POST["data"]);
					$this->db->update("transaksi",array("status"=>4,"keterangan"=>"stok habis","selesai"=>date("Y-m-d H:i:s")));

					$trx = $this->func->getTransaksi($_POST["data"],"semua");

					// TOTAL SALDO
					$total = 0;
					$this->db->where("idtransaksi",$_POST["data"]);
					$dbs = $this->db->get("transaksiproduk");
					foreach($dbs->result() as $res){
						$total += $res->jumlah * $res->harga;
					}
					$saldojml = $total + $trx->ongkir;
					$usrid = $trx->usrid;
					$saldoawal = $this->func->getSaldo($usrid,"saldo","usrid");

					// UPDATE SALDO
					$saldo = $saldoawal + $saldojml;
					$this->db->where("usrid",$usrid);
					$this->db->update("saldo",array("saldo"=>$saldo));
					// SALDO DARI KE
					$data = array(
						"tgl"	=> date("Y-m-d H:i:s"),
						"usrid"	=> $usrid,
						"jenis"	=> 1,
						"jumlah"	=> $saldojml,
						"darike"	=> 4,
						"saldoawal"	=> $saldoawal,
						"saldoakhir"	=> $saldo,
						"sambung"	=> $_POST["data"]
					);
					$this->db->insert("saldohistory",$data);

					echo json_encode(array("success"=>true));
				}else{
					echo json_encode(array("success"=>false,"msg"=>"tidak ada produk yang dipilih,
					apabila kendala ini terjadi berulang silahkan hubungi customer service kami"));
				}
			}else{
				redirect("404_notfound");
			}
		}else{
			redirect("404_notfound");
		}
	}
	public function accPembatalan(){
		if(isset($_GET["p"])){
			if($_GET["p"] == "tolak"){
				if(isset($_POST["data"]) AND $_POST["data"] > 0){
					$this->db->where("id",$_POST["data"]);
					$this->db->update("transaksi",array("ajukanbatal"=>2));
					echo json_encode(array("success"=>true));
				}else{
					echo json_encode(array("success"=>false,"msg"=>"tidak ada produk yang dipilih,
					apabila kendala ini terjadi berulang silahkan hubungi customer service kami"));
				}
			}elseif($_GET["p"] == "terima"){
				if(isset($_POST["data"]) AND $_POST["data"] > 0){
					$this->db->where("id",$_POST["data"]);
					$this->db->update("transaksi",array("status"=>4,"keterangan"=>"dibatalkan oleh pembeli","selesai"=>date("Y-m-d H:i:s")));

					$trx = $this->func->getTransaksi($_POST["data"],"semua");

					// TOTAL SALDO
					$total = 0;
					$this->db->where("idtransaksi",$_POST["data"]);
					$dbs = $this->db->get("transaksiproduk");
					foreach($dbs->result() as $res){
						$total += $res->jumlah * $res->harga;
					}
					$saldojml = $total + $trx->ongkir;
					$usrid = $trx->usrid;
					$saldoawal = $this->func->getSaldo($usrid,"saldo","usrid");

					// UPDATE SALDO
					$saldo = $saldoawal + $saldojml;
					$this->db->where("usrid",$usrid);
					$this->db->update("saldo",array("saldo"=>$saldo));
					// SALDO DARI KE
					$data = array(
						"tgl"	=> date("Y-m-d H:i:s"),
						"usrid"	=> $usrid,
						"jenis"	=> 1,
						"jumlah"	=> $saldojml,
						"darike"	=> 4,
						"saldoawal"	=> $saldoawal,
						"saldoakhir"	=> $saldo,
						"sambung"	=> $_POST["data"]
					);
					$this->db->insert("saldohistory",$data);

					echo json_encode(array("success"=>true));
				}else{
					echo json_encode(array("success"=>false,"msg"=>"tidak ada produk yang dipilih,
					apabila kendala ini terjadi berulang silahkan hubungi customer service kami"));
				}
			}else{
				redirect("404_notfound");
			}
		}else{
			redirect("404_notfound");
		}
	}

	public function upload(){
		if($_POST){

		}else{
			redirect("404_notfound");
		}
	}

	// MANAJEMEN SALDO
	function getHistoryTarik(){
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;

		$this->db->where("usrid",$_SESSION["usrid"]);
		$rows = $this->db->get("saldohistory");
		$rows = $rows->num_rows();

		$this->db->from('saldohistory');
		$this->db->where("usrid",$_SESSION["usrid"]);
		$this->db->order_by($orderby,"desc");
		$this->db->limit($perpage,($page-1)*$perpage);
		$injek["saldo"] = $this->db->get();
		$injek["rows"] = $rows;
		$injek["perpage"] = $perpage;
		$injek["page"] = $page;

		/*
		print_r($injek["produk"]);
		*/

		//$this->load->view("head");
		$this->load->view("admin/historysaldo",$injek);
	}
	function getHistoryTopup(){
		$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
		$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
		$perpage = 10;

		$this->db->where("usrid",$_SESSION["usrid"]);
		$this->db->where("jenis",2);
		$rows = $this->db->get("saldotarik");
		$rows = $rows->num_rows();

		$this->db->from('saldotarik');
		$this->db->where("usrid",$_SESSION["usrid"]);
		$this->db->where("jenis",2);
		$this->db->order_by($orderby,"desc");
		$this->db->limit($perpage,($page-1)*$perpage);
		$injek["saldo"] = $this->db->get();
		$injek["rows"] = $rows;
		$injek["perpage"] = $perpage;
		$injek["page"] = $page;

		/*
		print_r($injek["produk"]);
		*/

		//$this->load->view("head");
		$this->load->view("admin/topupsaldo",$injek);
	}

	// BELI PRODUK
	function beliproduk(){
		if(isset($_POST["token"])){
			if(isset($_SESSION["usrid"])){
				$id = $this->func->decode($_POST["token"]);
				$push["id"] = $id;
				$this->db->where("id",$id);
				$db = $this->db->get("produk");
				$idtoko = 0;
				foreach($db->result() as $res){
					$push["nama"] = $res->nama;
					$push["berat"] = $res->berat;
					$push["harga"] = $res->harga;
					$push["idtoko"] = $res->idtoko;
					$idtoko = $res->idtoko;
				}

				/*$this->db->where("usrid",$_SESSION["usrid"]);
				$alamat = $this->db->get("alamat");
				$push["alamat"] = $alamat;
				$this->db->order_by("nama","asc");
				$push["provinsi"] = $this->db->get("prov");*/
				$idkab = $this->func->getToko($idtoko,"idkab");
				$push["kotatoko"] = $this->func->getKab($idkab,"rajaongkir");
			}else{
				$push = array();
			}

			$this->load->view("main/beli",$push);
		}else{
			redirect("404_notfound");
		}
	}
	function prosesbeli(){
		if($_POST AND isset($_SESSION["usrid"])){
			$prod = $this->func->getProduk($_POST["idproduk"],"semua");
			$level = isset($_SESSION["lvl"]) ? $_SESSION["lvl"] : 0;
			$keterangan = (isset($_POST["keterangan"])) ? $_POST["keterangan"] : "";
			$variasi = (isset($_POST["variasi"])) ? $_POST["variasi"] : 0;

			if($variasi > 0){
				$var = $this->func->getVariasi($variasi,"semua");
				if(intval($_POST["jumlah"]) > $var->stok){
					echo json_encode(array("success"=>false,"msg"=>"Stok tidak mencukupi, stok tersedia hanya ".$var->stok." pcs"));
					exit;
				}
			}else{
				if(intval($_POST["jumlah"]) > $prod->stok){
					echo json_encode(array("success"=>false,"msg"=>"Stok tidak mencukupi, stok tersedia hanya ".$prod->stok." pcs"));
					exit;
				}
			}

			$data = array(
				"usrid"		=> $_SESSION["usrid"],
				"idproduk"	=> $_POST["idproduk"],
				"tgl"		=> date("Y-m-d H:i:s"),
				"jumlah"	=> $_POST["jumlah"],
				"harga"		=> $_POST["harga"],
				"keterangan"=> $keterangan,
				"variasi"	=> $variasi,
				"idtransaksi"	=> 0
			);
			$total = intval($_POST["jumlah"]) * intval($_POST["harga"]);
			if($this->db->insert("transaksiproduk",$data)){
				echo json_encode(array("success"=>true,"total"=>$total));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"terjadi kendala teknis, coba ulangi beberapa saat lagi"));
			}
		}else{
			redirect("404_notfound");
		}
	}
	function hapuskeranjang(){
		if(isset($_POST["hapus"]) AND $_POST["hapus"] > 0){
			$id = $_POST["hapus"];

			$this->db->where("id",$id);
			$this->db->delete("transaksiproduk");

			echo json_encode(array("success"=>true));
		}else{
			echo json_encode(array("success"=>false));
		}
	}
	function updatekeranjang(){
		if(isset($_POST["update"]) AND $_POST["update"] > 0){
			$id = $_POST["update"];
			unset($_POST["update"]);

			$trx = $this->func->getTransaksiProduk($id,"semua");
			$stok = ($trx->variasi > 0) ? $this->func->getVariasi($trx->variasi,"stok") : $this->func->getProduk($trx->idproduk,"stok");
			if($stok >= intval($_POST["jumlah"])){
				$this->db->where("id",$id);
				$this->db->update("transaksiproduk",$_POST);

				echo json_encode(array("success"=>true,"token"=>$this->security->get_csrf_hash()));
			}else{
				$this->db->where("id",$id);
				$this->db->update("transaksiproduk",["jumlah"=>$stok]);

				echo json_encode(array("success"=>false,"msg"=>"Stok produk tidak mencukupi, maksimal pemesanan ".$stok."pcs","token"=>$this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Produk tidak tersedia","token"=>$this->security->get_csrf_hash()));
		}
	}
	function bayarpesanan(){
		if(isset($_POST["total"]) AND isset($_SESSION["usrid"])){
			$idbayar = 0;
			//$token = $this->func->arrEnc($_POST["token"],"decode");
			$kodebayaran = rand(100,999);
			$kodebayar = $kodebayaran;
			$_POST["total"] = intval($_POST["total"]) - intval($_POST["diskon"]);
			$transfer = intval($_POST["total"]) - intval($_POST["saldo"]);
			if($transfer > 0){
				$total = $kodebayaran + intval($_POST["total"]);
			}else{
				$total = $_POST["total"];
				$kodebayar = 0;
			}

			$status = 0;
			$seli = intval($_POST["saldo"])-intval($_POST["total"]);
			$status = $seli >= 0 ? 1 : 0;
			$status = ($_POST["kurir"] == "cod") ? 1 : $status;

				if($_POST["alamat"] == "0"){
					$this->db->where("usrid",$_SESSION["usrid"]);
					$statusal = ($this->db->get("alamat")->num_rows() > 0) ? 0 : 1;
					$alamat = array(
						"usrid"		=> $_SESSION["usrid"],
						"status"	=> $statusal,
						"idkec"		=> $_POST["idkec"],
						"judul"		=> $_POST["judul"],
						"alamat"	=> $_POST["alamatbaru"],
						"kodepos"	=> $_POST["kodepos"],
						"nama"		=> $_POST["nama"],
						"nohp"		=> $_POST["nohp"]
					);
					$this->db->insert("alamat",$alamat);
					$idalamat = $this->db->insert_id();
				}else{
					$idalamat = $_POST["alamat"];
				}

				$voucher = $this->func->getVoucher($_POST["kodevoucher"],"id","kode");
				$bayar = array(
					"usrid"	=> $_SESSION["usrid"],
					"tgl"	=> date("Y-m-d H:i:s"),
					"total"	=> $total,
					"saldo"	=> $_POST["saldo"],
					"kodebayar"	=> $kodebayar,
					"transfer"	=> $transfer,
					"voucher"	=> $voucher,
					"metode"	=> $_POST["metode"],
					"diskon"	=> $_POST["diskon"],
					"status"	=> $status,
					"kadaluarsa"	=> date('Y-m-d H:i:s', strtotime("+2 days"))
				);
				$this->db->insert("pembayaran",$bayar);
				$idbayar = $this->db->insert_id();

				if($_POST["metode"] == 2){
					$saldoawal = $this->func->getSaldo($_SESSION["usrid"],"saldo","usrid");
					$saldoakhir = $saldoawal - intval($_POST["saldo"]);
					$this->db->where("usrid",$_SESSION["usrid"]);
					$this->db->update("saldo",array("saldo"=>$saldoakhir,"apdet"=>date("Y-m-d H:i:s")));

					$sh = array(
						"tgl"	=> date("Y-m-d H:i:s"),
						"usrid"	=> $_SESSION["usrid"],
						"jenis"	=> 2,
						"jumlah"	=> $_POST["saldo"],
						"darike"	=> 3,
						"sambung"	=> $idbayar,
						"saldoawal"	=> $saldoawal,
						"saldoakhir"	=> $saldoakhir
					);
					$this->db->insert("saldohistory",$sh);
				}

				$invoice = date("Ymd").$idbayar.$kodebayaran;
				$this->db->where("id",$idbayar);
				$this->db->update("pembayaran",array("invoice"=>$invoice));
				$invoice = "#".$invoice;

				$transaksi = array(
					"orderid"	=> "TRX".date("YmdHis"),
					"tgl"	=> date("Y-m-d H:i:s"),
					"kadaluarsa"	=> date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s"). ' + 2 days')),
					"usrid"	=> $_SESSION["usrid"],
					"alamat"	=> $idalamat,
					"berat"	=> $_POST["berat"],
					"ongkir"	=> $_POST["ongkir"],
					"kurir"	=> $_POST["kurir"],
					"paket"	=> $_POST["paket"],
					"dari"	=> $_POST["dari"],
					"tujuan"	=> $_POST["tujuan"],
					"status"	=> $status,
					"idbayar"	=> $idbayar
				);
				if($_POST["dropship"] != ""){
					$transaksi["dropship"] = $_POST["dropship"];
					$transaksi["dropshipnomer"] = $_POST["dropshipnomer"];
					$transaksi["dropshipalamat"] = $_POST["dropshipalamat"];
				}
				$this->db->insert("transaksi",$transaksi);
				$idtransaksi = $this->db->insert_id();

				for($i=0; $i<count($_POST["idproduk"]); $i++){
					$this->db->where("id",$_POST["idproduk"][$i]);
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
								"usrid"	=> $_SESSION["usrid"],
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
							"usrid"	=> $_SESSION["usrid"],
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
				//$idbayar = $this->func->arrEnc(array("idbayar"=>$idbayar),"encode");

				$usrid = $this->func->getUser($_SESSION["usrid"],"semua");
				$profil = $this->func->getProfil($_SESSION["usrid"],"semua","usrid");
				$alamat = $this->func->getAlamat($idalamat,"semua");
				$toko = $this->func->getSetting("semua");
				$diskon = $_POST["diskon"] != 0 ? "Diskon: <b>Rp ".$this->func->formUang(intval($_POST["diskon"]))."</b><br/>" : "";
				$diskonwa = $_POST["diskon"] != 0 ? "Diskon: *Rp ".$this->func->formUang(intval($_POST["diskon"]))."*\n" : "";
				$pesan = "
					Halo <b>".$profil->nama."</b><br/>".
					"Terimakasih sudah membeli produk kami.<br/>".
					"Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu <br/>".
					"No Invoice: <b>".$invoice."</b><br/> <br/>".
					"Total Pesanan: <b>Rp ".$this->func->formUang($total)."</b><br/>".
					"Ongkos Kirim: <b>Rp ".$this->func->formUang(intval($_POST["ongkir"]))."</b><br/>".$diskon.
					"Kurir Pengiriman: <b>".strtoupper($_POST["kurir"]." ".$_POST["paket"])."</b><br/> <br/>".
					"Detail Pengiriman <br/>".
					"Penerima: <b>".$alamat->nama."</b> <br/>".
					"No HP: <b>".$alamat->nohp."</b> <br/>".
					"Alamat: <b>".$alamat->alamat."</b>".
					"<br/> <br/>".
					"Untuk pembayaran silahkan langsung klik link berikut:<br/>".
					"<a href='".site_url("home/invoice")."?inv=".$idbayar."'>Bayar Pesanan Sekarang &raquo;</a>
				";
				$this->func->sendEmail($usrid->username,$toko->nama." - Pesanan",$pesan,"Pesanan");
				$pesan = "
					Halo *".$profil->nama."*\n".
					"Terimakasih sudah membeli produk kami.\n".
					"Saat ini kami sedang menunggu pembayaran darimu sebelum kami memprosesnya. Sebagai informasi, berikut detail pesananmu \n \n".
					"No Invoice: *".$invoice."*\n".
					"Total Pesanan: *Rp ".$this->func->formUang($total)."*\n".
					"Ongkos Kirim: *Rp ".$this->func->formUang(intval($_POST["ongkir"]))."*\n".$diskonwa.
					"Kurir Pengiriman: *".strtoupper($_POST["kurir"]." ".$_POST["paket"])."*\n \n".
					"Detail Pengiriman \n".
					"Penerima: *".$alamat->nama."*\n".
					"No HP: *".$alamat->nohp."*\n".
					"Alamat: *".$alamat->alamat."*\n \n".
					"Untuk pembayaran silahkan langsung klik link berikut\n".site_url("home/invoice")."?inv=".$idbayar."
				";
				$this->func->sendWA($profil->nohp,$pesan);
				$pesan = "
					<h3>Pesanan Baru</h3><br/>
					<b>".strtoupper(strtolower($profil->nama))."</b> telah membuat pesanan baru dengan total pembayaran 
					<b>Rp. ".$this->func->formUang($total)."</b> Invoice ID: <b>".$invoice."</b>
					<br/>&nbsp;<br/>&nbsp;<br/>
					Cek Pesanan Pembeli di <b>Dashboard Admin ".$toko->nama."</b><br/>
					<a href='".site_url("cdn")."'>Klik Disini</a>
				";
				$this->func->sendEmail($toko->email,$toko->nama." - Pesanan Baru",$pesan,"Pesanan Baru di ".$toko->nama);
				$pesan = "
					*Pesanan Baru*\n".
					"*".strtoupper(strtolower($profil->nama))."* telah membuat pesanan baru dengan detail:\n".
					"Total Pembayaran: *Rp. ".$this->func->formUang($total)."*\n".
					"Invoice ID: *".$invoice."*".
					"\n \n".
					"Cek Pesanan Pembeli di *Dashboard Admin ".$toko->nama."*
					"; 
				$this->func->sendWA($toko->wasap,$pesan);

				$url = $status == 0 ? site_url("home/invoice")."?inv=".$idbayar : site_url("manage/pesanan");
				/*if($transfer > 0){
					if($this->getipaymu($invoice,$transfer)){
						echo json_encode(array("success"=>true,"url"=>site_url("home/invoice")."?inv=".$idbayar));
					}else{
						echo json_encode(array("success"=>false,"message"=>"terjadi kesalahan saat membuat link pmebayaran"));
					}
				}else{*/
					echo json_encode(array("success"=>true,"url"=>$url));
			/*	}
			}else{
				echo json_encode(array("success"=>false,"idbayar"=>0));
			}*/

		}else{
			echo json_encode(array("success"=>false,"message"=>"forbidden"));
		}
	}
	function bayaripaymu($order_id){
		$set = $this->func->getSetting("semua");
		$bayar = $this->func->getBayar($order_id,"semua");
		$total = $bayar->transfer + $bayar->kodebayar;

		if((!empty($bayar) AND $bayar->ipaymu_link == "") OR (!empty($bayar) AND $bayar->ipaymu_link != "" AND $bayar->ipaymu_tipe != "")){
			$url = 'https://my.ipaymu.com/payment';
			$url = $set->ipaymu_url != "" ? $set->ipaymu_url : $url;
			$set = $this->func->getSetting("semua");
			$mobile = (isset($_GET["mobile"])) ? "&mobile=true" : "";
			$profil = $this->func->getProfil($bayar->usrid,"semua","usrid");
			$params = array(
				'key'      => $set->ipaymu, // API Key Merchant / Penjual
				'action'   => 'payment',
				'product'  => 'Order : #' . $bayar->invoice,
				'price'    => $total, // Total Harga
				'quantity' => 1,
				'reference_id' => $order_id,
				'comments' => 'Pembayaran pesanan di '.$set->nama, // Optional           
				'ureturn'  => site_url("home/ipaymustatus").'?id_order='.$bayar->invoice.$mobile,
				'unotify'  => site_url("home/ipaymustatus").'?id_order='.$bayar->invoice.'&params=notify',
				'ucancel'  => site_url("home/ipaymustatus").'?id_order='.$bayar->invoice.'&params=cancel'.$mobile,
				'buyer_name' => $profil->nama,
				'buyer_phone' => $profil->nohp,
				'buyer_email' => $this->func->getUser($bayar->usrid,"username"),
				'format'   => 'json' // Format: xml / json. Default: xml 
			);
			$params_string = http_build_query($params);

			//open connection
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, count($params));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			//execute post
			$request = curl_exec($ch);

			if ( $request === false ) {
				echo curl_error($ch);
			} else {
				
				$result = json_decode($request, true);
				if( isset($result['url']) ){
					$this->db->where("id",$order_id);
					$this->db->update("pembayaran",["ipaymu"=>$result['sessionID'],"ipaymu_link"=>$result['url']]);
					
					redirect($result['url'], 'refresh');
				} else {
					redirect("home/invoice?inv=".$this->func->arrEnc(array("idbayar"=>$order_id),"encode"));
					//$results = false;
					//echo "Request Error ". $result['Status'] .": ". $result['Keterangan'];
				}
			}

			//close connection
			curl_close($ch);
		}else if(!empty($bayar) AND $bayar->ipaymu_link != "" AND $bayar->ipaymu_tipe == ""){
			redirect($bayar->ipaymu_link, 'refresh');
			//echo "link lama";
		}else{
			redirect("home/invoice?inv=".$this->func->arrEnc(array("idbayar"=>$order_id),"encode"));
		}
	}

	// WISHLIST
	function tambahwishlist($idproduk){
		if(isset($_SESSION["usrid"])){
			$this->db->where("idproduk",$idproduk);
			$this->db->where("usrid",$_SESSION["usrid"]);
			$row = $this->db->get("wishlist");

			if($row->num_rows() == 0){
				$data = array(
					"usrid"	=> $_SESSION["usrid"],
					"idproduk"	=> $idproduk,
					"tgl"	=> date("Y-m-d H:i:s"),
					"status"=> 1
				);
				$this->db->insert("wishlist",$data);

				echo json_encode(array("success"=>true,"msg"=>"berhasil ditambahkan ke wishlist","token"=> $this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"produk sudah ada dalam wishlist","token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"silahkan login terlebih dahulu untuk menyimpan produk kedalam wishlist","token"=> $this->security->get_csrf_hash()));
		}
	}
	function hapuswishlist(){
		if(isset($_POST["id"])){
			$idproduk = $_POST["id"];
			$this->db->where("idproduk",$idproduk);
			$this->db->where("usrid",$_SESSION["usrid"]);
			$this->db->delete("wishlist");

			echo json_encode(array("success"=>true,"msg"=>"berhasil dihapus dari wishlist","token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"msg"=>"gagal menghapus produk dari wishlist","token"=> $this->security->get_csrf_hash()));
		}
	}

	// TOPUP SALDO
	function topupsaldo(){
		if(isset($_POST)){
			$idbayar = $_SESSION["usrid"].date("YmdHis");
			$data = array(
				"status"=> 0,
				"jenis"	=> 2,
				"usrid"	=> $_SESSION["usrid"],
				"total"	=> $_POST["jumlah"],
				"tgl"	=> date("Y-m-d H:i:s"),
				"trxid"	=> $idbayar
			);
			$this->db->insert("saldotarik",$data);

			$idbayar = $this->func->arrEnc(array("trxid"=>$idbayar),"encode");
			echo json_encode(array("success"=>true,"idbayar"=>$idbayar,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"message"=>"forbidden","token"=> $this->security->get_csrf_hash()));
		}
	}
	function bataltopup(){
		if(isset($_POST["id"])){
			$this->db->where("id",$_POST["id"]);
			$this->db->update("saldotarik",["selesai"=>date("Y-m-d H:i:s"),"status"=>2]);

			echo json_encode(array("success"=>true));
		}else{
			echo json_encode(array("success"=>false,"message"=>"forbidden"));
		}
	}
	function topupipaymu($order_id){
		$set = $this->func->getSetting("semua");
		$bayar = $this->func->getSaldotarik($order_id,"semua");
		$total = $bayar->total;

		if((!empty($bayar) AND $bayar->ipaymu_link == "") OR (!empty($bayar) AND $bayar->ipaymu_link != "" AND $bayar->ipaymu_tipe != "")){
			$url = 'https://my.ipaymu.com/payment';
			$url = $set->ipaymu_url != "" ? $set->ipaymu_url : $url;
			$set = $this->func->getSetting("semua");
			$mobile = (isset($_GET["mobile"])) ? "&mobile=true" : "";
			$profil = $this->func->getProfil($bayar->usrid,"semua","usrid");
			$params = array(
				'key'      => $set->ipaymu, // API Key Merchant / Penjual
				'action'   => 'payment',
				'product'  => 'Order : #' . $bayar->trxid,
				'price'    => $total, // Total Harga
				'quantity' => 1,
				'reference_id' => $order_id,
				'comments' => 'Pembayaran topup saldo di '.$set->nama, // Optional           
				'ureturn'  => site_url("home/ipaymustatustopup").'?id_order='.$bayar->trxid.$mobile,
				'unotify'  => site_url("home/ipaymustatustopup").'?id_order='.$bayar->trxid.'&params=notify',
				'ucancel'  => site_url("home/ipaymustatustopup").'?id_order='.$bayar->trxid.'&params=cancel'.$mobile,
				'buyer_name' => $profil->nama,
				'buyer_phone' => $profil->nohp,
				'buyer_email' => $this->func->getUser($bayar->usrid,"username"),
				'format'   => 'json' // Format: xml / json. Default: xml 
			);
			$params_string = http_build_query($params);

			//open connection
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, count($params));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			//execute post
			$request = curl_exec($ch);

			if ( $request === false ) {
				echo curl_error($ch);
			} else {
				
				$result = json_decode($request, true);
				if( isset($result['url']) ){
					$this->db->where("id",$order_id);
					$this->db->update("saldotarik",["ipaymu"=>$result['sessionID'],"ipaymu_link"=>$result['url']]);
					
					redirect($result['url'], 'refresh');
				} else {
					redirect("home/topupsaldo?inv=".$this->func->arrEnc(array("trxid"=>$bayar->trxid),"encode"));
					//$results = false;
					//echo "Request Error ". $result['Status'] .": ". $result['Keterangan'];
				}
			}

			//close connection
			curl_close($ch);
		}else if(!empty($bayar) AND $bayar->ipaymu_link != "" AND $bayar->ipaymu_tipe == ""){
			redirect($bayar->ipaymu_link, 'refresh');
			//echo "link lama";
		}else{
			redirect("home/topupsaldo?inv=".$this->func->arrEnc(array("trxid"=>$bayar->trxid),"encode"));
		}
	}
	
	// PRE ORDER
	function prosespreorder(){
		if(isset($_POST["idproduk"])){
			$total = (intval($_POST["harga"])*0.5)*intval($_POST["jumlah"]);
			$data = array(
				"tgl"	=> date("Y-m-d H:i:s"),
				"usrid"	=> $_SESSION["usrid"],
				"idproduk"	=> $_POST["idproduk"],
				"jumlah"	=> $_POST["jumlah"],
				"variasi"	=> $_POST["variasi"],
				"harga"	=> $_POST["harga"],
				"total"	=> $total,
				"transfer"	=> (intval($_POST["harga"])*0.5)*intval($_POST["jumlah"]),
				"kodebayar"	=> rand(100,999),
				"status"	=> 0,
				"invoice"	=> "PO".date("YmdHis")
			);
			$this->db->insert("preorder",$data);
			$idbayar = $this->db->insert_id();
				
			// UPDATE STOK PRODUK
			$this->db->where("id",$idbayar);
			$db = $this->db->get("preorder");
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
							"usrid"	=> $_SESSION["usrid"],
							"stokawal" => $stokawal[$i],
							"stokakhir" => $stock[$i],
							"variasi" => $variasi[$i],
							"jumlah" => $jml[$i],
							"tgl"	=> date("Y-m-d H:i:s"),
							"idtransaksi" => $idbayar
						);
						$this->db->insert("historystok",$data);
					}
				}
			}

			$idbayar = $this->func->arrEnc(array("idbayar"=>$idbayar),"encode");
			echo json_encode(array("success"=>true,"inv"=>$idbayar,"total"=>$total));
		}else{
			echo json_encode(array("success"=>false,"message"=>"forbidden"));
		}
	}

	// REKENING
	public function getrekeningdrop($id=1,$drop="all"){
		if($id != null){
			$this->db->where("usrid",$id);
			$db = $this->db->get("rekening");
			$asal = $id > 0 ? "asal" : "tujuan";
			$asal = $drop != "all" ? $drop : $asal;

			echo "<option value=''>Rekening ".$asal."</option>";
			foreach($db->result() as $res){
				echo "<option value='".$res->id."'>BANK ".$this->func->getBank($res->idbank,"nama")." - ".$res->norek." a/n ".$res->atasnama."</option>";
			}
			echo "<option value='tambah'>+ Tambah Baru</option>";
		}else{
			echo "<option value=''>ERROR</option>";
		}
	}
	public function tambahrekening(){
		if(isset($_POST["idbank"])){
			$data = array(
				"usrid"		=> $_SESSION["usrid"],
				"idbank"	=> $_POST["idbank"],
				"atasnama"	=> $_POST["atasnama"],
				"norek"		=> $_POST["norek"],
				"kcp"		=> $_POST["kcp"],
				"tgl"		=> date("Y-m-d H:i:s")
			);

			if(isset($_POST["id"]) AND $_POST["id"] > 0){
				$this->db->where("id",$_POST["id"]);
				$this->db->update("rekening",$data);
			}else{
				$this->db->insert("rekening",$data);
			}

			echo json_encode(array("success"=>true,"id"=>$this->db->insert_id(),"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}
	public function getrekening(){
		if(isset($_POST["rek"])){
			$rek = $this->func->getRekening($_POST["rek"],"semua");
			if(count((array)$rek) > 0){
				echo json_encode(array("success"=>true,"id"=>$rek->id,"idbank"=>$rek->idbank,"norek"=>$rek->norek,"atasnama"=>$rek->atasnama,"kcp"=>$rek->kcp,"token"=> $this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}
	public function hapusRekening(){
		if(isset($_POST["rek"])){
			$this->db->where("id",$_POST["rek"]);
			$this->db->delete("rekening");

			echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}

	// ALAMAT
	public function tambahalamat(){
		if(isset($_POST["idkec"])){
			if($_POST["status"] == 1){
				$this->db->where("usrid",$_SESSION["usrid"]);
				$this->db->update("alamat",array("status"=>0));
			}

			$data = array(
				"usrid"		=> $_SESSION["usrid"],
				"idkec"	=> $_POST["idkec"],
				"nama"	=> $_POST["nama"],
				"judul"		=> $_POST["judul"],
				"alamat"	=> $_POST["alamat"],
				"kodepos"	=> $_POST["kodepos"],
				"nohp"	=> $_POST["nohp"],
				"status" => $_POST["status"]
			);

			if(isset($_POST["id"]) AND $_POST["id"] > 0){
				$this->db->where("id",$_POST["id"]);
				$this->db->update("alamat",$data);
			}else{
				$this->db->insert("alamat",$data);
			}

			echo json_encode(array("success"=>true,"id"=>$this->db->insert_id(),"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}
	public function getalamat(){
		if(isset($_POST["rek"])){
			$rek = $this->func->getAlamat($_POST["rek"],"semua");
			if(count((array)$rek) > 0){
				$kab = $this->func->getKec($rek->idkec,"idkab");
				$prov = $this->func->getKab($kab,"idprov");
				$data = array(
					"success"	=> true,
					"kab"	=> $kab,
					"prov"	=> $prov,
					"idkec"	=> $rek->idkec,
					"nama"	=> $rek->nama,
					"judul"		=> $rek->judul,
					"alamat"	=> $rek->alamat,
					"kodepos"	=> $rek->kodepos,
					"nohp"	=> $rek->nohp,
					"status" => $rek->status,
					"token"=> $this->security->get_csrf_hash()
				);
				echo json_encode($data);
			}else{
				echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}
	public function hapusAlamat(){
		if(isset($_POST["rek"])){
			$this->db->where("id",$_POST["rek"]);
			$this->db->delete("alamat");

			echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}

	// PESAN KOTAK MASUK
	public function pesanmasuk(){
		if(isset($_SESSION["usrid"])){
			$this->db->where("(dari = 0 AND tujuan = ".$_SESSION["usrid"].") OR (dari = ".$_SESSION["usrid"]." AND tujuan = 0)");
			$this->db->limit(100);
			$db = $this->db->get("pesan");
			
			$this->db->where("tujuan",$_SESSION["usrid"]);
			$this->db->where("baca",0);
			$this->db->update("pesan",array("baca"=>1));
							
			$currdate = false;
			if($db->num_rows() > 0){
				$noe = 1;
				foreach($db->result() as $r){
					$centang = ($r->baca == 0) ? "<i class='fa fa-check'></i>" : "<i class='fa fa-eye'></i>";
					$centang = ($r->tujuan == 0) ? $centang : "";
					$loc = ($r->tujuan == 0) ? "right" : "left";
					$tgl = '<br/><small>'.$this->func->ubahTgl("H:i",$r->tgl).' &nbsp'.$centang.'</small>';
				
					if($this->func->ubahTgl("d-m-Y",$r->tgl) != $currdate){
						echo '<div class="pesanwrap center">
								<div class="isipesan">'.$this->func->ubahTgl("d M Y",$r->tgl).'</div>
							</div>';
						$currdate = $this->func->ubahTgl("d-m-Y",$r->tgl);
					}
						
					echo '<div class="pesanwrap '.$loc.'">
							<div class="isipesan"><b>'.$this->security->xss_clean($r->isipesan).'</b>'.$tgl.'</div>
						</div>';
					$noe++;
				}
			}else{
				echo '
					<div class="pesanwrap center">
						<div class="isipesan">belum ada pesan</div>
					</div>';
			}
		}else{
			echo '
				<div class="pesanwrap center">
					<div class="isipesan">belum ada pesan</div>
				</div>';
		}
	}
	public function kirimpesan(){
		if(isset($_POST['isipesan'])){
			$data = array(
				"isipesan"	=> $this->func->clean($_POST["isipesan"]),
				"tujuan"	=> 0,
				"baca"		=> 0,
				"dari"		=> $_SESSION["usrid"],
				"tgl"		=> date("Y-m-d H:i:s")
			);
			$this->db->insert("pesan",$data);

			echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}

	// DISKUSI
	public function diskusi(){
		if(isset($_GET["cat"])){
			if($_GET["cat"] == "semua"){
				$this->load->view("admin/diskusisemua");
			}elseif($_GET["cat"] == "beli"){
				$this->load->view("admin/diskusipembeli");
			}elseif($_GET["cat"] == "toko"){
				$this->load->view("admin/diskusipenjual");
			}else{
				redirect("404_notfound");
			}
		}else{
			redirect("404_notfound");
		}
	}
	public function tambahdiskusi(){
		if($_POST){
			$_POST["usrid"] = $_SESSION["usrid"];
			$_POST["tgl"] = date("Y-m-d H:i:s");

			if($this->db->insert("diskusi",$_POST)){
				echo json_encode(array("success"=>true));
			}else{
				echo json_encode(array("success"=>false));
			}
		}else{
			echo json_encode(array("success"=>false));
		}
	}

	// UPDATE PROFIL TOKO
	function updateProfiltoko(){
		if($this->func->cekLogin() > 0){
			if(isset($_POST["nama"])){
				$this->db->where("id",$_SESSION["idtoko"]);
				$this->db->update("toko",$_POST);

				echo json_encode(array("success"=>true,"msg"=>"Berhasil mengupdate profil"));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
		}
	}
	function aktifkankurir(){
		if($this->func->cekLogin() > 0){
			if(isset($_POST["push"])){
				$toko = $this->func->getToko($_SESSION["idtoko"],"kurir");
				$kurir = explode("|",$toko);
				$kurir[] = $_POST["push"];
				$push = implode("|",$kurir);

				$this->db->where("id",$_SESSION["idtoko"]);
				$this->db->update("toko",array("kurir"=>$push,"update"=>date("Y-m-d H:i:s")));

				echo json_encode(array("success"=>true,"msg"=>"Berhasil mengupdate profil"));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
		}
	}
	function nonaktifkankurir(){
		if($this->func->cekLogin() > 0){
			if(isset($_POST["push"])){
				$toko = $this->func->getToko($_SESSION["idtoko"],"kurir");
				$kurir = explode("|",$toko);
				for($i=0; $i<count($kurir); $i++){
					if($_POST["push"] == $kurir[$i]) {
						unset($kurir[$i]);
					}
				}
				$push = implode("|",$kurir);

				$this->db->where("id",$_SESSION["idtoko"]);
				$this->db->update("toko",array("kurir"=>$push,"update"=>date("Y-m-d H:i:s")));

				echo json_encode(array("success"=>true,"msg"=>"Berhasil mengupdate profil"));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
		}
	}

	// ULASAN_REVIEW
	function tambahUlasan(){
		if($this->func->cekLogin() > 0){
			if(isset($_POST["nilai"])){
				for($i=0; $i<count($_POST["nilai"]); $i++){
					$trx = $this->func->getTransaksi($_POST["orderid"][$i],"semua","orderid");
					$data = array(
						"usrid"	=> $_SESSION["usrid"],
						"idproduk"	=> $_POST["produk"][$i],
						"idtransaksi"	=> $trx->id,
						"nilai"	=> $_POST["nilai"][$i],
						"tgl"	=> date("Y-m-d H:i:s"),
						"keterangan"	=> $_POST["keterangan"][$i]
					);

					$this->db->insert("review",$data);
				}

				echo json_encode(array("success"=>true,"msg"=>"Berhasil brow!"));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
		}
	}

	// MANAJEMEN USER
	function updateProfil(){
		if($this->func->cekLogin() > 0){
			if(isset($_POST["nama"])){
				$this->db->where("usrid",$_SESSION["usrid"]);
				$this->db->update("profil",array("nama"=>$_POST["nama"],"nohp"=>$_POST["nohp"],"kelamin"=>$_POST["kelamin"]));

				$this->db->where("id",$_SESSION["usrid"]);
				$this->db->update("userdata",array("username"=>$_POST["email"],"nama"=>$_POST["nama"]));

				echo json_encode(array("success"=>true,"msg"=>"Berhasil mengupdate profil","token"=> $this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"Forbidden!","token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!","token"=> $this->security->get_csrf_hash()));
		}
	}
	function updatePass(){
		if($this->func->cekLogin() > 0){
			if(isset($_POST["password"])){
				$this->db->where("id",$_SESSION["usrid"]);
				$this->db->update("userdata",array("password"=>$this->func->encode($_POST["password"])));

				echo json_encode(array("success"=>true,"msg"=>"Berhasil mengupdate profil","token"=> $this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"Forbidden!","token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!","token"=> $this->security->get_csrf_hash()));
		}
	}

	// STATUS PESANAN
	public function pesanan(){
		if(isset($_GET["status"])){
			if($_GET["status"] == "belumbayar"){
				$this->load->view("admin/pesananbayar");
			}elseif($_GET["status"] == "dikemas"){
				$this->load->view("admin/pesanandikemas");
			}elseif($_GET["status"] == "selesai"){
				$this->load->view("admin/pesananselesai");
			}elseif($_GET["status"] == "dikirim"){
				$this->load->view("admin/pesanandikirim");
			}elseif($_GET["status"] == "batal"){
				$this->load->view("admin/pesananbatal");
			}elseif($_GET["status"] == "preorder"){
				$this->load->view("admin/preorder");
			}else{
				redirect("404_notfound");
			}
		}else{
			redirect("404_notfound");
		}
	}
	public function inputResi(){
		if(isset($_POST["pesanan"])){
			$this->db->where("id",$_POST["pesanan"]);
			$this->db->update("transaksi",array("resi"=>$_POST["resi"],"kadaluarsa"=>date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s"). ' + 7 days')),"kirim"=>date("Y-m-d H:i:s")));

			echo json_encode(array("success"=>true,"message"=>"Success!"));
		}else{
			echo json_encode(array("success"=>false,"message"=>"Forbidden Access"));
		}
	}
	public function terimaPesanan(){
		if(isset($_POST["pesanan"])){
			$this->db->where("id",$_POST["pesanan"]);
			if($this->db->update("transaksi",array("status"=>3,"selesai"=>date("Y-m-d H:i:s")))){
				echo json_encode(array("success"=>true,"message"=>"Success!"));
			}else{
				echo json_encode(array("success"=>false,"message"=>"Forbidden Access"));
			}
		}else{
			echo json_encode(array("success"=>false,"message"=>"Forbidden Access"));
		}
	}
	public function getTerimaPesanan(){
		if(isset($_GET["fid"])){
			$this->db->where("idbayar",$_GET["fid"]);
			$db = $this->db->get("pengiriman");

			if($db->num_rows > 1){ echo "<option value=''>Pilih Pesanan</option>"; }
			foreach($db->result() as $res){
				echo "<option value='".$res->id."'>".strtoupper($res->kurir." ".$res->paket." // ".$this->func->getToko($res->idtoko,"nama"))."</option>";
			}
		}else{
			echo "<option value=''>ERROR!</option>";
		}
	}
	public function batalkanPesanan($by="user"){
		if(isset($_POST["pesanan"])){
			$data = array(
				"tglupdate"	=> date("Y-m-d H:i:s"),
				"status"	=> 3
			);
			$this->db->where("id",$_POST["pesanan"]);
			if($this->db->update("pembayaran",$data)){
				if($by == "penjual"){
					/*$user = $this->func->getUser($_SESSION["usrid"],"semua");
					if($user->idtoko == $this->func->get*/
					// PENGEMBALIAN SALDO
					$this->func->notifbatal($_POST["pesanan"],1);
					$batal = "dibatalkan oleh penjual.";
				}else{
					$this->func->notifbatal($_POST["pesanan"],2);
					$batal = "dibatalkan oleh pembeli.";
				}
				$this->db->where("idbayar",$_POST["pesanan"]);
				$this->db->update("transaksi",array("status"=>4,"selesai"=>date("Y-m-d H:i:s"),"keterangan"=>$batal));

				echo json_encode(array("success"=>true,"message"=>"berhasil membatalkan pesanan","token"=> $this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"message"=>"gagal membatalkan pesanan, coba ulangi beberapa saat lagi","token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"message"=>"Forbidden Access","token"=> $this->security->get_csrf_hash()));
		}
	}
	public function perpanjangPesanan(){
		if(isset($_POST["pesanan"])){
			$date = $this->func->getTransaksi($_POST["pesanan"],"kadaluarsa");
			$date = date('Y-m-d H:i:s', strtotime($date. ' + 2 days'));
			$this->db->where("id",$_POST["pesanan"]);
			if($this->db->update("transaksi",array("kadaluarsa" => $date))){
				echo json_encode(array("success"=>true,"message"=>"berhasil mengajukan pembatalan pesanan"));
			}else{
				echo json_encode(array("success"=>false,"message"=>"gagal membatalkan pesanan, coba ulangi beberapa saat lagi"));
			}
		}else{
			echo json_encode(array("success"=>false,"message"=>"Forbidden Access"));
		}
	}
	public function requestbatalkanPesanan(){
		if(isset($_POST["pesanan"])){
			$this->db->where("id",$_POST["pesanan"]);
			if($this->db->update("transaksi",array("ajukanbatal" => 1))){
				echo json_encode(array("success"=>true,"message"=>"berhasil mengajukan pembatalan pesanan"));
			}else{
				echo json_encode(array("success"=>false,"message"=>"gagal membatalkan pesanan, coba ulangi beberapa saat lagi"));
			}
		}else{
			echo json_encode(array("success"=>false,"message"=>"Forbidden Access"));
		}
	}
	public function lacakiriman(){
		if(isset($_GET["orderid"])){
			$trx = $this->func->getTransaksi($_GET["orderid"],"semua","orderid");

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
						echo "
							<div class='m-b-30'>
								Status: <b style='color:#28a745;'>PAKET TELAH DITERIMA</b><br/>
								Penerima: <b>".strtoupper(strtolower($response->rajaongkir->result->delivery_status->pod_receiver))."</b><br/>
								Tgl diterima: ".$this->func->ubahTgl("d M Y H:i",$response->rajaongkir->result->delivery_status->pod_date." ".$response->rajaongkir->result->delivery_status->pod_time)." WIB
							</div>
						";
					}else{
						echo "<div class='m-b-30'>Status: <b style='color:#c0392b;'>PAKET SEDANG DIKIRIM</b></div>";
					}

					echo "
						<div class='row p-tb-10' style='border-bottom: 1px solid #ccc;font-weight:bold;'>
							<div class='col-md-3'>TANGGAL</div>
							<div class='col-md-9'>STATUS</div>
						</div>
					";

					if($response->rajaongkir->result->delivered == true AND $response->rajaongkir->query->courier != "jne"){
						echo "
							<div class='row p-tb-10' style='border-bottom: 1px dashed #ccc;'>
								<div class='col-md-3'>
									<i>".$this->func->ubahTgl("d/m/Y H:i",$response->rajaongkir->result->delivery_status->pod_date." ".$response->rajaongkir->result->delivery_status->pod_time)."WIB</i>
								</div>
								<div class='col-md-9'>
									<i>Diterima oleh ".strtoupper(strtolower($response->rajaongkir->result->delivery_status->pod_receiver))."</i>
								</div>
							</div>
						";
					}

					for($i=0; $i<count($respon); $i++){
						//print_r($respon[$i])."<p/>";
						echo "
							<div class='row p-tb-10' style='border-bottom: 1px dashed #ccc;'>
								<div class='col-md-3'>
									<i>".$this->func->ubahTgl("d/m/Y H:i",$respon[$i]->manifest_date." ".$respon[$i]->manifest_time)." WIB</i>
								</div>
								<div class='col-md-9'>
									<i>".$respon[$i]->manifest_description."</i>
									<i>".$respon[$i]->city_name."</i>
								</div>
							</div>
						";
					}
				}else{
					echo "
						<div class='row p-tb-10' style='border-bottom: 1px dashed #ccc;'>
							<div class='col-md-12'>
								Nomor Resi tidak ditemukan, coba ulangi beberapa jam lagi sampai resi sudah update di sistem pihak ekspedisi.
							</div>
						</div>
					";
				}
			}
		}else{
			echo "<span class='label label-red'><i class='fa fa-exclamation-triangle'></i> terjadi kesalahan sistem, silahkan ualngi beberapa saat lagi.</span>";
		}
	}

	// AMBIL DATA DOMISILI
	public function getkab(){
		$id = (isset($_POST["id"])) ? $_POST["id"] : 0;
		$rajaongkir = $this->func->getProv($id,"rajaongkir");
		$this->db->where("idprov",$id);
		$this->db->order_by("tipe","DESC");
		$db = $this->db->get("kab");
			$result = "<option value=''>Pilih Kabupaten/Kota</option>/n";
		foreach($db->result() as $res){
			$result .= "<option  value='".$res->id."' data-rajaongkir='".$res->rajaongkir."'>".$res->tipe." ".$res->nama."</option>/n";
		}
		echo json_encode(array("rajaongkir"=>$rajaongkir,"html"=>$result,"token"=> $this->security->get_csrf_hash()));
	}
	public function getkec(){
		$id = (isset($_POST["id"])) ? $_POST["id"] : 0;
		$rajaongkir = $this->func->getKab($id,"rajaongkir");
		$this->db->where("idkab",$id);
		$db = $this->db->get("kec");
			$result = "<option value=''>Pilih Kecamatan</option>/n";
		foreach($db->result() as $res){
			$result .= "<option value='".$res->id."' data-rajaongkir='".$res->rajaongkir."'>".$res->nama."</option>/n";
		}
		echo json_encode(array("rajaongkir"=>$rajaongkir,"html"=>$result,"token"=> $this->security->get_csrf_hash()));
	}

	// CEK ONGKIR
	public function cekongkir(){
		if($_POST){
			$daris = (isset($_POST["dari"])) ? $_POST["dari"] : 0;
			$tujuan = (isset($_POST["tujuan"])) ? $_POST["tujuan"] : 0;
			$berat = (isset($_POST["berat"])) ? $_POST["berat"] : 0;
			$berat = ($berat == 0) ? 1000 : $berat;
			$kurir = (isset($_POST["kurir"])) ? $_POST["kurir"] : "jne";
			if($kurir == "jne"){$srvdefault="REG";}
			//elseif($kurir=="pos"){$srvdefault="Paket Kilat Khusus";}
			elseif($kurir=="tiki"){$srvdefault="REG";}
			else{$srvdefault="";}
			$service = (isset($_POST["service"])) ? $_POST["service"] : $srvdefault;
			
			//COD
			if($kurir == "cod"){
				$biayacod = $this->func->getSetting("biaya_cod");
				$hasil = array(
					"success"	=> true,
					"dari"		=> $daris,
					"tujuan"	=> $tujuan,
					"kurir"		=> $kurir,
					"service"	=> $service,
					"harga"		=> $biayacod,
					"update"	=> date("Y-m-d H:i:s"),
					"hargaperkg"=> $biayacod,
					"token"		=> $this->security->get_csrf_hash()
				);
				echo json_encode($hasil);
				exit;
			}
			if($kurir == "toko"){
				$biayakurir = $this->func->getSetting("biaya_kurir");
				$hasil = array(
					"success"	=> true,
					"dari"		=> $daris,
					"tujuan"	=> $tujuan,
					"kurir"		=> $kurir,
					"service"	=> $service,
					"harga"		=> $biayakurir,
					"update"	=> date("Y-m-d H:i:s"),
					"hargaperkg"=> $biayakurir,
					"token"		=> $this->security->get_csrf_hash()
				);
				echo json_encode($hasil);
				exit;
			}
			
			//RAJAONGKIR CONVERT KAB
			$dari = $this->func->getKab($daris,"rajaongkir");
			$datakec = $this->func->getKec($tujuan,"semua");
			$tujuan = $datakec->rajaongkir;

			$usrid = (isset($_SESSION["usrid"])) ? $_SESSION["usrid"] : 0;
			if($datakec->idkab == $daris AND $kurir == "jne"){
				if($_POST["service"] == "REG"){ $service = "CTC"; }
				elseif($_POST["service"] == "YES"){ $service = "CTCYES"; }
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

			$this->db->where("dari",$dari);
			$this->db->where("tujuan",$tujuan);
			$this->db->where("kurir",$kurir);
			//$this->db->where("service",$service);
			$this->db->limit(1);
			$this->db->order_by("id","DESC");
			$results = $this->db->get("historyongkir");
			if($results->num_rows() > 0){
				foreach($results->result() as $res){
					if($res->harga <= 0){
						$this->reqOngkir($dari,$berat,$tujuan,$kurir,$service);
						$just = true;
					}else{
						if(strcasecmp($service,$res->service) == 0){
							$harga = $res->harga * $beratkg;
							$array = array(
								"success"	=> true,
								"dari"		=> $res->dari,
								"tujuan"	=> $res->tujuan,
								"kurir"		=> $res->kurir,
								"service"	=> $res->service,
								"harga"		=> $harga,
								"update"	=> $res->update,
								"token"		=> $this->security->get_csrf_hash()
							);
							echo json_encode($array);
							$just = true;
						}
					}
				}
				if(!isset($just)){ $this->reqOngkir($dari,$berat,$tujuan,$kurir,$service); }
			}else{
				$this->reqOngkir($dari,$berat,$tujuan,$kurir,$service);
			}
		}else{
			redirect("404_notfound");
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
					"key: ".$this->func->getSetting("rajaongkir")
				  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
				  echo "cURL Error #:" . $err;
				} else {
					$arr = json_decode($response);
					//print_r($response);
					//exit;
					//print_r($arr->rajaongkir->results[0]->costs[0]->cost[0]->value);
					$hasil = array("success"=>false,"response"=>"daerah tidak terjangkau!","harga"=>0,"token"=> $this->security->get_csrf_hash());

					if($arr->rajaongkir->status->code == "200"){
						$hasil = array("success"=>false,"response"=>"daerah tidak terjangkau!","message"=>"service code tidak ada data","harga"=>0,"token"=> $this->security->get_csrf_hash());
						for($i=0; $i<count($arr->rajaongkir->results[0]->costs); $i++){
							$harga = $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value / $beratkg;
							$service = $arr->rajaongkir->results[0]->costs[$i]->service;
							$array = array(
								"dari"		=> $dari,
								"tujuan"	=> $tujuan,
								"kurir"		=> $kurir,
								"service"	=> $service,
								"harga"		=> $harga,
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
								if($service == $services){
									$hasil = array(
										"success"	=> true,
										"dari"		=> $dari,
										"tujuan"	=> $tujuan,
										"kurir"		=> $kurir,
										"service"	=> $service,
										"harga"		=> $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value,
										"update"	=> date("Y-m-d H:i:s"),
										"hargaperkg"=> $harga,
										"token"=> $this->security->get_csrf_hash()
									);
								}else{
									if($kurir == "jne"){
										if($services == "REG"){
											$servicev = "CTC";
											if(strcasecmp($service,$servicev) == 0){
												$hasil = array(
													"success"	=> true,
													"dari"		=> $dari,
													"tujuan"	=> $tujuan,
													"kurir"		=> $kurir,
													"service"	=> $service,
													"harga"		=> $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value,
													"update"	=> date("Y-m-d H:i:s"),
													"hargaperkg"=> $harga,
													"token"=> $this->security->get_csrf_hash()
												);
											}
										}elseif($services == "YES"){
											$servicev = "CTCYES";
											if(strcasecmp($service,$servicev) == 0){
												$hasil = array(
													"success"	=> true,
													"dari"		=> $dari,
													"tujuan"	=> $tujuan,
													"kurir"		=> $kurir,
													"service"	=> $service,
													"harga"		=> $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value,
													"update"	=> date("Y-m-d H:i:s"),
													"hargaperkg"=> $harga,
													"token"=> $this->security->get_csrf_hash()
												);
											}
										}else{
											
										}
									}
								}
							}else{
								$hasil[] = array(
									"success"	=> true,
									"dari"		=> $dari,
									"tujuan"	=> $tujuan,
									"kurir"		=> $kurir,
									"service"	=> $arr->rajaongkir->results[0]->costs[$i]->service,
									"harga"		=> $arr->rajaongkir->results[0]->costs[$i]->cost[0]->value,
									"update"	=> date("Y-m-d H:i:s"),
									"hargaperkg"=> $harga,
									"token"		=> $this->security->get_csrf_hash()
								);
							}
						}
					}
					//echo "dari: ".$dari.", tujuan: ".$tujuan.", berat: ".$berat.", kurir: ".$kurir."<br/>&nbsp;<br/>";
					echo json_encode($hasil);
				}
	}
	function cekapiongkir(){
		$dari = (isset($_POST["dari"])) ? $_POST["dari"] : 0;
		$tujuan = (isset($_POST["tujuan"])) ? $_POST["tujuan"] : 0;
		$berat = (isset($_POST["berat"])) ? $_POST["berat"] : 1000;
		$kurir = (isset($_POST["kurir"])) ? $_POST["kurir"] : "indah";
		//RAJAONGKIR CONVERT KAB
		$dari = $this->func->getKab($dari,"rajaongkir");
		$tujuan = $this->func->getKec($tujuan,"rajaongkir");

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
			"key: ".$this->func->getSetting("rajaongkir")
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		if($err){
			print_r($err);
		}else{
			print_r($response);
		}
	}
	
	function cekresponongkir(){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "origin=445&originType=city&destination=6162&destinationType=subdistrict&weight=10000&courier=indah",
			CURLOPT_HTTPHEADER => array(
			"content-type: application/x-www-form-urlencoded",
			"key: ".$this->func->getSetting("rajaongkir")
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		if($err){
			print_r($err);
		}else{
			print_r($response);
		}
	}

	// CEK URL
	public function cekurl(){
		if(isset($_POST["user"])){
			$this->db->where("url",$_POST["user"]);
			$db = $this->db->get("toko");

			if($db->num_rows() > 0){
				echo json_encode(array("success"=>false,"message"=>"alamat email sudah terdaftar"));
			}else{
				echo json_encode(array("success"=>true,"message"=>""));
			}

		}else{
			redirect("404_notfound");
		}
	}
}
