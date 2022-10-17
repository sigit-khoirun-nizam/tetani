
	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb">
			<a href="<?php echo site_url(); ?>" class="text-primary">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

      <?php
		$set = $this->func->getSetting("semua");
		$kategori = $this->func->getKategori($data->idcat,"semua");
		$kategorinama = is_object($kategori) ? $kategori->nama : "";
		$textorder = $data->preorder == 0 ? "Tambah Ke Keranjang" : "Pre Order";
		
		$level = isset($_SESSION["lvl"]) ? $_SESSION["lvl"] : 0;
		if($level == 5){
			$result = $data->hargadistri;
		}elseif($level == 4){
			$result = $data->hargaagensp;
		}elseif($level == 3){
			$result = $data->hargaagen;
		}elseif($level == 2){
			$result = $data->hargareseller;
		}else{
			$result = $data->harga;
		}
		
		$this->db->where("idproduk",$data->id);
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
			<a href="<?php echo site_url("kategori/".$kategori->url); ?>" class="text-primary">
				<?php echo ucwords(strtolower($kategorinama)); ?>
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="">
				<?php echo $data->nama; ?>
			</span>
		</div>
	</div>


	<!-- Product Detail -->
	<section class="sec-product-detail p-t-65 p-b-60">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-lg-7 p-b-30">
					<div class="p-l-25 p-r-30 p-lr-0-lg">
							<?php
								$this->db->where("idproduk",$data->id);
								$this->db->order_by("jenis","DESC");
								$db = $this->db->get("upload");
								$no = 1;
								foreach ($db->result() as $res){
									if($no == 1){
							?>
								<div class="prod-preview">
									<img src="<?php echo base_url("cdn/uploads/".$res->nama); ?>" alt="IMG-PRODUCT" id="prod-img">
								</div>
								<div class="prod-thumb">
									<div class="m-r-26"></div>
							<?php
									}
							?>
									<div class="prod-thumb-item" data-thumb="<?php echo base_url("cdn/uploads/".$res->nama); ?>" style="background-image:url('<?php echo base_url("cdn/uploads/".$res->nama); ?>');"></div>
							<?php
									if($no == $db->num_rows()){ echo "</div>"; }
									$no++;
								}
							?>
					</div>
				</div>

				<div class="col-md-6 col-lg-5 p-b-20">
					<div class="p-t-5 p-lr-0-lg">
						<h4 id="js-name-detail" class="font-medium p-b-14">
							<?php echo $data->nama; ?>
						</h4>

						<?php 
							if($data->hargacoret > 0){
								echo "<span class=\"fs-20 m-r-10 harga-coret\">Rp. ".$this->func->formUang($data->hargacoret)."</span>";
							}
						?>
						
						<span id="hargacetak" class="fs-24 font-bold text-success color1">
							<?php 
								if($hargs > 0){
									echo "Rp. ".$this->func->formUang(min($harga))." - ".$this->func->formUang(max($harga));
								}else{
									echo "Rp. ".$this->func->formUang($result);
								}
							?>
						</span>
						
						<?php if($data->preorder != 0){ ?>
						<div class="m-t-20 m-b-20">
							<div class="btn font-bold text-info">
								PRODUK PRE ORDER
							</div>
						</div>
						<?php } ?>

						<p class="mtext-102 cl2 p-t-14">
						  <span class="fs-18">
							<?php
							  $ulasan = $this->func->getBintang($data->id);
							  $star = $ulasan["star"];
							  for($i=1; $i<=5; $i++){
								$color = ($i <= $star) ? "text-warning" : "text-secondary";
								echo '<i class="fa fa-star '.$color.'"></i>';
							  }
							?>
						  </span> &nbsp;
						  <?php echo $ulasan["jml"]; ?> Ulasan
						</p>

						<!--  -->
						<?php if($this->func->cekLogin() == true){ ?>
						<?php if($data->preorder == 0){ ?>
						<!--<div class="p-t-10 p-b-10 p-l-10 p-r-20 m-b-16 m-t-16" style="border-radius:6px;background-color:#dcdde1;color:#c0392b;position:relative;align-items:middle;">
							<span onclick="$(this).parent().hide();" class="pointer" style="position:absolute;right:10px;"><i class="fa fa-times"></i></span>
							Sebelum membeli pastikan terlebih dahulu ketersediaan stok.
						</div>-->
						<?php } ?>
						<?php
							if($dbv->num_rows() == 0){ $totalstok = $data->stok; }
							if($data->preorder > 0){
								$this->db->where("idproduk",$data->id);
								$t = $this->db->get("preorder");
								$totalorder = 0;
								foreach($t->result() as $r){
									$totalorder += $r->jumlah;
								}
								$totalstok = $totalstok - $totalorder;
							}
							
							if($totalstok > 0){
						?>
						<form id="keranjang">
						  <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash();?>" />
						  <input type="hidden" name="idproduk" value="<?php echo $data->id; ?>" />
						  <input type="hidden" id="variasi" name="variasi" value="0" />
						  <input type="hidden" id="harga" name="harga" value="<?=$result?>" />
							<div class="p-t-33">
								<div class="flex-w p-b-10">
									<?php
										if($dbv->num_rows() > 0){
											$warnaid = array();
											$sizeid = array();
											foreach($dbv->result() as $var){
												$this->db->where("variasi",$var->id);
												$dbf = $this->db->get("preorder");
												$totalpre = 0;
												foreach($dbf->result() as $rf){
													$totalpre += $rf->jumlah;
												}
							
												//$warna[] = $this->func->getWarna($var->warna,"nama");
												$warnaid[] = $var->warna;
												$variasi[$var->warna][] = $var->id;
												$sizeid[$var->warna][] = $var->size;
												$har[$var->warna][] = $var->harga;
												$harreseller[$var->warna][] = $var->hargareseller;
												$haragen[$var->warna][] = $var->hargaagen;
												$haragensp[$var->warna][] = $var->hargaagensp;
												$hardistri[$var->warna][] = $var->hargadistri;
												if(isset($stoks[$var->warna])){
													$stoks[$var->warna] += ($data->preorder == 0) ? $var->stok : $var->stok - $totalpre;
												}else{
													$stoks[$var->warna] = ($data->preorder == 0) ? $var->stok : $var->stok - $totalpre;
												}
												$stok[$var->warna][] = ($data->preorder == 0) ? $var->stok : $var->stok - $totalpre;
												//$size[$var->warna][] = $this->func->getSize($var->size,"nama");
											}
											$warnaid = array_unique($warnaid);
											$warnaid = array_values($warnaid);
											//$sizeid = array_unique($sizeid);
											//$sizeid = array_values($sizeid);
									?>
									<div class="col-12 p-lr-0 m-b-6">
									<?=$data->variasi?>
									</div>
									<div class="col-12 p-lr-0 m-b-10">
										<select class="form-control" id="warna" required >
											<option value="">= Pilih <?=$data->variasi?> =</option>
											<?php
												for($i=0; $i<count($warnaid); $i++){
													if($stoks[$warnaid[$i]] > 0){
														echo "<option value='".$warnaid[$i]."'>".$this->func->getWarna($warnaid[$i],"nama")."</option>";
													}
												}
											?>
										</select>
									</div>
									<div class="col-12 p-lr-0 m-b-6">
										<?=$data->subvariasi?>
									</div>
									<div class="col-12 p-lr-0 m-b-10">
										<select class="form-control" id="size" required >
											<option value="">= Pilih <?=$data->variasi?> dulu =</option>
										</select>
									</div>
									<?php
										}
									?>
									<div class="col-12 p-lr-0">
										Jumlah
									</div>
									<div class="m-b-10 col-12 row p-lr-0 m-lr-0 align-items-center">
										<div class="wrap-num-product input-group m-tb-10 col-6 p-lr-0">
											<div class="btn-num-product-down input-group-prepend cursor-pointer">
												<span class="input-group-text btn-info"><i class="fs-16 fa fa-minus text-light"></i></span>
											</div>
											
											<input class="form-control text-center num-product" type="number" min="<?php echo $data->minorder; ?>" name="jumlah" value="<?php echo $data->minorder; ?>" id="jumlahorder" required>
												
											<div class="btn-num-product-up input-group-append cursor-pointer">
												<span class="input-group-text btn-info"><i class="fs-16 fa fa-plus text-light"></i></span>
											</div>
										</div>
										<div id="stokrefresh" class="col-6 p-lr-20"></div>
									</div>
									<div class="col-12 m-b-0 p-lr-0">
										Catatan pembeli
									</div>
									<div class="col-12 p-lr-0">
										<input class="form-control" type="text" name="keterangan" value="">
									</div>

									<div class="col-12 m-t-40 p-lr-0">
										<?php if($this->func->cekWishlist($data->id) == false){ ?>
										<button type="button" onclick="tambahWishlist(<?=$data->id?>,'<?=$data->nama?>')" class="btn btn-lg btn-warning btn-block btn-wish m-b-40"><i class="fas fa-heart"></i> &nbsp;Tambah ke Wishlist</button>
										<?php } ?>
										<button type="submit" id="submit" class="btn btn-lg btn-primary btn-block">
											<?=$textorder?>
										</button>
										<a href="https://wa.me/<?=$this->func->getRandomWasap()?>/?text=Halo,%20saya%20ingin%20membeli%20produk%20*<?=$data->nama?>*%20apakah%20masih%20tersedia?" class="btn btn-block btn-lg btn-success">
											<i class="fab fa-whatsapp"></i> &nbsp;Beli via Whatsapp
										</a>
										<span id="proses" class="" style="display:none;"><b><i class="fa fa-spin fa-spinner text-primary"></i> Memproses pesanan</b></span>
										<span id="gagal" class="m-t-20" style="display:none;"><i class="text-danger fa fa-exclamation-triangle"></i> Gagal memproses pesanan.</span>
									</div>
								</div>
							</div>
						</form>
						<?php }else{ ?>
						<div class="p-tb-10 p-lr-20 m-b-16 m-t-32 btn font-medium bg-danger text-light btn-block">
							<?php if($data->preorder == 0){ ?>
								Maaf, Stok telah habis
							<?php }else{ ?>
								Maaf, Kuota Pre Order sudah penuh
							<?php } ?>
						</div>
						<?php } ?>
					<?php }else{ ?>
						<div class="col-12 m-t-40 p-lr-0">
							<a href="<?php echo site_url("home/signin"); ?>" class="btn btn-lg btn-primary btn-block">
								Beli Produk
							</a>
							<a href="https://wa.me/<?=$this->func->getRandomWasap()?>/?text=Halo,%20saya%20ingin%20membeli%20produk%20*<?=$data->nama?>*%20apakah%20masih%20tersedia?" class="btn btn-lg btn-success btn-block">
								<i class="fab fa-whatsapp"></i> &nbsp;Beli via Whatsapp
							</a>
						</div>
					<?php } ?>
						<div class="m-t-20 sharer">
							<div class="m-b-10 font-medium">Bagikan</div>
							<a href="whatsapp://send?text=<?=site_url("produk/".$data->url)?>" class="btn btn-success m-r-4 showsmall-inline" target="_blank" data-action="share/whatsapp/share">
								<i class="fab fa-whatsapp fs-20 m-t-4"></i>
							</a>
							<a href="https://api.whatsapp.com/send?text=<?=site_url("produk/".$data->url)?>" class="btn btn-success m-r-4 hidesmall" target="_blank" data-action="share/whatsapp/share">
								<i class="fab fa-whatsapp fs-20 m-t-4"></i>
							</a>
							<a href="http://www.facebook.com/sharer.php?u=<?=site_url("produk/".$data->url)?>" class="btn btn-fb m-r-4" target="_blank">
								<i class="fab fa-facebook-f fs-20 m-lr-4 m-t-4"></i>
							</a>
							<a href="https://plus.google.com/share?url=<?=site_url("produk/".$data->url)?>" class="btn btn-gplus m-r-4" target="_blank">
								<i class="fab fa-google-plus-g fs-20 m-t-4"></i>
							</a>
							<a href="https://twitter.com/share?url=<?=site_url("produk/".$data->url)?>" class="btn btn-tw m-r-4" target="_blank">
								<i class="fab fa-twitter fs-20 m-t-4"></i>
							</a>
							<a href="mailto:?Subject=Beli%20produk%20ini&amp;Body=<?=site_url("produk/".$data->url)?>" class="btn btn-warning m-r-4" target="_blank">
								<i class="fas fa-envelope fs-20 m-t-4"></i>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="m-t-30 p-t-43 p-b-40 row">
				<div class="wrap-dropdown-content p-t-15 p-b-14 active-dropdown-content col-md-8">
					<h5 class="js-toggle-dropdown-content flex-sb-m cursor-pointer text-primary font-medium m-b-12">
						Deskripsi
					</h5>

					<div class="dropdown-content dis-none p-t-15 p-b-23">
						<p class="s-text8">
							<?=$this->security->xss_clean($data->deskripsi)?>
						</p>
					</div>
				</div>
				<div class="wrap-dropdown-content p-t-15 p-b-14 active-dropdown-content col-md-4">
					<h5 class="js-toggle-dropdown-content flex-sb-m cursor-pointer text-primary font-medium m-b-12">
						Ulasan Pembeli
					</h5>

					<div class="dropdown-content dis-none p-t-15 p-b-23 p-lr-14">
						<p class="s-text8">
							<?php
								$this->db->where("idproduk",$data->id);
								$this->db->limit(8);
								$this->db->order_by("nilai,id DESC");
								$re = $this->db->get("review");
								if($re->num_rows() > 0){
									foreach($re->result() as $rev){
										$staron = "<i class='fa fa-star text-warning'></i>";
										$staroff = "<i class='fa fa-star text-secondary'></i>";
										$star = "";
										for($i=1; $i<=5; $i++){
											$star .= ($i <= $rev->nilai) ? $staron : $staroff;
										}
										echo "
											<div class='ulasan row m-b-16'>
												<div class='col-8 font-medium text-info'>".$this->func->getProfil($rev->usrid,"nama","usrid")."</div>
												<div class='col-4 font-medium text-info'><small>".$this->func->ubahTgl("d-m-Y",$rev->tgl)."</small></div>
												<div class='col-12 m-t-4'>".$star."</div>
												<div class='col-12 m-t-10 keterangan'>
												".$rev->keterangan."
												</div>
											</div>
										";
									}
								}else{
									echo "<i>Belum ada ulasan.</i>";
								}
							?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</section>


	<!-- Related Products -->
	<section class="sec-relate-product p-t-45 p-b-105">
		<div class="container">
			<div class="p-b-45">
				<h3 class="t-center font-bold text-primary">
					Produk Terkait
				</h3>
			</div>

			<!-- Slide2 -->
			<div class="wrap-slick2">
				<div class="prod-thumb">
					<div class="m-r-28"></div>
          <?php
            $this->db->where("idcat",$kategori->id);
            $this->db->where("id!=",$data->id);
            $this->db->limit(12);
            $this->db->order_by("id","RANDOM");
            $dbs = $this->db->get("produk");
            foreach($dbs->result() as $re){
				$level = isset($_SESSION["lvl"]) ? $_SESSION["lvl"] : 0;
				if($level == 5){
					$result = $re->hargadistri;
				}elseif($level == 4){
					$result = $re->hargaagensp;
				}elseif($level == 3){
					$result = $re->hargaagen;
				}elseif($level == 2){
					$result = $re->hargareseller;
				}else{
					$result = $re->harga;
				}
				$ulasan = $this->func->getReviewProduk($re->id);

				$this->db->where("idproduk",$re->id);
				$dbv = $this->db->get("produkvariasi");
				$totalstok = ($dbv->num_rows() > 0) ? 0 : $re->stok;
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
          ?>
			<div class="cursor-pointer produk-item m-lr-12 m-tb-24" style="width:240px;" onclick="window.location.href='<?php echo site_url('produk/'.$re->url); ?>'">
				<!-- Block2 -->
				<div class="block2">
					<div class="block2-img wrap-pic-w of-hidden pos-relative" style="background-image:url('<?=$this->func->getFoto($re->id,"utama")?>');"></div>
					<div class="block2-txt">
						<a href="<?php echo site_url('produk/'.$re->url); ?>" class="block2-name dis-block p-b-5">
							<?=$re->nama?>
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
					<div class="row block2-ulasan">
						<div class='col-6'>
							<small><?=$ulasan['ulasan']?> Ulasan</small>
						</div>
						<div class='col-6 text-right'>
							<span class="badge badge-warning bdg-1"><i class='fa fa-star'></i> <?=$ulasan['nilai']?></span>
						</div>
					</div>
				</div>
			</div>
          <?php } ?>

				</div>
			</div>
		</div>
	</section>

  <script>
	$(function(){
		$(".prod-thumb-item").on("click",function(){
			$("#prod-img").attr("src",$(this).data("thumb"));
		});

		$(".btn-wish").click(function(){
			setTimeout(() => {
				$(this).hide("slow");
			}, 1000);
		});

		$("#keranjang").on("submit",function(e){
			e.preventDefault();
			var submit = $("#submit").html();
			$("#submit").html("<i class='fas fa-compact-disk fa-spin'></i> memproses...");
			<?php if($data->preorder == 0){ ?>
				$.post("<?php echo site_url("assync/prosesbeli"); ?>",$(this).serialize(),function(msg){
					var data = eval("("+msg+")");
					//$("#proses").hide();
					$("#submit").html(submit);
					if(data.success == true){
						fbq('track', 'AddToCart', {content_ids:"<?=$data->id?>",content_type:"<?=$kategorinama?>",content_name:"<?=$data->nama?>",currency: "IDR", value: data.total});
						var nameProduct = $('#js-name-detail').html();
						swal.fire(nameProduct, "berhasil ditambahkan ke keranjang", "success").then((value) => {
							window.location.href = "<?php echo site_url("home/keranjang"); ?>";
						});
					}else{
						swal.fire("Gagal", "tidak dapat memproses pesanan \n "+data.msg, "error");
					}
				});
			<?php }else{ ?>
				$.post("<?php echo site_url("assync/prosespreorder"); ?>",$(this).serialize(),function(msg){
					var data = eval("("+msg+")");
					//$("#proses").hide();
					$("#submit").html(submit);
					if(data.success == true){
						fbq('track', 'AddToCart', {content_ids:"<?=$data->id?>",content_type:"<?=$kategori->nama?>",content_name:"<?=$data->nama?>",currency: "IDR", value: data.total});
						var nameProduct = $('#js-name-detail').html();
						swal.fire(nameProduct, "berhasil mengikuti preorder", "success").then((value) => {
							window.location.href = "<?php echo site_url("home/invoicepreorder"); ?>?inv="+data.inv;
						});
					}else{
						swal.fire("Gagal", "tidak dapat memproses pesanan", "error");
					}
				});
			<?php } ?>
		});

		$("#jumlahorder").change(function(){
			if(parseInt($(this).val()) < parseInt($(this).attr("min"))){
				$(this).val($(this).attr("min")).trigger("change");
			}
			
			if(parseInt($(this).val()) > parseInt($(this).attr("max"))){
				$(this).val($(this).attr("max")).trigger("change");
			}
		});
		
		$("#warna").on("change",function(){
			if($(this).val() != ""){
				$("#size").html($("#warna_"+$(this).val()).html());
			}else{
				$("#size").html("<option value=\"\">= Pilih <?=$data->variasi?> dulu =</option>");
			}
			$("#stokrefresh").html("");
		});
		$("#size").on("change",function(){
			$("#variasi").val($(this).find(":selected").data('variasi'));
			$("#jumlahorder").attr("max",$(this).find(":selected").data('stok'));
			$("#stokmaks").html($(this).find(":selected").data('stok'));
			$("#harga").val($(this).find(":selected").data('harga'));
			$("#hargacetak").html("Rp. "+formUang($(this).find(":selected").data('harga')));
			$("#stokrefresh").html("stok: "+$(this).find(":selected").data('stok'));
		});
	});
  </script>
  
  <div style="display:none;">
	<?php
		for($i=0; $i<count($warnaid); $i++){
			echo "
				<div id='warna_".$warnaid[$i]."'>
					<option value=''>= Pilih ".$data->subvariasi." =</option>
			";
			for($a=0; $a<count($sizeid[$warnaid[$i]]); $a++){
				if($stok[$warnaid[$i]][$a] > 0){
					if($level == 5){
						$result = $hardistri[$warnaid[$i]][$a];
					}elseif($level == 4){
						$result = $haragensp[$warnaid[$i]][$a];
					}elseif($level == 3){
						$result = $haragen[$warnaid[$i]][$a];
					}elseif($level == 2){
						$result = $harreseller[$warnaid[$i]][$a];
					}else{
						$result = $har[$warnaid[$i]][$a];
					}
					echo "<option value='".$sizeid[$warnaid[$i]][$a]."' data-stok='".$stok[$warnaid[$i]][$a]."' data-harga='".$result."' data-variasi='".$variasi[$warnaid[$i]][$a]."'>".$this->func->getSize($sizeid[$warnaid[$i]][$a],"nama")."</option>";
				}
			}
			echo "
				</div>
			";
		}
	?>
  </div>
