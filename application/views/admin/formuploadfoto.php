<!--<div class="uploadfoto">
	<form id="upload" method="POST" enctype="multipart/form-data" action="<?php echo site_url("assync/uploadFotoProduk"); ?>">
		<input type="hidden" name="jenis" value="1" />
		<input type="hidden" name="idproduk" value="0" />
		<label class="form-uploadfoto">
			<input type="file" name="fotoProduk" onchange="this.form.submit()"></input>
			<img src="<?php echo base_url("assets/img/komponen/add-product.png"); ?>"/>
		</label>
	</form>
</div>-->

<?php
	$this->db->where("idtoko",$_SESSION["idtoko"]);
	$this->db->where("idproduk",$idproduk);
	$this->db->order_by("jenis","DESC");
	$db = $this->db->get("upload");
	foreach($db->result() as $res){
		if($res->jenis == 1){
			$btn = "<button type='button' class='utama' disabled>foto utama</button>";
		}else{
			$btn = "<button type='button' class='jadiutama' onclick='jadikanUtama(".$res->id.")'>Utama</button>
				<button type='button' class='hapus' onclick='hapusFoto(".$res->id.")'>Hapus</button>";
		}
		echo "
			<div class='uploadfoto-item'>
				<img src='".base_url("assets/img/produk/".$res->nama)."' />
				".$btn."
			</div>
		";
	}
?>
<script type="text/javascript">
	$(function(){
		
	});
	
	<?php
		if($db->num_rows() >= 5){ echo "dontAdd()"; }
		else{ echo "Add()"; }
	?>
</script>