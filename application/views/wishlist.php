<?php
	$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
	$orderby = (isset($_GET["orderby"]) AND $_GET["orderby"] != "") ? $_GET["orderby"] : "id DESC";
	$cari = (isset($_GET["cari"]) AND $_GET["cari"] != "") ? $_GET["cari"] : "";
	$perpage = 12;
?>
	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb">
			<a href="<?php echo site_url(); ?>" class="text-primary">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>
			<span class="">
				Wishlist
			</span>
		</div>
	</div>
	<!-- Content page -->
	<section class="bgwhite p-t-60 p-b-65">
		<div class="container">
			<div class="m-b-60 text-center text-primary font-bold">
				<h2>Wishlist</h2>
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
							$where = "status = 1 AND usrid = '".$_SESSION["usrid"]."'";
							$this->db->where($where);
							$dbs = $this->db->get("wishlist");
							
							$this->db->where($where);
							$this->db->limit($perpage,($page-1)*$perpage);
							$this->db->order_by($orderby);
							$db = $this->db->get("wishlist");
							$totalproduk = 0;
							
							foreach($db->result() as $w){
								$r = $this->func->getProduk($w->idproduk,"semua");
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

								$totalproduk += 1;
						?>
						<div class="col-6 col-md-3 m-b-30 cursor-pointer produk-item">
							<!-- Block2 -->
							<div class="block2">
								<div class="block2-delete"><button onclick="hapusWishlist(<?=$r->id?>)" class="btn btn-danger btn-rounded"><i class="fas fa-times"></i> &nbsp;hapus</div>
								<div class="block2-img wrap-pic-w of-hidden pos-relative" style="background-image:url('<?=$this->func->getFoto($r->id,"utama")?>');" onclick="window.location.href='<?php echo site_url('produk/'.$r->url); ?>'"></div>
								<div class="block2-txt" onclick="window.location.href='<?php echo site_url('produk/'.$r->url); ?>'">
									<a href="<?php echo site_url('produk/'.$r->url); ?>" class="block2-name dis-block p-b-5">
										<?=$r->nama?>
									</a>
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
										<span class="badge badge-warning bdg-1"><i class='fa fa-star'></i> <?=$ulasan['nilai']?></span>
									</div>
								</div>
							</div>
						</div>
						<?php
							}
							
							if($totalproduk == 0){
								echo "<div class='col-12 text-center m-tb-40'><h2><mark>Belum ada wishlist</mark></h2></div>";
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
			window.location.href = "<?=site_url("home/wishlist")?>?page="+page;
		}

		function hapusWishlist(id){
			swal.fire({
				title: "Anda yakin?",
				text: "produk akan dihapus dari daftar wishlist anda.",
				icon: "error",
				showDenyButton: true,
				confirmButtonText: "Oke",
				denyButtonText: "Batal"
			})
			.then((willDelete) => {
				if (willDelete.isConfirmed) {
					$.post("<?php echo site_url("assync/hapuswishlist"); ?>",{"id":id,[$("#names").val()]:$("#tokens").val()},function(msg){
						var data = eval("("+msg+")");
						updateToken(data.token);
						if(data.success == true){
							refreshTabel(1);
						}else{
							swal.fire("Gagal!","Gagal menghapus produk dari wishlist, coba ulangi beberapa saat lagi","error");
						}
					});
				}
			});
		}
	</script>
