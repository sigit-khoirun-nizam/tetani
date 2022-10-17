<?php
	$page = (isset($_GET["page"]) AND $_GET["page"] != "" AND intval($_GET["page"]) > 0) ? intval($_GET["page"]) : 1;
	$perpage = 10;

	$this->db->where("status",3);
	$this->db->where("usrid",$_SESSION["usrid"]);
	$rows = $this->db->get("transaksi");
	$rows = $rows->num_rows();

	$this->db->where("status",3);
	$this->db->where("usrid",$_SESSION["usrid"]);
	$this->db->order_by("selesai","DESC");
	$this->db->limit($perpage,($page-1)*$perpage);
	$db = $this->db->get("transaksi");
  	if($db->num_rows() > 0){
?>
	<div class="pesanan">
<?php
    foreach($db->result() as $rx){
?>

		<div class="m-b-30">
			<div class="pesanan-item p-all-30 m-lr-0-xl">
				<div class="row p-b-30">
					<div class="col-6">
						<span class="text-dark font-medium fs-18">
							Order ID <span class="text-success">#<?php echo $rx->orderid; ?></span>
						</span>
					</div>
					<div class="col-6 text-right">
						<a href="<?php echo site_url("manage/detailpesanan/?orderid=").$rx->orderid; ?>" class="btn btn-sm btn-primary"><i class="fas fa-angle-double-right"></i> Rincian<span class="hidesmall"> Pesanan</span></a>
					</div>
				</div>
				<div class="row m-lr-0">
					<div class="col-md-8 p-lr-0 m-b-10">
						<?php
							$this->db->where("idtransaksi",$rx->id);
							$trp = $this->db->get("transaksiproduk");
							$totalproduk = 0;
							$no = 1;
							foreach ($trp->result() as $key) {
								$totalproduk += $key->harga * $key->jumlah;
								$produk = $this->func->getProduk($key->idproduk,"semua");
								$variasee = $this->func->getVariasi($key->variasi,"semua");
								$variasi = ($key->variasi != 0 AND $variasee != null) ? $this->func->getWarna($variasee->warna,"nama")." ".$produk->subvariasi." ".$this->func->getSize($variasee->size,"nama") : "";
								$variasi = ($key->variasi != 0 AND $variasee != null) ? "<small class='text-primary'>".$produk->variasi.": ".$variasi."</small>" : "";
								if($no == 2){
						?>
							<div class="p-b-30 show-product">
						<?php
								}
						?>
							<div class="row p-b-30 m-lr-0 produk-item">
								<div class="col-4 col-md-2">
									<div class="img" style="background-image:url('<?php echo $this->func->getFoto($key->idproduk,"utama"); ?>')" alt="IMG"></div>
								</div>
								<div class="col-8 col-md-10">
									<p class="font-medium text-dark btn-block"><?php if($produk != null){ echo $produk->nama; }else{ echo "Produk telah dihapus"; } ?></p>
									<?=$variasi?>
									<p>Rp <?php echo $this->func->formUang($key->harga); ?> <span style="font-size:11px">x<?php echo $key->jumlah; ?></span></p>
								</div>
							</div>
						<?php
								$no++;
							}
							if($no > 2){
						?>
            				</div>
							<div class="row p-b-30 p-r-10 m-lr-0">
								<a href="javascript:void(0)" class="view-product text-info"><i class="fa fa-chevron-circle-down"></i> Lihat produk lainnya</a>
								<a href="javascript:void(0)" class="view-product text-info" style="display:none;"><i class='fa fa-chevron-circle-up'></i> Sembunyikan produk</a>
							</div>
						<?php
							}
						?>
					</div>
					<div class="col-md-4">
						Pesanan telah Anda terima pada<br/>
						<i class="text-success p-r-8 font-medium"><?php echo $this->func->ubahTgl("d M Y H:i",$rx->selesai); ?> WIB</i>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-3">
						<?php
							$this->db->select("id");
							$this->db->where("idtransaksi",$rx->id);
							$ul = $this->db->get("review");
							if($ul->num_rows() > 0){
						?>
							<a href="<?php echo site_url("manage/ulasan/".$rx->orderid); ?>" class="btn btn-warning btn-block m-b-10">
								LIHAT ULASAN
							</a>
						<?php
							}else{
						?>
							<a href="<?php echo site_url("manage/ulasan/".$rx->orderid); ?>" class="btn btn-warning btn-block m-b-10">
								BERIKAN ULASAN
							</a>
						<?php
							}
						?>
					</div>
					<div class="col-md-5 m-b-14"></div>
					<div class="col-md-4 text-right">
						<h5 class="text-dark">Total Order &nbsp;<span class="text-success font-bold text-right">Rp <?php echo $this->func->formUang($rx->ongkir + $totalproduk); ?></span></h5>
					</div>
				</div>
			</div>
		</div>
<?php
		}
		echo $this->func->createPagination($rows,$page,$perpage,"refreshDikirim");
?>
	</div>
<?php
	}else{
?>
	<div class="text-center p-tb-40 section m-t-30">
		<i class="fas fa-box-open fs-120 text-danger m-b-20"></i>
		<h5 class="text-dark font-bold">TIDAK ADA PESANAN</h5>
	</div>
<?php
	}
?>

<script type="text/javascript">
	$(document).ready(function(){
		$(".show-product").hide();
		$(".view-product").click(function(){
			$(this).parent().parent().find(".show-product").slideToggle();
			$(this).parent().parent().find(".view-product").toggle();
		});
	});
</script>
