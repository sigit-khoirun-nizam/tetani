<?php
	$this->db->where("idbayar",$idkirim);
	$db = $this->db->get("pengiriman");
	//$data = $this->func->getPengiriman($idkirim,"semua");
	foreach($db->result() as $data){
	$toko = $this->func->getToko($data->idtoko,"semua");
	//$kec = $this->func->getKec($toko->idkec,"nama");
	//$idkab = $this->func->getKec($toko->idkec,"idkab");
	$kota = $this->func->getKab($toko->idkab,"nama");
	$idprov = $this->func->getKab($toko->idkab,"idprov");
	$prov = $this->func->getProv($idprov,"nama");
	$alamat = ucwords(strtolower(/*$kec.", ".*/$kota.", ".$prov));
	
	$alamatp = $this->func->getAlamat($data->alamat,"semua");
	$kec = $this->func->getKec($alamatp->idkec,"nama");
	$idkab = $this->func->getKec($alamatp->idkec,"idkab");
	$kota = $this->func->getKab($idkab,"nama");
	$idprov = $this->func->getKab($idkab,"idprov");
	$prov = $this->func->getProv($idprov,"nama");
	$alamats = ucwords(strtolower($kec.", ".$kota.", ".$prov));
	//print_r($idkirim);
?>
<div class="pengiriman">
	<div class="title">
		<b>Pengirim</b><br/>
		<?php echo strtoupper($toko->nama)." (".$toko->wasap.")<br/>".$alamat; ?><p/>
		<b>Penerima</b><br/>
		<?php echo $alamatp->nama." (".$alamatp->nohp.")<br/>".$alamats; ?><p/>
	</div>
	<div class="detil">
	
	</div>
</div>
<?php
	} 
?>