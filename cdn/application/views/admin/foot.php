
				</div>
			</div>
		</div>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="modalUpdatePro" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<h6 class="modal-title"><i class="la la-frown-o"></i> Under Development</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-center">									
					<p>Currently the pro version of the <b>Ready Dashboard</b> Bootstrap is in progress development</p>
					<p>
					<b>We'll let you know when it's done</b></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</body>
<script src="<?=base_url()?>assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="<?=base_url()?>assets/js/core/popper.min.js"></script>
<script src="<?=base_url()?>assets/js/core/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/js/moment.min.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?=base_url()?>assets/js/plugin/chartist/chartist.min.js"></script>
<script src="<?=base_url()?>assets/js/plugin/chartist/plugin/chartist-plugin-tooltip.min.js"></script>
<script src="<?=base_url()?>assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="<?=base_url()?>assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="<?=base_url()?>assets/js/plugin/jquery-mapael/jquery.mapael.min.js"></script>
<script src="<?=base_url()?>assets/js/plugin/chart-circle/circles.min.js"></script>
<script src="<?=base_url("assets/js/sweetalert2.min.js")?>" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/ready.min.js"></script>
<script type="text/javascript">
	$(function(){
		$("#userpass").on("submit",function(e){
			e.preventDefault();
			if($("#usrpass").val() == $("#usrpass2").val()){
				swal.fire({
					text: "pastikan lagi data yang anda masukkan sudah sesuai",
					title: "Validasi data",
					type: "warning",
					showCancelButton: true,
					cancelButtonText: "Cek Lagi"
				}).then((vals)=>{
					if(vals.value){
						var datar = $(this).serialize();
						datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
						$.post("<?=site_url("api/tambahuser")?>",datar,function(msg){
							var data = eval("("+msg+")");
							updateToken(data.token);
							if(data.success == true){
								$("#modalgantipass").modal("hide");
								swal.fire("Berhasil","data user sudah disimpan","success");
							}else{
								swal.fire("Gagal!","gagal menyimpan data, coba ulangi beberapa saat lagi","error");
							}
						});
					}
				});
			}else{
				swal.fire("Cek Password","password yang Anda masukkan tidak sesuai, pastikan isi formulirnya dengan benar","error");
			}
		});
	});

	function logout(){
		swal.fire({
			text: "Anda yakin akan keluar?",
			title: "Logout",
			type: "warning",
			showCancelButton: true,
			cancelButtonText: "Batal"
		}).then((vals)=>{
			if(vals.value == true){
				window.location.href = "<?=site_url("ngadimin/logout")?>";
			}
		});
	}
	
	function updateToken(token){
		$("#tokens,.tokens").val(token);
	}
</script>
<input type="hidden" id="names" value="<?=$this->security->get_csrf_token_name()?>" />
<input type="hidden" id="tokens" value="<?=$this->security->get_csrf_hash();?>" />

<div class="modal fade" id="modalgantipass" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Ganti Password</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
					if($this->func->demo() == true){
						echo "Fitur tidak tersedia di mode demo aplikasi";
					}else{
						echo '
							<form id="userpass">
							<div class="form-group">
								<label>Password Baru</label>
								<input type="password" id="usrpass" name="gantipass" class="form-control" required />
							</div>
							<div class="form-group">
								<label>Ulangi Password</label>
								<input type="password" id="usrpass2" class="form-control" required />
							</div>
							<div class="form-group m-tb-10">
								<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
								<button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fas fa-times"></i> Batal</button>
							</div>
						</form>';
					}
				?>
			</div>
		</div>
	</div>
</div>
</html>