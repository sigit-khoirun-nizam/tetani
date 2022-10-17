<?php
	$this->db->where("url",$namatoko);
	$db = $this->db->get("toko");
	foreach($db->result() as $res){
		$foto = file_exists("assets/img/toko/".$res->foto) ? base_url("assets/img/toko/".$res->foto) : base_url("assets/img/toko/default_toko.jpg");
		$kota = $this->func->getKab($res->idkab,"nama");
		$lastOnline = $this->func->lastOnline($res->id);
		$nilaitoko = $this->func->getNilaiToko($res->id);
		$followers = $this->func->getFollowers($res->id);
		$produk = $this->func->getTotalProduk($res->id);
		$terjual = $this->func->getTerjual($res->id);
	}
?>

	<!-- Title page -->
	<section class="banner-toko txt-center p-lr-15 p-tb-92">
    <div class="banner-text m-t-30">
    </div>
  </section>

	<!-- Content page -->
	<section class="bg10">
		<div class="container">
			<div class="row">
				<div class="col-md-4 toko-block1">
					<div class="row">
            <div class="col-md-12 img-toko text-center">
              <img src="<?php echo $foto; ?>">
            </div>
            <div class="col-md-12 m-t-30 m-lr-auto nama-toko">
              <h2 class="mtext-103 text-center p-b-15"><?php echo $res->nama; ?></h2>
            </div>
            <div class="col-md-12 m-t-40 profil-toko">
              <ul>
                <li class="mtext-102 p-b-5">
                  <i class="lnr lnr-map-marker p-r-20"></i> <?php echo $kota; ?>
                </li>
                <li class="mtext-102 p-b-5">
                  <i class="lnr lnr-star p-r-20"></i> <?php echo $nilaitoko; ?>
                </li>
                <li class="mtext-102 p-b-5">
                  <i class="lnr lnr-tag p-r-20"></i> <?php echo $produk; ?> Produk
                </li>
                <li class="mtext-102 p-b-5">
                	<i class="lnr lnr-gift p-r-20"></i> <?php echo $terjual; ?> Terjual
                </li>
                <li class="mtext-102 p-b-5">
                  <i class="lnr lnr-history p-r-20"></i> <?php echo $lastOnline; ?>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-8 p-tb-50 toko-block2">
          <div class="container">
          	<h2 class="mtext-105">Produk <span class="stext-102 p-l-3" style="font-size: 12px;">(<?php echo $produk; ?> produk)</span></h2>

            <!-- Filter -->
            <div class="flex-w flex-sb-m">
              <div class="flex-w flex-l-m filter-tope-group m-tb-10">
                <button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">
                  Semua Produk
                </button>
  							<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".terbaru">
                  Terbaru
              	</button>
                <button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".terlaris">
                  Terlaris
                </button>
              </div>
          	</div>

            <!-- Product grid -->
            <div class="row m-t-23" id="productLoad" style="position:relative;">
							<i class="fa fa-spin fa-power-off"></i> &nbsp; Loading...
            </div>
          </div>
      	</div>
			</div>
		</div>
	</section>
<script type="text/javascript">
	$(function(){
		$("#productLoad").load("<?php echo site_url("assync/daftarProduk/".$res->id."/onboard"); ?>");
	});

	function refreshTabel(id){
		var loding = '<i class="fa fa-spin fa-circle-notch"></i> sedang memuat produk...';
		$("#productLoad").html(loding);
		$("#productLoad").load("<?php echo site_url("assync/daftarProduk/".$res->id."/onboard"); ?>?page="+id);
	}

	function hapusProduk(id){
		var asn = confirm("Yakin akan menghapus produk ini?");
		if(asn == true){
			$.post("<?php echo site_url("assync/hapusProduk"); ?>",{"id":id},function(msg){
				var data = eval("("+msg+")");
				if(data.success == true){
					window.location.href = "<?php echo site_url("manage/produk"); ?>";
				}else{
					alert(data.msg);
				}
			});
		}
	}
	function ubahProduk(id){
		window.location.href = "<?php echo site_url("manage/ubahproduk"); ?>/?tokens="+id;
	}
</script>
