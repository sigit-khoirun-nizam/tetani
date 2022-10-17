
	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="<?php echo site_url(); ?>" class="text-primary">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>
			<a href="<?php echo site_url("manage/pesanan"); ?>" class="text-primary">
				Pesananku
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>
			<span class="text-dark">
				Lacak Paket Pengiriman
			</span>
		</div>
	</div>


    <div class="container p-t-50 p-b-50">
			<div class="row">
        <div class="col-12 m-lr-auto m-b-30 text-center">
          <h3 class="font-bold text-primary p-b-20">
            Order ID <span class="text-success">#<?php echo $orderid; ?></span>
          </h3>
        </div>
        <div class="col-md-5 m-lr-auto m-b-30">
          <h4 class="font-bold text-primary p-b-20">
            Informasi Paket
          </h4>
          <div class="section p-lr-24 p-tb-30">
            <div class="p-b-10 p-t-10">
              <p class="m-b-20">
                Kurir Pengiriman:<br/>
                <b class='badge badge-warning fs-18'><?php echo strtoupper(strtolower($this->func->getKurir($transaksi->kurir,"nama","rajaongkir")." - ".$transaksi->paket)); ?></b>
              </p>
              <p class="m-b-10">
                No Resi Pengiriman:<br/>
                <b class="fs-18 text-success font-medium"><?php echo strtoupper(strtolower($transaksi->resi)); ?></b>
              </p>
            </div>
            <div class="p-b-10 p-t-10">
              <p class="m-b-10">
                Waktu Pengiriman:<br/>
                <i class="p-r-4 text-success font-medium"><?php echo $this->func->ubahTgl("d M Y H:i",$transaksi->kirim); ?> WIB</i>
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-7 m-lr-auto m-b-30">
          <h4 class="font-bold text-primary p-b-20">
            Status Pengiriman
          </h4>
          <div class="section p-lr-24 p-tb-30">
            <div class="of-hidden" id="load">
              <h5><i class="fa fa-spin fa-compact-disc"></i> &nbsp;menghubungi ekspedisi, mohon tunggu sebentar...</h5>
            </div>
          </div>
        </div>
      </div>
    </div>

  <script type="text/javascript">
    $(function(){
      $("#load").load("<?php echo site_url("assync/lacakiriman?orderid=".$orderid); ?>");
    });
  </script>
