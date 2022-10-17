
	<!-- Produk Baru -->
	<section class="bg0 p-t-23 p-b-140">
		<div class="container">
			<div class="p-b-10">
				<h3 class="mtext-103 cl5">
				  hasil untuk pencarian produk "<?php echo $query; ?>"
				</h3>
			</div>

			<div class="flex-w flex-sb-m p-b-52">
				<div class="flex-w flex-l-m filter-tope-group m-tb-10">
					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">
						Semua Produk
					</button>

					<?php
						$this->db->where("parent",0);
						$kat = $this->db->get("kategori");
						foreach ($kat->result() as $kad) {
					?>
					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".<?php echo $kad->url; ?>">
						<?php echo $kad->nama; ?>
					</button>
				<?php } ?>
				</div>

				<div class="flex-w flex-c-m m-tb-10">
					<div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter tombolfilter">
						<i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
						<i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
						 Filter
					</div>

					<!-- <div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
						<i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
						<i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
						Search
					</div> -->
				</div>

				<!-- Filter -->
				<div class="dis-none panel-filter w-full p-t-10">
					<div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
						<div class="filter-col1 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Urutkan
							</div>

							<ul>
								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 filter-link-active">
										Paling Sesuai
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Rating Tertinggi
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Terbaru
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Harga terendah
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Harga Tertinggi
									</a>
								</li>
							</ul>
						</div>

						<div class="filter-col2 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Harga
							</div>

							<ul>
								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 filter-link-active">
										Semua
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Dibawah Rp100.000
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Rp100.000 - Rp150.000
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Rp150.000 - Rp200.000
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Rp200.000 Keatas
									</a>
								</li>
							</ul>
						</div>

						<div class="filter-col3 p-b-27" style="width:45%;">
							<div class="mtext-102 cl2 p-b-15">
								Pencarian Terkait
							</div>

							<div class="flex-w p-t-4 m-r--5">
								<?php
									$this->db->where("parent >",0);
									$kat = $this->db->get("kategori");
									foreach($kat->result() as $cat){
										$active = (in_array($cat->nama,$addedquery,true)) ? 'style="border-color:#c0392b;font-weight:bold;"' : "";
								?>
								<a href="javascript:void(0)" onclick="addNewSearchParameter('<?php echo $cat->nama; ?>')" <?php echo $active; ?> class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									<?php echo $cat->nama; ?>
								</a>
								<?php
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Product Grid -->
			<div class="row isotope-grid">
				<?php
					foreach ($produk->result() as $prd) {
						$kategori = $this->func->getKategori($prd->idcat,"url");
						$url = explode("_",$prd->url);
						$url = implode("-",$url);
				?>
				<div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item <?php echo $kategori; ?>">
					<!-- Block2 -->
					<div class="block2">
						<div class="block2-pic img-product hov-img0"  onclick="window.location.href='<?php echo site_url('produk/'.$prd->url); ?>'" style="cursor:pointer;background-image: url(<?php echo $this->func->getFoto($prd->id,'utama'); ?>);">
							<!-- <img src="images/product1.jpg" class="img-product" alt="IMG-PRODUCT">

							<a href="#" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
								Quick View
							</a> -->
						</div>

						<div class="block2-txt flex-w flex-t p-t-14">
							<div class="block2-txt-child1 flex-col-l ">
								<a href="<?php echo site_url('produk/'.$url); ?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
									<?php echo $prd->nama; ?>
								</a>

								<span class="stext-105 cl3">
									Rp <?php echo $this->func->formUang($prd->harga); ?>
								</span>
							</div>

							<!--
							<div class="block2-txt-child2 flex-r p-t-3">
								<a href="javascript:void(0);" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
									<img class="icon-heart1 dis-block trans-04" src="<?php echo base_url('assets/v2/images/icons/icon-heart-01.png'); ?>" alt="ICON">
									<img class="icon-heart2 dis-block trans-04 ab-t-l" src="<?php echo base_url('assets/v2/images/icons/icon-heart-02.png'); ?>" alt="ICON">
								</a>
							</div>
							-->
						</div>
					</div>
				</div>
				<?php } ?>
			</div>

			<!-- Pagination -->
			<?php echo $this->func->createPagination($rows,$page,$perpage,"refreshPage"); ?>
		</div>
	</section>

	<script type="text/javascript">
		$(document).ready(function(){
			//$(".tombolfilter").trigger("click");

		});
		function refreshPage(page){
			window.location.href="<?php echo site_url("search")."?page="; ?>"+page+"<?php echo "&token=".$_GET["token"]; ?>";
		}
		function addNewSearchParameter(paramex){
			$.post(
				"<?php echo site_url("search/secure"); ?>",
				{"query":"<?php echo $query; ?>","addquery[]":paramex},
				function(msg){
					var data = eval("("+msg+")");
					if(data.success == true){
						window.location.href="<?php echo site_url("search")."?token=".$_GET["token"]; ?>";
					}
				}
			);
		}
	</script>
