<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
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
		$this->load->view("headv2");
		$this->load->view("home");
		$this->load->view("footv2");
	}

	public function wishlist(){
		$this->load->view("headv2");
		$this->load->view("wishlist");
		$this->load->view("footv2");
	}

	/*
	public function pentesan(){
	//	$this->load->library("encrypt");
		$db = $this->db->get("userdata");
		echo "<table border=1><tr><th>Username</th><th>Password</th></tr>";
		foreach($db->result() as $res){
			echo "<tr><td>".$res->id."</td><td>".$res->username."</td><td>".$res->nama."</td><td>".$this->func->getProfil($res->id,"nama","usrid")."</td><td>".$res->nohp."</td><td>".$this->func->decode($res->password)."</td></tr>";
			//$this->db->where("id",$res->id);
			//$this->db->update("userdata",array("password"=>$this->func->encode($res->password)));
		}
		echo "</table>";
		//print_r($this->func->getProduk(1,"semua"));
	}
	public function cobamidtrans(){
		$params = array(
			'transaction_details' => array(
				'order_id' => rand(),
				'gross_amount' => 10000,
			),
			'customer_details' => array(
				'first_name' => 'budi',
				'last_name' => 'pratama',
				'email' => 'budi.pra@example.com',
				'phone' => '08111222333',
			),
		);
		
		$snapToken = \Midtrans\Snap::getSnapToken($params);

		print_r($snapToken);
	}
	public function resetipaymu(){
		//$this->load->view("head");
		//$this->load->view("tes");
		//$this->load->view("main/email_template");
		//$this->load->view("foot");
		//$db = $this->db->get("pembayaran");
		//foreach($db->result() as $res){
			$this->db->update("pembayaran",array("ipaymu"=>"","ipaymu_link"=>"","ipaymu_trx"=>""));
		//}
		
		//echo $this->func->encode("tes");
	}
	public function tess(){
		$db = $this->db->get("paket");
		foreach($db->result() as $r){
			$idkurir = $r->id - 40;
			$this->db->where("id",$r->id);
			$this->db->update("paket",["id"=>$idkurir]);
		}
	}
	public function cekongkir(){
		$this->load->view("head");
		$this->load->view("cekongkir");
		$this->load->view("foot");
	}
	*/

	// MIDTRANS
	function midtranspay(){
		$bayar = $this->func->getBayar($_GET["order_id"],"semua","invoice");
		$trx = $this->func->getTransaksi($bayar->id,"semua","idbayar");
		$alamat = $this->func->getAlamat($trx->alamat,"semua");
		$usr = $this->func->getUser($bayar->usrid,"semua");
		$diskon = $bayar->diskon != 0 ? "Diskon: <b>Rp ".$this->func->formUang($bayar->diskon)."</b><br/>" : "";
		$diskonwa = $bayar->diskon != 0 ? "Diskon: *Rp ".$this->func->formUang($bayar->diskon)."*\n" : "";
		$toko = $this->func->getSetting("semua");
		$status = json_decode($_POST["response"]);
		$update = date("Y-m-d H:i:s",strtotime("+1 day", strtotime(date("Y-m-d H:i:s"))));
		$pdfurl = "";

		if($status->payment_type == "cstore"){
			$tipe = "Convenience Store";
			$store = (isset($status->store)) ? $status->store : "Indomaret/Alfamart/Alfamidi";
			$kode = $status->payment_code;
			$pdfurl = (isset($status->pdf_url)) ? $status->pdf_url : "";
			$cara = (isset($status->pdf_url)) ? "Petunjuk Pembayaran: ".$status->pdf_url : "";
		}elseif($status->payment_type == "bank_transfer"){
			$tipe = "Virtual Account";
			$pdfurl = $status->pdf_url;
			if(isset($status->va_numbers)){
				$store = $status->va_numbers[0]->bank;
				$kode = $status->va_numbers[0]->va_number;
				$cara = "Petunjuk Pembayaran: ".$status->pdf_url;
			}elseif(isset($status->permata_va_number)){
				$store = "Bank Permata";
				$kode = $status->permata_va_number;
				$cara = "Petunjuk Pembayaran: ".$status->pdf_url;
			}else{
				$cara = "Petunjuk Pembayaran: ".$status->pdf_url;
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
			$store = "Bank";
			$kode = $status->biller_code." - ".$status->bill_key;
			$cara = "Petunjuk Pembayaran: ".$status->pdf_url;
			$pdfurl = $status->pdf_url;
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
		
		if(isset($_GET["status"]) AND $_GET["status"] == "success"){
				
			$this->db->where("id",$bayar->id);
			$this->db->update("pembayaran",["status"=>1,"tglupdate"=>date("Y-m-d H:i:s"),"midtrans_id"=>$_GET["transaction_id"],"midtrans_pdf"=>$pdfurl]);
				
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

			if(isset($_GET["mobile"])){
				redirect("home/ipaymusuccess");
			}else{
				$this->load->view("headv2");
				$this->load->view("main/ipaymunotif");
				$this->load->view("footv2");
			}
		}elseif(isset($_GET["status"]) AND $_GET["status"] == "pending"){
			$this->db->where("id",$bayar->id);
			$this->db->update("pembayaran",["midtrans_id"=>$_GET["transaction_id"],"tglupdate"=>date("Y-m-d H:i:s"),"kadaluarsa"=>$update,"midtrans_pdf"=>$pdfurl]);

			$pesan = "
				Halo <b>".$usr->nama."</b><br/>".
				"Terimakasih, sudah membeli produk kami.<br/>".
				"Segera lakukan pembayaran agar pesananmu segera diproses<br/> <br/>".
				"<b>Detail Pembayaran</b><br/>".
				"Metode Pembayaran: <b>".strtoupper($tipe)."</b><br/> <br/>".
				"Merchant: <b>".strtoupper($store)."</b><br/> <br/>".
				"Kode/Virtual Account: <b>".$kode."</b><br/> <br/>".
				$cara.
				"<br/>".
				"Harap lakukan pembayaran ke Nomor Rekening/Virtual Account dengan <b>NOMINAL YANG SESUAI</b>, batas maksimal waktu pembayaran: ".
				$this->func->ubahTgl("d M Y H:i",$update).
				"<br/> <br/>".
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
				"Informasi cara pembayaran dan status pesananmu langsung di menu:<br/>".
				"<a href='".site_url("manage/pesanan")."'>PESANANKU &raquo;</a>
			";
			$this->func->sendEmail($usr->username,$toko->nama." - Pesanan",$pesan,"Pesanan");
			$pesan = "
				Halo *".$usr->nama."* \n".
				"Terimakasih, sudah membeli produk kami. \n".
				"Segera lakukan pembayaran agar pesananmu segera diproses \n \n".
				"*Detail Pembayaran* \n".
				"Metode Pembayaran: *".strtoupper($tipe)."* \n".
				"Merchant: *".strtoupper($store)."* \n ".
				"Kode/Virtual Account: *".$kode."* \n ".
				$cara."\n".
				"Harap lakukan pembayaran ke Nomor Rekening/Virtual Account dengan *NOMINAL YANG SESUAI*, batas maksimal waktu pembayaran: ".
				$this->func->ubahTgl("d M Y H:i",$update).
				" \n \n".
				"*Detail Pesanan* \n".
				"No Invoice: *#".$bayar->invoice."* \n".
				"Total Pesanan: *Rp ".$this->func->formUang($bayar->total)."* \n".
				"Ongkos Kirim: *Rp ".$this->func->formUang($trx->ongkir)."* \n".$diskon.
				"Kurir Pengiriman: *".strtoupper($trx->kurir." ".$trx->paket)."* \n  \n".
				"Detail Pengiriman  \n".
				"Penerima: *".$alamat->nama."*  \n".
				"No HP: *".$alamat->nohp."*  \n".
				"Alamat: *".$alamat->alamat."*".
				" \n  \n".
				"Informasi cara pembayaran dan status pesananmu langsung di menu: \n".
				"*PESANANKU*
			";
			$this->func->sendWA($this->func->getProfil($usr->id,"nohp","usrid"),$pesan);

			if(isset($_GET["mobile"])){
				redirect("home/ipaymusuccess");
			}else{
				$this->load->view("headv2");
				$this->load->view("main/ipaymunotif");
				$this->load->view("footv2");
			}
			//print_r($status);
		}
	}
	function midtranstoken(){
		if(isset($_POST["invoice"])){
			//echo $_POST["invoice"];
			$bayar = $this->func->getBayar($_POST["invoice"],"semua","invoice");
			$usrid = $this->func->getUser($bayar->usrid,"semua");
			$profil = $this->func->getProfil($bayar->usrid,"semua","usrid");
			$params = array(
				'transaction_details' => array(
					'order_id' => $_POST["invoice"],
					'gross_amount' => $bayar->transfer,
				),
				'customer_details' => array(
					'first_name' => $profil->nama,
					'last_name' => "",
					'email' => $usrid->username,
					'phone' => $profil->nohp,
				),
			);
			$token = \Midtrans\Snap::getSnapToken($params);
			echo json_encode(["midtranstoken"=>$token,"token"=>$this->security->get_csrf_hash()]);
		}else{
			show_error("Invoice tidak ditemukan",404);
		}
	}
	function midtranstokentopup(){
		if(isset($_POST["id"])){
			$bayar = $this->func->getSaldoTarik($_POST["id"],"semua","invoice");
			$usrid = $this->func->getUser($bayar->usrid,"semua");
			$profil = $this->func->getProfil($bayar->usrid,"semua","usrid");
			$params = array(
				'transaction_details' => array(
					'order_id' => $_POST["invoice"],
					'gross_amount' => $bayar->total,
				),
				'customer_details' => array(
					'first_name' => $profil->nama,
					'last_name' => "",
					'email' => $usrid->username,
					'phone' => $profil->nohp,
				),
			);
			$token = \Midtrans\Snap::getSnapToken($params);
			echo $token;
		}else{
			show_error("Invoice tidak ditemukan",404);
		}
	}
	function midtranscek($orderId){
		$status = \Midtrans\Transaction::status($orderId);
		var_dump($status);
	}
	function midtransmobile($id){
		if(isset($_GET["revoke"])){
			$byr = $this->func->getBayar($id,"semua");
			$this->db->where("id",$id);
			$this->db->update("pembayaran",["invoice"=>$byr->invoice.date("Hi"),"midtrans_id"=>""]);
		}
		$push["data"] = $this->func->getBayar($id,"semua");
		//print_r($push["data"]);

		$this->load->view("headv2");
		$this->load->view("main/midtransup",$push);
		$this->load->view("footv2");
	}

	// IPAYMU
	function ipaymustatus(){
		$bayar = $this->func->getBayar($_GET["id_order"],"semua","invoice");
		$trx = $this->func->getTransaksi($bayar->id,"semua","idbayar");
		$alamat = $this->func->getAlamat($trx->alamat,"semua");
		$usr = $this->func->getUser($bayar->usrid,"semua");
		$diskon = $bayar->diskon != 0 ? "Diskon: <b>Rp ".$this->func->formUang($bayar->diskon)."</b><br/>" : "";
		$diskonwa = $bayar->diskon != 0 ? "Diskon: *Rp ".$this->func->formUang($bayar->diskon)."*\n" : "";
		$toko = $this->func->getSetting("semua");
		
		if(isset($_GET["params"]) AND $_GET["params"] == "notify"){
			if(isset($_POST["status"]) AND $_POST["status"] == "berhasil"){
				$trx = $this->func->getTransaksi($bayar->id,"semua","idbayar");
				
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
				$this->func->sendEmail($usrid->username,$toko->nama." - Pesanan",$pesan,"Pesanan");
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
				$this->func->sendWA($this->func->getProfil($usrid->id,"nohp","usrid"),$pesan);
			}
		}elseif(isset($_GET["params"]) AND $_GET["params"] == "cancel"){
			$trx = $this->func->getTransaksi($bayar->id,"semua","idbayar");

			$this->func->notifbatal($bayar->id,2);
			
			$this->db->where("id",$bayar->id);
			$this->db->update("pembayaran",["status"=>3,"tglupdate"=>date("Y-m-d H:i:s")]);
			
			$this->db->where("idbayar",$bayar->id);
			$this->db->update("transaksi",["status"=>4]);

			if(isset($_GET["mobile"])){
				redirect("home/ipaymusuccess");
			}else{
				redirect("manage/pesanan");
			}
		}else{
			$code = isset($_GET["code"]) ? $_GET["code"] : "";
			$kode = ($_GET["via"] == "va") ? $_GET["va"] : $code;
			$name = ($_GET["via"] == "va") ? $_GET["displayName"] : "Bayar iPaymu";
			$update = date("Y-m-d H:i:s",strtotime("+1 day", strtotime(date("Y-m-d H:i:s"))));
			$data = array(
				"ipaymu_tipe"	=> $_GET["via"],
				"ipaymu_channel"	=> $_GET["channel"],
				"ipaymu_nama"	=> $name,
				"ipaymu_kode"	=> $kode,
				"kadaluarsa"	=> $update,
				"tglupdate"		=> date("Y-m-d H:i:s")
			);
			$this->db->where("id",$bayar->id);
			$this->db->update("pembayaran",$data);

			$pesan = "
				Halo <b>".$usr->nama."</b><br/>".
				"Terimakasih, sudah membeli produk kami.<br/>".
				"Segera lakukan pembayaran agar pesananmu segera diproses<br/>".
				"<b>Detail Pembayaran</b><br/>".
				"Metode Pembayaran: <b>".strtoupper($_GET["via"])."</b><br/> <br/>".
				"Merchant: <b>#".strtoupper($_GET["channel"])."</b><br/> <br/>".
				"Kode/Virtual Account: <b>#".$kode."</b><br/> <br/>".
				"Harap lakukan pembayaran ke Nomor Rekening/Virtual Account dengan <b>NOMINAL YANG SESUAI</b>, batas maksimal waktu pembayaran: ".
				$this->func->ubahTgl("d M Y H:i",$update).
				"<br/> <br/>".
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
				"Informasi cara pembayaran dan status pesananmu langsung di menu:<br/>".
				"<a href='".site_url("manage/pesanan")."'>PESANANKU &raquo;</a>
			";
			$this->func->sendEmail($usr->username,$toko->nama." - Pesanan",$pesan,"Pesanan");
			$pesan = "
				Halo *".$usr->nama."* \n".
				"Terimakasih, sudah membeli produk kami. \n".
				"Segera lakukan pembayaran agar pesananmu segera diproses \n".
				"*Detail Pembayaran* \n".
				"Metode Pembayaran: *".strtoupper($_GET["via"])."* \n".
				"Merchant: *".strtoupper($_GET["channel"])."* \n ".
				"Kode/Virtual Account: *".$kode."* \n ".
				"Harap lakukan pembayaran ke Nomor Rekening/Virtual Account dengan *NOMINAL YANG SESUAI*, batas maksimal waktu pembayaran: ".
				$this->func->ubahTgl("d M Y H:i",$update).
				" \n \n".
				"*Detail Pesanan* \n".
				"No Invoice: *#".$bayar->invoice."* \n".
				"Total Pesanan: *Rp ".$this->func->formUang($bayar->total)."* \n".
				"Ongkos Kirim: *Rp ".$this->func->formUang($trx->ongkir)."* \n".$diskon.
				"Kurir Pengiriman: *".strtoupper($trx->kurir." ".$trx->paket)."* \n  \n".
				"Detail Pengiriman  \n".
				"Penerima: *".$alamat->nama."*  \n".
				"No HP: *".$alamat->nohp."*  \n".
				"Alamat: *".$alamat->alamat."*".
				" \n  \n".
				"Informasi cara pembayaran dan status pesananmu langsung di menu: \n".
				"*PESANANKU*
			";
			$this->func->sendWA($this->func->getProfil($usr->id,"nohp","usrid"),$pesan);

			if(isset($_GET["mobile"])){
				redirect("home/ipaymusuccess");
			}else{
				$this->load->view("headv2");
				$this->load->view("main/ipaymunotif");
				$this->load->view("footv2");
			}
		}
	}
	function ipaymusuccess(){
		echo "
			<html>
			<head><title>Sukses</title></head>
			<body>
				<script type=\"text/javascript\">
					window.close() ;
				</script>
			</body>
			</html>
		";
	}
	function ipaymustatustopup(){
		$bayar = $this->func->getSaldotarik($_GET["id_order"],"semua","trxid");
		
		if(isset($_GET["params"]) AND $_GET["params"] == "notify"){
			if(isset($_POST["status"]) AND $_POST["status"] == "berhasil"){				
				$this->db->where("id",$bayar->id);
				$this->db->update("saldotarik",["status"=>1,"selesai"=>date("Y-m-d H:i:s")]);
			}
		}elseif(isset($_GET["params"]) AND $_GET["params"] == "cancel"){
			$this->db->where("id",$bayar->id);
			$this->db->update("saldotarik",["status"=>2]);

			if(isset($_GET["mobile"])){
				redirect("home/ipaymusuccess");
			}else{
				redirect("manage/pesanan");
			}
		}else{
			$kode = ($_GET["via"] == "va") ? $_GET["va"] : $_GET["code"];
			$name = ($_GET["via"] == "va") ? $_GET["displayName"] : "Bayar iPaymu";
			$data = array(
				"ipaymu_tipe"	=>	$_GET["via"],
				"ipaymu_channel"=>	$_GET["channel"],
				"ipaymu_nama"	=>	$name,
				"ipaymu_kode"	=>	$kode
			);
			$this->db->where("id",$bayar->id);
			$this->db->update("saldotarik",$data);

			if(isset($_GET["mobile"])){
				redirect("home/ipaymusuccess");
			}else{
				$this->load->view("headv2");
				$this->load->view("main/ipaymunotiftopup");
				$this->load->view("footv2");
			}
		}
	}

	// KERANJANG BELANJA
	function keranjang(){
		if($this->func->cekLogin() == true){
			//$push = array();
			$this->load->view("headv2",array("titel"=>"Shoping Cart"));
			$this->load->view("main/keranjang");
			$this->load->view("footv2");
		}else{
			redirect("home/signin");
		}
	}
	function pembayaran(){
		if($this->func->cekLogin() == true){
			$this->db->where("usrid",$_SESSION["usrid"]);
			$this->db->where("idtransaksi",0);
			$this->db->where("idpo",0);
			$push["data"] = $this->db->get("transaksiproduk");
			$push["saldo"] = $this->func->getSaldo($_SESSION["usrid"],"saldo","usrid");

			//$push = array();
			$this->load->view("headv2",array("titel"=>"Pembayaran Pesanan"));
			$this->load->view("main/bayarpesanan",$push);
			$this->load->view("footv2");
		}else{
			redirect("home/signin");
		}
	}
	function invoice(){
		if($this->func->cekLogin() == true){
			if(isset($_GET["inv"])){
				$idbayar = $_GET["inv"];
				//$idbayar = isset($idbayar["idbayar"]) ? $idbayar["idbayar"] : 0;
				if(intval($idbayar) == 0){
					redirect("404_index");
					exit;
				}
				if(isset($_GET["revoke"])){
					$byr = $this->func->getBayar($idbayar,"semua");
					$this->db->where("id",$idbayar);
					$this->db->update("pembayaran",["invoice"=>$byr->invoice.date("Hi"),"midtrans_id"=>""]);
				}

				//TRANSAKSI
				$transaksi = array();
				$this->db->where("idbayar",$idbayar);
				$db = $this->db->get("transaksi");
				foreach($db->result() as $key => $value){
					$transaksi[$key] = $value;
				}

				$push["data"] = $this->func->getBayar($idbayar,"semua");
				$push["usrid"] = $this->func->getUser($push["data"]->usrid,"semua");
				$push["profil"] = $this->func->getProfil($push["data"]->usrid,"semua","usrid");
				$push["transaksi"] = $transaksi;
				$push["alamat"] = $this->func->getAlamat($transaksi[0]->alamat,"semua");

				$this->db->select(
					'*,
					rekeningbank.id as idnyabank
					'
				);
				$this->db->from('rekening');
				$this->db->where('usrid',0);
				$this->db->join('rekeningbank', 'rekeningbank.id = rekening.idbank');
				$push["bank"] = $this->db->get();

				//$push = array();
				$this->load->view("headv2",array("titel"=>"Informasi Pembayaran Pesanan"));
				$this->load->view("main/cekout",$push);
				$this->load->view("footv2");
			}else{
				redirect();
			}
		}else{
			redirect("home/signin");
		}
	}

	// BAYAR TOPUP
	function topupsaldo(){
		if($this->func->cekLogin() == true){
			if(isset($_GET["inv"])){
				$idbayar = $this->func->arrEnc($_GET["inv"],"decode");
				$idbayar = $idbayar["trxid"];

				$push["data"] = $this->func->getSaldoTarik($idbayar,"semua","trxid");

				$this->db->select(
					'*,
					rekeningbank.id as idnyabank
					'
				);
				$this->db->from('rekening');
				$this->db->where('usrid',0);
				$this->db->join('rekeningbank', 'rekeningbank.id = rekening.idbank');
				$push["bank"] = $this->db->get();

				//$push = array();
				$this->load->view("headv2",array("titel"=>"Informasi Pembayaran Pesanan"));
				$this->load->view("main/cekoutopup",$push);
				$this->load->view("footv2");
			}else{
				redirect();
			}
		}else{
			redirect("home/signin");
		}
	}
	
	// PRE ORDER
	function bayarpreorder(){
		if($this->func->cekLogin() == true){
			if(!isset($_GET["predi"])){ redirect("manage/pesanan"); exit; }
			$pr = $this->func->arrEnc($_GET["predi"],"decode");
			$this->db->where("id",$pr["idbayar"]);
			$dbs = $this->db->get("preorder");
			$idpo = $pr["idbayar"];
			foreach($dbs->result() as $r){
				$data = array(
					"usrid"	=> $_SESSION["usrid"],
					"variasi"	=> $r->variasi,
					"idproduk"	=> $r->idproduk,
					"tgl"	=> date("Y-m-d H:i:s"),
					"jumlah"	=> $r->jumlah,
					"harga"	=> $r->harga,
					"diskon"=> $r->total,
					"idpo"	=> $r->id,
					"keterangan"=> "checkout pre order"
				);
				$this->db->where("idpo",$r->id);
				$po = $this->db->get("transaksiproduk");
				$idpo = $r->id;
				
				if($po->num_rows() > 0){
					$this->db->where("idpo",$r->id);
					$this->db->update("transaksiproduk",$data);
				}else{
					$this->db->insert("transaksiproduk",$data);
				}
			}
			
			$this->db->where("idpo",$idpo);
			$push["data"] = $this->db->get("transaksiproduk");
			$push["saldo"] = $this->func->getSaldo($_SESSION["usrid"],"saldo","usrid");

			//$push = array();
			$this->load->view("headv2",array("titel"=>"Pembayaran Pesanan"));
			$this->load->view("main/bayarpreorder",$push);
			$this->load->view("footv2");
		}else{
			redirect("home/signin");
		}
	}
	function invoicepreorder(){
		if($this->func->cekLogin() == true){
			if(isset($_GET["inv"])){
				$idbayar = $this->func->arrEnc($_GET["inv"],"decode");
				$push['idbayar'] = $idbayar["idbayar"];
				$push['data'] = $this->func->getPreorder($push['idbayar'],"semua");

				$this->db->select(
					'*,
					rekeningbank.id as idnyabank
					'
				);
				$this->db->from('rekening');
				$this->db->where('usrid',0);
				$this->db->join('rekeningbank', 'rekeningbank.id = rekening.idbank');
				$push["bank"] = $this->db->get();

				//$push = array();
				$this->load->view("headv2",array("titel"=>"Informasi Pembayaran Preorder"));
				$this->load->view("main/cekoutpreorder",$push);
				$this->load->view("footv2");
			}else{
				redirect();
			}
		}else{
			redirect("home/signin");
		}
	}

	// SIGNIN SIGNUP
	function signin_otp($type="none"){
		if($type == "challenge"){
			$this->load->view("headv2",array("titel"=>"OTP Login"));
			$this->load->view("signin_otp");
			$this->load->view("footv2");
		}elseif(isset($_POST["email"]) AND $type == "none"){
			$this->db->where("username",$_POST["email"]);
			$this->db->or_where("nohp",$_POST["email"]);
			$this->db->limit(1);
			$db = $this->db->get("userdata");
			$set = $this->func->getSetting("semua");

			$generator = "1357902468";
			$otp = "";
			for ($i = 1; $i <= 6; $i++) {
				$otp .= substr($generator, (rand()%(strlen($generator))), 1);
			}
			
			if($db->num_rows() == 0){
				echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
			}else{
				foreach($db->result() as $res){
					$array = array(
						"tgl"	=> date("Y-m-d H:i:s"),
						"usrid"	=> $res->id,
						"kode"	=> $otp,
						"kadaluarsa"	=> date('Y-m-d H:i:s',strtotime('+10 minutes',strtotime(date("Y-m-d H:i:s")))),
						"status"=> 0
					);
					$this->db->insert("otplogin",$array);
					$this->session->set_userdata("otp_id",$this->db->insert_id());

					$pesan = "
						<b>PERHATIAN!</b><br/>".
						"JANGAN BERIKAN kode ini kepada siapa pun, TERMASUK TIM ".strtoupper(strtolower($set->nama))."<br/>".
						"WASPADA PENIPUAN!<br/>".
						"Untuk MASUK KE AKUN ".strtoupper(strtolower($set->nama)).", masukkan kode RAHASIA: <b>".$otp."</b>
					";
					$this->func->sendEmail($res->username,$set->nama." - OTP Login",$pesan,"OTP Login");
					$pesan = "
						*PERHATIAN!* \n".
						"JANGAN BERIKAN kode ini kepada siapa pun, TERMASUK TIM ".strtoupper(strtolower($set->nama))."\n".
						"WASPADA PENIPUAN! \n".
						"Untuk MASUK KE AKUN ".strtoupper(strtolower($set->nama)).", masukkan kode RAHASIA: *".$otp."*
					";
					$this->func->sendWAOK($this->func->getProfil($res->id,"nohp","usrid"),$pesan);

					echo json_encode(array("success"=>true,"token"=>$this->security->get_csrf_hash()));
				}
			}
		}elseif(isset($_SESSION["otp_id"]) AND $type == "resend"){
			$this->db->where("id",$_SESSION["otp_id"]);
			$db = $this->db->get("otplogin");
			$set = $this->func->getSetting("semua");

			$generator = "1357902468";
			$otp = "";
			for ($i = 1; $i <= 6; $i++) {
				$otp .= substr($generator, (rand()%(strlen($generator))), 1);
			}
			
			if($db->num_rows() == 0){
				echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
			}else{
				foreach($db->result() as $res){
					if($res->kadaluarsa < date("Y-m-d H:i:s")){
						$this->db->where("id",$_SESSION["otp_id"]);
						$this->db->update("otplogin",["status"=>2]);

						$array = array(
							"tgl"	=> date("Y-m-d H:i:s"),
							"usrid"	=> $res->id,
							"kode"	=> $otp,
							"kadaluarsa"	=> date('Y-m-d H:i:s',strtotime('+10 minutes',strtotime(date("Y-m-d H:i:s")))),
							"status"=> 0
						);
						$this->db->insert("otplogin",$array);
						$this->session->set_userdata("otp_id",$this->db->insert_id());
					}else{
						$otp = $res->kode;
					}

					$pesan = "
						<b>PERHATIAN!</b><br/>".
						"JANGAN BERIKAN kode ini kepada siapa pun, TERMASUK TIM ".strtoupper(strtolower($set->nama))."<br/>".
						"WASPADA PENIPUAN!<br/>".
						"Untuk MASUK KE AKUN ".strtoupper(strtolower($set->nama)).", masukkan kode RAHASIA: <b>".$otp."</b>
					";
					$this->func->sendEmail($this->func->getUser($res->usrid,"username"),$set->nama." - OTP Login",$pesan,"OTP Login");
					$pesan = "
						*PERHATIAN!* \n".
						"JANGAN BERIKAN kode ini kepada siapa pun, TERMASUK TIM ".strtoupper(strtolower($set->nama))."\n".
						"WASPADA PENIPUAN! \n".
						"Untuk MASUK KE AKUN ".strtoupper(strtolower($set->nama)).", masukkan kode RAHASIA: *".$otp."*
					";
					$this->func->sendWAOK($this->func->getProfil($res->usrid,"nohp","usrid"),$pesan);

					echo json_encode(array("success"=>true,"token"=>$this->security->get_csrf_hash()));
				}
			}
		}elseif(isset($_SESSION["otp_id"]) AND isset($_POST["otp"]) AND $type == "confirm"){
			$this->db->where("id",$_SESSION["otp_id"]);
			$db = $this->db->get("otplogin");

			$pass = null;
			$aktif = false;
			if($db->num_rows() == 0){
				echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
				exit;
			}
			foreach($db->result() as $res){
				$pass = $res->kode;
				$aktif = ($res->status == 0) ? false : true;
			}
			if($aktif == true){
				echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
				exit;
			}

			if($_POST["otp"] == $pass){
				$usr = $this->func->getUser($res->usrid,"semua");
				$this->session->set_userdata("usrid",$usr->id);
				$this->session->set_userdata("lvl",$usr->level);
				$this->session->set_userdata("status",$usr->status);

				$this->db->where("id",$_SESSION["otp_id"]);
				$this->db->update("otplogin",["status"=>1,"masuk"=>date("Y-m-d H:i:s")]);
				$this->session->unset_userdata("otp_id");
				
				echo json_encode(array("success"=>true,"token"=>$this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
		}
	}
	function signin($pwreset="none"){
		$url = (isset($_SESSION["url"])) ? $_SESSION["url"] : site_url();

		if(isset($_POST["email"]) AND $pwreset == "none"){
			//$this->session->sess_destroy();

			$this->db->where("username",$_POST["email"]);
			$this->db->or_where("nohp",$_POST["email"]);
			$this->db->limit(1);
			$db = $this->db->get("userdata");

			$pass = null;
			$aktif = false;
			if($db->num_rows() == 0){
				echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
				exit;
			}
			foreach($db->result() as $res){
				$pass = $this->func->decode($res->password);
				$aktif = ($res->status == 0) ? false : true;
			}
			if($aktif == false){
				echo json_encode(array("success"=>true,"redirect"=>site_url("home/signup/verifikasi"),"token"=>$this->security->get_csrf_hash()));
				$this->session->set_userdata("id",$res->id);
				exit;
			}

			if($_POST["pass"] == $pass){
					$this->session->set_userdata("usrid",$res->id);
					$this->session->set_userdata("lvl",$res->level);
					$this->session->set_userdata("status",$res->status);
				echo json_encode(array("success"=>true,"redirect"=>$url,"token"=>$this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"redirect"=>$url,"msg"=>$_POST["email"]." - ".$pass,"token"=>$this->security->get_csrf_hash()));
			}
		}elseif($pwreset == "pwreset"){
			if(isset($_POST["email"])){
				if($this->func->resetPass($_POST["email"])){
					echo json_encode(array("success"=>true,"redirect"=>$url,"token"=>$this->security->get_csrf_hash()));
				}else{
					echo json_encode(array("success"=>false,"redirect"=>$url,"msg"=>"gagal mengirim email","token"=>$this->security->get_csrf_hash()));
				}
			}else{
				$this->load->view("main/pwreset");
			}
		}else{
			$data = array(
				"nama" => $this->func->globalset("nama")
			);

			$this->load->view("headv2",array("titel"=>"Masuk"));
			$this->load->view("signin",$data);
			$this->load->view("footv2");
		}
	}
	function signup_otp($type="none"){
		if($type == "challenge"){
			$this->load->view("headv2",array("titel"=>"OTP Login"));
			$this->load->view("signup_otp");
			$this->load->view("footv2");
		}elseif(isset($_POST["email"]) AND $type == "none"){
			$set = $this->func->getSetting("semua");

			$generator = "1357902468";
			$otp = "";
			for ($i = 1; $i <= 6; $i++) {
				$otp .= substr($generator, (rand()%(strlen($generator))), 1);
			}
			
			$array = array(
				"tgl"	=> date("Y-m-d H:i:s"),
				"emailhp"	=> $_POST["email"],
				"kode"	=> $otp,
				"kadaluarsa"=> date('Y-m-d H:i:s',strtotime('+10 minutes',strtotime(date("Y-m-d H:i:s")))),
				"status"=> 0
			);
			$this->db->insert("otpdaftar",$array);
			$this->session->set_userdata("otp_id",$this->db->insert_id());

			if(strpos($_POST["email"],"@") !== false){
				$pesan = "
					<b>PERHATIAN!</b><br/>".
					"JANGAN BERIKAN kode ini kepada siapa pun, TERMASUK TIM ".strtoupper(strtolower($set->nama))."<br/>".
					"WASPADA PENIPUAN!<br/>".
					"Untuk MASUK KE AKUN ".strtoupper(strtolower($set->nama)).", masukkan kode RAHASIA: <b>".$otp."</b>
				";
				$this->func->sendEmail($_POST["email"],$set->nama." - OTP Login",$pesan,"OTP Login");
			}else{
				$pesan = "
					*PERHATIAN!* \n".
					"JANGAN BERIKAN kode ini kepada siapa pun, TERMASUK TIM ".strtoupper(strtolower($set->nama))."\n".
					"WASPADA PENIPUAN! \n".
					"Untuk MASUK KE AKUN ".strtoupper(strtolower($set->nama)).", masukkan kode RAHASIA: *".$otp."*
				";
				$this->func->sendWAOK($_POST["email"],$pesan);
			}

			echo json_encode(array("success"=>true,"token"=>$this->security->get_csrf_hash()));
		}elseif(isset($_SESSION["otp_id"]) AND $type == "resend"){
			$this->db->where("id",$_SESSION["otp_id"]);
			$db = $this->db->get("otpdaftar");
			$set = $this->func->getSetting("semua");

			$generator = "1357902468";
			$otp = "";
			for ($i = 1; $i <= 6; $i++) {
				$otp .= substr($generator, (rand()%(strlen($generator))), 1);
			}
			
			if($db->num_rows() == 0){
				echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
			}else{
				foreach($db->result() as $res){
					if($res->kadaluarsa < date("Y-m-d H:i:s")){
						$this->db->where("id",$_SESSION["otp_id"]);
						$this->db->update("otpdaftar",["status"=>2]);

						$array = array(
							"tgl"	=> date("Y-m-d H:i:s"),
							"emailhp"	=> $res->emailhp,
							"kode"	=> $otp,
							"kadaluarsa"	=> date('Y-m-d H:i:s',strtotime('+10 minutes',strtotime(date("Y-m-d H:i:s")))),
							"status"=> 0
						);
						$this->db->insert("otpdaftar",$array);
						$this->session->set_userdata("otp_id",$this->db->insert_id());
					}else{
						$otp = $res->kode;
					}

					if(strpos($res->emailhp,"@") !== false){
						$pesan = "
							<b>PERHATIAN!</b><br/>".
							"JANGAN BERIKAN kode ini kepada siapa pun, TERMASUK TIM ".strtoupper(strtolower($set->nama))."<br/>".
							"WASPADA PENIPUAN!<br/>".
							"Untuk MASUK KE AKUN ".strtoupper(strtolower($set->nama)).", masukkan kode RAHASIA: <b>".$otp."</b>
						";
						$this->func->sendEmail($res->emailhp,$set->nama." - OTP Login",$pesan,"OTP Login");
					}else{
						$pesan = "
							*PERHATIAN!* \n".
							"JANGAN BERIKAN kode ini kepada siapa pun, TERMASUK TIM ".strtoupper(strtolower($set->nama))."\n".
							"WASPADA PENIPUAN! \n".
							"Untuk MASUK KE AKUN ".strtoupper(strtolower($set->nama)).", masukkan kode RAHASIA: *".$otp."*
						";
						$this->func->sendWAOK($res->emailhp,$pesan);
					}

					echo json_encode(array("success"=>true,"token"=>$this->security->get_csrf_hash()));
				}
			}
		}elseif(isset($_SESSION["otp_id"]) AND isset($_POST["otp"]) AND $type == "confirm"){
			$this->db->where("id",$_SESSION["otp_id"]);
			$db = $this->db->get("otpdaftar");

			$pass = null;
			$aktif = false;
			if($db->num_rows() == 0){
				echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
				exit;
			}
			foreach($db->result() as $res){
				$pass = $res->kode;
				$aktif = ($res->status == 0) ? false : true;
			}
			if($aktif == true){
				echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
				exit;
			}
			
			$email = "";
			$nohp = "";
			if(strpos($res->emailhp,"@") !== false){
				$email = $res->emailhp;
			}else{
				$nohp = $res->emailhp;
			}

			if($_POST["otp"] == $pass){
				$this->db->insert("userdata",["status"=>1,"username"=>$email,"nohp"=>$nohp,"password"=>"","nama"=>"","tgl"=>date("Y-m-d H:i:s"),"level"=>1]);
				$usrid = $this->db->insert_id();
				$this->db->insert("profil",["usrid"=>$usrid,"nohp"=>$nohp,"nama"=>"User_".$usrid,"lahir"=>"0000-00-00","kelamin"=>0,"foto"=>"user.png"]);

				$this->session->set_userdata("usrid",$usrid);
				$this->session->set_userdata("lvl",1);
				$this->session->set_userdata("status",1);

				$this->db->where("id",$_SESSION["otp_id"]);
				$this->db->update("otpdaftar",["status"=>1,"masuk"=>date("Y-m-d H:i:s")]);
				$this->session->unset_userdata("otp_id");
				
				echo json_encode(array("success"=>true,"token"=>$this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
		}
	}
	function signup($pwreset="none"){
		if(isset($_GET["verify"])){
			$selesai = false;
			if(isset($_POST["verify"])){
				$verifid = $this->func->arrEnc($_GET["verify"],"decode");
				$id = $verifid["id"];
				$time = $verifid["time"];
				$selang = intval(date("YmdHis")) - intval($time);

				$this->db->where("id",$id);
				$this->db->update("userdata",array("status"=>1));

				$selesai = true;
			}
			$this->load->view("headv2",array("titel"=>"Verifikasi Alamat Email"));
			$this->load->view("main/sukses_verifikasi",["selesai"=>$selesai]);
			$this->load->view("footv2");
		}elseif(isset($_POST["id"]) AND $pwreset == "kirimulang"){
			$id = $this->func->decode($_POST["id"]);

			if($this->func->verifEmail($id)){
				$this->func->verifWA($id);
				echo json_encode(array("success"=>true,"message"=>"","token"=>$this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"message"=>"alamat email sudah terdaftar","token"=>$this->security->get_csrf_hash()));
			}

		}elseif(isset($_POST["email"]) AND $pwreset == "cekemail"){
			$this->db->where("username",$_POST["email"]);
			$this->db->or_where("nohp",$_POST["email"]);
			$this->db->limit(1);
			$db = $this->db->get("userdata");

			if($db->num_rows() > 0){
				echo json_encode(array("success"=>false,"message"=>"alamat email/no handphone sudah terdaftar","token"=>$this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>true,"message"=>"","token"=>$this->security->get_csrf_hash()));
			}

		}elseif(isset($_POST["email"]) AND $pwreset="none"){
			$this->db->where("username",$_POST["email"]);
			$usd = $this->db->get("userdata");

			if($usd->num_rows() == 0){
				$tgl = $_POST['thn'].'-'.$_POST['bln'].'-'.$_POST['tgl'];
				$insert = array(
					"email"	=> $_POST["email"],
					"nowa"	=> $_POST["nohp"]
				);

				$data = array(
					"username"	=> $_POST["email"],
					"nama"	=> $_POST["nama"],
					"nohp"	=> $_POST["nohp"],
					"password"	=> $this->func->encode($_POST["pass"]),
					"level"		=> 1,
					"status"	=> 0
				);
				$this->db->insert("userdata",$data);
				$usrid = $this->db->insert_id();
				$data = array(
					"usrid"	=> $usrid,
					"nohp"	=> $_POST["nohp"],
					"nama"	=> $_POST["nama"],
					"kelamin"=> $_POST["kelamin"],
					"foto"	=> "user.png",
					"lahir"	=> $tgl
				);
				$this->db->insert("profil",$data);
				$data = array(
					"usrid"	=> $usrid,
					"apdet"	=> date("Y-m-d H:i:s"),
					"saldo"	=> 0
				);
				$this->db->insert("saldo",$data);

				// SEND EMAIL
				$this->func->verifEmail($usrid);
				// SEND WA
				$this->func->verifWA($usrid);

				$res = $this->load->view("main/selesai_daftar",$insert,true);
				echo json_encode(array("success"=>true,"result"=>$res,"token"=>$this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"result"=>"Email sudah terdaftar","token"=>$this->security->get_csrf_hash()));
			}
		}elseif($pwreset=="verifikasi"){
			$this->load->view("headv2",array("titel"=>"Verifikasi Alamat Email"));
			$this->load->view("main/sukses_verifikasi",array("belumverif"=>true));
			$this->load->view("footv2");
		}else{

			$this->load->view("headv2",array("titel"=>"Mendaftar"));
			$this->load->view("signup");
			$this->load->view("footv2");
		}
	}
	function signout(){
		$this->session->sess_destroy();
		redirect();
	}

	// ERROR 404
	public function _404(){
		$this->load->view("headv2",array("titel"=>"Halaman tidak ditemukan"));
		$this->load->view("error404");
		$this->load->view("footv2");
	}
}
