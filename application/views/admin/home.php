<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>	
<div class="seksen-shadow"></div>
<div class="seksen">
	<div class="wrapper">
		<div class="divider"></div>
		<div class="tambahtoko">
			<?php if($_SESSION["idtoko"] > 0){ ?>
				Terima kasih anda sudah bergabung sebagai penjual di <b>BELIWARGA</b>.
				<div class="tombol">
					<a href="<?php echo site_url("manage/tambahproduk"); ?>" class="btn btn-green">
						<i class="fa fa-box"></i> &nbsp;Tambah Produk Barumu
					</a>
					<a href="<?php echo site_url("manage/produk"); ?>" class="btn btn-default">
						<i class="fa fa-boxes"></i> &nbsp;Daftar Produk
					</a>
				</div>

			<?php }else{ ?>
				Anda punya usaha atau punya barang bekas tak terpakai? Mari bergabung bersama kami, jual barangmu disini.<br/>Pasti <b>MAU</b> 
				(Pasti <b>Mudah</b>, Pasti <b>Aman</b>, dan Pasti <b>Untung</b>).
				<div class="tombol">
					<a href="<?php echo site_url("manage/bukatoko"); ?>" class="btn btn-green">
						<i class="fa fa-shopping-bag"></i> &nbsp;Buka Tokomu
					</a>
				</div>
			<?php } ?>
		</div>
		<div class="divider"></div>
		<div class="seksen">
			<div class="seksen-title">
				Produk terbaru di <b>Beliwarga</b>
				<div class="seksen-title-border"></div>
			</div>
			<div id="produkbaruin" class="seksen-content"><i class="fa fa-spin fa-refresh"></i> tunggu sebentar...</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$("#produkbaruin").load("<?php echo site_url('assync/newprod/?display=20'); ?>");
	});
</script>