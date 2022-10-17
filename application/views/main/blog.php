<!-- breadcrumb -->

<div class="container-xxl position-relative p-0">

	<div class="container-xxl py-5  hero-header" style="background-color:#FBA504;">
		<div class="container my-5 py-5 px-lg-5">
			<div class="row g-5 py-5">
				<div class="col-12 text-center">
					<h1 class="text-white" data-aos-delay="" data-aos="fade-up">Blog</h1>
					<hr class="bg-white mx-auto mt-0" style="width: 90px;">
					<nav aria-label="breadcrumb text-white">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item" data-aos-delay="300" data-aos="fade-up"><a class="text-white" href="<?= base_url(); ?>">Home</a></li>
							<li class="breadcrumb-item" data-aos-delay="600" data-aos="fade-up"><a class="text-white" href="<?=base_url();?>Blog">Blog</a></li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>



<!-- Shoping Cart -->
<style rel="stylesheet">
	@media only screen and (min-width:721px) {
		.mobilefix {
			margin-left: -36px;
		}
	}
</style>
<div class="container p-t-10 p-b-50" style="background: #f8f9fa1c;">
	<div class="p-t-40 p-b-40 text-center">
		<h2 class="text-primary font-bold" data-aos-delay="" data-aos="fade-up"><b>BLOG TERBARU</b></h2>
	</div>
	<div class="row m-t-20 m-b-30" style="justify-content:center;">
		<?php
		$page = (isset($_GET["page"]) and $_GET["page"] != "") ? $_GET["page"] : 1;
		$orderby = (isset($_GET["orderby"]) and $_GET["orderby"] != "") ? $_GET["orderby"] : "id DESC";
		$perpage = 12;

		$dbs = $this->db->get("blog");

		$this->db->limit($perpage, ($page - 1) * $perpage);
		$this->db->order_by($orderby);
		$db = $this->db->get("blog");

		if ($db->num_rows() > 0) {
			foreach ($db->result() as $res) {
		?>
				<div class="col-6 col-md-3 blog-wrap">
					<div class="blog-grid" onclick="window.location.href='<?= site_url('blog/' . $res->url) ?>'">
						<div class="img" style="background-image: url('<?= base_url("cdn/uploads/" . $res->img) ?>')"></div>
						<div class="m-t-10 titel">
							<?= $this->func->potong($res->judul, 40, "...") ?>
						</div>
						<div class="m-t-10 konten">
							<?= $this->func->potong(strip_tags($res->konten), 90, "...") ?>
						</div>
					</div>
				</div>
		<?php
			}
		} else {
			echo "
						<div class='text-danger fw-bold text-center p-tb-20'>
							BELUM ADA POSTINGAN
						</div>
					";
		}
		?>
	</div>
	<div class="m-b-60">
		<!-- Pagination -->
		<div class="pagination flex-m flex-w p-t-26">
			<?= $this->func->createPagination($dbs->num_rows(), $page, $perpage) ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	function refreshTabel(page) {
		window.location.href = "<?= site_url("blog") ?>?page=" + page;
	}
</script>