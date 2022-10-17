<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<form id="form" class="p-t-50 p-b-50 p-lr-30">
	<div class="m-b-12">
		Masukkan alamat email Anda untuk mengatur ulang password.
		<p id="error" class="text-danger" style="display:none;"><small>alamat email tidak ditemukan, mohon cek kembali.</small></p>
		<p id="sukses" class="text-success" style="display:none;"><small>berhasil menyetel ulang password, silahkan cek email anda untuk langkah selanjutnya.</small></p>
	</div>
	<div class="m-b-12">
		<input class="form-control" type="email" name="email" placeholder="Email / No Handphone">
	</div>
	<div class="row m-t-10">
		<div class="col-md-12">
			<div id="prosesmail" style="display:none;"><h5 class="cl1"><i class="fa fa-circle-o-notch fa-spin"></i> Memproses...</h5></div>
			<button id="submitmail" type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> KIRIM KONFIRMASI</button>
		</div>
	</div>
</form>

<script type="text/javascript">
	$(function(){
		$("#form").on("submit",function(e){
			e.preventDefault();

			$(".form").prop("readonly",true);
			$("#submitmail").hide();
			$("#prosesmail").show();
			$.post("<?php echo site_url("home/signin/pwreset"); ?>",$(this).serialize(),function(msg){
				var data = eval('('+msg+')');
				$("#submitmail").show();
				$("#prosesmail").hide();
				if(data.success == true){
					$(".form").val("");
					$(".form").prop("readonly",false);
					swal("Berhasil!","Berhasil mengirimkan password baru, silahkan cek inbox email Anda.","success").then((value) =>{
						window.location.href = "<?php echo site_url("home/signin"); ?>";
					});
				}else{
					swal("Gagal!","Alamat email tidak ditemukan, pastikan alamat email sudah benar.","error");
				}
			});
		});
	});
</script>
