<div class="container p-t-50">
	<div class="row m-lr-0 m-b-100">
		<div class="col-md-12 m-lr-auto p-lr-10 m-b-40">
			<h5 class="mtext-109">daftar produk</h5>
		</div>
		<div class="container">
			<div id="daftarProduk" class="row">
				<i class="fa fa-spin fa-spinner"></i> Tunggu sebentar...
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function refreshTabel(page){
		$("#daftarProduk").load("<?php echo site_url("assync/daftarProduk")."?page="; ?>"+page);
	}
	refreshTabel(1);

	function hapusProduk(id){
		swal({
			title: "Anda yakin?",
			text: "produk yang telah dihapus tidak dapat dikembalikan lagi",
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) {
				$.post("<?php echo site_url("assync/hapusProduk"); ?>",{"id":id},function(msg){
					var data = eval("("+msg+")");
					if(data.success == true){
						window.location.href = "<?php echo site_url("manage/produk"); ?>";
					}else{
						alert(data.msg);
					}
				});
			}
		});
	}
	function ubahProduk(id){
		window.location.href = "<?php echo site_url("manage/ubahproduk"); ?>/?tokens="+id;
	}
</script>
