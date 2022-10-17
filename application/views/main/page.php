<?php
$this->db->where("slug", $slug);
$db = $this->db->get("page");

if ($db->num_rows() == 0) {
	redirect("404_error");
	exit;
}

foreach ($db->result() as $res) {
?>

	<!-- breadcrumb -->

	<div class="container-xxl position-relative p-0">

		<div class="container-xxl py-5 hero-header" style="background-color:#FBA504;">
			<div class="container my-5 py-5 px-lg-5">
				<div class="row g-5 py-5">
					<div class="col-12 text-center">
						<h1 class="text-white" data-aos-delay="" data-aos="fade-up"> <?= $res->nama ?></h1>
						<hr class="bg-white mx-auto mt-0" style="width: 90px;">
						<nav aria-label="breadcrumb text-white">
							<ol class="breadcrumb justify-content-center">
								<li class="breadcrumb-item" data-aos-delay="300" data-aos="fade-up"><a class="text-white" href="<?= base_url(); ?>">Home</a></li>
								<li class="breadcrumb-item" data-aos-delay="600" data-aos="fade-up"><a class="text-white" href="#"> <?= $res->nama ?></a></li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>




	<!-- Page Content -->
	<div class="container" data-aos-delay="600" data-aos="fade-up">
		<ol><?= $res->konten ?></ol>
		<div class="m-b-80"></div>
	</div>
<?php
}
?>