<div class="container-xxl hero-header" style="background-color:#FBA504;">
	<div class="container px-lg-5">
		<div class="row g-5 align-items-end">
			<div class="col-lg-6 text-center text-lg-start">
				<h1 class="text-white mb-4" data-aos-delay="" data-aos="fade-up"><?= $this->func->getSetting("nama") ?></h1>
				<p class="text-white pb-3" data-aos-delay="300" data-aos="fade-up">We are always commited to present the best masterpiece for life’s happiness</p>
				<a style="background-color:#198754;"href="" class="text-white btn py-sm-3 px-sm-5 rounded-pill me-3 " data-aos-delay="600" data-aos="fade-left">Read More</a>
				<a href="" class="btn btn-light py-sm-3 px-sm-5 rounded-pill" data-aos-delay="600" data-aos="fade-right">Contact Us</a>
			</div>
			<div class="col-lg-6 text-center text-lg-start">
				<img class="img-fluid" data-aos-delay="600" data-aos="zoom-in" src="<?= base_url(); ?>assets/images/hero.png" alt="">
			</div>
		</div>
	</div>
</div>
</div>
<!-- Navbar & Hero End -->
<!-- <div class="carousel slider">
	<?php
	$this->db->where("tgl<=", date("Y-m-d H:i:s"));
	$this->db->where("tgl_selesai>=", date("Y-m-d H:i:s"));
	$this->db->where("jenis", 1);
	$this->db->where("status", 1);
	$this->db->order_by("id", "DESC");
	$sld = $this->db->get("promo");
	if ($sld->num_rows() > 0) {
		foreach ($sld->result() as $s) {
	?>
			<div class="slider-item" style="cursor:pointer;" data-onclick="<?= $s->link ?>">
				<div class="wrap">
					<img src="<?= base_url('cdn/promo/' . $s->gambar) ?>" />
				</div>
			</div>
	<?php
		}
	}
	?>
</div> -->
<!-- Feature Start -->

<!-- Kategori -->
<!-- <div class="container-xxl py-5 " data-aos-delay="" data-aos="fade-up">
	<div class="container py-5 px-lg-5">
		<div class="cat">
			<?php
			$this->db->where("parent", 0);
			$db = $this->db->get("kategori");
			foreach ($db->result() as $r) {
			?>
				<div class="cat-item owl-carousel">
					<div class="cat-bg" style="background-position:center center;background-image:url('<?= base_url("cdn/kategori/" . $r->icon) ?>');background-size:cover;" onclick="window.location.href='<?= site_url('kategori/' . $r->url) ?>'">
					</div>
					<div class="cat-nama"><?= $r->nama ?></div>
				</div>
			<?php
			}
			?>
		</div>
	</div>
</div> -->

<!-- </div> -->
<div style="background-color:#198754;"class="container-xxl  playstore-section py-5 " data-aos-delay="" data-aos="fade-up">
	<div class="container py-5 px-lg-5">
		<div class="row">
			<div class="col-md-8" data-aos-delay="300" data-aos="fade-up">
				<h2 class="font-bold text-light ">Belanja kini lebih mudah</h2>
				<h5 class="text-light">Langsung dari handphone Anda, download aplikasinya sekarang!</h5>
			</div>
			<div class="col-md-4" data-aos-delay="500" data-aos="fade-right">
				<a href="https://play.google.com/store/apps/details?id=com.bikin.online" class="playstore">
					<img style="width:80%; height:auto;"src="<?= base_url("assets/images/playstore.png") ?>" />
				</a>
				<div class="m-t-10 showsmall"></div>
			</div>
		</div>
	</div>
</div>


<!-- Facts Start -->
<!-- <div class="container-xxl bg-primary fact py-5 wow fadeInUp" data-wow-delay="0.1s">
	<div class="container py-5 px-lg-5">
		<div class="row g-4">
			<div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.1s">
				<i class="fa fa-certificate fa-3x text-secondary mb-3"></i>
				<h1 class="text-white mb-2" data-toggle="counter-up">1234</h1>
				<p class="text-white mb-0">Years Experience</p>
			</div>
			<div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.3s">
				<i class="fa fa-users-cog fa-3x text-secondary mb-3"></i>
				<h1 class="text-white mb-2" data-toggle="counter-up">1234</h1>
				<p class="text-white mb-0">Team Members</p>
			</div>
			<div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.5s">
				<i class="fa fa-users fa-3x text-secondary mb-3"></i>
				<h1 class="text-white mb-2" data-toggle="counter-up">1234</h1>
				<p class="text-white mb-0">Satisfied Clients</p>
			</div>
			<div class="col-md-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.7s">
				<i class="fa fa-check fa-3x text-secondary mb-3"></i>
				<h1 class="text-white mb-2" data-toggle="counter-up">1234</h1>
				<p class="text-white mb-0">Compleate Projects</p>
			</div>
		</div>
	</div>
</div> -->
<!-- Facts End -->


<!-- Service Start -->
<!-- <div class="container-xxl py-5">
	<div class="container py-5 px-lg-5">
		<div class="wow fadeInUp" data-wow-delay="0.1s">
			<p class="section-title text-secondary justify-content-center"><span></span>Our Services<span></span></p>
			<h1 class="text-center mb-5">What Solutions We Provide</h1>
		</div>
		<div class="row g-4">
			<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
				<div class="service-item d-flex flex-column text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fa fa-search fa-2x"></i>
					</div>
					<h5 class="mb-3">SEO Optimization</h5>
					<p class="m-0">Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem sed diam stet diam sed stet lorem.</p>
					<a class="btn btn-square" href=""><i class="fa fa-arrow-right"></i></a>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
				<div class="service-item d-flex flex-column text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fa fa-laptop-code fa-2x"></i>
					</div>
					<h5 class="mb-3">Web Design</h5>
					<p class="m-0">Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem sed diam stet diam sed stet lorem.</p>
					<a class="btn btn-square" href=""><i class="fa fa-arrow-right"></i></a>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
				<div class="service-item d-flex flex-column text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fab fa-facebook-f fa-2x"></i>
					</div>
					<h5 class="mb-3">Social Media Marketing</h5>
					<p class="m-0">Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem sed diam stet diam sed stet lorem.</p>
					<a class="btn btn-square" href=""><i class="fa fa-arrow-right"></i></a>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
				<div class="service-item d-flex flex-column text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fa fa-mail-bulk fa-2x"></i>
					</div>
					<h5 class="mb-3">Email Marketing</h5>
					<p class="m-0">Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem sed diam stet diam sed stet lorem.</p>
					<a class="btn btn-square" href=""><i class="fa fa-arrow-right"></i></a>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
				<div class="service-item d-flex flex-column text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fa fa-thumbs-up fa-2x"></i>
					</div>
					<h5 class="mb-3">PPC Advertising</h5>
					<p class="m-0">Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem sed diam stet diam sed stet lorem.</p>
					<a class="btn btn-square" href=""><i class="fa fa-arrow-right"></i></a>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
				<div class="service-item d-flex flex-column text-center rounded">
					<div class="service-icon flex-shrink-0">
						<i class="fab fa-android fa-2x"></i>
					</div>
					<h5 class="mb-3">App Development</h5>
					<p class="m-0">Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem sed diam stet diam sed stet lorem.</p>
					<a class="btn btn-square" href=""><i class="fa fa-arrow-right"></i></a>
				</div>
			</div>
		</div>
	</div>
</div> -->
<!-- Service End -->


<!-- Newsletter Start -->
<!-- <div class="container-xxl bg-primary newsletter py-5 wow fadeInUp" data-wow-delay="0.1s">
	<div class="container py-5 px-lg-5">
		<div class="row justify-content-center">
			<div class="col-lg-7 text-center">
				<p class="section-title text-white justify-content-center"><span></span>Newsletter<span></span></p>
				<h1 class="text-center text-white mb-4">Stay Always In Touch</h1>
				<p class="text-white mb-4">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore. Clita erat ipsum et lorem et sit sed stet lorem sit clita duo justo</p>
				<div class="position-relative w-100 mt-3">
					<input class="form-control border-0 rounded-pill w-100 ps-4 pe-5" type="text" placeholder="Enter Your Email" style="height: 48px;">
					<button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-1 me-2"><i class="fa fa-paper-plane text-primary fs-4"></i></button>
				</div>
			</div>
		</div>
	</div>
</div> -->
<!-- Newsletter End -->


<!-- Projects Start -->
<!-- <div class="container-xxl py-5">
	<div class="container py-5 px-lg-5">
		<div class="wow fadeInUp" data-wow-delay="0.1s">
			<p class="section-title text-secondary justify-content-center"><span></span>Our Projects<span></span></p>
			<h1 class="text-center mb-5">Recently Completed Projects</h1>
		</div>
		<div class="row mt-n2 wow fadeInUp" data-wow-delay="0.3s">
			<div class="col-12 text-center">
				<ul class="list-inline mb-5" id="portfolio-flters">
					<li class="mx-2 active" data-filter="*">All</li>
					<li class="mx-2" data-filter=".first">Web Design</li>
					<li class="mx-2" data-filter=".second">Graphic Design</li>
				</ul>
			</div>
		</div>
		<div class="row g-4 portfolio-container">
			<div class="col-lg-4 col-md-6 portfolio-item first wow fadeInUp" data-wow-delay="0.1s">
				<div class="rounded overflow-hidden">
					<div class="position-relative overflow-hidden">
						<img class="img-fluid w-100" src="<?= base_url(); ?>assets/images/portfolio-1.jpg" alt="">
						<div class="portfolio-overlay">
							<a class="btn btn-square btn-outline-light mx-1" href="<?= base_url(); ?>assets/images/portfolio-1.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
							<a class="btn btn-square btn-outline-light mx-1" href=""><i class="fa fa-link"></i></a>
						</div>
					</div>
					<div class="bg-light p-4">
						<p class="text-primary fw-medium mb-2">UI / UX Design</p>
						<h5 class="lh-base mb-0">Digital Agency Website Design And Development</a>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 portfolio-item second wow fadeInUp" data-wow-delay="0.3s">
				<div class="rounded overflow-hidden">
					<div class="position-relative overflow-hidden">
						<img class="img-fluid w-100" src="<?= base_url(); ?>assets/images/portfolio-2.jpg" alt="">
						<div class="portfolio-overlay">
							<a class="btn btn-square btn-outline-light mx-1" href="<?= base_url(); ?>assets/images/portfolio-2.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
							<a class="btn btn-square btn-outline-light mx-1" href=""><i class="fa fa-link"></i></a>
						</div>
					</div>
					<div class="bg-light p-4">
						<p class="text-primary fw-medium mb-2">UI / UX Design</p>
						<h5 class="lh-base mb-0">Digital Agency Website Design And Development</a>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 portfolio-item first wow fadeInUp" data-wow-delay="0.5s">
				<div class="rounded overflow-hidden">
					<div class="position-relative overflow-hidden">
						<img class="img-fluid w-100" src="<?= base_url(); ?>assets/images/portfolio-3.jpg" alt="">
						<div class="portfolio-overlay">
							<a class="btn btn-square btn-outline-light mx-1" href="<?= base_url(); ?>assets/images/portfolio-3.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
							<a class="btn btn-square btn-outline-light mx-1" href=""><i class="fa fa-link"></i></a>
						</div>
					</div>
					<div class="bg-light p-4">
						<p class="text-primary fw-medium mb-2">UI / UX Design</p>
						<h5 class="lh-base mb-0">Digital Agency Website Design And Development</a>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 portfolio-item second wow fadeInUp" data-wow-delay="0.1s">
				<div class="rounded overflow-hidden">
					<div class="position-relative overflow-hidden">
						<img class="img-fluid w-100" src="<?= base_url(); ?>assets/images/portfolio-4.jpg" alt="">
						<div class="portfolio-overlay">
							<a class="btn btn-square btn-outline-light mx-1" href="<?= base_url(); ?>assets/images/portfolio-4.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
							<a class="btn btn-square btn-outline-light mx-1" href=""><i class="fa fa-link"></i></a>
						</div>
					</div>
					<div class="bg-light p-4">
						<p class="text-primary fw-medium mb-2">UI / UX Design</p>
						<h5 class="lh-base mb-0">Digital Agency Website Design And Development</a>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 portfolio-item first wow fadeInUp" data-wow-delay="0.3s">
				<div class="rounded overflow-hidden">
					<div class="position-relative overflow-hidden">
						<img class="img-fluid w-100" src="<?= base_url(); ?>assets/images/portfolio-5.jpg" alt="">
						<div class="portfolio-overlay">
							<a class="btn btn-square btn-outline-light mx-1" href="<?= base_url(); ?>assets/images/portfolio-5.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
							<a class="btn btn-square btn-outline-light mx-1" href=""><i class="fa fa-link"></i></a>
						</div>
					</div>
					<div class="bg-light p-4">
						<p class="text-primary fw-medium mb-2">UI / UX Design</p>
						<h5 class="lh-base mb-0">Digital Agency Website Design And Development</a>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 portfolio-item second wow fadeInUp" data-wow-delay="0.5s">
				<div class="rounded overflow-hidden">
					<div class="position-relative overflow-hidden">
						<img class="img-fluid w-100" src="<?= base_url(); ?>assets/images/portfolio-6.jpg" alt="">
						<div class="portfolio-overlay">
							<a class="btn btn-square btn-outline-light mx-1" href="<?= base_url(); ?>assets/images/portfolio-6.jpg" data-lightbox="portfolio"><i class="fa fa-eye"></i></a>
							<a class="btn btn-square btn-outline-light mx-1" href=""><i class="fa fa-link"></i></a>
						</div>
					</div>
					<div class="bg-light p-4">
						<p class="text-primary fw-medium mb-2">UI / UX Design</p>
						<h5 class="lh-base mb-0">Digital Agency Website Design And Development</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> -->
<!-- Projects End -->


<!-- Testimonial Start -->
<?php
$this->db->where("status", 1);
$this->db->limit(9);
$db = $this->db->get("testimoni");

?>
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
	<div class="container py-5 px-lg-5">
		<p data-aos-delay="" data-aos="fade-up" class="section-title text-secondary justify-content-center"><span></span>Testimonial<span></span></p>
		<h1 data-aos-delay="300" data-aos="fade-up" class="text-center mb-5">What Say Our Clients!</h1>
		<div class="owl-carousel testimonial-carousel">

			<?php foreach ($db->result() as $r) { ?>
				<div data-aos-delay="600" data-aos="zoom-in" class="testimonial-item bg-light rounded my-4">
					<p class="fs-6"><i class="fa fa-quote-left fa-2x text-primary mt-n4 me-3"></i><?= $r->komentar ?> </p>
					<div class="d-flex align-items-center">
						<img class="img-fluid flex-shrink-0 rounded-circle" src="<?= base_url("cdn/uploads/" . $r->foto) ?>" style="width: 65px; height: 65px;">
						<div class="ps-4">
							<h6 class="mb-1"><?= $r->nama ?></h6>
							<span><?= $r->jabatan ?></span>
						</div>
					</div>
				</div>
			<?php } ?>

		</div>
	</div>
</div>
<!-- Testimonial End -->


<!-- Team Start -->
<!-- <div class="container-xxl py-5">
	<div class="container py-5 px-lg-5">
		<div class="wow fadeInUp" data-wow-delay="0.1s">
			<p class="section-title text-secondary justify-content-center"><span></span>Our Team<span></span></p>
			<h1 class="text-center mb-5">Our Team Members</h1>
		</div>
		<div class="row g-4">
			<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
				<div class="team-item bg-light rounded">
					<div class="text-center border-bottom p-4">
						<img class="img-fluid rounded-circle mb-4" src="<?= base_url(); ?>assets/images/team-1.jpg" alt="">
						<h5>John Doe</h5>
						<span>CEO & Founder</span>
					</div>
					<div class="d-flex justify-content-center p-4">
						<a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
						<a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
						<a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>
						<a class="btn btn-square mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
				<div class="team-item bg-light rounded">
					<div class="text-center border-bottom p-4">
						<img class="img-fluid rounded-circle mb-4" src="<?= base_url(); ?>assets/images/team-2.jpg" alt="">
						<h5>Jessica Brown</h5>
						<span>Web Designer</span>
					</div>
					<div class="d-flex justify-content-center p-4">
						<a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
						<a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
						<a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>
						<a class="btn btn-square mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
				<div class="team-item bg-light rounded">
					<div class="text-center border-bottom p-4">
						<img class="img-fluid rounded-circle mb-4" src="<?= base_url(); ?>assets/images/team-3.jpg" alt="">
						<h5>Tony Johnson</h5>
						<span>SEO Expert</span>
					</div>
					<div class="d-flex justify-content-center p-4">
						<a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
						<a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
						<a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>
						<a class="btn btn-square mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> -->
<!-- Team End -->