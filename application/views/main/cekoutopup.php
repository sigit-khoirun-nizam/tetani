<?php $set = $this->func->getSetting("semua"); ?>
	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="<?php echo site_url(); ?>" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				Invoice
			</span>
		</div>
	</div>


	<!-- Shoping Cart -->
	<style rel="stylesheet">
		@media only screen and (min-width:721px){
			.mobilefix{
				margin-left: -36px;
			}
		}
	</style>
	<form class="p-t-0 p-b-85">
		<div class="container p-t-10 p-b-50" style="background: #f8f9fa1c;">
			<div class="row">
				<div class="col-md-7 m-l-auto m-r-auto">
					<div class="p-lr-40 p-t-30 p-b-40 m-l-0-xl m-r-0-xl p-r-15-sm p-l-15-sm">
						<div class="row">
							<div class="col-2 mobilefix">
								<img src="<?php echo base_url("assets/images/komponen/checked.png"); ?>" width="50">
							</div>
							<div class="col-10 mobilefix">
								<p style="font-size: 16px;color:#383838">Order ID <?php echo $data->trxid; ?></p>
								<h4 class="mtext-105">Terima Kasih <?php echo $this->func->getProfil($data->usrid,"nama","usrid"); ?></h4>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-7 m-l-auto m-r-auto m-b-30">
					<div class="bg0 bor10 p-l-40 p-r-40 p-t-30 p-b-40 m-r-0-xl m-l-0-xl p-r-15-sm p-l-15-sm">
						<h4 class="mtext-109 cl2 p-b-20">
							Pembayaran
						</h4>

						<?php
							if($data->total > 0){
								$bayartotal = $data->total;
						?>
							<div class="p-b-13">
								<div class="row p-t-20">
									<div class="col-md-12 m-b-20">
										<!--<h5 class="text-black">Metode Pembayaran: <span class="cl1" style="font-size: 16px;">Virtual Account, E-Wallet, Mini Market, Dll</span> </h5>-->
										<h5 class="text-black">Mohon lakukan pembayaran sejumlah <span style="color: #c0392b; font-size: 20px;"><b>Rp <?php echo $this->func->formUang($bayartotal); ?></b></span></h5>
									</div>
								</div>
								<?php if($set->payment_ipaymu != 1){ ?>
								<div class="row p-t-20">
									<div class="col-md-12 m-b-20">
										<h5 class="text-black">Silahkan transfer pembayaran ke rekening berikut:</h5>
									</div>
									<div class="col-md-12">
										<p></p>
										<?php
											foreach($bank->result() as $bn){
													echo '
														<h5 class="cl2 m-t-10 m-b-10 p-t-10 p-l-10 p-b-10" style="border-left: 8px solid #C0A230;">
															<b class="text-danger">Bank '.$bn->nama.': </b><b class="text-success">'.$bn->norek.'</b><br/>
															<span style="font-size: 90%">a/n '.$bn->atasnama.'<br/>
															KCP '.$bn->kcp.'</span>
														</h5>
													';
											}
										?>
										<p class="m-b-5 m-t-20">
										<b>PENTING: </b>
										</p>
										<ul style="margin-left: 15px;">
											<li style="list-style-type: disc;">Mohon lakukan pembayaran dalam <b>1x24 jam</b></li>
											<li style="list-style-type: disc;">Sistem akan otomatis mendeteksi apabila pembayaran sudah masuk</li>
											<li style="list-style-type: disc;">Apabila sudah transfer dan status pembayaran belum berubah, mohon konfirmasi pembayaran manual di bawah</li>
											<li style="list-style-type: disc;">Pesanan akan dibatalkan secara otomatis jika Anda tidak melakukan pembayaran.</li>
										</ul>
									</div>
								</div>
								<?php } ?>
							</div>
							<hr class="m-t-30"/>
							<?php if($set->payment_ipaymu == 1){ ?>
							<a href="<?php echo site_url("assync/topupipaymu/".$data->id); ?>" class="btn btn-success btn-block btn-lg text-center bayarotomatis"><i class="fa fa-chevron-circle-right"></i> &nbsp;<b>BAYAR SEKARANG</b></a>
							<?php } ?>
							<a href="<?php echo site_url("manage"); ?>" class="btn btn-danger btn-block btn-lg text-center bayarotomatis"><i class="fa fa-times"></i> &nbsp;<b>BAYAR NANTI SAJA</b></a>
						<?php
							}else{
						?>
							<div class="p-b-13">
								<div class="row p-t-20">
									<div class="col-md-12">
										<h5 class="text-black">Metode Pembayaran: <span class="cl1" style="font-size: 16px;">Saldo <?=$this->func->getSetting("nama")?></span> </h5>
									</div>
								</div>
								<div class="row p-t-5">
									<div class="col-md-12">
										<p>Terima kasih, saldo <b class='cl1'><?=$this->func->getSetting("nama")?></b> sudah terpotong sebesar
											<span style="color: #c0392b; font-size: 20px;"><b>Rp <?php echo $this->func->formUang($data->saldo); ?></b></span>
											untuk pembayaran pesanan Anda.<br/>
											<!--Kami sudah menginformasikan kepada merchant untuk memproses pesanan Anda.-->
										</p>
									</div>
								</div>
							</div>
							<hr class="m-t-30"/>
							<a href="<?php echo site_url("manage/pesanan"); ?>" class="cl1 text-center w-full dis-block"><b>STATUS PESANAN</b> <i class="fa fa-chevron-circle-right"></i></a>
						<?php } ?>

					</div>
				</div>
			</div>
		</div>
	</form>

<script type="text/javascript">
	function bayarManual(){
		$(".metode-item").removeClass("active");
		$(".metode-item.manual").addClass("active");
		$(".bayarmanual").show();
		$(".bayarotomatis").hide();
	}
	function bayarOtomatis(){
		$(".metode-item").removeClass("active");
		$(".metode-item.otomatis").addClass("active");
		$(".bayarmanual").hide();
		$(".bayarotomatis").show();
	}
</script>
