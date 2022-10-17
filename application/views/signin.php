<!-- breadcrumb -->
<div class="container-xxl position-relative p-0">

	<div class="container-xxl py-5 bg-primary hero-header">
		<div class="container my-5 py-5 px-lg-5">
			<div class="row g-5 py-5">
				<div class="col-12 text-center">
					<h1 class="text-white" data-aos-delay="" data-aos="fade-up"> Sign In</h1>
					<hr class="bg-white mx-auto mt-0" style="width: 90px;">
					<nav aria-label="breadcrumb text-white">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item" data-aos-delay="300" data-aos="fade-up"><a class="text-white" href="<?= base_url(); ?>">Home</a></li>
							<li class="breadcrumb-item" data-aos-delay="600" data-aos="fade-up"><a class="text-white" href="#"> Sign In</a></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>



<!-- Login -->
<div class="p-t-30">
	<div class="container p-b-20">
		<div class="row">
			<div class="col-md-6 m-r-auto m-l-auto m-b-30">
				<div class="p-lr-40 p-t-30 m-lr-0-xl p-lr-15-sm">
					<h4 class="font-black text-primary fs-30 text-center" data-aos-delay="" data-aos="fade-up">
						Login
					</h4>
				</div>
			</div>
		</div>

		<div class="row p-lr-20">
			<div class="col-md-6 m-l-auto m-r-auto m-b-80 bg-sign">
				<div class="p-l-20 p-r-20 m-lr-0-xl p-lr-15-sm" id="load">
					<?php
					$set = $this->func->getSetting("login_otp");
					if ($set == 0) {
					?>
						<form id="signin" class="p-t-50 p-b-50 p-lr-30">
							<div class="m-b-12" data-aos-delay="" data-aos="zoom-in">
								<input class="form-control" type="text" name="email" placeholder="Email" required>
							</div>
							<div class="m-t-15 m-b-12" data-aos-delay="300" data-aos="zoom-in">
								<input class="form-control" type="password" name="pass" placeholder="Password" required>
							</div>
							<div class="row m-b-30">
								<div class="col-6" data-aos-delay="600" data-aos="zoom-in">
									<div class="checkbox checkbox-danger">
										<input id="checkbox6" class="dis-inline" type="checkbox" name="remember">
										<label for="checkbox6" class="dis-inline cursor-pointer">
											Ingat Saya
										</label>
									</div>
								</div>
								<div class="col-6 text-right d-flex justify-content-end" data-aos-delay="800" data-aos="zoom-in">
									<a href="javascript:void(0)" id="reset" class="text-danger"><b>Lupa Password?</b></a>
								</div>
							</div>
							<div class="row m-t-20" data-aos-delay="800" data-aos="fade-in">
								<div class="col-md-12 ">
									<button type="submit" id="submit" class="text-center btn btn-primary btn-block btn-lg d-flex justify-content-center">MASUK</button>
									<p class="text-center m-t-20 m-b-10">Belum punya akun?</p>
									<a href="<?php echo site_url("home/signup"); ?>" class=" d-flex justify-content-center btn btn-warning btn-block btn-lg"><i class="fas fa-chevron-circle-right"></i> MENDAFTAR DISINI </a>
								</div>
							</div>
						</form>
					<?php
					} else {
					?>
						<form id="signin_otp" class="p-t-50 p-b-50 p-lr-30">
							<div class="m-b-12 t-center">
								masukkan nomor handphone atau alamat email anda untuk mengirimkan kode otp
							</div>
							<div class="m-b-18">
								<input class="form-control p-tb-28 p-lr-24 fs-20 font-medium text-center" type="text" name="email" placeholder="No Handphone / Email" required>
							</div>
							<div class="row m-t-20">
								<div class="col-md-12">
									<button type="submit" id="submit" class="btn btn-primary btn-block btn-lg">MASUK</button>
									<p class="text-center m-t-20 m-b-10">Belum punya akun?</p>
									<a href="<?php echo site_url("home/signup"); ?>" class="btn btn-warning btn-block btn-lg"><i class="fas fa-chevron-circle-right"></i> MENDAFTAR DISINI </a>
								</div>
							</div>
						</form>
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	</form>


	<script type="text/javascript">
		$(function() {
			$("#signin").on("submit", function(e) {
				e.preventDefault();

				var submit = $("#submit").html();
				$(".form").prop("readonly", true);
				$("#submit").html("<i class='fas fa-spin fa-compact-disc'></i> tunggu sebentar...");
				var datar = $(this).serialize();
				datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
				$.post("<?php echo site_url("home/signin"); ?>", datar, function(msg) {
					var data = eval('(' + msg + ')');
					updateToken(data.token);
					if (data.success == true) {
						window.location.href = data.redirect;
					} else {
						$("#submit").html(submit);
						swal.fire("Warning!", "alamat email atau password salah, silahkan cek kembali", "error");
					}
				});
			});

			$("#signin_otp").on("submit", function(e) {
				e.preventDefault();

				var submit = $("#submit").html();
				$(".form").prop("readonly", true);
				$("#submit").html("<i class='fas fa-spin fa-compact-disc'></i> tunggu sebentar...");
				var datar = $(this).serialize();
				datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
				$.post("<?php echo site_url("home/signin_otp"); ?>", datar, function(msg) {
					var data = eval('(' + msg + ')');
					updateToken(data.token);
					if (data.success == true) {
						window.location.href = "<?= site_url("home/signin_otp/challenge") ?>";
					} else {
						$("#submit").html(submit);
						swal.fire("Warning!", "alamat email atau no handphone tidak ditemukan, silahkan cek kembali", "error");
					}
				});
			});

			$("#reset").click(function() {
				$("#load").html("<div class='t-center m-tb-40'><i class='fas fa-spin fa-compact-disc text-info'></i> mohon tunggu sebentar...</div>");
				$("#load").load("<?php echo site_url("home/signin/pwreset"); ?>");
			});
		});
	</script>