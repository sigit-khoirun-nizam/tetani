<?php
if(!defined('BASEPATH')) exit('Hacking Attempt : Keluar dari sistem !! ');

class Global_data extends CI_Model{
    public function __construct(){
        parent::__construct();
    }

	function demo(){
		return false;
	}

	function clean($string) {
		//$string = str_replace(' ', '-', $string);
		return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string);
	}
	function cleanURL($string) {
		$string = str_replace(' ', '-', $string);
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
	}
	function getSetting($data){
		if($data != "semua"){
		$this->db->where("field",$data);
		}
		$res = $this->db->get("setting");
		$result = null;
		if($data == "semua"){
			$result = array(null);
			foreach($res->result() as $re){
				$result[$re->field] = $re->value;
			}
			$result = (object)$result;
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->value;
			}
		}
		return $result;
	}
	function globalset($data){
		if($data != "semua"){
		$this->db->where("field",$data);
		}
		$res = $this->db->get("setting");
		$result = null;
		if($data == "semua"){
			$result = array(null);
			foreach($res->result() as $re){
				$result[$re->field] = $re->value;
			}
			$result = (object)$result;
		}else{
			$result = "";
			foreach($res->result() as $re){
				$result = $re->value;
			}
		}
		return $result;
	}

	function maintenis(){
		//return true;
		return false;
	}

	function getBintang($idproduk=0){
		$this->db->where("idproduk",$idproduk);
		$db = $this->db->get("review");
		$total = 0;
		foreach($db->result() as $res){
			$total += $res->nilai;
		}
		$total = ($total > 0 OR $db->num_rows() > 0) ? $total / $db->num_rows() : 0;
		$nilai = round($total,0,PHP_ROUND_HALF_DOWN);
		return array("star"=>$nilai,"jml"=>$db->num_rows());
	}
	
	function getPesanNotif(){
		$this->db->select("id");
		$this->db->where("baca",0);
		$this->db->where("tujuan",$_SESSION["usrid"]);
		$db = $this->db->get("pesan");
		
		return $db->num_rows();
	}

	function getCategory($data="option",$cat=0){
		if($data == "option"){
			$this->db->where("parent","0");
			$sql = $this->db->get("kategori");
			$result = "";

			foreach($sql->result() as $res){
				$select = ($res->id == $cat) ? "selected" : "";
				$result .= "<option value='".$res->id."' ".$select.">".$res->nama."</option>";
			}
			return $result;
		}else{
			return "data not found";
		}
	}

	function getFoto($id,$kat="utama"){
		$server = base_url('cdn/uploads');
		$this->db->where("idproduk",$id);
		if($kat == "utama"){
			$this->db->where("jenis",1);
		}
		$this->db->limit(1);
		$res = $this->db->get("upload");

		$result = base_url("assets/images/favicon.png");
		foreach($res->result() as $re){
			$result = $server.'/'.$re->nama;
		}
		return $result;
	}
	function getUpload($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$res = $this->db->get("upload");
		foreach($res->result() as $re){
			$result = $re->$what;
		}
		return $result;
	}
	function getFotoUpload($id,$what,$opo="id"){
		$this->db->where("idproduk",$id);
		if($opo == "utama"){
			$this->db->where("jenis",1);
		}
		$res = $this->db->get("upload");
		foreach($res->result() as $re){
			$result = $re->$what;
		}
		return $result;
	}
	
	// RANDOM WASAP
	function getRandomWasap(){
		$this->db->order_by("tgl","ASC");
		$this->db->limit(1);
		$res = $this->db->get("wasap");
		
		$result = 0;
		foreach($res->result() as $r){
			if(substr($r->wasap,0,1) == 0){
				$result = "+62".substr($r->wasap,1);
			}elseif(substr($r->wasap,0,2) == "62"){
				$result = "+".$r->wasap;
			}elseif(substr($r->wasap,0,1) == "+"){
				$result = $r->wasap;
			}
		}
		return $result;
	}

	// RESET USERDATA
	function resetData(){
		$this->session->unset_userdata("securesearch");
	}

	// CEK LOGIN
	function cekLogin(){
		if(isset($_SESSION['usrid']) AND isset($_SESSION['lvl'])){
			$this->db->where("id",$_SESSION['usrid']);
			$this->db->update("userdata",array("tgl"=>date("Y-m-d H:i:s")));

			return $_SESSION["usrid"];
		}else{
			return 0;
		}
	}
	function randomPassword() {
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i <= 16; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
	function resetPass($email){
		$usrid = $this->getUser($email,"id","username");
		if($usrid > 0){
		$user = $this->getUser($usrid,"semua");
		$profil = $this->getProfil($usrid,"semua","usrid");
		$generated = $this->randomPassword();
		$this->db->where("id",$usrid);
		$this->db->update("userdata",array("password"=>$this->encode($generated)));

		$pesan = '
			<html>
			<head>
				<style>
				.border{width:90%;padding:20px;border:1px solid #ccc;border-radius:3px;margin:auto;}
				.pesan{margin-bottom:30px;}
				.link{margin-bottom:20px;}
				.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}
				</style>
			</head>
			<body>
				<div class="border">
				<div class="pesan">
					<h3>Halo, '.$profil->nama.'</h3><p/>
					Selamat, reset password Anda berhasil dan untuk login ke akun Anda, silahkan menggunakan password dibawah:<br/>
					Pass: '.$generated.'<p/>&nbsp;<p/>
					Segera masuk dan ganti password Anda untuk meningkatkan keamanan akun Anda kembali.<p/>
				</div>
				<div class="link">
					<a class="alink" style="color:#fff;" href="'.site_url("home/signin").'">LOGIN DISINI</a>
				</div>
				</div>
			</body>
			</html>
		';
		$pesanWA = '
			Halo, *'.$profil->nama.'* \n'.
			'Selamat, reset password Anda berhasil dan untuk login ke akun Anda, silahkan menggunakan password dibawah: \n'.
			'Pass: '.$generated.' \n \n'.
			'Segera masuk dan ganti password Anda untuk meningkatkan keamanan akun Anda kembali.
			';
		if($this->sendEmail($user->username,$this->getSetting("nama"),$pesan,"Reset password")){
			$this->sendWA($profil->nohp,$pesanWA);
			return true;
		}else{
			return false;
		}
		}else{
		return false;
		}
	}

	// VERIFIKASI
	function sendEmail($tujuan,$judul,$pesan,$subyek,$pengirim=null){
		$data = array(
			"jenis"		=> 1,
			"tujuan"	=> $tujuan,
			"judul"		=> $judul,
			"pesan"		=> $pesan,
			"subyek"	=> $subyek,
			"pengirim"	=> $pengirim,
			"tgl"		=> date("Y-m-d H:i:s"),
			"status"	=> 0
		);
		$this->db->insert("notifikasi",$data);

		return true;
	}
	function sendEmailOK($tujuan,$judul,$pesan,$subyek,$pengirim=null){
		$this->load->library('email');
		$seting = $this->getSetting("semua");
		if($seting->email_jenis == 2){
			$config['protocol'] = "smtp";
			$config['smtp_host'] = $seting->email_server;
			$config['smtp_port'] = $seting->email_port;
			$config['smtp_user'] = $seting->email_notif;
			$config['smtp_pass'] = $seting->email_password;

			if($seting->email_port == 465){
				$config['smtp_crypto'] = "ssl";
			}
		}
		$config['charset'] = "utf-8";
		$config['mailtype'] = "html";
		$config['newline'] = "\r\n";
		$this->email->initialize($config);

		$this->email->from($seting->email_notif, $judul);
		$this->email->to($tujuan);
		if($pengirim != null){
		$this->email->cc($pengirim);
		}

		$pesan = $this->load->view("main/email_template",array("content"=>$pesan),true);
		$this->email->subject($subyek);
		$this->email->message($pesan);

		if($this->email->send()){
			return true;
		}else{
		//show_error($this->email->print_debugger());
			return false;
		}
	}
	public function sendWA($nomer,$pesan){
		$data = array(
			"jenis"		=> 2,
			"tujuan"	=> $nomer,
			"pesan"		=> $pesan,
			"tgl"		=> date("Y-m-d H:i:s"),
			"status"	=> 0
		);
		$this->db->insert("notifikasi",$data);
		
		return true;
	}
	public function sendWAOK($nomer,$pesan){
		$set = $this->getSetting("semua");
		$nomer = intval($nomer);

		if($set->api_wasap == "woowa"){
			$key = $set->woowa;
			if($key == ""){
				return false;
				exit;
			}

			$nomer = substr($nomer,0,2) != "62" ? "+62".$nomer : "+".$nomer;
			$url='http://116.203.92.59/api/send_message';
			$data = array(
			"phone_no"	=> $nomer,
			"key"		=> $key,
			"message"	=> $pesan."\n".date("Y/m/d H:i:s")
			);
			$data_string = json_encode($data);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_VERBOSE, 0);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 360);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
			);
			$res = curl_exec($ch);
			curl_close($ch);

			if($res == "Success"){
				return true;
			}else{
				return false;
			}
		}elseif($set->api_wasap == "wablas"){
			$token = $set->wablas;
			if($token == "" OR $set->wablas_server == ""){
				return false;
				exit;
			}

			$nomer = substr($nomer,0,2) != "62" ? "62".$nomer : $nomer;
			$curl = curl_init();
			$payload = [
				"data" => [
					[
						'phone' => $nomer,
						'message' => $pesan
					]
				]
			];
			
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload) );
			curl_setopt($curl, CURLOPT_URL, $set->wablas_server."/api/v2/send-bulk/text");
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json",
				"Authorization: ".$token
				)
			);
			$result = curl_exec($curl);
			curl_close($curl);
			
			//echo "<pre>";
			$res = json_decode($result);
			if($res->status > 0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	function verifEmail($id=0){
		if($id > 0){
			$profil = $this->getProfil($id,"semua","usrid");
			$user = $this->getUser($id,"semua");
			$verifid = $this->arrEnc(array("id"=>$id,"time"=>date("YmdHis")));
			$subyek = 'Verifikasi Pendaftaran '.$this->getSetting("nama");
			$pesan = '
				<html>
				<head>
					<style>
					.border{padding:20px;border-radius:3px;margin:auto;}
					.pesan{margin-bottom:30px;}
					.link{margin-bottom:20px;}
					.alink{text-decoration:none;background:#c0392b;padding:10px 24px;border-radius:3px;margin-bottom:20px;}
					</style>
				</head>
				<body>
					<div class="border">
					<div class="pesan">
					<h3>Halo, '.$profil->nama.'</h3><p/>
					Terima kasih sudah mendaftar di <b>'.$this->getSetting("nama").'</b>, untuk mengaktifkan akun Anda, silahkan klik link berikut:<br/>
					</div>
					<div class="link">
						<a class="alink" style="color:#fff;" href="'.site_url("home/signup?verify=".$verifid).'">VERIFIKASI AKUN '.strtoupper(strtolower($this->globalset("nama"))).'</a>
						<br/>&nbsp;<br/>atau link dibawah ini<br/>
						<a href="'.site_url("home/signup?verify=".$verifid).'">'.site_url("home/signup?verify=".$verifid).'</a>
					</div>
					</div>
				</body>
				</html>
			';

			if($this->sendEmail($user->username,$this->getSetting("nama"),$pesan,$subyek)) {
				return true;
			} else {
				return false;
			}
		}
	}
	function verifWA($id=0){
		if($id > 0){
			$profil = $this->getProfil($id,"semua","usrid");
			$user = $this->getUser($id,"semua");
			$verifid = $this->arrEnc(array("id"=>$id,"time"=>date("YmdHis")));
			$subyek = 'Verifikasi Pendaftaran '.$this->getSetting("nama");
			$pesan = '
				Halo, *'.$profil->nama.'* \n \n'.'Terima kasih sudah mendaftar di *'.$this->getSetting("nama").'*, untuk mengaktifkan akun Anda, silahkan klik link berikut:\n'.site_url("home/signup?verify=".$verifid).' \n'.'_*Apabila link tidak bisa di klik, simpan nomer whatsapp ini terlebih dahulu_
			';

			if($this->sendWA($profil->nohp,$pesan)) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	// SEND NOTIF
	function notiftransfer($order_id=null){
		if($order_id != null){
			$bayar = $this->getBayar($order_id,"semua");
			$trx = $this->getTransaksi($bayar->id,"semua","idbayar");
			$alamat = $this->getAlamat($trx->alamat,"semua");
			$usr = $this->getUser($bayar->usrid,"semua");
			$diskon = $bayar->diskon != 0 ? "Diskon: <b>Rp ".$this->formUang($bayar->diskon)."</b><br/>" : "";
			$diskonwa = $bayar->diskon != 0 ? "Diskon: *Rp ".$this->formUang($bayar->diskon)."*\n" : "";
			$toko = $this->getSetting("semua");

			$rekening = "";
			$rekeningwa = "";
			$this->db->where("usrid",0);
			$rek = $this->db->get("rekening");
			foreach($rek->result() as $res){
				$bank = strtoupper($this->getBank($res->idbank,"nama"));
				$rekening .= "
					<b>".$bank." - ".$res->norek."</b><br/>
					a/n ".$res->atasnama."<br/>
				";
				$rekeningwa .= "
					*".$bank." - ".$res->norek."* \n
					a/n ".$res->atasnama." \n
				";
			}

			$pesan = "
				Halo <b>".$usr->nama."</b><br/>".
				"Terimakasih, sudah membeli produk kami.<br/>".
				"Segera lakukan pembayaran agar pesananmu segera diproses<br/> <br/>".
				"<b>Transfer pembayaran ke rekening berikut</b><br/>".
				$rekening.
				"<br/>".
				"<b>Detail Pesanan</b><br/>".
				"No Invoice: <b>#".$bayar->invoice."</b><br/>".
				"Total Pesanan: <b>Rp ".$this->formUang($bayar->total)."</b><br/>".
				"Ongkos Kirim: <b>Rp ".$this->formUang($trx->ongkir)."</b><br/>".$diskon.
				"Kurir Pengiriman: <b>".strtoupper($trx->kurir." ".$trx->paket)."</b><br/> <br/>".
				"Detail Pengiriman <br/>".
				"Penerima: <b>".$alamat->nama."</b> <br/>".
				"No HP: <b>".$alamat->nohp."</b> <br/>".
				"Alamat: <b>".$alamat->alamat."</b>".
				"<br/> <br/>".
				"Informasi cara pembayaran dan status pesananmu langsung di menu:<br/>".
				"<a href='".site_url("manage/pesanan")."'>PESANANKU &raquo;</a>
			";
			$this->sendEmail($usr->username,$toko->nama,$pesan,"Pesanan Dibatalkan");
			$pesan = "
				Halo *".$usr->nama."* \n".
				"Terimakasih, sudah membeli produk kami. \n".
				"Segera lakukan pembayaran agar pesananmu segera diproses \n \n".
				"*Transfer pembayaran ke rekening berikut:* \n".
				$rekeningwa."\n".
				" \n".
				"*Detail Pesanan* \n".
				"No Invoice: *#".$bayar->invoice."* \n".
				"Total Pesanan: *Rp ".$this->formUang($bayar->total)."* \n".
				"Ongkos Kirim: *Rp ".$this->formUang($trx->ongkir)."* \n".$diskon.
				"Kurir Pengiriman: *".strtoupper($trx->kurir." ".$trx->paket)."* \n  \n".
				"Detail Pengiriman  \n".
				"Penerima: *".$alamat->nama."*  \n".
				"No HP: *".$alamat->nohp."*  \n".
				"Alamat: *".$alamat->alamat."*".
				" \n  \n".
				"Informasi cara pembayaran dan status pesananmu langsung di menu: \n".
				"*PESANANKU*
			";
			$this->sendWA($this->getProfil($usr->id,"nohp","usrid"),$pesan);
		}
	}
	function notifbatal($order_id=null,$jenis=1){
		if($order_id != null){
			$bayar = $this->getBayar($order_id,"semua");
			$trx = $this->getTransaksi($bayar->id,"semua","idbayar");
			$alamat = $this->getAlamat($trx->alamat,"semua");
			$usr = $this->getUser($bayar->usrid,"semua");
			$diskon = $bayar->diskon != 0 ? "Diskon: <b>Rp ".$this->formUang($bayar->diskon)."</b><br/>" : "";
			$diskonwa = $bayar->diskon != 0 ? "Diskon: *Rp ".$this->formUang($bayar->diskon)."*\n" : "";
			$toko = $this->getSetting("semua");
			
			if($jenis == 1){
				$alasan = "DIBATALKAN OLEH ADMIN";
			}elseif($jenis == 2){
				$alasan = "DIBATALKAN OLEH PEMBELI";
			}elseif($jenis == 3){
				$alasan = "TELAH MELEWATI BATAS WAKTU JATUH TEMPO PEMBAYARAN";
			}else{
				$alasan = "-";
			}

			$pesan = "
				Halo <b>".$usr->nama."</b><br/>".
				"Pesanan Anda telah dibatalkan<br/>".
				"Status: <br/>".
				"<b>".$alasan."</b><br/>".
				"<br/>".
				"<b>Detail Pesanan</b><br/>".
				"No Invoice: <b>#".$bayar->invoice."</b><br/>".
				"Total Pesanan: <b>Rp ".$this->formUang($bayar->total)."</b><br/>".
				"Ongkos Kirim: <b>Rp ".$this->formUang($trx->ongkir)."</b><br/>".$diskon.
				"Kurir Pengiriman: <b>".strtoupper($trx->kurir." ".$trx->paket)."</b><br/> <br/>".
				"Detail Pengiriman <br/>".
				"Penerima: <b>".$alamat->nama."</b> <br/>".
				"No HP: <b>".$alamat->nohp."</b> <br/>".
				"Alamat: <b>".$alamat->alamat."</b>".
				"<br/> <br/>".
				"Informasi cara pembayaran dan status pesananmu langsung di menu:<br/>".
				"<a href='".site_url("manage/pesanan")."'>PESANANKU &raquo;</a>
			";
			$this->sendEmail($usr->username,$toko->nama,$pesan,"Pesanan Dibatalkan");
			$pesan = "
				Halo *".$usr->nama."* \n".
				"Pesanan Anda telah dibatalkan \n".
				"Status: \n".
				"*".$alasan."* \n".
				" \n".
				"*Detail Pesanan* \n".
				"No Invoice: *#".$bayar->invoice."* \n".
				"Total Pesanan: *Rp ".$this->formUang($bayar->total)."* \n".
				"Ongkos Kirim: *Rp ".$this->formUang($trx->ongkir)."* \n".$diskon.
				"Kurir Pengiriman: *".strtoupper($trx->kurir." ".$trx->paket)."* \n  \n".
				"Detail Pengiriman  \n".
				"Penerima: *".$alamat->nama."*  \n".
				"No HP: *".$alamat->nohp."*  \n".
				"Alamat: *".$alamat->alamat."*".
				" \n  \n".
				"Informasi cara pembayaran dan status pesananmu langsung di menu: \n".
				"*PESANANKU*
			";
			$this->sendWA($this->getProfil($usr->id,"nohp","usrid"),$pesan);
		}
	}

	// GET VOUCHERs
	function getVoucher($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("voucher");

		if($what == "semua"){
			if($res->num_rows() == 0){ $result = array(0); }
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = 0;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}


	// GET WISHLIST
	function getWishlist($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("wishlist");

		if($what == "semua"){
			if($res->num_rows() == 0){ $result = array(0); }
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = 0;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function cekWishlist($id){
		$usrid = isset($_SESSION["usrid"]) ? $_SESSION["usrid"] : 0;
		$this->db->where("idproduk",$id);
		$this->db->where("usrid",$usrid);
		$res = $this->db->get("wishlist");

		if($res->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	function getWishlistCount(){
		$this->db->where("usrid",$_SESSION["usrid"]);
		$res = $this->db->get("wishlist");
		
		return $res->num_rows();
	}

	// GET PREORDER
	function getPreorder($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("preorder");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// GET KATEGORI
	function getKategori($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("kategori");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
				$result = $result[0];
			}
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// GET TOKO
	function getToko($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("toko");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	/*function lastOnline($id){
		$this->db->where("id",$id);
		$db = $this->db->get("toko");
		foreach($db->result() as $res){
			$online = $res->online;
			//$online = $this->ubahTgl("YmdHi",$online);
			$online = date("Y-m-d H:i:s") - $online;
		}
		if($online > 1440){
			//$hari = $online/1440;
			//$hari = round($hari,0,PHP_ROUND_HALF_DOWN);
			return date("j",$hari)." hari yang lalu";
		}elseif($online <= 1440 AND $online > 60){
			//$jam = round($online/60,0,PHP_ROUND_HALF_DOWN);
			return date("G",$jam)." jam yang lalu";
		}else{
			return $online." menit yang lalu";
		}
	}	*/
	function lastOnline($id){
		$this->db->where("id",$id);
		$db = $this->db->get("toko");
		foreach($db->result() as $res){
			$time = strtotime($res->online);
		}

		$SECOND = 1;
		$MINUTE = 60 * $SECOND;
		$HOUR = 60 * $MINUTE;
		$DAY = 24 * $HOUR;
		$MONTH = 30 * $DAY;
		$before = time() - $time;

		if ($before <= 0){
			return "tidak pernah";
		}

		if ($before < 1 * $MINUTE){
			return ($before <= 1) ? "sekarang" : $before . " detik yg lalu";
		}
		if ($before < 45 * $MINUTE){
			return floor($before / 60) . " menit yg lalu";
		}
		if ($before < 24 * $HOUR){

			return (floor($before / 60 / 60) == 1 ? 'sekitar sejam' : floor($before / 60 / 60).' jam'). " yg lalu";
		}
		if ($before < 48 * $HOUR){
			return "kemarin";
		}
		if ($before < 30 * $DAY){
			return floor($before / 60 / 60 / 24) . " hari yg lalu";
		}
		if ($before < 12 * $MONTH){

			$months = floor($before / 60 / 60 / 24 / 30);
			return $months <= 1 ? "one month ago" : $months . " bulan yg lalu";
		}
		else
		{
			$years = floor  ($before / 60 / 60 / 24 / 30 / 12);
			return $years <= 1 ? "setahun yg lalu" : $years." tahun yg lalu";
		}

		return "$time";
	}
	function getNilaiToko($id){
		$this->db->where("idtoko",$id);
		$db = $this->db->get("review");
		$rows = $db->num_rows();
		$total = 0;
		foreach($db->result() as $res){
			$total += $res->nilai;
		}
		$nilai = $total > 0 ? round($total/$rows,0) : 0;

		if($nilai > 0){ return $nilai; }
		else{ return "Belum ada penilaian"; }
	}
	function getFollowers($id){
		$this->db->where("idtoko",$id);
		$db = $this->db->get("followers");
		$rows = $db->num_rows();
		return $rows;
	}
	function getTotalProduk($id){
		$this->db->where("idtoko",$id);
		$db = $this->db->get("produk");
		$rows = $db->num_rows();
		return $rows;
	}
	function getTerjual($id){
		$this->db->where("idtoko",$id);
		$this->db->where("status",3);
		$db = $this->db->get("transaksi");
		$rows = $db->num_rows();
		return $rows;
	}

	// GET TRANSAKSI
	function getTransaksi($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("transaksi");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getTransaksiProduk($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("transaksiproduk");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// GET PESAN
	function getPesan($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("pesan");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// GET USERDATA
	function getProfil($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("profil");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getUser($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("userdata");

		if($what == "semua"){
			$result = array(0);
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// GET REVIEW ULASAN
	function getReview($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("review");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getReviewProduk($id){
		$this->db->where("idproduk",$id);
		$res = $this->db->get("review");

		$count = 0;
		foreach($res->result() as $r){
			$count += $r->nilai;
		}
		$result = $count > 0 ? round($count/$res->num_rows(),1) : 0;
		$result = ["nilai"=>$result,"ulasan"=>$res->num_rows()];
		return $result;
	}

	// GET BANK
	function getBank($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("rekeningbank");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getRekening($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("rekening");

		if($what == "semua"){
			$result = array();
      if($res->num_rows() > 0){
  			foreach($res->result() as $key => $value){
  				$result[$key] = $value;
  			}
  			$result = $result[0];
      }
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	
	// GET PRODUK
	function getProduk($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("produk");

		if($what == "semua"){
			$result = null;
			if($res->num_rows() > 0){
				foreach($res->result() as $key => $value){
					$result[$key] = $value;
				}
				$result = $result[0];
			}
		}else{
			$result = null;
			foreach($res->result() as $re){
				if($what == "harga"){
					$level = isset($_SESSION["lvl"]) ? $_SESSION["lvl"] : "";
					if($level == 3){
						$result = $re->hargaagen;
					}elseif($level == 2){
						$result = $re->hargareseller;
					}else{
						$result = $re->harga;
					}
				}else{
					$result = $re->$what;
				}
			}
		}
		return $result;
	}
	function getVariasi($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("produkvariasi");

		if($what == "semua"){
			$result = array();
      if($res->num_rows() > 0){
  			foreach($res->result() as $key => $value){
  				$result[$key] = $value;
  			}
  			$result = $result[0];
      }
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getWarna($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("variasiwarna");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getSize($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("variasisize");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	
	// GET ALAMAT
	function getAlamat($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("alamat");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// KERANJANG BELANJA
	function getKeranjang(){
		if($this->cekLogin() == true){
			$this->db->where("idtransaksi",0);
			$this->db->where("usrid",$_SESSION["usrid"]);
			$db = $this->db->get("transaksiproduk");
			$keranjang = $db->num_rows();
			return $keranjang;
		}else{
			return 0;
		}
	}

	// GET PENGIRIMAN
	function getPengiriman($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("pengiriman");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// GET LOKASI
	function getKec($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("kec");

		$result = "kecamatan tidak ditemukan";
    if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getKab($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("kab");

		$result = "kabupaten tidak ditemukan";
    if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
  		foreach($res->result() as $re){
  			if(is_array($what)){
  				$result = array();
  				for($i=0; $i<count($what); $i++){
  					$result[$what[$i]] = $re->$what[$i];
  				}
  			}else{
  				$result = $re->$what;
  			}
  		}
    }
		return $result;
	}
	function getProv($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("prov");

		$result = "provinsi tidak ditemukan";
		foreach($res->result() as $re){
			$result = $re->$what;
		}
		return $result;
	}

	// PEMBAYARAN
	function getBayar($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("pembayaran");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = ($res->num_rows() > 0) ? $result[0] : null;
		}else{
			$result = null;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// KURIR & PAKET
	function getKurir($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("kurir");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = $id == 0 ? "COD" : 0;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getPaket($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("paket");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = $id == 0 ? "Bayar Ditempat" : 0;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// SALDO WARGA
	function getSaldo($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("saldo");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = 0;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getSaldodarike($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("saldodarike");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = 0;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getSaldohistory($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("saldohistory");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = 0;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function getSaldotarik($id,$what,$opo="id"){
		$this->db->where($opo,$id);
		$this->db->limit(1);
		$res = $this->db->get("saldotarik");

		if($what == "semua"){
			$result = array();
			foreach($res->result() as $key => $value){
				$result[$key] = $value;
			}
			$result = $result[0];
		}else{
			$result = 0;
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}

	// STATUS PESANAN
	function addStatusPesanan($idtrx,$status,$updater,$ket){

	}

	// ONGKIR
	function getHistoryOngkir($id,$what="id",$opo="id"){
		if(is_array($id)){
			foreach($id as $key => $val){
				$this->db->where($key,$val);
			}
			$this->db->limit(1);
			$res = $this->db->get("historyongkir");

			$result = "tidak ditemukan";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}else{
			$this->db->where($opo,$id);
			$this->db->limit(1);
			$res = $this->db->get("historyongkir");

			$result = "tidak ditemukan";
			foreach($res->result() as $re){
				$result = $re->$what;
			}
		}
		return $result;
	}
	function beratkg($berat=0,$kurir="jne"){
		$beratkg = ($berat < 1000) ? 1 : round(intval($berat) / 1000,0,PHP_ROUND_HALF_DOWN);
		if($kurir == "jne"){
			$selisih = $berat - ($beratkg * 1000);
			if($selisih > 300){
				$beratkg = $beratkg + 1;
			}
		}elseif($kurir == "jnt"){
			$selisih = $berat - ($beratkg * 1000);
			if($selisih > 200){
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
		return $beratkg;
	}

	// USABLE FUNCTION
	function encode($string){
		return $this->encryption->encrypt($string);
	}
	function decode($string){
		return $this->encryption->decrypt($string);
	}

	function getLabelCOD($cod=1){
		switch($cod){
			case 0: $label = "<span class='label tooltip'>Rekber Saja<span class='tooltiptext'>produk ini hanya bisa dibeli melalui rekber BELIWARGA</span></span>";
			break;
			case 1: $label = "<span class='label tooltip'>Rekber<span class='tooltiptext'>produk ini bisa dibeli melalui rekber BELIWARGA</span></span><span class='label tooltip'>COD<span class='tooltiptext'>produk ini bisa dibeli langsung dengan bertemu penjual tanpa melalui rekber</span></span>";
			break;
			case 2: $label = "<span class='label tooltip'>COD Saja<span class='tooltiptext'>produk ini hanya bisa dibeli langsung dengan bertemu penjual</span></span>";
			break;
		}
		return $label;
	}
	function potong($str,$max,$after=""){
		if(strlen($str) > $max){
			$str = substr($str, 0, $max);
			$str = rtrim($str).$after;
		}
		return $str;
	}
	function formUang($format){
		$result= number_format($format,0,",",".");
		return $result;
	}
	function ubahTgl($format, $tanggal="now", $bahasa="id"){
		$en = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		$id = array("Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");

		return str_replace($en,$$bahasa,date($format,strtotime($tanggal)));
	}
	function arrEnc($arr,$type="encode"){
		if($type == "encode"){
			$result = base64_encode(serialize($arr));
		}else{
			$result = unserialize(base64_decode($arr));
		}
		return $result;
	}
	function starRating($star=1){
		$star = "<i class='fa fa-star'></i>";
		$staro = "<i class='fa fa-star-o'></i>";
		$starho = "<i class='fa fa-star-half-o'></i>";
		if($star == 1){
			$hasil = $star.$staro.$staro.$staro.$staro;
		}
	}
	function createPagination($rows,$page,$perpage=10,$function="refreshTabel"){
		$tpages = ceil($rows/$perpage);
		$reload = "";
        $adjacents = 2;
		$prevlabel = "&lsaquo;";
		$nextlabel = "&rsaquo;";
		$out = "<div class=\"pagination\">";
		// previous
		if ($page == 1) {
			$out.= "";
		} else {
			$out.="<a href=\"javascript:void(0)\" class='item' onclick=\"".$function."(1)\">&laquo;</a>\n";
			$out.="<a href=\"javascript:void(0)\" class='item' onclick=\"".$function."(".($page - 1).")\">".$prevlabel."</a>\n";
		}
		$pmin=($page>$adjacents)?($page - $adjacents):1;
		$pmax=($page<($tpages - $adjacents))?($page + $adjacents):$tpages;
		for ($i = $pmin; $i <= $pmax; $i++) {
			if ($i == $page) {
				$out.= "<a href=\"javascript:void(0)\" class='item active'>".$i."</a>\n";
			} elseif ($i == 1) {
				$out.= "<a href=\"javascript:void(0)\" class='item' onclick=\"".$function."(".$i.")\">".$i."</a>\n";
			} else {
				$out.= "<a href=\"javascript:void(0)\" class='item' onclick=\"".$function."(".$i.")\">".$i. "</a>\n";
			}
		}

		// next
		if ($page < $tpages) {
			$out.= "<a href=\"javascript:void(0)\" onclick=\"".$function."(".($page + 1).")\" class='item'>".$nextlabel."</a>\n";
		}
		if($page < ($tpages - $adjacents)) {
			$out.= "<a href=\"javascript:void(0)\" onclick=\"".$function."(".$tpages.")\" class='item'>&raquo;</a>\n";
		}
		$out.= "</div>";
		return $out;
	}
}
