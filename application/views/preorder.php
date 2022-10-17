<?php
	$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
	$orderby = (isset($_GET["orderby"]) AND $_GET["orderby"] != "") ? $_GET["orderby"] : "id DESC";
	$cari = (isset($_GET["cari"]) AND $_GET["cari"] != "") ? $_GET["cari"] : "";
	$perpage = 12;
?>
	<!-- Content page -->
	<section class="bgwhite p-t-55 p-b-65">
		<div class="container">
					<!-- 
					<div class="flex-sb-m flex-w p-b-35">
						<span class="s-text8 p-t-5 p-b-5">
							Showing 1–12 of 16 results
						</span>
					</div> -->

					<!-- Product -->
					<div class="row">
						<?php							
							$where = "(nama LIKE '%$cari%' OR harga LIKE '%$cari%' OR hargareseller LIKE '%$cari%' OR hargaagen LIKE '%$cari%' OR deskripsi LIKE '%$cari%') AND status = 1 AND preorder > 0";
							$this->db->where($where);
							$dbs = $this->db->get("produk");
							
							$this->db->where($where);
							$this->db->limit($perpage,($page-1)*$perpage);
							$this->db->order_by($orderby);
							$db = $this->db->get("produk");
							
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
								
								$this->db->where("idproduk",$r->id);
								$dbv = $this->db->get("produkvariasi");
								$totalstok = 0;
								$hargs = 0;
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
						?>
						<div class="col-6 col-md-4 col-lg-3 p-b-50 cursor-pointer" onclick="window.location.href='<?= site_url('produk/'.$r->url) ?>'">
							<!-- Block2 -->
							<div class="block2">
								<div class="block2-img wrap-pic-w of-hidden pos-relative">
									<div class="badge badge-warning badge-lg pos-absolute p-tb-8 p-lr-12" style="top:5%;left:5%;">PRE ORDER</div>
									<img src="<?=$this->func->getFoto($r->id,"utama")?>" alt="IMG-PRODUCT" style="object-fit:cover;">

									<div class="block2-overlay trans-0-4">
										<a href="#" class="block2-btn-addwishlist hov-pointer trans-0-4">
											<i class="icon-wishlist icon_heart_alt" aria-hidden="true"></i>
											<i class="icon-wishlist icon_heart dis-none" aria-hidden="true"></i>
										</a>

										<div class="block2-btn-addcart w-size1 trans-0-4">
											<!-- Button -->
											<button class="flex-c-m size1 bg-1 bo-rad-23 hov1 s-text1 trans-0-4">
												Details
											</button>
										</div>
									</div>
								</div>

								<div class="block2-txt p-t-20">
									<a href="<?= site_url('produk/'.$r->url) ?>" class="block2-name dis-block s-text3 p-b-5">
										<?=$r->nama?>
									</a>

									<span class="block2-price m-text6 p-r-5 color1">
										<?php 
											if($hargs > 0){
												echo "Rp. ".$this->func->formUang(min($harga))." - ".$this->func->formUang(max($harga));
											}else{
												echo "Rp. ".$this->func->formUang($result);
											}
										?>
									</span>
								</div>
							</div>
						</div>
						<?php
							}
							
							if($db->num_rows() == 0){
								echo "<div class='col-12 text-center'><h2><mark>Produk Kosong</mark></h2></div>";
							}
						?>
					</div>

					<!-- Pagination -->
					<div class="pagination flex-m flex-w p-t-26">
						<?=$this->func->createPagination($db->num_rows(),1,10)?>
					</div>
		</div>
	</section>
