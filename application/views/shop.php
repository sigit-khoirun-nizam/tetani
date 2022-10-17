<?php
	$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
	$orderby = (isset($_GET["orderby"]) AND $_GET["orderby"] != "") ? $_GET["orderby"] : "stok DESC";
	$cari = (isset($_GET["cari"]) AND $_GET["cari"] != "") ? $this->func->clean($_GET["cari"]) : "";
	$perpage = 12;
?>
	<!-- Content page -->
	<div class="container-xxl position-relative p-0">

<div class="container-xxl py-5 hero-header" style="background-color:#FBA504;">
	<div class="container my-5 py-5 px-lg-5">
		<div class="row g-5 py-5">
			<div class="col-12 text-center">
				<h1 class="text-white animated slideInDown">Produk</h1>
				<hr class="bg-white mx-auto mt-0" style="width: 90px;">
				<nav aria-label="breadcrumb text-white">
					<ol class="breadcrumb justify-content-center">
						<li class="breadcrumb-item"><a class="text-white" href="<?= base_url(); ?>">Home</a></li>
						<li class="breadcrumb-item"><a class="text-white" href="<?=base_url();?>Shop">Produk</a></li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
</div>

	<section class="bgwhite p-t-30 p-b-65">
		<div class="container">
			<div class="p-b-50">
				<div class="search-product pos-relative m-b-40 m-l-auto m-r-auto col-md-8">
					<form action="" class="row" method="GET">
						<input class="col-md-11 col-10" type="text" value="<?=$cari?>" name="cari" placeholder="Cari Produk">

						<button type="submit" class="col-2 col-md-1">
							<i class="fas fa-search text-primary" aria-hidden="true"></i>
						</button>
					</form>
				</div>
				<div class="p-r-20 p-r-0-sm t-center">
					<div class="cat-button">
						<a href="javascript:void(0)" class="btn btn-warning bo-rad-23 p-l-16 p-r-16 m-r-4 m-b-12">Semua Kategori</a>
						<?php 
							$this->db->where("parent",0);
							$db = $this->db->get("kategori");
							foreach($db->result() as $r){
						?>
							<a href="<?=site_url("kategori/".$r->url)?>" class="btn btn-primary bo-rad-23 p-l-16 p-r-16 m-r-4 m-b-12">
								<?=ucwords(strtolower($r->nama))?>
							</a>
						<?php
							}
						?>
					</div>
				</div>
			</div>

			<div class="p-b-50">
					<!-- 
					<div class="flex-sb-m flex-w p-b-35">
						<span class="s-text8 p-t-5 p-b-5">
							Showing 1–12 of 16 results
						</span>
					</div> -->

					<!-- Product -->
					<div class="row produk-wrap">
						<?php
							$this->db->select("SUM(stok) AS stok,idproduk");
							$this->db->group_by("idproduk");
							$dbvar = $this->db->get("produkvariasi");
							$notin = array();
							foreach($dbvar->result() as $not){
								if($not->stok <= 0){
									$notin[] = $not->idproduk;
								}
							}
			
							$where = "(nama LIKE '%$cari%' OR harga LIKE '%$cari%' OR hargareseller LIKE '%$cari%' OR hargaagen LIKE '%$cari%' OR deskripsi LIKE '%$cari%') AND status = 1 AND preorder != 1 AND stok > 0";
							$this->db->where($where);
							if(count($notin) > 0){
								$this->db->where_not_in($notin);
							}
							$dbs = $this->db->get("produk");
							
							$this->db->where($where);
							if(count($notin) > 0){
								$this->db->where_not_in($notin);
							}
							$this->db->limit($perpage,($page-1)*$perpage);
							$this->db->order_by($orderby);
							$db = $this->db->get("produk");
							$totalproduk = 0;
							
							foreach($db->result() as $r){
								$level = isset($_SESSION["lvl"]) ? $_SESSION["lvl"] : 0;
								if($level == 5){
									$result = $r->hargadistri;
								}elseif($level == 4){
									$result = $r->hargaagensp;
								}elseif($level == 3){
									$result = $r->hargaagen;
								}elseif($level == 2){
									$result = $r->hargareseller;
								}else{
									$result = $r->harga;
								}
								$ulasan = $this->func->getReviewProduk($r->id);

								$this->db->where("idproduk",$r->id);
								$dbv = $this->db->get("produkvariasi");
								$totalstok = ($dbv->num_rows() > 0) ? 0 : $r->stok;
								$hargs = 0;
								$harga = array();
								foreach($dbv->result() as $rv){
									$totalstok += $rv->stok;
									if($level == 5){
										$harga[] = $rv->hargadistri;
									}elseif($level == 4){
										$harga[] = $rv->hargaagensp;
									}elseif($level == 3){
										$harga[] = $rv->hargaagen;
									}elseif($level == 2){
										$harga[] = $rv->hargareseller;
									}else{
										$harga[] = $rv->harga;
									}
									$hargs += $rv->harga;
								}

								if($totalstok > 0){
									$totalproduk += 1;
									$wishis = ($this->func->cekWishlist($r->id)) ? "active" : "";
						?>
						<div class="col-6 col-md-3 m-b-30 cursor-pointer produk-item">
							<!-- Block2 -->
							<div class="block2">
								<div class="block2-wishlist" onclick="tambahWishlist(<?=$r->id?>,'<?=$r->nama?>')"><i class="fas fa-heart <?=$wishis?>"></i></div>
								<div class="block2-img wrap-pic-w of-hidden pos-relative" style="background-image:url('<?=$this->func->getFoto($r->id,"utama")?>');" onclick="window.location.href='<?php echo site_url('produk/'.$r->url); ?>'"></div>
								<div class="block2-txt" onclick="window.location.href='<?php echo site_url('produk/'.$r->url); ?>'">
									<a href="<?php echo site_url('produk/'.$r->url); ?>" class="block2-name dis-block p-b-5">
										<?=$r->nama?>
									</a>
									<span class="block2-price-coret btn-block">
										<?php if($r->hargacoret > 0){ echo "Rp. ".$this->func->formUang($r->hargacoret); } ?>
									</span>
									<span class="block2-price p-r-5 color1">
										<?php 
											if($hargs > 0){
												echo "Rp. ".$this->func->formUang(min($harga))." - ".$this->func->formUang(max($harga));
											}else{
												echo "Rp. ".$this->func->formUang($result);
											}
										?>
									</span>
								</div>
								<div class="row block2-ulasan" onclick="window.location.href='<?php echo site_url('produk/'.$r->url); ?>'">
									<div class='col-6'>
										<small><?=$ulasan['ulasan']?> Ulasan</small>
									</div>
									<div class='col-6 text-right'>
										<span class="text-warning font-bold"><i class='fa fa-star'></i> <?=$ulasan['nilai']?></span>
									</div>
								</div>
							</div>
						</div>
						<?php
								}
							}
							
							if($totalproduk == 0){
								echo "<div class='col-12 text-center m-tb-40'><h2><mark>Produk Kosong</mark></h2></div>";
							}
						?>
					</div>

					<!-- Pagination -->
					<div class="pagination flex-m flex-w p-t-26">
						<?php
							if($totalproduk > 0){
								echo $this->func->createPagination($dbs->num_rows(),$page,$perpage);
							}
						?>
					</div>
				</div>
			</div>
	</section>
	
	<script type="text/javascript">
		function refreshTabel(page){
			window.location.href = "<?=site_url("shop?cari=".$cari)?>&page="+page;
		}
	</script>
