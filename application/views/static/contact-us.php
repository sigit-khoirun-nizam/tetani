

	<!-- Title page -->
	<section class="top-banner banner-contact txt-center p-lr-15 p-tb-92">
		<h2 class="banner-title ltext-110 cl0 txt-center">
			Hubungi Kami
		</h2>
	</section>


	<!-- Content page -->
	<section class="p-t-75 p-b-120">
		<div class="container">
			<div class="flex-w flex-tr">
				<div class="size-210 bor10 p-lr-70 p-t-55 p-b-70 p-lr-15-lg m-b-15 w-full-md">
					<form id="kontak">
						<h4 class="ltext-108 cl2 p-b-30">
							Kirim Pesan
						</h4>

						<div class="bor8 m-b-12 how-pos4-parent">
							<input class="stext-111 cl2 plh3 size-116 p-l-20 p-r-30" type="email" name="email" placeholder="Email" required>
						</div>
						<div class="bor8 m-b-12 how-pos4-parent">
							<input class="stext-111 cl2 plh3 size-116 p-l-20 p-r-30" type="text" name="nama" placeholder="Nama" required>
						</div>

						<div class="bor8">
							<textarea class="stext-111 cl2 plh3 size-120 p-l-20 p-r-30 p-t-20" name="pesan" placeholder="Pesan" required></textarea>
						</div>
            <div class="m-b-30 cl1">
              <small>
                <i>
                  <b>*</b> apabila pesan terkait kendala pesanan, wajib menyertakan <b>ID Pesanan</b>.
                </i>
              </small>
            </div>

						<button id="submit" type="submit" class="flex-c-m stext-101 cl0 size-101 bg1 hov-btn1 p-lr-15 trans-04">
							Kirim
						</button>
            <h5 style="display:none;" id="loader"><i class="cl1"><i class="fa fa-spin fa-circle-o-notch"></i> mengirim pesan</i></h5>
					</form>
				</div>

				<div class="size-210 flex-col-m p-lr-70 p-t-55 p-b-70 p-lr-15-lg m-b-15 w-full-md" style="background: #f8f8f9">
					<h4 class="ltext-108 cl2 p-b-30">
						Hubungi Kami
					</h4>
          <!--
					<div class="flex-w w-full p-b-30">
						<div class="size-212">
							<span class="mtext-105 cl2">
								Telepon
							</span>

							<p class="stext-115 cl1 size-213">
								0271 676 7878
							</p>
						</div>
					</div>
          -->

					<div class="flex-w w-full p-b-50">

						<div class="size-212">
							<span class="mtext-105 cl2">
								Customer Support
							</span>

							<p class="stext-115 cl1 size-213">
								<a class="cl1" href="mailto:cs@allbatik.id">cs@allbatik.id</a>
							</p>
						</div>
					</div>

					<div class="flex-w w-full">
						<h4 class="mtext-108 flex-c-m cl2 m-r-15">Ikuti kami: </h4>
						<a href="https://fb.me/allbatikid" class="icon-circle txt-center size-211 m-r-10" target="_blank" title="Facebook">
							<i class="fa fa-facebook"></i>
						</a>
						<a href="https://instagram.com/allbatik" class="icon-circle txt-center size-211 m-r-10" target="_blank" title="Instagram">
							<i class="fa fa-instagram"></i>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>

  <script type="text/javascript">
    $(function(){
      $("#kontak").on("submit",function(e){
        e.preventDefault();
        $("#submit").hide();
        $("#loader").show();
        $.post("<?php echo site_url("assync/kirimpesankontak"); ?>",$(this).serialize(),function(){
          $("#submit").show();
          $("#loader").hide();
          swal("Terima Kasih!","Pesan Anda telah kami terima, tunggu beberapa saat sampai admin kami merespon pesan Anda.","success").then((value)=>{
            location.reload();
          });
        });
      });
    });
  </script>
