<?php
/*
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://api.rajaongkir.com/starter/city",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "key: b24386c0c9a767d26561b624d7c021c5"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  //echo $response;
  $respon = json_decode($response);
  $respon = $respon->rajaongkir->results;
  //print_r($respon);
	for($i=0; $i<count($respon); $i++){
		//echo $respon[$i]->province_id." - ".$respon[$i]->province."<br/>";
		$this->db->where("nama",$respon[$i]->type ." ".$respon[$i]->city_name);
		if($this->db->update("kab",array("rajaongkir"=>$respon[$i]->city_id))){
			$id = $this->func->getKab($respon[$i]->city_id,"rajaongkir","rajaongkir");
			echo "berhasil update: ".$respon[$i]->city_name." - ".$respon[$i]->city_id."<br/>Hasil ID: ".$id."<br/>&nbsp;<br/>";
		}else{
			echo "<b>GAGAL</b> update: ".$respon[$i]->city." - ".$respon[$i]->city_id."<br/>&nbsp;<br/>";
		}
	}
}
*/
$this->db->order_by("nama","asc");
$result = $this->db->get("kab");
?>
<div class="seksen">
	<div class="wrapper">
	<div class="kolom">
		<div class="kolom6">
			<form id="cekongkir" method="POST" action="<?php echo site_url("assync/cekongkir"); ?>">
				<ul>
					<li>
						DARI
						<select id="dari" name="dari" class="form-bordered">
							<option value="0">Pilih Kabupaten/Kota</option>
							<?php
								foreach($result->result() as $res){
									echo "<option value='".$res->id."'>".$res->nama."</option>";
								}
							?>
						</select>
						<div class="divider"></div>
					</li>
					<li>
						TUJUAN KABUPATEN/KOTA
						<select id="tujuan" class="form-bordered">
							<option value="0">Pilih Kabupaten/Kota</option>
							<?php
								foreach($result->result() as $res){
									echo "<option value='".$res->id."'>".$res->nama."</option>";
								}
							?>
						</select>
						<div class="divider"></div>
					</li>
					<li>
						TUJUAN KECAMATAN
						<select id="kec" name="tujuan" class="form-bordered">
							<option value="0">Pilih Kabupaten/Kota Duu</option>
						</select>
						<div class="divider"></div>
					</li>
					<li>
						KURIR
						<select id="kurir" name="kurir" class="form-bordered">
							<option value="jne">JNE</option>
							<option value="pos">POS</option>
							<option value="tiki">TIKI</option>
							<option value="pcp">PCP</option>
							<option value="esl">ESL</option>
							<option value="rpx">RPX</option>
							<option value="pandu">PANDU</option>
							<option value="wahana">WAHANA</option>
							<option value="sicepat">SICEPAT</option>
							<option value="jnt">J&T</option>
							<option value="pahala">PAHALA</option>
							<option value="sap">SAP</option>
							<option value="jet">JET Express</option>
							<option value="first">First Logistic</option>
							<option value="ncs">NCS</option>
							<option value="star">Star Cargo</option>
							<option value="lion">Lion Parcel</option>
							<option value="ninja">NINJA Express</option>
							<option value="rex">REX</option>
							<option value="idl">IDL Cargo</option>
						</select>
						<div class="divider"></div>
					</li>
					<li>
						SERVICE
						<select id="service" name="service" class="form-bordered">
							<option value="">Tidak Dipilih</option>
							<option value="REG">REG</option>
							<option value="OKE">OKE</option>
							<option value="YES">YES</option>
						</select>
						<div class="divider"></div>
					</li>
					<li>
						BERAT
						<input type="number" value="1000" name="berat" class="form-bordered" />
						<div class="divider"></div>
					</li>
					<li>
						<button type="submit" class="btn btn-green">Cek Ongkir</button>
					</li>
				</ul>
			</form>
		</div>
	</div>
	<div class="divider"></div>
	<div class="well well-tipis" id="hasil">
		HASIL
	</div>
	<div class="divider"></div>
	<div class="divider"></div>
</div>

<script type="text/javascript">
	$(function(){
		$("#cekongkir").on("submit",function(e){
			e.preventDefault();
			$("#hasil").html("<i class='fa fa-spin fa-spinner'></i> tunggu sebentar...");
			$.post("<?php echo site_url("assync/cekapiongkir"); ?>",$(this).serialize(), function(msg){
				$("#hasil").html(msg);
			});
		});


		$("#tujuan").change(function(){
      $("#kec").html("<option value=''>Loading...</option>");
			$.post("<?php echo site_url("assync/getkec"); ?>",{"id":$(this).val()},function(msg){
				var data = eval("("+msg+")");
				$("#kec").html(data.html);
			});
		});

		$("#kurir").change(function(){
			var data = $("#service"+$(this).val()).html();
			$("#service").html(data);
		});
	});
</script>
<select id="servicejne" style="display:none;">
	<option value="">Tidak Dipilih</option>
	<option value="REG">REG</option>
	<option value="OKE">OKE</option>
	<option value="YES">YES</option>
</select>
<select id="servicepos" style="display:none;">
	<option value="">Tidak Dipilih</option>
	<option value="Paket Kilat Khusus">Paket Kilat Khusus</option>
	<option value="Express Next Day Barang">Express Next Day</option>
	<option value="Paketpos biasa">POS Biasa</option>
</select>
<select id="servicetiki" style="display:none;">
	<option value="">Tidak Dipilih</option>
	<option value="REG">REG</option>
	<option value="ECO">ECO</option>
	<option value="ONS">ONS</option>
</select>
<!-- 7JacFvQCJin -->
