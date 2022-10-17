<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require '../application/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Api extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		redirect();
	}
	
	// CETAK MENCETAK
	function cetakInvoice(){
		$this->load->view("print/invoice");
	}
	function cetakLabel(){
		$this->load->view("print/label");
	}
	
	// KURIR
	function aktifkankurir(){
		if(isset($_POST["push"])){
			$toko = $this->func->globalset("kurir");
			$kurir = explode("|",$toko);
			$kurir[] = $this->security->xss_clean($_POST["push"]);
			$push = implode("|",$kurir);
			
			$this->db->where("field","kurir");
			$this->db->update("setting",array("value"=>$push,"tgl"=>date("Y-m-d H:i:s")));

			echo json_encode(array("success"=>true,"msg"=>"Berhasil mengupdate profil","token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
		}
	}
	function nonaktifkankurir(){
		if(isset($_POST["push"])){
			$toko = $this->func->globalset("kurir");
			$kurir = explode("|",$toko);
			for($i=0; $i<count($kurir); $i++){
				if($kurir[$i] == $_POST["push"]){
					unset($kurir[$i]);
				}
			}
			array_values($kurir);
			$push = implode("|",$kurir);
				
			$this->db->where("field","kurir");
			$this->db->update("setting",array("value"=>$push,"tgl"=>date("Y-m-d H:i:s")));

			echo json_encode(array("success"=>true,"msg"=>"Berhasil mengupdate profil","token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"msg"=>"Forbidden!"));
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
						if($trx->status < 3){
							$this->db->where("id",$trx->id);
							$this->db->update("transaksi",["status"=>3,"selesai"=>date("Y-m-d H:i:s")]);
						}
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

	// IMPORT PRODUK
	public function import(){
		if(isset($_POST)){
			$config['upload_path'] = './import/';
			$config['allowed_types'] = 'xls|xlsx|csv';
			$config['file_name'] = "import_".date("YmdHis");

			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('fileupload')){
				$error = $this->upload->display_errors();
				echo json_encode(array("success"=>false,"msg"=>$error,"token"=> $this->security->get_csrf_hash()));
			}else{
				$uploadData = $this->upload->data();
				$inputFileName = FCPATH."import/".$uploadData["file_name"];

				if(!file_exists($inputFileName)){
					echo json_encode(array("success"=>false,"msg"=>"file tidak ditemukan"));
					exit;
				}

				//$file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				//if(mime_content_type($inputFileName) !== null && in_array(mime_content_type($inputFileName), $file_mimes)) {
					$extension = pathinfo($inputFileName, PATHINFO_EXTENSION);
				
					if('csv' == $extension) {
						$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
					} else {
						$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
					}
				
					$spreadsheet = $reader->load($inputFileName);
					
					$sheetData = $spreadsheet->getActiveSheet()->toArray();
					if(count($sheetData) > 1){
						for($i=1; $i<count($sheetData); $i++){
							$string = $this->clean($sheetData[$i]['1']);
							$data = array(
								"tglbuat"	=> date("Y-m-d H:i:s"),
								"tglupdate"	=> date("Y-m-d H:i:s"),
								"nama"		=> $sheetData[$i]['0'],
								"url"		=> $string."-".date("His"),
								"kode"		=> $sheetData[$i]['1'],
								"deskripsi"	=> $sheetData[$i]['2'],
								"idcat"		=> $sheetData[$i]['3'],
								"berat"		=> $sheetData[$i]['4'],
								"harga"		=> $sheetData[$i]['5'],
								"hargareseller"	=> $sheetData[$i]['6'],
								"hargaagen"		=> $sheetData[$i]['7'],
								"hargaagensp"	=> $sheetData[$i]['8'],
								"hargadistri"	=> $sheetData[$i]['9'],
								"minorder"	=> $sheetData[$i]['10'],
								"variasi"	=> $sheetData[$i]['11'],
								"subvariasi"=> $sheetData[$i]['12'],
								"status"	=> $sheetData[$i]['13']
							);
							$this->db->insert("produk",$data);
							$idpro = $this->db->insert_id();

							for($a=1; $a<=4; $a++){
								$e = ($a-1 > 0) ? 3 * ($a - 1) : 0;
								$nom = 14 + $e;
								if($sheetData[$i][$nom] != ""){
									$var = array(
										"idproduk"	=> $idpro,
										"tgl"		=> date("Y-m-d H:i:s"),
										"warna"		=> $sheetData[$i][$nom],
										"size"		=> $sheetData[$i][$nom+1],
										"stok"		=> $sheetData[$i][$nom+2]
									);
									$this->db->insert("produkvariasi",$var);
								} 
							}
						}
						echo json_encode(array("success"=>true,"msg"=>"berhasil mengimpor produk","token"=> $this->security->get_csrf_hash()));
					}else{
						echo json_encode(array("success"=>false,"msg"=>"file tidak sesuai atau kosong","token"=> $this->security->get_csrf_hash()));
					}
				//}else{
				//	echo json_encode(array("success"=>false,"msg"=>"file tidak sesuai: ".mime_content_type($inputFileName." | ".$inputFileName)));
				//}
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"form not submitted!"));
		}
	}
	
	// TAMBAH UBAH PRODUK
	public function hapusProduk(){
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$this->db->where("id",$_POST["id"]);
			$this->db->delete("produk");

			$this->db->where("idproduk",$_POST["id"]);
			$this->db->delete("produkvariasi");
			$this->db->where("idproduk",$_POST["id"]);
			$this->db->delete("upload");
			/*$this->db->where("idproduk",$_POST["id"]);
			$this->db->delete("sundul");*/

			echo json_encode(array("success"=>true,"msg"=>"berhasil menghapus","token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"msg"=>"form not submitted!"));
		}
	}
	public function tambahProduk(){
		if(isset($_POST)){
			if($_SESSION["uploadedPhotos"] != 0){
				$tgl = date("Y-m-d H:i:s");
				$string = $this->clean($_POST["nama"]);
				$arr = $_POST;
				$arr2 = array(
					"tglbuat"	=> $tgl,
					"tglupdate"	=> $tgl,
					"url"		=> $string."-".date("His")
				);
				$data = array_merge($arr,$arr2);
				$this->db->insert("produk",$data);
				$insertid = $this->db->insert_id();

				if(isset($_SESSION["fotoProduk"]) AND count($_SESSION["fotoProduk"]) > 0){
					for($i=0; $i<count($_SESSION["fotoProduk"]); $i++){
						$this->db->where("id",$_SESSION["fotoProduk"][$i]);
						$this->db->update("upload",array("idproduk"=>$insertid));
					}
					$this->session->unset_userdata("fotoProduk");
				}

				echo json_encode(array("success"=>true,"msg"=>"berhasil","id"=>$insertid,"token"=> $this->security->get_csrf_hash()));
			}else{
				echo json_encode(array("success"=>false,"msg"=>"foto wajib di isi: ".$_SESSION["uploadedPhotos"],"token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"form not submitted!"));
		}
	}
	function simpanvariasi($id=0){
		if(isset($_POST["id"])){
			for($i=0; $i<count($_POST["id"]); $i++){
				$data = [
					"idproduk"	=> $id,
					"warna"	=> $_POST["warna"][$i],
					"size"	=> $_POST["size"][$i],
					"stok"	=> $_POST["stok"][$i],
					"kuota"	=> $_POST["stok"][$i],
					"harga"	=> $_POST["harga"][$i],
					"hargareseller"	=> $_POST["hargareseller"][$i],
					"hargaagen"		=> $_POST["hargaagen"][$i],
					"hargaagensp"	=> $_POST["hargaagensp"][$i],
					"hargadistri"	=> $_POST["hargadistri"][$i],
					"tgl"	=> date("Y-m-d H:i:s")
				];
				
				if($_POST["id"][$i] != 0){
					if($this->func->getProduk($id,"preorder") > 0){
						$this->db->where("variasi",$_POST["id"][$i]);
						$t = $this->db->get("preorder");
						$tot = 0;
						foreach($t->result() as $r){
							$tot += $r->jumlah;
						}
						
						if($_POST["stok"][$i] < $tot){
							echo json_encode(array("success"=>false,"msg"=>"stok variasi harus lebih dari jumlah pre order masuk [jumlah pre order masuk: $tot]","token"=> $this->security->get_csrf_hash()));
							exit;
						}
					}
				
					$this->db->where("id",$_POST["id"][$i]);
					$this->db->update("produkvariasi",$data);
				}else{
					$this->db->insert("produkvariasi",$data);
				}
			}
			echo json_encode(array("success"=>true,"msg"=>"berhasil","token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"msg"=>"form not submitted!"));
		}
	}
	private function clean($string) {
		$string = str_replace(' ', '-', $string);
		$string = preg_replace('/[^A-Za-z0-9\-]/', '-', $string);

		return preg_replace('/-+/', '-', $string);
	}
	public function updateproduk(){
		if(isset($_POST["id"])){
			//$_POST["id"] = $this->clean($_POST["id"]);
			$tgl = date("Y-m-d H:i:s");
			$arr = $_POST;
			//$string = $this->clean($_POST["nama"]);
			$arr2 = array("tglupdate"=> $tgl); //,"url"=>$string."-".date("His"));
			$data = array_merge($arr,$arr2);
			$this->db->where("id",intval($_POST["id"]));
			$this->db->update("produk",$data);

			echo json_encode(array("success"=>true,"msg"=>"berhasil","id"=>$_POST["id"],"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"msg"=>"form not submitted!"));
		}
	}

	// HALAMAN STATIS
	function halaman(){
		if(isset($_POST["formid"])){
			//$_POST["formid"] = intval(["formid"]);
			$this->db->where("id",intval($_POST["formid"]));
			$db = $this->db->get("page");
			$data = array();
			foreach($db->result() as $r){
				$data = array(
					"nama"	=> $r->nama,
					"konten"=> $r->konten
				);
			}
			
			echo json_encode(array("success"=>true,"data"=>$data,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false));
		}
	}
	function updatehalaman(){
		if(isset($_POST["id"])){
			
			$this->db->where("id",intval($_POST["id"]));
			$this->db->update("page",$_POST);
			
			echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}
	
	// PESANAN
	function detailpesanan(){
		if(isset($_GET["theid"])){
			$this->load->view("admin/pesanandetail");
		}else{
			echo "404 - Request Not Found";
		}
	}
	function updatepreorder(){
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			
			$this->db->where("id",intval($_POST["id"]));
			$this->db->update("preorder",$_POST);
			
			echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}
	function updatepesanan(){
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$status = isset($_POST["status"]) ? $_POST["status"] : 1;
			$trx = $this->func->getTransaksi(intval($_POST["id"]),"semua","idbayar");
			
			if(isset($_POST["statusbayar"])){
				/*$this->db->where("idtransaksi",$trx->id);
				$db = $this->db->get("transaksiproduk");
				foreach($db->result() as $r){
					$var = $this->func->getVariasi($r->variasi,"semua","id");
					if($r->jumlah > $var->stok){
						echo json_encode(array("success"=>false,"msg"=>"stok produk tidak mencukupi"));
						$stok = 0;
						exit;
					}else{
						$stok = $var->stok - $r->jumlah;
					}
					$variasi[] = $r->variasi;
					$stock[] = $stok;
					$stokawal[] = $var->stok;
					$jml[] = $r->jumlah;
				}
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
						"idtransaksi" => $trx->id
					);
					$this->db->insert("historystok",$data);
				}*/
				if($_POST["statusbayar"] == 1){
					$this->func->notifsukses($_POST["id"]);
				}
				
				$this->db->where("id",intval($_POST["id"]));
				$this->db->update("pembayaran",["status"=>intval($_POST["statusbayar"]),"tglupdate"=>date("Y-m-d H:i:s")]);
			}

			if($status >= 3){
				$data = ["status"=>$status,"selesai"=>date("Y-m-d H:i:s")];
			}else{
				$data = ["status"=>$status];
			}
			
			$this->db->where("idbayar",intval($_POST["id"]));
			$this->db->update("transaksi",$data);
			
			echo json_encode(array("success"=>true,"token"=>$this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
		}
	}
	function batalkanpesanan(){
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$this->func->notifbatal($_POST["id"],1);
			$trx = $this->func->getTransaksi(intval($_POST["id"]),"semua","idbayar");
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
							"usrid"	=> $trx->usrid,
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
						"usrid"	=> $trx->usrid,
						"stokawal" => $stokawal[$i],
						"stokakhir" => $stock[$i],
						"variasi" => $variasi[$i],
						"jumlah" => $jml[$i],
						"tgl"	=> date("Y-m-d H:i:s"),
						"idtransaksi" => $trx->id
					);
					$this->db->insert("historystok",$data);
				}
				
				$this->db->where("id",intval($_POST["id"]));
				$this->db->update("pembayaran",["status"=>3,"tglupdate"=>date("Y-m-d H:i:s")]);
			
			$this->db->where("idbayar",intval($_POST["id"]));
			$this->db->update("transaksi",["status"=>4,"selesai"=>date("Y-m-d H:i:s")]);
			
			echo json_encode(array("success"=>true,"token"=>$this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=>$this->security->get_csrf_hash()));
		}
	}
	function inputresi(){
		if(isset($_POST["theid"])){
			//$_POST["theid"] = intval(["theid"]);
			$trx = $this->func->getTransaksi(intval($_POST["theid"]),"semua");
			$usrid = $this->func->getUserdata($trx->usrid,"semua");

			$status = $trx->kurir != "cod" ? 2 : 3;
			$resi = (isset($_POST["resi"])) ? $_POST["resi"] : "";
			$data = array(
				"resi" => $resi,
				"kirim" => date("Y-m-d H:i:s"),
				"status" => $status
			);
			if($status == 3){
				$data["selesai"] = date("Y-m-d H:i:s");
			}

			$this->db->where("id",intval($_POST["theid"]));
			$this->db->update("transaksi",$data);

			$namatoko = $this->func->globalset("nama");

			if($trx->kurir != "cod" AND $trx->kurir != "toko"){
				$pesan = "
					Berikut resi pengiriman untuk pesanan anda di <b>".$namatoko."</b><br/>
					Resi: <b style='font-size:120%'>".$resi."</b><br/>&nbsp;<br/>&nbsp;<br/>
					Lacak pengirimannya langsung di menu <b>pesananku</b><br/>
					<a href='".$this->func->mainsite_url('manage/pesanan')."'>Klik Disini</a>
				";
				$this->func->sendEmail($usrid->username,$namatoko." - Resi Pengiriman Pesanan",$pesan,"Resi Pengiriman");
				$pesan = "
					Berikut resi pengiriman untuk pesanan anda di *".$namatoko."* \n".
					"Resi: *".$resi."* \n \n".
					"Lacak pengirimannya langsung di menu *pesananku* \n ".
					$this->func->mainsite_url('manage/pesanan')."
				";
				$this->func->sendWA($this->func->getProfil($trx->usrid,"nohp","usrid"),$pesan);
			}else{
				$pesan = "
					Pesanan Anda di <b>".$namatoko."</b> akan segera kami kirimkan<br/>
					Kurir Toko: <b style='font-size:120%'>".$resi."</b><br/>&nbsp;<br/>&nbsp;<br/>
					Untuk waktu pengiriman bisa langsung menghubungi kurir kami, atau juga bisa ditanyakan ke admin kami di web<br/>
					<a href='".$this->func->mainsite_url('manage')."'>Klik Disini</a>
				";
				$this->func->sendEmail($usrid->username,$namatoko." - Pengiriman Pesanan",$pesan,"Informasi Pengiriman");
				$pesan = "
					Pesanan Anda di *".$namatoko."* akan segera kami kirimkan \n".
					"Kurir Toko: *".$resi."* \n \n".
					"Untuk waktu pengiriman bisa langsung menghubungi kurir kami, atau juga bisa ditanyakan ke admin kami di web \n".
					$this->func->mainsite_url('manage')."
				";
				$this->func->sendWA($this->func->getProfil($trx->usrid,"nohp","usrid"),$pesan);
			}
			
			echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}

	// FILE UPLOAD
	public function jadikanFotoUtama($id){
		$idproduk = (isset($_POST["idproduk"])) ? intval($_POST["idproduk"]) : 0;
		$this->db->where("idproduk",$idproduk);
		$this->db->where("jenis",1);
		$this->db->update("upload",array("jenis"=>0));

		$this->db->where("id",$id);
		$this->db->update("upload",array("jenis"=>1));

		echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
	}
	public function hapusFotoProduk($id){
		if($id == "all"){
			$idproduk = (isset($_POST["idproduk"])) ? intval($_POST["idproduk"]) : 0;
			$this->db->where("idproduk",$idproduk);
			$db = $this->db->get("upload");
			foreach($db->result() as $res){
				if(file_exists("uploads/".$res->nama)){
					unlink("uploads/".$res->nama);
				}
			}

			$_SESSION["uploadedPhotos"] = 0;
			$this->session->unset_userdata("fotoProduk");
			$this->db->where("idproduk",$idproduk);
			$this->db->delete("upload");
		}else{
			$url = "uploads/".$this->func->getUpload($id,"nama");
			if(file_exists($url)){
				unlink($url);
			}
			$this->db->where("id",$id);
			$this->db->delete("upload");
			$_SESSION["uploadedPhotos"] = $_SESSION["uploadedPhotos"]-1;
			if(($key = array_search($id, $_SESSION["fotoProduk"])) !== false) {
				unset($_SESSION["fotoProduk"][$key]);
			}
		}

		echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
	}
	public function uploadFotoResult($idpro=0){
		$this->load->view("admin/produkuploadfoto",array("idproduk"=>$idpro));
	}
	public function uploadFotoProduk(){
		if(isset($_POST)){
			//$_POST["idproduk"] = intval(["idproduk"]);
			$this->db->where("idproduk",$_POST["idproduk"]);
			$db = $this->db->get("upload");
			$jenis = $db->num_rows() > 0 ? 0 : 1;

			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpeg|jpg|png';
			$config['file_name'] = $_SESSION["usrid"].date("YmdHis");

			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('fotoProduk')){
				echo json_encode(array("success"=>false,"msg"=>"error: ".$this->upload->display_errors(),"token"=> $this->security->get_csrf_hash()));
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
				$config_resize['height'] = 720;
				$this->image_lib->initialize($config_resize);
				$this->image_lib->resize();
				*/

				$data = array(
					"idproduk"=> $_POST["idproduk"],
					"jenis"=> $jenis,
					"nama"=> $uploadData["file_name"],
					"tgl"=> date("Y-m-d H:i:s")
				);
				$this->db->insert("upload",$data);
				$_SESSION["fotoProduk"][] = $this->db->insert_id();
				echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
				$upl = (isset($_SESSION["uploadedPhotos"])) ? $_SESSION["uploadedPhotos"] : 0;
				$_SESSION["uploadedPhotos"] = $upl+1;
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"File tidak ditemukan","token"=> $this->security->get_csrf_hash()));
		}
	}
	public function uploadLogo($tipe=1){
		if(isset($_POST)){
			$type = $tipe == 1 ? "logo" : "favicon";
			$foto = $this->func->globalset($type);
			if(file_exists("./assets/img/".$foto)){
				unlink("./assets/img/".$foto);
			}

			$config['upload_path'] = './assets/img/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['file_name'] = $type."-".date("YmdHis");

			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('logo')){
				$error = array('error' => $this->upload->display_errors());
			}else{
				$uploadData = $this->upload->data();
				$data = array(
					"value"=> $uploadData["file_name"],
					"tgl"=> date("Y-m-d H:i:s")
				);
				$this->db->where("field",$type);
				$this->db->update("setting",$data);
				echo json_encode(array("success"=>true,"filename"=>$uploadData["file_name"],"token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"File tidak ditemukan","token"=> $this->security->get_csrf_hash()));
		}
	}

	// BLOG
	public function hapusblog(){
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$img = $this->func->getBlog($_POST["id"],"img");
			if($img != "no-image.png"){
				unlink("uploads/".$img);
			}
			
			$this->db->where("id",$_POST["id"]);
			$this->db->delete("blog");

			echo json_encode(array("success"=>true,"msg"=>"berhasil menghapus","token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"msg"=>"form not submitted!"));
		}
	}
	public function uploadblog($id=0){
		if(isset($_POST)){
			if(isset($_SESSION["fotoPage"])){
				unlink("uploads/".$_SESSION["fotoPage"]);
				$this->session->unset_userdata("fotoPage");
			}

			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['file_name'] = "blogpost_".$_SESSION["usrid"].date("YmdHis");

			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('foto')){
				echo json_encode(array("success"=>false,"msg"=>$this->upload->display_errors(),"token"=> $this->security->get_csrf_hash()));
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
				$config_resize['height'] = 720;
				$this->image_lib->initialize($config_resize);
				$this->image_lib->resize();
				*/

				if($id == 0){
					$_SESSION["fotoPage"] = $uploadData["file_name"];
				}else{
					$img = $this->func->getBlog($id,"img");
					$url = "uploads/".$img;
					if($img != "no-image.png" AND file_exists($url)){
						unlink($url);
					}
					$this->db->where("id",$id);
					$this->db->update("blog",["img"=>$uploadData["file_name"],"tgl"=>date("Y-m-d H:i:s")]);
				}

				echo json_encode(array("success"=>true,"filename"=>$uploadData["file_name"],"token"=> $this->security->get_csrf_hash()));
			}
		}else{
			echo json_encode(array("success"=>false,"msg"=>"File tidak ditemukan","token"=> $this->security->get_csrf_hash()));
		}
	}
	
	
	// PESAN KOTAK MASUK
	public function pesanmasuk($usrid){
		if(isset($usrid)){
			$this->db->where("(dari = 0 AND tujuan = ".$usrid.") OR (dari = ".$usrid." AND tujuan = 0)");
			$this->db->limit(100);
			$db = $this->db->get("pesan");
			
			$this->db->where("dari",$usrid);
			$this->db->where("baca",0);
			$this->db->update("pesan",array("baca"=>1));
							
			$currdate = false;
			if($db->num_rows() > 0){
				$noe = 1;
				foreach($db->result() as $r){
					$centang = ($r->baca == 0) ? "<i class='fas fa-check'></i>" : "<i class='fas fa-check-double'></i>";
					$centang = ($r->tujuan != 0) ? $centang : "";
					$loc = ($r->tujuan == 0) ? "left" : "right";
					$tgl = '<br/><small>'.$this->func->ubahTgl("d/m H:i",$r->tgl).' &nbsp'.$centang.'</small>';
				
					if($this->func->ubahTgl("d-m-Y",$r->tgl) != $currdate){
						echo '<div class="pesanwrap center">
								<div class="isipesan">'.$this->func->ubahTgl("d M Y",$r->tgl).'</div>
							</div>';
						$currdate = $this->func->ubahTgl("d-m-Y",$r->tgl);
					}
						
					echo '<div class="pesanwrap '.$loc.'">
							<div class="isipesan"><b>'.$this->func->clean($r->isipesan)."</b>".$tgl.'</div>
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
				"tujuan"	=> $_POST["tujuan"],
				"baca"		=> 0,
				"dari"		=> 0,
				"tgl"		=> date("Y-m-d H:i:s")
			);
			$this->db->insert("pesan",$data);

			echo json_encode(array("success"=>true,"token"=> $this->security->get_csrf_hash()));
		}else{
			echo json_encode(array("success"=>false,"token"=> $this->security->get_csrf_hash()));
		}
	}
	
	/* RESELLER AGEN */
	public function tambahagen(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$data = array(
				"level"	=> $_POST["level"],
				"tgl"	=> date("Y-m-d H:i:s")
			);
			if($_POST["id"] > 0){
				$this->db->where("id",intval($_POST["id"]));
				$this->db->update("userdata",$data);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}elseif($_POST["id"] == 0){
				$this->db->insert("userdata",$data);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}else{
				echo json_encode(["success"=>false,"token"=> $this->security->get_csrf_hash()]);
			}
		}else{
			$res = $this->load->view("admin/agenform","",true);
			echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
		}
	}
	public function hapusagen(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}

		if(isset($_POST)){
			//$_POST["id"] = intval(["id"]);
			$this->db->where("id",$_POST["id"]);
			$this->db->delete("userdata");
			$this->db->where("usrid",$_POST["id"]);
			$this->db->delete("alamat");
			$this->db->where("usrid",$_POST["id"]);
			$this->db->delete("profil");
			$this->db->where("usrid",$_POST["id"]);
			$this->db->delete("transaksi");
			$this->db->where("usrid",$_POST["id"]);
			$this->db->delete("preorder");
			$this->db->where("usrid",$_POST["id"]);
			$this->db->delete("transaksiproduk");
			$this->db->where("usrid",$_POST["id"]);
			$this->db->delete("pembayaran");
			$this->db->where("usrid",$_POST["id"]);
			$this->db->delete("rekening");
			$this->db->where("usrid",$_POST["id"]);
			$this->db->delete("wishlist");
			$this->db->where("usrid",$_POST["id"]);
			$this->db->delete("saldo");
			$this->db->where("usrid",$_POST["id"]);
			$this->db->delete("saldohistory");
			$this->db->where("usrid",$_POST["id"]);
			$this->db->delete("saldotarik");
			$this->db->where("usrid",$_POST["id"]);
			$this->db->delete("token");
			$this->db->where("dari",$_POST["id"]);
			$this->db->or_where("tujuan",$_POST["id"]);
			$this->db->delete("pesan");

			echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
		}else{
			echo json_encode(["success"=>false,"token"=> $this->security->get_csrf_hash()]);
		}
	}
	function getusrid(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["level"])){
			//$_POST["level"] = intval(["level"]);
			$this->db->where("level",$_GET["level"]);
			$this->db->order_by("nama ASC, id DESC");
			$db = $this->db->get("userdata");

			echo '<option value="">== Pilih Pengguna ==</option>';
			foreach($db->result() as $r){
				echo "<option value='".$r->id."'>".strtoupper(strtolower($r->nama))." - ".$r->username."</option>";
			}
		}else{
			show_404();
		}
	}
	
	/* VOUCHER */
	public function voucher(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			$res = $this->load->view("admin/voucherlist","",true);
			echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
		}elseif(isset($_POST["formid"])){
			$_POST["formid"] = $_POST["formid"];
			$this->db->where("id",intval($_POST["formid"]));
			$db = $this->db->get("voucher");
			$data = [];
			foreach($db->result() as $r){
			$data = [
				"id"		=> $_POST["formid"],
				"usrid"		=> $r->usrid,
				"nama"		=> $r->nama,
				"deskripsi"	=> $r->deskripsi,
				"kode"		=> strtoupper(strtolower($r->kode)),
				"mulai"		=> $r->mulai,
				"selesai"	=> $r->selesai,
				"jenis"		=> $r->jenis,
				"tipe"		=> $r->tipe,
				"idproduk"	=> $r->idproduk,
				"potongan"	=> $r->potongan,
				"potonganmin"	=> $r->potonganmin,
				"potonganmaks"	=> $r->potonganmaks,
				"peruser"	=> $r->peruser,
				"token"		=> $this->security->get_csrf_hash()
			];
			}
			echo json_encode($data);
		}else{
			redirect("ngadimin");
		}
	}
	public function tambahvoucher(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$_POST["usrid"]	= $_SESSION["usrid"];
			$_POST["kode"]	= strtoupper(strtolower($_POST["kode"]));
			
			if($_POST["id"] > 0){
				$this->db->where("id",intval($_POST["id"]));
				$this->db->update("voucher",$_POST);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}elseif($_POST["id"] == 0){
				$this->db->insert("voucher",$_POST);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}else{
				echo json_encode(["success"=>false,"token"=> $this->security->get_csrf_hash()]);
			}
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	public function hapusvoucher(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$this->db->where("id",intval($_POST["id"]));
			$this->db->delete("voucher");
			echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	
	/* VOUCHER */
	public function testimoni(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			$res = $this->load->view("admin/testimonilist","",true);
			echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
		}elseif(isset($_POST["formid"])){
			//$_POST["formid"] = intval(["formid"]);
			$this->db->where("id",intval($_POST["formid"]));
			$db = $this->db->get("voucher");
			$data = [];
			foreach($db->result() as $r){
			$data = [
				"id"		=> $_POST["formid"],
				"usrid"		=> $r->usrid,
				"nama"		=> $r->nama,
				"deskripsi"	=> $r->deskripsi,
				"kode"		=> strtoupper(strtolower($r->kode)),
				"mulai"		=> $r->mulai,
				"selesai"	=> $r->selesai,
				"jenis"		=> $r->jenis,
				"tipe"		=> $r->tipe,
				"idproduk"	=> $r->idproduk,
				"potongan"	=> $r->potongan,
				"potonganmin"	=> $r->potonganmin,
				"potonganmaks"	=> $r->potonganmaks,
				"peruser"	=> $r->peruser,
				"token"		=> $this->security->get_csrf_hash()
			];
			}
			echo json_encode($data);
		}else{
			redirect("ngadimin");
		}
	}
	public function tambahtestimoni(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$_POST["usrid"]	= $_SESSION["usrid"];
			$_POST["kode"]	= strtoupper(strtolower($_POST["kode"]));
			
			if($_POST["id"] > 0){
				$this->db->where("id",intval($_POST["id"]));
				$this->db->update("testimoni",$_POST);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}elseif($_POST["id"] == 0){
				$this->db->insert("testimoni",$_POST);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}else{
				echo json_encode(["success"=>false,"token"=> $this->security->get_csrf_hash()]);
			}
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	public function hapustestimoni(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$this->db->where("id",intval($_POST["id"]));
			$this->db->delete("testimoni");
			echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	
	/* SALES BOSTER */
	public function booster(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			$res = $this->load->view("admin/boosterlist","",true);
			echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
		}elseif(isset($_POST["formid"])){
			//$_POST["formid"] = intval(["formid"]);
			$this->db->where("id",intval($_POST["formid"]));
			$db = $this->db->get("booster");
			$data = [];
			foreach($db->result() as $r){
			$data = [
				"id"		=> $_POST["formid"],
				"usrid"		=> $r->usrid,
				"idproduk"	=> $r->idproduk,
				"token"		=> $this->security->get_csrf_hash()
			];
			}
			echo json_encode($data);
		}else{
			redirect("ngadimin");
		}
	}
	public function tambahbooster(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$_POST["tgl"]	= date("Y-m-d H:i:s");
			
			if($_POST["id"] > 0){
				$this->db->where("id",intval($_POST["id"]));
				$this->db->update("booster",$_POST);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}elseif($_POST["id"] == 0){
				$this->db->insert("booster",$_POST);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}else{
				echo json_encode(["success"=>false,"token"=> $this->security->get_csrf_hash()]);
			}
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	public function hapusbooster(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$this->db->where("id",intval($_POST["id"]));
			$this->db->delete("booster");
			echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	
	/* REKENING */
	public function rekening(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			$this->load->view("admin/rekeninglist");
		}elseif(isset($_POST["formid"])){
			//$_POST["formid"] = intval(["formid"]);
			$rek = $this->func->getRekening(intval($_POST["formid"]),"semua");
			$data = [
				"id"		=> $_POST["formid"],
				"pass"		=> $this->func->decode($rek->pass),
				"userid"	=> $rek->userid,
				"atasnama"	=> $rek->atasnama,
				"idbank"	=> $rek->idbank,
				"norek"		=> $rek->norek,
				"kcp"		=> $rek->kcp,
				"token"		=> $this->security->get_csrf_hash()
			];
			echo json_encode($data);
		}else{
			redirect("ngadimin");
		}
	}
	public function tambahrekening(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$_POST["usrid"]	= 0;
			$_POST["tgl"]	= date("Y-m-d H:i:s");
			if(isset($_POST["pass"])){
				$_POST["pass"]	=  $this->func->encode($_POST["pass"]);
			};
			
			if($_POST["id"] > 0){
				$this->db->where("id",intval($_POST["id"]));
				$this->db->update("rekening",$_POST);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}elseif($_POST["id"] == 0){
				$this->db->insert("rekening",$_POST);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}else{
				echo json_encode(["success"=>false,"token"=> $this->security->get_csrf_hash()]);
			}
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	public function hapusrekening(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		if(isset($_POST["id"])){
			$this->db->where("id",intval($_POST["id"]));
			$this->db->delete("rekening");
			echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	
	/* WHATSAPP */
	public function wasap(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET["load"])){
			$this->load->view("admin/wasaplist");
		}elseif(isset($_POST["formid"])){
			//$_POST["formid"] = intval(["formid"]);
			$rek = $this->func->getWasap(intval($_POST["formid"]),"semua");
			$data = [
				"id"	=> $_POST["formid"],
				"nama"	=> $rek->nama,
				"wasap"	=> $rek->wasap,
				"token"	=> $this->security->get_csrf_hash()
			];
			echo json_encode($data);
		}else{
			redirect("ngadimin");
		}
	}
	public function tambahwasap(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$this->db->where("id",intval($_POST["id"]));
			$_POST["tgl"]	= time();
			if($_POST["id"] > 0){
				$this->db->where("id",intval($_POST["id"]));
				$this->db->update("wasap",$_POST);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}elseif($_POST["id"] == 0){
				$this->db->insert("wasap",$_POST);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}else{
				echo json_encode(["success"=>false,"token"=> $this->security->get_csrf_hash()]);
			}
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	public function hapuswasap(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}

		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$this->db->where("id",intval($_POST["id"]));
			$this->db->delete("wasap");
			echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	
	/* USER MANAJER */
	public function usermanajer(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if($this->func->demo() == true){
			echo json_encode(["result"=>"maaf, fitur tidak tersedia untuk mode demo aplikasi","token"=>$this->security->get_csrf_hash()]);
		}else{
			if(isset($_POST["formid"])){
				//$_POST["formid"] = intval(["formid"]);
				$rek = $this->func->getUser(intval($_POST["formid"]),"semua");
				$data = [
					"id"		=> $_POST["formid"],
					"pass"		=> $this->func->decode($rek->password),
					"username"	=> $rek->username,
					"nama"		=> $rek->nama,
					"level"		=> $rek->level,
					"token"		=> $this->security->get_csrf_hash()
				];
				echo json_encode($data);
			}else{
				$res = $this->load->view("admin/userlist","",true);
				echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
			}
		}
	}
	public function hapususer(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}

		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			$this->db->where("id",intval($_POST["id"]));
			$this->db->delete("admin");
			echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	public function tambahuser(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST["id"])){
			//$_POST["id"] = intval(["id"]);
			if(isset($_POST["pass"])){
				$_POST["password"]	=  $this->func->encode($_POST["pass"]);
				unset($_POST["pass"]);
			};
			
			if($_POST["id"] > 0){
				$this->db->where("id",intval($_POST["id"]));
				$this->db->update("admin",$_POST);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}elseif($_POST["id"] == 0){
				$this->db->insert("admin",$_POST);
				echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
			}else{
				echo json_encode(["success"=>false,"token"=> $this->security->get_csrf_hash()]);
			}
		}elseif(isset($_POST["gantipass"])){
			$set["password"] = $this->func->encode($_POST["gantipass"]);
			
			$this->db->where("id",$_SESSION["usrid"]);
			$this->db->update("admin",$set);
			echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
		}else{
			echo json_encode(["success"=>false]);
		}
	}
	
	/* PENGATURAN */
	public function savesetting(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_POST)){
			foreach($_POST as $key=>$value){
				$key = $key;
			  	$this->db->where("field",$key);
			  	$this->db->update("setting",["value"=>$value]);
			}
			
			echo json_encode(["success"=>true,"token"=> $this->security->get_csrf_hash()]);
		}else{
			echo json_encode(["success"=>false,"msg"=>"not allowed"]);
		}
	}
	public function setting(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		$this->load->view("admin/pengaturanform");
	}
	public function settingkurir(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		$this->load->view("admin/pengaturankurir");
	}
	public function settingserver(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		$this->load->view("admin/pengaturanserver");
	}
	public function settingpayment(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		$this->load->view("admin/pengaturanpayment");
	}
	
	/* PESANAN */
	public function pesanan(){
		if(!isset($_SESSION["isMasok"])){
			redirect("ngadimin/login");
			exit;
		}
		
		if(isset($_GET['load']) AND $_GET['load'] == "bayar"){
			$res = $this->load->view("admin/pesananbayar","",true);
		}elseif(isset($_GET['load']) AND $_GET['load'] == "dikemas"){
			$res = $this->load->view("admin/pesanandikemas","",true);
		}elseif(isset($_GET['load']) AND $_GET['load'] == "dikirim"){
			$res = $this->load->view("admin/pesanandikirim","",true);
		}elseif(isset($_GET['load']) AND $_GET['load'] == "selesai"){
			$res = $this->load->view("admin/pesananselesai","",true);
		}elseif(isset($_GET['load']) AND $_GET['load'] == "batal"){
			$res = $this->load->view("admin/pesananbatal","",true);
		}else{
			redirect("ngadimin");exit;
		}
		echo json_encode(["result"=>$res,"token"=>$this->security->get_csrf_hash()]);
	}
	
	
}
