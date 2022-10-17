<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<?php
		$col = 4;
		if($onb != "onboard"){
			$col = 3;
	?>
	<a href='<?php echo site_url('manage/tambahproduk'); ?>' class='block-produk col-md-3 p-b-35 bor8 isotope-item txt-center'>
		<div class="block-produk-icon">
			<img src='<?php echo base_url("assets/img/komponen/add-product.png"); ?>' /><br/>
			Tambah Produk
		</div>
	</a>
	<?php } ?>
<?php
	foreach($produk->result() as $res){
		$judul = $this->func->potong($res->nama,25,'...');
		$label = $this->func->getLabelCOD($res->cod);
		$tombol = "";
		if(isset($_SESSION["idtoko"]) AND $_SESSION["idtoko"] == $res->idtoko){
			$tombol = "
				<div class='kotak-tombol'>
					<button type='button' class='kiri' onclick='ubahProduk(\"".$this->func->ubahTgl("YmdHis",$res->tglbuat)."-".$res->id."\")'>Edit</button>
					<button type='button' class='kanan' onclick='hapusProduk(".$res->id.")'>Hapus</button>
				</div>";
		}

		echo "
			<div class=\"col-md-".$col." p-b-35 isotope-item terbaru\">
				<div class=\"block2\">
					<div onclick=\"window.location.href=('".site_url('produk/'.$res->url)."')\" class=\"block2-pic pointer img-product hov-img0\" style=\"background-image: url('".$this->func->getFoto($res->id,'utama')."');\">
					</div>
					<div class=\"block2-txt flex-w flex-t p-t-14 m-b-10\">
						<div class=\"block2-txt-child1 flex-col-l \">
							<a href=\"".site_url('produk/'.$res->url)."\" class=\"stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6\">
								".$judul."
							</a>
							<span class=\"stext-105 cl3\">
								Rp ".$this->func->formUang($res->harga)."
							</span>
						</div>
					</div>
					".$tombol."
				</div>
			</div>
		";
	}
?>
<div class="col-md-12 p-t-30">
	<?php echo $this->func->createPagination($rows,$page,$perpage); ?>
</div>
