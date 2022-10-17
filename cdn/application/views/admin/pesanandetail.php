<?php
	$this->db->where("idtransaksi",intval($_GET["theid"]));
	$db = $this->db->get("transaksiproduk");
	
	foreach($db->result() as $r){
		$produk = $this->func->getProduk($r->idproduk,"semua");
		if(is_object($produk)){
			$nama = $produk->nama;
			$variasi = $r->variasi != 0 ? $this->func->getVariasi($r->variasi,"semua","id") : "";
			$variasi = $r->variasi != 0 ? $this->func->getVariasiWarna($variasi->warna,"nama")." ".$produk->subvariasi." ".$this->func->getVariasiSize($variasi->size,"nama") : "";
		}else{
			$nama = "Produk telah dihapus";
			$variasi = "";
		}
?>
	<div class="p-all-10 m-b-10" style="border:1px solid #ccc;">
	<div class="row">
		<div class="col-4 col-md-3">
			<img class="col-12" src="<?=$this->func->getFoto($r->idproduk,"utama")?>" />
		</div>
		<div class="col-8 col-md-8 row">
			<div class="col-8 col-md-6">
				<b><?=$nama?></b><br/>
				<small><?=$variasi?></small>
			</div>
			<div class="col-4 col-md-6">
				<?=$r->jumlah." x Rp ".$this->func->formUang($r->harga)?>
			</div>
		</div>
	</div>
	</div>
<?php
	}
?>