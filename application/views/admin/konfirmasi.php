<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$idbayar = $this->func->arrEnc($idbayar,"decode");
$idbayar = $idbayar["idbayar"];

$this->db->where("id",$idbayar);
$db = $this->db->get("pembayaran");
$status = 0;
foreach($db->result() as $resah){ $status = $resah->status; }
if($status == '0'){
?>
<div class="seksen-shadow"></div>
<div class="seksen">
	<div class="divider"></div>
	<div class="wrapper" id="load">
		<div class="kolom">
			<div class="subtitle">
				<b>KONFIRMASI PEMBAYARAN</b>
				<div class="line"></div>
			</div>
			<div class="kolom6">
				<?php
					$this->db->where("id",$idbayar);
					$db = $this->db->get("pembayaran");
					foreach($db->result() as $res){
				?>
					<div class="invoice" style="margin-right:20px;">
						<div class="head">
							<div class="noinvoice">Invoice: <b><?php echo $res->invoice; ?></b></div>
							<div class="detil">
								Tgl Transaksi: <b><?php echo $this->func->ubahTgl("d M Y",$res->tgl); ?></b>
								<span class="hidesmall">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br class="showsmall"/>
								Total: <b style="color:#cc0000;">Rp. <?php echo $this->func->formUang($res->total); ?>,-</b>
							</div>
						</div>
						<div class="detil">
							<?php
								$this->db->where("idbayar",$res->id);
								$db = $this->db->get("pengiriman");
								foreach($db->result() as $re){
									$toko = $this->func->getToko($re->idtoko,"semua");
									$alamat = $this->func->getAlamat($re->alamat,"semua");
									$kec = $this->func->getKec($alamat->idkec,"nama");
									$idkab = $this->func->getKec($alamat->idkec,"idkab");
									$kota = $this->func->getKab($idkab,"nama");
									$idprov = $this->func->getKab($idkab,"idprov");
									$prov = $this->func->getProv($idprov,"nama");
									echo "
										<div class='item'>
											<div class='title'>
												pembelian dari: <br/>
												<a href='".site_url("toko/".$toko->url)."'>
												".strtoupper($toko->nama)."
												</a>
												<br/><br/>
												pengiriman: <br/>
												<b>".$alamat->nama."</b> ".ucwords(strtolower("( $kec, $kota, $prov )"))."<br/>
												<b>".strtoupper($re->kurir." ".$re->paket)."</b>
											</div>
									";
									$this->db->where("pengiriman",$re->id);
									$db = $this->db->get("transaksi");
									foreach($db->result() as $r){
										$produk = $this->func->getProduk($r->idproduk,"semua");
										$harga = $r->harga / $r->jumlah;
										echo "
											<div class='produk'>
												<div class='img' style='background-image: url(\"".$this->func->getFoto($r->idproduk,"utama")."\");'>
												</div>
												<div class='detil'>
													<a href='".site_url("produk/".$produk->url)."'>".$produk->nama."</a><p>
													$r->jumlah pcs @Rp ".$this->func->formUang($harga)."
												</div>
											</div>
										";
									}
									echo "
										</div>
									";
								}
							?>
						</div>
					</div>
				<?php
					}
				?>
			</div>
			<div class="kolom6">
				<form method="post" action="<?php echo site_url("manage/konfirmasi"); ?>" enctype='multipart/form-data'>
					<input type="hidden" name="idbayar" value="<?php echo $idbayar; ?>" />
					<ul class="form-parent">
						<li>
							<div class="inner-addon left-addon">
								<span class="fa fa-credit-card"></span>
								<select id="dari" name="dari" class="form-bordered" required>
									<option value="">Rekening asal</option>
								</select>
							</div>
						</li>
						<li>
							<div class="inner-addon left-addon">
								<span class="fa fa-credit-card"></span>
								<select name="tujuan" class="form-bordered" required>
									<option value="">Rekening tujuan</option>
									<?php
										$this->db->where("usrid",0);
										$db = $this->db->get("rekening");
										foreach($db->result() as $res){
											echo "<option value='".$res->id."'>BANK ".$this->func->getBank($res->idbank,"nama")." - ".$res->norek." a/n ".$res->atasnama."</option>";
										}
									?>
								</select>
							</div>
						</li>
						<li class="kolom8">
							<div class="inner-addon left-addon">
								<span class="fa">Rp</span>
								<input class="form-bordered" type="number" name="total" placeholder="total transfer" required />
							</div>
						</li>
						<li class="kolom6">
							<div class="inner-addon left-addon">
								<span class="far fa-calendar-alt"></span>
								<input class="form-bordered datepicker" type="text" name="tgltrf" placeholder="tanggal transfer" required />
							</div>
						</li>
						<li class="kolom8">
							<div class="inner-addon left-addon">
								<span class="fa fa-paperclip"></span>
								<input class="form-bordered" type="file" name="bukti" placeholder="lampirkan bukti transfer" required />
							</div>
						</li>
						<li>
							<textarea name="keterangan" class="form-bordered" placeholder="keterangan tambahan (opsional)" rows=5></textarea>
						</li>
						<li>
							<button type="submit" class="btn btn-green"><i class="fa fa-check"></i> Konfirmasi</button>
						</li>
					</ul>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function loadRekening(id){
		$("#dari").load("<?php echo site_url("assync/getrekeningdrop/".$_SESSION["usrid"]); ?>",function(){
			if(id != 0){
				$("#dari").val(id);
			}
		});
	}
	
	$(function(){
		loadRekening(0);
		$("#dari").change(function(){
			if($(this).val() == "tambah"){
				modal("modal");
			}
		});
		
		$(".closer").click(function(){
			$("#dari").val("");
		});
		
		$("#tambahrek").submit(function(e){
			e.preventDefault();
			var noreksub = $("#noreksubmit").html();
			$("#noreksubmit").html("<i class='fa fa-circle-notch fa-spin'></i> menyimpan");
			$("#noreksubmit").prop("disabled",true);
			$.post("<?php echo site_url("assync/tambahrekening"); ?>",$(this).serialize(),function(msg){
				var data = eval("("+msg+")");
				if(data.success == true){
					loadRekening(data.id);
					modal('hide');
					$("#noreksubmit").prop("disabled",false);
					$("#noreksubmit").html(noreksub);
					
				}else{
					alert("ERROR! gagal menyimpan.");
				}
			});
		});
	});
</script>


<div id="modal" class="modal">
	<div class="modal-content modal-kecil">
		<span class="close closer">TUTUP</span>
		<div class="subtitle">
			<b>Tambah Rekening</b>
			<div class="line"></div>
		</div>
		<div>
			<form id="tambahrek" method="post" action="<?php echo site_url("assync/tambahrekening"); ?>">
				<ul class="form-parent">
					<li>
						<div class="inner-addon left-addon">
							<span class="fa fa-university"></span>
							<select name="idbank" class="form-bordered" required >
								<option value="">Pilih Bank</option>
								<?php
									$db = $this->db->get("rekeningbank");
									foreach($db->result() as $re){
										echo "<option value='".$re->id."'>BANK ".$re->nama."</option>";	
									}
								?>
							</select>
						</div>
					</li>
					<li>
						<div class="inner-addon left-addon">
							<span class="fa fa-address-card"></span>
							<input type="text" name="atasnama" class="form-bordered" placeholder="atasnama rekening" reqquired />
						</div>
					</li>
					<li>
						<div class="inner-addon left-addon">
							<span class="fa fa-book"></span>
							<input type="text" name="norek" class="form-bordered" placeholder="nomor rekening" reqquired />
						</div>
					</li>
					<li>
						<div class="inner-addon left-addon">
							<span class="fa fa-building"></span>
							<input type="text" name="kcp" class="form-bordered" placeholder="kantor cabang" reqquired />
						</div>
					</li>
					<li>
						<button id="noreksubmit" type="submit" class="btn btn-green"><i class="fa fa-check"></i> Simpan</button>
					</li>
				</ul>
			</form>
		</div>
	</div>
</div>
<?php
}else{
	redirect("manage/pesanan");
}
?>