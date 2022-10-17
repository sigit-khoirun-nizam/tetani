
	<!-- breadcrumb
	<div class="container">
		<div class="bread-crumb m-b-30">
			<a href="/" class="text-primary">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="cl4">
				Shopping Cart
			</span>
		</div>
	</div>
 	-->

	<!-- Shoping Cart -->
	<div class="container">
			<div class="row m-lr-0">
				<div class="col-md-6 m-r-auto m-l-auto m-b-40 m-t-40">
					<div class="m-lr-0-xl">
						<h2 class="font-black text-primary text-center">
							Keranjang Belanja
						</h2>
					</div>
				</div>
			</div>
      <?php
		$keranjang = (isset($_SESSION["usrid"]) AND $_SESSION["usrid"] > 0) ? $this->func->getKeranjang() : 0;
		$hapusProduk = "";
        if($keranjang > 0){
      ?>
			<div class="m-lr-auto m-b-50">
                <?php
                  $this->db->where("usrid",$_SESSION['usrid']);
                  $this->db->where("idtransaksi",0);
                  $ca = $this->db->get("transaksiproduk");
                  $totalbayar = 0;
                  foreach ($ca->result() as $car) {
					$produk = $this->func->getProduk($car->idproduk,"semua");
					if($produk == null){ $hapusProduk .= "hapusProduk(".$car->id.")"; }
                    $totalbayar += $car->harga * $car->jumlah;
					$variasi = $this->func->getVariasi($car->variasi,"semua");
                ?>
					<div class="keranjang row" id="produk_<?php echo $car->id; ?>">
						<div class="col-md-2 col-4">
							<div class="img" style="background-image:url('<?php echo $this->func->getFoto($produk->id,"utama"); ?>')"></div>
						</div>
						<div class="col-md-10 col-8 row m-lr-0">
							<div class="col-md-5 m-b-10 centered flex-column">
								<span class="font-medium fs-20 w-full"><?php echo $produk->nama; ?></span>
								<?php
								if($car->variasi > 0){
									echo "<span class='text-info w-full' style='font-size:80%;'><b>".$produk->variasi." ".$this->func->getWarna($variasi->warna,'nama')." ".$produk->subvariasi." ".$this->func->getSize($variasi->size,'nama')."</b></span>";
								}
								if($car->keterangan != ""){
									echo "<span class='text-warning w-full' style='font-size:80%;'><b>Note: </b> <i>".$car->keterangan."</i></span>";
								}
								?>
							</div>
							<div class="col-md-3 col-12 m-b-20 centered">
								<div class="wrap-num-product input-group">
									<div class="num-product-down input-group-prepend cursor-pointer">
										<span class="input-group-text btn-info"><i class="fs-16 fas fa-minus text-light"></i></span>
									</div>

									<input class="form-control text-center num-product produk-jumlah" type="number" min="<?php echo $produk->minorder; ?>" id="jumlah_<?php echo $car->id; ?>" name="jumlah[]" value="<?php echo $car->jumlah; ?>" data-proid="<?php echo $car->id; ?>" />

									<div class="num-product-up input-group-append cursor-pointer">
										<span class="input-group-text btn-info"><i class="fs-16 fas fa-plus text-light"></i></span>
									</div>
								</div>
							</div>
							<div class="col-8 col-md-3 centered">
								Rp&nbsp;<span id="totalhargauang_<?php echo $car->id; ?>"><?php echo $this->func->formUang($car->harga*$car->jumlah); ?></span>
							</div>
							<div class="col-4 col-md-1 centered">
								<a href="javascript:void(0)" onclick="hapus(<?=$car->id?>)" class="btn btn-danger"><i class="fa fa-trash-alt"></i></a>
							</div>
						</div>
					</div>
					<input type="hidden" id="harga_<?php echo $car->id; ?>" value="<?php echo $car->harga; ?>" />
					<input type="hidden" class="totalhargaproduk" id="totalharga_<?php echo $car->id; ?>" value="<?php echo $car->harga*$car->jumlah; ?>" />
					<input type="hidden" name="id[]" value="<?php echo $car->id; ?>" />
                <?php
                  }
                ?>
			</div>

			<div class="p-t-18 p-b-15 p-lr-40 p-lr-15-sm m-b-90">
				<div class="row">
					<div class="col-md-8"></div>
					<div class="col-md-4">
						<h5 class="m-b-20 font-bold text-success text-right">Total : Rp <span id="totalbayar" style="padding-left:40px"><?php echo $this->func->formUang($totalbayar); ?></span></h5>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6"></div>
					<div class="col-md-3 m-b-10 p-lr-6">
						<a href="<?php echo site_url("shop"); ?>" class="btn btn-primary btn-lg btn-block">
							Kembali Berbelanja
						</a>
					</div>
					<div class="col-md-3 m-b-10 p-lr-6">
						<a href="<?php echo site_url("home/pembayaran"); ?>" class="btn btn-success btn-lg btn-block">
							Selesaikan Pesanan
						</a>
					</div>
				</div>
			</div>
		<?php
			}else{
		?>
			<div class="row p-lr-16">
				<div class="col-lg-12 m-lr-auto p-tb-150 m-t-40 m-b-150 text-light bg-info" style="border-radius: 16px;">
					<div class="m-lr-0-xl text-center">
						<h3>Keranjang belanja masih kosong.</h3>
					</div>
				</div>
			</div>
		<?php
			}
		?>
	</div>
  <script>
	<?=$hapusProduk?>
	  
	$('.num-product-down').on('click', function(e){
        e.stopPropagation();
        e.preventDefault();
        var numProduct = Number($(this).next().val());
        if(numProduct > 1) $(this).next().val(numProduct - 1).trigger("change");
    });

    $('.num-product-up').on('click', function(e){
        e.stopPropagation();
        e.preventDefault();
        var numProduct = Number($(this).prev().val());
        $(this).prev().val(numProduct + 1).trigger("change");
    });

    $(".produk-jumlah").on('change',function(){
      var jumlah = $(this).val();
      var prodid = $(this).attr("data-proid");
      var harga = Number($("#harga_"+prodid).val());
	  var hargatotal = Number(jumlah) * harga;

      if(jumlah > 0){

		if(jumlah < Number($(this).attr("min"))){
			$(this).val($(this).attr("min")).trigger("change");
			return;
	    }

		$.post("<?php echo site_url("assync/updatekeranjang"); ?>",{"update":prodid,"jumlah":jumlah,[$("#names").val()]: $("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			if(data.success == false){
				swal.fire("Gagal",data.msg,"error")
				.then((willDelete) => {
					location.reload();
				});
			}
		});

        $("#totalhargauang_"+prodid).html(formUang(hargatotal));
        $("#totalharga_"+prodid).val(hargatotal);
        var sum = 0;
        $(".totalhargaproduk").each(function(){
          sum += parseFloat($(this).val());
        });
        $("#totalbayar").html(formUang(sum));

	  }else{
		swal.fire({
		  	title: "Anda yakin?",
		  	text: "menghapus produk dari keranjang belanja",
		  	icon: "warning",
			showDenyButton: true,
			confirmButtonText: "Oke",
			denyButtonText: "Batal",
		})
		.then((willDelete) => {
		  if (willDelete.isConfirmed) {
          //$("#produk_"+prodid).hide();
	        $.post("<?php echo site_url("assync/hapuskeranjang"); ?>",{"hapus":prodid,[$("#names").val()]: $("#tokens").val()},function(msg){
	          	var data = eval("("+msg+")");
			  	updateToken(data.token);
	          	if(data.success == true){
	            	location.reload();
	          	}else{
	            	swal.fire("Gagal","Gagal menghapus pesanan, silahkan ulangi beberapa saat lagi","error");
	          	}
	        });
		  }else{
			$(this).val($(this).attr("min"));
		  }
        });
      }
    });
	
	function hapus(id){
		$("#jumlah_"+id).val(0).trigger("change");
	}
	
	function hapusProduk(id){
		$.post("<?php echo site_url("assync/hapuskeranjang"); ?>",{"hapus":id,[$("#names").val()]: $("#tokens").val()},function(msg){
	        var data = eval("("+msg+")");
			updateToken(data.token);
	        if(data.success == true){
	            location.reload();
	        }else{
	            swal.fire("Gagal","Gagal menghapus pesanan, silahkan ulangi beberapa saat lagi","error");
	        }
	    });
	}
  </script>
