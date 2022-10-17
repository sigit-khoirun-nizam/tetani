<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$id = (isset($_SESSION['id'])) ? $_SESSION['id'] : 0;
?>
<?php if(isset($belumverif) AND $belumverif == true){ ?>
	<div class="wrapper" id="load">
		<div id="main" class="stext-102 txt-center p-tb-60 m-b-80 m-t-40" style="font-size:16px;">
			Maaf, akun anda belum aktif, silahkan melakukan verifikasi melalui link yang kami kirim ke alamat email dan nomor whatsapp anda.<br/>
			atau anda belum menerima email verifikasi?<br/><br/>
			<a href="javascript:void(0)" class="stext-101 cl0 size-101 bg1 hov-btn1 p-lr-15 p-tb-10" onclick="kirimUlang()"><i class="fa fa-refresh"></i> KIRIM ULANG</a>
			<br/><br/>
			untuk login <a href="<?php echo site_url("home/signin"); ?>">klik disini</a>
		</div>
		<div id="sukses" class="stext-102 txt-center p-tb-60 m-b-80 m-t-40" style="font-size:16px;display:none;">
			Silahkan cek kembali email anda, kami sudah mengirimkan ulang link verifikasi ke alamat email dan nomor whatsapp anda. Apabila belum masih
			belum menerima email dari kami, silahkan cek folder <b>spam</b>.<br/>atau<br/>
			<a href="javascript:void(0)" class="stext-101 cl0 size-101 bg1 hov-btn1 p-lr-15 p-tb-10" onclick="kirimUlang()"><i class="fa fa-refresh"></i> KIRIM ULANG</a>
			<br/><br/>
			untuk login <a href="<?php echo site_url("home/signin"); ?>">klik disini</a>
		</div>
		<div id="gagal" class="stext-102 txt-center p-tb-60 m-b-80 m-t-40" style="font-size:16px;display:none;">
			Maaf, kami sedang mengalami kendala pada server kami. Silahkan ulangi beberapa saat lagi.<br/><br/>
			<a href="javascript:void(0)" class="stext-101 cl0 size-101 bg1 hov-btn1 p-lr-15 p-tb-10" onclick="kirimUlang()"><i class="fa fa-refresh"></i> KIRIM ULANG</a>
			<br/><br/>
			untuk login <a href="<?php echo site_url("home/signin"); ?>">klik disini</a>
		</div>
	</div>
<?php
	}else{
		if($selesai == false){
?>
	<div class="stext-102 txt-center p-tb-60 m-b-80 m-t-40" style="font-size:16px;">
		<form method="POST">
			<input type="hidden" name="verify" value="<?=$_GET["verify"]?>" />
			<input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
			Untuk memverifikasi akun anda, silahkan klik tombol dibawah.<br/>
			<button type="submit" class="btn btn-success">VERIFIKASI</button>
		</form>
	</div>
<?php 	}else{ ?>
	<div class="stext-102 txt-center p-tb-60 m-b-80 m-t-40" style="font-size:16px;">
		Selamat! Akun anda telah aktif.<br/><br/>
		untuk login:<br/>
		<a href="<?php echo site_url("home/signin"); ?>" class="btn btn-success">klik disini</a>
	</div>
<?php
		}
	}
?>

<script type="text/javascript">
	function kirimUlang(){
		$(".fa-refresh").addClass("fa-spin");
		$.post("<?php echo site_url("home/signup/kirimulang"); ?>",{"id":"<?php echo $this->func->encode($id); ?>",[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			$(".fa-refresh").removeClass("fa-spin");
			updateToken(data.token);
			if(data.success == true){
				$("#main").hide();
				swal("Berhasil!","Berhasil mengirimkan link verifikasi, silahkan cek inbox email dan nomor whatsapp Anda, cek folder spam apabila belum ada email masuk di inbox.","success").then((value) =>{
					window.location.href = "<?php echo site_url("home/signout"); ?>";
				});
			//	$("#sukses").show();
			}else{
				$("#main").hide();
				swal("Gagal!","Gagal mengirimkan link verifikasi ke alamat email Anda, pastikan alamat email benar	.","error");
			}
		});
	}
</script>
