<div style="">
	<a class="float-right btn btn-primary" href="<?=site_url("ngadimin/kategoriform")?>"><i class="fas fa-plus-circle"></i> Tambah Kategori</a>
	<h4 class="page-title">Daftar Kategori</h4>
</div>
<div class="m-lr-0">
	<div class="card">
		<div class="card-body">
			<table class="table mt-3">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Nama</th>
					<th scope="col">Jumlah Produk</th>
					<th scope="col">Aksi</th>
				</tr>
		<?php
			$page = (isset($_GET["page"]) AND $_GET["page"] != "") ? $_GET["page"] : 1;
			$orderby = (isset($data["orderby"]) AND $data["orderby"] != "") ? $data["orderby"] : "id";
			$perpage = 10;

			$rows = $this->db->get("kategori");
			$rows = $rows->num_rows();

			$this->db->from('kategori');
			$this->db->order_by($orderby,"desc");
			$this->db->limit($perpage,($page-1)*$perpage);
			$pro = $this->db->get();
			
			if($rows > 0){
				$no = 1;
			foreach($pro->result() as $r){
				$this->db->select("id");
				$this->db->where("idcat",$r->id);
				$produk = $this->db->get("produk");
		?>
			<tr>
				<td class="text-center"><img style="max-height:70px;" src="<?=base_url("kategori/".$r->icon)?>" /></td>
				<td><?=$r->nama?></td>
				<td><b><?=$produk->num_rows()?></b></td>
				<td>
					<a href="<?php echo site_url("ngadimin/kategoriform/".$r->id); ?>" class="btn btn-primary"><i class="fas fa-pencil-alt"></i> Edit</a>
					<a href="javascript:hapusPromo(<?php echo $r->id; ?>)" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</a>
				</td>
			</tr>
		<?php	
				$no++;
			}
			}else{
				echo "<tr><td colspan=3>Belum ada kategori</td></tr>";
			}
		?>
		</table>

		<?=$this->func->createPagination($rows,$page,$perpage);?>

		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		
	});
	
	function hapusPromo(pro){
		swal.fire({
			title: "Anda yakin menghapus?",
			text: "kategori yang sudah dihapus tidak dapat dikembalikan",
			type: "error",
  			showCancelButton: true,
  			cancelButtonColor: '#d33',
			cancelButtonText: "Batal",
			confirmButtonText: "Tetap Hapus"
		}).then((result)=>{
			if(result.value){
				$.post("<?php echo site_url('ngadimin/hapuskategori'); ?>",{"pro":pro,[$("#names").val()]:$("#tokens").val()},function(msg){
					var data = eval("("+msg+")");
					updateToken(data.token);
					if(data.success == true){
						swal.fire("Berhasil!","Berhasil menghapus data","success").then((data) =>{
							location.reload();
						});
					}else{
						swal.fire("Gagal!","Gagal menghapus data, terjadi kesalahan sistem","error");
					}
				});
			}
		});
	}
	
	function refreshTabel(page){
		window.location.href = "<?php echo site_url('ngadimin/kategori/?page='); ?>"+page;
	}
</script>