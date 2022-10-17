<?php
	$page = (isset($_GET["page"]) AND $_GET["page"] != "" AND intval($_GET["page"]) > 0) ? intval($_GET["page"]) : 1;
	$perpage = 10;

	$this->db->where("usrid",$_SESSION["usrid"]);
	$rows = $this->db->get("preorder");
	$rows = $rows->num_rows();

	$this->db->where("usrid",$_SESSION["usrid"]);
	$this->db->order_by("status","ASC");
	$this->db->limit($perpage,($page-1)*$perpage);
	$db = $this->db->get("preorder");
  	if($db->num_rows() > 0){
?>
		<div class="pesanan">
		<?php
			foreach($db->result() as $rx){
		?>
		<div class="m-b-30">
			<div class="pesanan-item p-all-30 m-lr-0-xl">
				<div class="row p-b-30">
					<div class="col-md-8">
						<span class="text-dark font-medium fs-18">
							Order ID <span class="text-success">#<?php echo $rx->invoice; ?></span>
						</span>
					</div>
				</div>
								<?php 
									$produk = $this->func->getProduk($rx->idproduk,"semua");
									if(isset($produk->tglpo)){ $tglpo = $produk->tglpo; }else{ $tglpo =  "0000-00-00 00:00:00"; }
								?>
				<div class="row m-lr-0">
					<div class="col-md-8 p-lr-0 m-b-10">
						<div class="row p-b-30 m-lr-0 produk-item">
							<div class="col-4 col-md-2">
								<div class="img" style="background-image:url('<?php echo $this->func->getFoto($rx->idproduk,"utama"); ?>')" alt="IMG"></div>
							</div>
							<div class="col-8 col-md-10">
								<p class="font-medium text-dark"><?php if($produk != null){ echo $produk->nama; }else{ echo "Produk telah dihapus"; } ?></p>
								<?=$variasi?>
								<p>Rp <?php echo $this->func->formUang($rx->harga); ?> <span style="font-size:11px">x<?php echo $rx->jumlah; ?></span></p>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-8 row">
						<?php if($rx->status == 0){ ?>
							<div class="col-md-6 m-b-10"><span class="text-danger">Belum Bayar</span></div>
							<div class="col-md-6">
								<a class="btn btn-primary" href="<?=site_url("home/invoicepreorder/?inv=".$this->func->arrEnc(array("idbayar"=>$rx->id),"encode"))?>">Cara Pembayaran</a>
							</div>
						<?php }elseif($rx->status == 1){?>
							<?php if($this->func->ubahTgl("Ymd",$tglpo) > date("Ymd")){?>
								<div class="col-12 m-b-10"><b class="text-primary">Sedang Dalam Proses Produksi</b></div>
							<?php }else{
									$this->db->where("idpo",$rx->id);
									$this->db->where("idtransaksi >",0);
									$dbx = $this->db->get("transaksiproduk");
									if($dbx->num_rows() > 0){
							?>
								<b class="text-success">Pesanan sudah diproses</b>
							<?php }else{ ?>
								<div class="col-md-6 m-b-10"><b class="text-success">Stok Ready Silahkan Melakukan Checkout/Pelunasan</b></div>
								<div class="col-md-6">
									<a class="btn btn-primary" href="<?=site_url("home/bayarpreorder/?predi=".$this->func->arrEnc(array("idbayar"=>$rx->id),"encode"))?>">Checkout Sekarang</a>
								</div>
							<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
					<div class="col-md-4">
						<div class="row">
							<div class="col-md-6 text-right">
								<h5 class="text-black">Total DP</h5>
							</div>
							<div class="col-md-6">
								<h5 class="text-success font-bold text-right">Rp <?php echo $this->func->formUang($rx->total); ?></h5>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 text-right">
								<h5 class="text-danger">Total Pelunasan</h5>
							</div>
							<div class="col-md-6">
								<h5 class="text-danger font-bold text-right">Rp <?php echo $this->func->formUang(($rx->jumlah*$rx->harga) - $rx->total); ?></h5>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
		}
		echo $this->func->createPagination($rows,$page,$perpage,"refreshPO");
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
