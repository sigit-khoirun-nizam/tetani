<?php
  if($data->num_rows() == 0){
    redirect("home");
    exit;
  }
?>

	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-l-0 p-l-0">
			<a href="<?php echo site_url(); ?>" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Pembayaran
			</span>
		</div>
	</div>


	<!-- Shoping Cart -->
	<form class="bg0 p-t-75 p-b-85" id="cekout">
		<div class="container">
			<div class="row">
        <div class="col-md-10 m-l-auto m-r-auto m-b-10">
          <div class="bor10 p-r-40  p-l-40 p-t-30 p-b-40 m-l-0-xl m-r-0-xl p-l-15 p-r-25">
            <h4 class="mtext-109 cl2 p-b-30">
              Alamat Pengiriman
            </h4>

            <?php
              $this->db->where("usrid",$_SESSION["usrid"]);
              $this->db->order_by("status","DESC");
              $row = $this->db->get("alamat");
            ?>
            <div class="p-b-13">
              <input type="hidden" id="tujuan" value="" name="tujuan" />
              <div class="m-b-12 alamatform">
                <b>Alamat Pengiriman</b>
              </div>
              <div class="rs1-select2 rs2-select2 bor8 bg0 m-b-12 alamatform">
                <select class="js-select2" name="alamat" id="idalamat">
                  <option value="">= Pilih Alamat Tujuan =</option>
                  <?php
                    foreach($row->result() as $al){
                      //RAJAONGKIR
                      $kec = $this->func->getKec($al->idkec,"semua");
                      $idkab = $kec->idkab;
                      $keckab = $kec->nama.", ".$this->func->getKab($idkab,"nama");
                      echo '<option value="'.$al->id.'" data-tujuan="'.$al->idkec.'">'.strtoupper(strtolower($al->judul.' - '.$al->nama)).' ('.$keckab.')</option>';
                    }
                  ?>
                  <option value="0">+ Tambah Alamat Baru</option>
                </select>
                <div class="dropDownSelect2"></div>
              </div>
              <div class="m-b-12">
                <?php
                  foreach($row->result() as $als){
                    $kec = $this->func->getKec($al->idkec,"semua");
                    $idkab = $kec->idkab;
                    $kec = $kec->nama;
                    $kab = $this->func->getKab($idkab,"nama");
                    echo "
                      <div class='alamat bor8 p-l-20 p-r-20 p-t-20 p-b-20' id='alamat_".$als->id."' data-tujuan='".$al->idkec."' style='display:none;'>
                        <b>Nama Penerima:</b><br/>".strtoupper(strtolower($als->nama))."<br/>
                        <b>No HP:</b><br/>".$als->nohp."<br/>
                        <b>Alamat Lengkap:</b><br/>".strtoupper(strtolower($als->alamat."<br/>".$kec.", ".$kab))."<br/>KODEPOS ".$als->kodepos."
                      </div>
                    ";
                  }
                ?>
              </div>
            </div>

            <div class="p-b-13">
              <div class="m-b-12 tambahalamat" style="display:none;">
                <b>Tambah Alamat Pengiriman</b>
              </div>
              <div class="tambahalamat" style="display:none;">
                  <div class="bor8 m-b-12 how-pos4-parent">
                    <input class="stext-111 cl2 plh3 size-116 p-l-20 p-r-30" type="text" name="judul" placeholder="Simpan Sebagai? ex: Alamat Rumah, Alamat Kantor, Dll">
                  </div>
                  <div class="bor8 m-b-12 how-pos4-parent">
                    <input class="stext-111 cl2 plh3 size-116 p-l-20 p-r-30" type="text" name="nama" placeholder="Nama Penerima">
                  </div>
                  <div class="bor8 m-b-12 how-pos4-parent">
                    <input class="stext-111 cl2 plh3 size-116 p-l-20 p-r-30" type="text" name="nohp" placeholder="No Handphone Penerima">
                  </div>
                  <div class="bor8 m-b-12">
                    <textarea class="w-full stext-111 cl2 plh3 p-l-20 p-m-20 p-t-25 p-b-25" name="alamatbaru" placeholder="Alamat lengkap"></textarea>
                  </div>
                    <div class="rs1-select2 rs2-select2 bor8 bg0 m-b-12">
                      <select class="js-select2" name="negara" readonly>
                        <option value="ID">Indonesia</option>
                      </select>
                      <div class="dropDownSelect2"></div>
                    </div>
                    <div class="rs1-select2 rs2-select2 bor8 bg0 m-b-12">
                      <select class="js-select2" id="prov">
                        <option value="">Provinsi</option>
                        <?php
                          $prov = $this->db->get("prov");
                          foreach($prov->result() as $pv){
                            echo '<option value="'.$pv->id.'">'.$pv->nama.'</option>';
                          }
                        ?>
                      </select>
                      <div class="dropDownSelect2"></div>
                    </div>
                    <div class="rs1-select2 rs2-select2 bor8 bg0 m-b-12">
                      <select class="js-select2" id="kab">
                        <option value="">Kabupaten</option>
                      </select>
                      <div class="dropDownSelect2"></div>
                    </div>
                    <div class="rs1-select2 rs2-select2 bor8 bg0 m-b-12">
                      <select class="js-select2" id="kec" name="idkec">
                        <option value="">Kecamatan</option>
                      </select>
                      <div class="dropDownSelect2"></div>
                    </div>
                  <div class="row m-l-0 m-r-0">
                    <div class="bor8 m-b-12 how-pos4-parent col-md-6 p-l-0 p-r-0">
                      <input class="stext-111 cl2 plh3 size-116 p-l-20 p-r-30" type="number" name="kodepos" placeholder="Kode POS">
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
		<div class="col-md-10 m-r-auto m-l-auto m-b-10">
			<div class="bor10 p-l-40 p-r-40 p-t-30 p-b-30 m-r-0 m-l-0">
              <div class="row m-l-0 m-r-0 p-t-20 p-b-20" style="background-color:rgba(192,57,43,0.1);">
                <div class="col-md-4 p-t-10 p-b-10">
                  <b>Kurir Pengiriman</b>
                </div>
                <div class="col-md-4">
                  <div class="rs1-select2 rs2-select2 bor8 bg0 w-full">
                    <select class="js-select2 kurir"  name="kurir">
                      <option value="">Pilih Ekspedisi</option>
                      <?php
                        $kur = $this->db->get("kurir");
                        foreach($kur->result() as $kr){
                          $kurir = explode("|",$this->func->getSetting("kurir"));
                          if(in_array($kr->id,$kurir)){
                            echo '<option value="'.$kr->rajaongkir.'">'.$kr->nama.'</option>';
                          }
                        }
                      ?>
                    </select>
                    <div class="dropDownSelect2"></div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="rs1-select2 rs2-select2 bor8 bg0 w-full" id="paketform">
                    <select class="js-select2 paket" name="paket">
                      <option value="">Pilih Paket Pengiriman</option>
                    </select>
                    <div class="dropDownSelect2"></div>
                  </div>
                </div>
                <div class="error cl1 col-md-12 m-t-10" id="error-kurir" style="display:none;">
                  <span><i class="fa fa-exclamation-circle"></i> pilihan ekspedisi atau paket pengiriman tidak tersedia, silahkan pilih yg lain.</span><p/>
                </div>
              </div>
			</div>
		</div>
		<div class="col-md-10 m-r-auto m-l-auto m-b-50">
			<div class="bor10 p-lr-40 p-t-30 p-b-30 m-r-0 m-l-0">
        <div class="row m-l-0 m-r-0 p-t-20 p-b-20">
          <!--
          <div class="col-md-5 p-tb-0 m-b-20">
            <b>Voucher</b>
            <div class="input-group m-t-10">
              <input type="hidden" name="diskon" id="diskon" value='0' />
              <input type="text" class="form-control" placeholder="Masukkan voucher" id="kodevoucher" name="kodevoucher">
              <div class="input-group-append">
                <button class="btn btn-secondary" type="button" onclick="cekVoucher()">Cek Voucher</button>
              </div>
            </div>
            <div class="m-t-20">
              <div class="vouchergagal text-danger" style="display:none;">Maaf, Voucher sudah tidak berlaku</div>
              <div class="vouchersukses text-success" style="display:none;">Selamat, Voucher berhasil dipakai dan nikmati potongan harganya</div>
            </div>
          </div>
          -->
          <div class="col-md-7 p-tb-0">
            <b>Dropship</b>
            <div class="m-t-10">
              <button type="button" id="nodrop" class="btn btn-primary"><i class="fa fa-check-square"></i> Tidak Dropship</button>
              <button type="button" id="yesdrop" class="btn btn-outline-primary"><i class="fa fa-check-square" style="display:none"></i> Dropship</button>
            </div>
            <div class="p-t-20" id="dropform" style="display:none;">
              <input type="text" name="dropship" class="form-control col-md-8" placeholder="nama" />
              <input type="text" name="dropshipnomer" class="form-control col-md-6 m-t-5" placeholder="no telepon" />
              <input type="text" name="dropshipalamat" class="form-control m-t-5" placeholder="alamat" />
            </div>
          </div>
			  </div>
			</div>
		</div>
		<div class="col-md-10 m-l-auto m-r-auto m-b-10">
			<div class="bor10 p-l-40 p-r-40 p-t-30 p-b-30 m-l-0 m-r-0">
            <div class="row">
              <div class="col-md-6">
    			<h4 class="mtext-109 cl2">
    				Produk Pesanan
    			</h4>
              </div>
              <div class="col-md-2 hidesmall txt-center">Jumlah</div>
              <div class="col-md-2 hidesmall txt-center">Harga</div>
              <div class="col-md-2 hidesmall txt-center">Subtotal</div>
            </div>
          </div>
        </div>
        <?php
          $totalharga = 0;
          $totalbayar = 0;
          $totalberat = 0;
          $totaldiskon = 0;
        ?>
		<div class="col-md-10 m-r-auto m-l-auto m-b-10">
			<div class="bor10 p-l-40 p-r-40 p-t-30 p-b-40 m-l-0 m-r-0">

              <?php
                  foreach($data->result() as $res){
                    $produk = $this->func->getProduk($res->idproduk,"semua");
                    $totalbayar += $res->harga * $res->jumlah;
                    $totalberat += $produk->berat * $res->jumlah;
                    $totalproduks = $res->harga*$res->jumlah;
                    $totalharga += $res->harga*$res->jumlah;
                    $totaldiskon += $res->diskon;
                    $variasee = $this->func->getVariasi($res->variasi,"semua");
                    $variasi = ($res->variasi != 0) ? $this->func->getWarna($variasee->warna,"nama")." ".$produk->subvariasi." ".$this->func->getSize($variasee->size,"nama") : "";
                    $variasi = ($res->variasi != 0) ? "<br/><small class='text-primary'>".$produk->variasi.": ".$variasi."</small>" : "";
              ?>
				<div class="row p-b-30 cl2" style="align-items:center;">
					<div class="col-md-6 row m-l-0 m-r-0" style="align-items:center;">
						<div class="how-itemcart1">
							<img src="<?php echo $this->func->getFoto($produk->id,"utama"); ?>" alt="IMG">
						</div>
						<div class="p-l-10"><?php echo $produk->nama.$variasi; ?></div>
					</div>
					<div class="col-2 txt-center hidesmall">
						<p><?php echo $res->jumlah; ?></p>
					</div>
					<div class="col-2 txt-center hidesmall">
						<p><?php echo $this->func->formUang($res->harga); ?></p>
					</div>
					<div class="col-md-2 txt-center">
						<p><?php echo $this->func->formUang($res->harga*$res->jumlah); ?></p>
					</div>

          <input type="hidden" class="idproduks" name="idproduk[]" value="<?php echo $res->id; ?>" />
					<input type="hidden" id="totalproduks_<?php echo $res->id; ?>" value="<?php echo $totalproduks; ?>" />
					<input type="hidden" id="namaproduks_<?php echo $res->id; ?>" value="<?php echo $produk->nama; ?>" />
					<input type="hidden" id="kategoriproduks_<?php echo $res->id; ?>" value="<?php echo $this->func->getKategori($produk->idcat,"nama"); ?>" />
				</div>
              <?php
                  }
              ?>
                <input type="hidden" id="totalharga" value="<?php echo $totalharga; ?>" />
                <input type="hidden" id="totaldp" value="<?php echo $totaldiskon; ?>" />
				<input type="hidden" name="berat" class="berat" id="berat" value="<?php echo $totalberat; ?>" />
				<input type="hidden" name="ongkir" class="ongkir" id="ongkir" value="0" />
				<!-- RAJAONGKIR -->
				<input type="hidden" name="dari" id="dari" value="<?php echo $this->func->getSetting("kota"); ?>" />
			</div>
		</div>
  		<div class="col-md-10 m-l-auto m-r-auto m-b-50">
			<div class="bor10 p-l-40 p-r-40 p-t-30 p-b-40 m-r-0 m-l-0">
				<input type="hidden" id="subtotal" value="<?php echo $totalbayar; ?>" />
				<input type="hidden" id="total" name="total" value="<?php echo $totalbayar; ?>" />
				<!-- Subtotal -->
				<div class="row">
					<div class="col-md-6">
						<p>Subtotal</p>
					</div>
					<div class="col-md-6">
						<p style="text-align: right">Rp <span id="subtotalbayar" style="padding-left:40px"><?php echo $this->func->formUang($totalbayar); ?></p>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<p>Ongkos Kirim</p>
					</div>
					<div class="col-md-6">
						<p style="text-align: right">Rp <span id="ongkirshow">0</span></p>
					</div>
				</div>
        <!--
				<hr>
				<div class="row">
					<div class="col-md-6">
						<p class="text-danger">Potongan Voucher</p>
					</div>
					<div class="col-md-6">
						<p style="text-align: right" class="text-danger">- Rp <span id="diskonshow">0</span></p>
					</div>
				</div>
        -->
				<hr>
				<div class="row">
					<div class="col-md-6">
						<p class="text-danger">Pembayaran DP</p>
					</div>
					<div class="col-md-6">
						<p style="text-align: right" class="text-danger">- Rp <?=$this->func->formUang($totaldiskon)?></p>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<h5>Total</h5>
					</div>
					<div class="col-md-6">
						<h5 style="text-align: right">Rp <span id="totalbayar" style="padding-left:40px"><?php echo $this->func->formUang($totalbayar-$totaldiskon); ?></h5>
					</div>
				</div>
			</div>
        </div>
        <div class="col-md-10 m-r-auto m-l-auto m-b-50">
          <div class="bor10 p-r-40 p-l-40 p-t-30 p-b-40 m-l-0 m-l-0 p-r-15 p-l-15">
            <h4 class="mtext-109 cl2 p-b-30">
              Pembayaran
            </h4>
            <div class="error cl1" id="error-bayar">
              <i>Belum dapat menyelesaikan pesanan, silahkan lengkapi alamat dan total beserta ongkos kirim terlebih dahulu.</i><p/>
            </div>
            <div class="cl1" id="proses" style="display:none;">
              <h5><i class="fa fa-circle-o-notch fa-spin"></i> <i>Memproses pesanan, mohon tunggu sebentar</i></h5><p/>
            </div>
            <div class="p-b-13 pembayaran" style="display:none;">
              <div class="m-b-12">
                <b>Metode Pembayaran</b>
              </div>
              <div class="row m-r-0 m-l-0">
                <div class="rs1-select2 rs2-select2 bor8 bg0 m-b-12 col-md-4 p-l-0 p-r-0">
                  <select class="js-select2" name="metode" id="metode">
                    <option value="1">Transfer</option>
                    <?php if($saldo > 0){ ?>
                    <option value="2">Saldo (Rp <?php echo $this->func->formUang($saldo); ?>)</option>
                    <?php } ?>
                  </select>
                  <div class="dropDownSelect2"></div>
                </div>
                <div class="col-md-8 row m-r-0 m-l-0">
                  <?php if($saldo > 0){ ?>
                  <div class="col-md-6">
                    Potong Saldo:<br/>
                    <h5 id="saldo">Rp 0</h5>
                  </div>
                  <?php } ?>
                  <div class="col-md-6">
                    Transfer:<br/>
                    <h5 id="transfer">Rp <?php echo $this->func->formUang($totalbayar); ?></h5>
                  </div>
                </div>
                <input type="hidden" id="saldoval" value="<?php echo $saldo ?>" />
                <input type="hidden" name="saldo" id="saldopotong" value="0" />
              </div>
            </div>
            <div class="p-b-13 pembayaran" style="display:none;">
            	<a href="javascript:void(0);" onclick="checkoutNow();" class="flex-c-m stext-101 cl0 size-101 bg-1 hovbtn2 p-l-15 p-r-10 trans-04">
            		Lanjut Pembayaran
            	</a>
          	</div>
          </div>
        </div>
			</div>
		</div>
	</form>

  <script type="text/javascript">
	$(function(){
		$("#cekout").on("submit",function(e){
		  e.preventDefault();
		});
		
		$("#nodrop").click(function(){
			$("#yesdrop").removeClass("btn-primary");
			$("#yesdrop").addClass("btn-outline-primary");
			$(this).removeClass("btn-outline-primary");
			$(this).addClass("btn-primary");
			$(".fa",this).show()
			$("#yesdrop .fa").hide();
			$("#dropform").hide();
			$("#dropform input").val("");
			$("#dropform input").prop("required",false);
		});
		$("#yesdrop").click(function(){
			$("#nodrop").removeClass("btn-primary");
			$("#nodrop").addClass("btn-outline-primary");
			$(this).removeClass("btn-outline-primary");
			$(this).addClass("btn-primary");
			$("#dropform").show();
			$(".fa",this).show()
			$("#nodrop .fa").hide();
			$("#dropform input").prop("required",true);
		});

		$(".ongkir").change(function(){
		  var ongkir = $(this).val();
		  //if(ongkir > 0){
			var harga = $("#totalharga").val();
			var totalharga = Number(harga) + Number(ongkir);

			//$("#ongkirtoko").html(formUang(ongkir));
			//$("#totaltoko").html(formUang(totalharga));
		  //}
		});

		$("#idalamat").change(function(){
		  var idalamat = $(this).val();
		  var tujuan = $("#alamat_"+idalamat).data('tujuan');
		  $("#tujuan").val(tujuan);

		  $(".alamat").hide();
		  if($(this).val() == ""){
			resetOngkir();
			$(".tambahalamat").hide();
			$(".tambahalamat input,.tambahalamat textarea").prop("required",false);
		  }else if($(this).val() == 0){
			resetOngkir();
			$(".tambahalamat").show();
			$(".tambahalamat input,.tambahalamat textarea").prop("required",true);
			if($("#kab").val() != ""){
			  $("#tujuan").val($("#kab").val());
			  hitungOngkir();
			}else{
			  resetOngkir();
			}
		  }else if($(this).val() > 0){
			$("#alamat_"+idalamat).show();
			$(".tambahalamat").hide();
			$(".tambahalamat input,.tambahalamat textarea").prop("required",false);

			hitungOngkir();
		  }
		});
		
		$("#kodevoucher").change(function(){
			$("#diskon").val(0);
			$("#diskonshow").html(0);
			//$("#totalbayar").html(formUang($("#total").val()));
			hitungOngkir();
			$(".vouchergagal").hide();
			$(".vouchersukses").hide();
		});
	});
	
	//CEK VOUCHER
	function cekVoucher(){
		if($("#kodevoucher").val() != ""){
			$.post("<?=site_url("assync/cekvoucher")?>",{"kode":$("#kodevoucher").val(),"harga":$("#totalharga").val(),"ongkir":$("#ongkir").val()},function(msg){
				var data = eval("("+msg+")");
				if(data.success == true){
					var total = parseFloat($("#total").val()) - data.diskon - parseFloat($("#totaldp").val());
					$("#diskon").val(data.diskon);
					$("#diskonshow").html(formUang(data.diskon));
					$("#totalbayar").html(formUang(total));
					$("#transfer").html(formUang(total));
					$(".vouchergagal").hide();
					$(".vouchersukses").show();
				}else{
					$("#diskon").val(0);
					$("#diskonshow").html(0);
					$(".vouchergagal").show();
					$(".vouchersukses").hide();
				}
			});
		}else{
			swal("Masukkan Kode Voucher!","masukkan kode voucher terlebih dahulu lalu klik tombol cek voucher","warning");
		}
	}

    //HITUNG ONGKIR
    function hitungOngkir(){
      if(($("#idalamat").val() != "" && $("#idalamat").val() != "0") || $("#tujuan").val() > 0){
        var tujuan = $("#tujuan").val();

          var kurir = $(".kurir").val();
          var service = $(".paket").val();
          var berat = $("#berat").val();
          var dari = $("#dari").val();
          if(kurir != "" && service != ""){
            $.post("<?php echo site_url("assync/cekongkir"); ?>",{"berat":berat,"dari":dari,"tujuan":tujuan,"kurir":kurir,"service":service},function(msg){
              var data = eval("("+msg+")");
              if(data.success == true){
                $("#ongkir").val(data.harga).trigger("change");
                if(data.harga == 0 && $(".kurir").val() != "cod"){
                  errorKurir();
                }
                calculateOngkir();
              }else{
                $("#ongkir").val(0).trigger("change");
                calculateOngkir();
                errorKurir();
              }
            });
          }
      }else{
        swal("Penting!","mohon cek kembali dan pastikan alamat sudah benar","error");
      }
    }
    function calculateOngkir(){
      var sum = 0;
      var sumi = true;
      $(".ongkir").each(function(){
        sum += parseFloat($(this).val());
        if($(this).val() > 0 && sumi == true){
          sumi = true;
        }else{
			if($(".kurir").val() == "cod"){
				sumi == true;
			}else{
				sumi = false;
			}
        }
      });
      var totalbayar = sum + parseFloat($("#subtotal").val()) - parseFloat($("#diskon").val()) - parseFloat($("#totaldp").val());

      if(sumi == false){
        $(".pembayaran").hide();
        $("#error-bayar").show();
      }else{
        $(".pembayaran").show();
        $("#error-bayar").hide();
      }

      $("#total").val(totalbayar);
      $("#totalbayar").html(formUang(totalbayar));
      $("#ongkirshow").html(formUang(sum));
      //$('html, body').animate({ scrollTop: $("#totalbayar").offset().top - 400 });

      //RESET PEMBAYARAN
      $("#transfer").html("Rp "+formUang(totalbayar));
      $("#saldo").html("Rp 0");
      $("#saldopotong").val(0);
      $("#metode option").prop("selected",false);
      $("#metode option[value=1]").prop("selected",true).trigger("change");
    }
    function resetOngkir(){
	  var total = parseFloat($("#subtotal").val()) - parseFloat($("#diskon").val()) - parseFloat($("#totaldp").val());
      $("#total").val($("#subtotal").val());
      $("#totalbayar").html(formUang(total));
      $("#saldo").html("Rp 0");
      $(".ongkir").val(0);
      $("#ongkirshow").html(0);
      //$("#tujuan").val(0);

      //RESET PEMBAYARAN
      $(".pembayaran").hide();
      $("#error-bayar").show();
      $("#transfer").html("Rp "+formUang($("#subtotal").val()));
      $("#saldopotong").val(0);
      $("#saldo").html("Rp 0");
      $("#metode option").prop("selected",false);
      $("#metode option[value=1]").prop("selected",true).trigger("change");
    }
    function errorKurir(){
      $("#error-kurir").show();
      //$('html, body').animate({ scrollTop: $("#error-kurir-"+idtoko).offset().top - 260 });
    }

    //METODE Bayar
    $("#metode").change(function(){
      var saldo = parseFloat($("#saldoval").val());
      var total = parseFloat($("#total").val());

      if($(this).val() == 2){
        if(saldo >= total){
          $("#saldopotong").val(total);
          $("#saldo").html("Rp "+formUang(total));
          $("#transfer").html("Rp 0");
        }else{
          var selisih = total - saldo;
          $("#saldopotong").val(saldo);
          $("#saldo").html("Rp "+formUang(saldo));
          $("#transfer").html("Rp "+formUang(selisih));
        }
      }else{
        $("#saldopotong").val(0);
        $("#saldo").html("Rp 0");
        $("#transfer").html("Rp "+formUang(total));
      }
    });

    //KURIR PENGIRIMAN
    $(".kurir").change(function(){
      $("#error-kurir").hide();
      if($(this).val() != ""){
        var data = $("#service"+$(this).val()).html();
        $(".paket").html(data)
        $(".ongkir").val("0");
        $("#ongkirshow").html("0");
        $(".alamatform").show();

        hitungOngkir();
      }else{
        resetOngkir();
        $(".alamatform").hide();
      }
    });
    $(".paket").change(function(){
      $("#error-kurir").hide();
      hitungOngkir();
    });

    //LOAD KABUPATEN KOTA & KECAMATAN
    $("#prov").change(function(){
      $("#kab").html("<option value=''>Loading...</option>");
      $("#kec").html("<option value=''>Kecamatan</option>");
      resetOngkir();
			$.post("<?php echo site_url("assync/getkab"); ?>",{"id":$(this).val()},function(msg){
				var data = eval("("+msg+")");
				$("#kab").html(data.html);
			});
		});
		$("#kab").change(function(){
      resetOngkir();
      $("#kec").html("<option value=''>Loading...</option>");
			$.post("<?php echo site_url("assync/getkec"); ?>",{"id":$(this).val()},function(msg){
				var data = eval("("+msg+")");
				$("#kec").html(data.html);
			});
		});
    $("#kec").change(function(){
      var data = $(this).find(":selected").val();
      $("#tujuan").val(data);
			hitungOngkir();
    });

    function checkoutNow(){
      $(".pembayaran").hide();
      $("#proses").show();

      $(".idproduks").each(function(){
        var id = $(this).val();
        var nama = $("#namaproduks_"+id).val();
        var kategori = $("#kategoriproduks_"+id).val();
        var total = $("#totalproduks_"+id).val();
        fbq('track', 'Purchase', {content_ids:id,content_type:kategori,content_name:nama,currency: "IDR", value: total});
      });

      $.post("<?php echo site_url("assync/bayarpesanan"); ?>",$("#cekout").serialize(),function(msg){
        var data = eval("("+msg+")");
        if(data.success == true){
          window.location.href = data.url;
        }else{
          $(".pembayaran").show();
          $("#proses").hide();
        }
      });
    }
  </script>

  <!-- ADDITIONAL DATA FOR RAJAONGKIR -->
  <select id="servicecod" style="display:none;">
  	<option value="cod" selected>COD</option>
  </select>
  <select id="servicetoko" style="display:none;">
  	<option value="toko" selected>Kurir Toko</option>
  </select>
  <?php
    $kr = $this->db->get("kurir");
    foreach($kr->result() as $kur){
  ?>
  <select id="service<?php echo $kur->rajaongkir; ?>" style="display:none;">
  	<option value="">Pilih Paket</option>
    <?php
      $this->db->where("idkurir",$kur->id);
      $pak = $this->db->get("paket");
      $no = 1;
      foreach($pak->result() as $pk){
        $select = ($no == 1) ? "selected" : "";
  	    echo '<option value="'.$pk->rajaongkir.'" '.$select.'>'.$pk->nama.'</option>';
        $no++;
      }
    ?>
  </select>
  <?php
    }
  ?>
