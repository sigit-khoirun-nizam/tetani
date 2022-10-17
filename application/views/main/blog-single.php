<?php
	$this->db->where("url",$url);
	$db = $this->db->get("blog");
	
	foreach($db->result() as $res){
?>
	<!-- breadcrumb -->
	<div class="container">
		<div class="hidesmall m-t-40"></div>
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="<?php echo site_url(); ?>" class="text-primary">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>
			<a href="<?php echo site_url("blog"); ?>" class="text-primary">
				Blog
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				<?=$this->func->potong($res->judul,60,"...")?>
			</span>
		</div>
	</div>


	<!-- Shoping Cart -->
	<style rel="stylesheet">
		@media only screen and (min-width:721px){
			.mobilefix{
				margin-left: -36px;
			}
		}
	</style>
	<div class="container p-t-10 p-b-50">
		<div class="m-t-30 blog-header m-b-20" style="background-image: url('<?=base_url("cdn/uploads/".$res->img)?>');"></div>
		<div class="m-b-10">
			<h3><b><?=$res->judul?></b></h3>
		</div>
		<div class="blog-info m-b-40">
			Diposting oleh <b>admin</b> pada <?=$this->func->ubahTgl("d M Y",$res->tgl)?>
		</div>
		<div class="blog-text m-b-40">
			<?=$this->security->xss_clean($res->konten)?>
		</div>
	</div>
<?php
	}
?>
	