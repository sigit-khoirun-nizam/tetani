<a href="javascript:tambahSB()" class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> Tambah Testimoni</a>
<h4 class="page-title">Testimoni Pembeli</h4>

<div class="m-b-60">
	<div class="card">
		<div class="card-header align-items-center">
			<div class="card-title">
			</div>
		</div>
		<div class="card-body" id="load">
			<i class="fas fa-spin fa-spinner"></i> Loading data...
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		loadTesti(1);
		
		$("#sbform").on("submit",function(e){
			e.preventDefault();
			swal.fire({
				text: "pastikan lagi data yang anda masukkan sudah sesuai",
				title: "Validasi data",
				type: "warning",
				showCancelButton: true,
				cancelButtonText: "Cek Lagi"
			}).then((vals)=>{
				if(vals.value){
					var datar = $("#sbform").serialize();
					datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
					$.post("<?=site_url("api/tambahtestimoni")?>",datar,function(msg){
						var data = eval("("+msg+")");
						updateToken(data.token);
						if(data.success == true){
							loadSB(1);
							$("#modal").modal("hide");
							swal.fire("Berhasil","testimoni baru sudah tersimpan","success");
						}else{
							swal.fire("Gagal!","gagal menyimpan data, coba ulangi beberapa saat lagi","error");
						}
					});
				}
			});
		});
	});

	function loadTesti(page){
		$("#load").html('<i class="fas fa-spin fa-spinner"></i> Loading data...');
		$.post("<?=site_url("api/testimoni?load=true&page=")?>"+page,{"cari":$("#cari").val(),[$("#names").val()]:$("#tokens").val()},function(msg){
			var data = eval("("+msg+")");
			updateToken(data.token);
			$("#load").html(data.result);
		});
	}
	function edit(id){
		$.post("<?=site_url('api/testimoni')?>",{"formid":id},function(ev){
			var data = eval("("+ev+")");
			$("#id").val(id);
			
			$("#usrid option").each(function(){
				if($(this).val() == data.usrid){
					$(this).prop("selected",true);
				}else{
					$(this).prop("selected",false);
				}
			});
			$("#produk option").each(function(){
				if($(this).val() == data.idproduk){
					$(this).prop("selected",true);
				}else{
					$(this).prop("selected",false);
				}
			});
			
			$("#modal").modal();
		});
	}
	function tambahSB(){
		$('#sbform')[0].reset();
		
		$("#modal").modal();
	}
	function hapus(id){
		swal.fire({
			text: "data yang sudah dihapus tidak dapat dikembalikan lagi",
			title: "Yakin menghapus data ini?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: "#ff646d",
			cancelButtonText: "Batal"
		}).then((vals)=>{
			if(vals.value){
				$.post("<?=site_url("api/hapustestimoni")?>",{"id":id},function(msg){
					var data = eval("("+msg+")");
					if(data.success == true){
						loadSB(1);
						swal.fire("Berhasil","data sudah dihapus","success");
					}else{
						swal.fire("Gagal!","gagal menghapus data, coba ulangi beberapa saat lagi","error");
					}
				});
			}
		});
	}
</script>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLagu" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Pengaturan testimoni</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="sbform">
					<input type="hidden" name="id" id="id" value="0" />
					<div class="form-group">
						<label>Pengguna</label>
						<select id="usrid" name="usrid" class="form-control" required >
							<?php
								$this->db->order_by("nama","ASC");
								$dbs = $this->db->get("profil");
								foreach($dbs->result() as $rs){
									echo "<option value='".$rs->usrid."'>".$rs->nama."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Produk</label>
						<select id="produk" name="idproduk" class="form-control" required >
							<?php
								$this->db->where("status",1);
								$this->db->where("preorder",0);
								$this->db->order_by("nama","ASC");
								$dbs = $this->db->get("produk");
								foreach($dbs->result() as $rs){
									echo "<option value='".$rs->id."'>".$rs->nama."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group m-tb-10">
						<button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Simpan</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal" ><i class="fas fa-times"></i> Batal</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>