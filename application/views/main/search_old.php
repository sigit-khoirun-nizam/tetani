<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>	
<div class="seksen-shadow"></div>
<div class="seksen">
	<div class="wrapper" id="load">
		<div class="search-filter">
			<li class="active"><a href=""><i class="fa fa-cube"></i> Produk</a></li>
			<li><a href=""><i class="fa fa-shopping-bag"></i> Toko</a></li>
		</div>
		<div class="search-result">
		<?php
			foreach($produk->result() as $res){
				$kota = explode(" ",$this->func->getKab($res->idkab,"nama"),2);
				$kota = $kota[1];
				$judul = $this->func->potong($res->nama,35,'...');
				$label = $this->func->getLabelCOD($res->cod);
				
				echo "
					<div class='kotak'>
						<a href='".site_url('produk/'.$res->url)."' class='kotak-atas'>
							<div class='kotak-img' style='background-image:url(\"".$this->func->getFoto($res->id,'utama')."\")'>
								<!--<img src='".$this->func->getFoto($res->id,'utama')."' />-->
							</div>
							<div class='kotak-detail'>
								<div class='kotak-judul'>".$judul."</div>
								<p class='kotak-harga'> Rp. ".$this->func->formUang($res->harga)."</p>
								".$label."
							</div>
						</a>
						<div class='kotak-toko'>
							<span class='kotak-namatoko'>".strtoupper($this->func->potong($res->namatoko,25,'...'))."</span><br/>
							<span class='kotak-alamat'><i class='fa fa-map-marker'></i> ".$kota."</span>
						</div>
					</div>
				";
			} 
		?>
		</div>
		<div class="search-pagination">
			<?php echo $this->func->createPagination($rows,$page,$perpage); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		
	});
	
	function refreshTabel(page){
		window.location.href="<?php echo site_url("search")."?page="; ?>"+page+"<?php echo "&token=".$_GET["token"]; ?>";
	}
</script>