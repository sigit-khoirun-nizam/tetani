<?php
defined('BASEPATH') or exit('No direct script access allowed');
$set = $this->func->globalset("semua");
$nama = (isset($titel)) ? $set->nama . " &#8211; " . $titel : $set->nama . " &#8211; " . $set->slogan;
$nama = ($this->func->demo() == true) ? $nama . " App by @masbil_al 085691257411" : $nama;
$headerclass = (isset($titel)) ? "header-v4" : "";
$keranjang = (isset($_SESSION["usrid"]) and $_SESSION["usrid"] > 0) ? $this->func->getKeranjang() : 0;
$keyw = $this->db->get("kategori");
$keywords = "";
$img = (isset($img)) ? $img : base_url("cdn/assets/img/" . $set->favicon);
$url = (isset($url)) ? $url : site_url();
$desc = (isset($desc)) ? $desc : "Aplikasi toko online " . $nama;
foreach ($keyw->result() as $key) {
	$keywords .= "," . $key->nama;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title><?= $nama ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" type="image/png" href="<?= base_url("cdn/assets/img/" . $set->favicon) ?>" />
	<meta name="google-site-verification" content="G35UyHn6lX6mRzyFws0NJYYxHQp_aejuAFbagRKCL7c" />
	<meta name="description" content="<?= $desc ?>" />
	<!--  Social tags      -->
	<meta name="keywords" content="Aplikasi toko online <?= $nama ?>">
	<meta name="description" content="<?= $desc ?>">
	<!-- Schema.org markup for Google+ -->
	<meta itemprop="name" content="<?= $nama ?>">
	<meta itemprop="description" content="<?= $desc ?>">
	<meta itemprop="image" content="<?= $img ?>">
	<!-- Twitter Card data -->
	<meta name="twitter:card" content="product">
	<meta name="twitter:site" content="@masbil_al">
	<meta name="twitter:title" content="<?= $nama ?>">
	<meta name="twitter:description" content="<?= $desc ?>">
	<meta name="twitter:creator" content="@masbil_al">
	<meta name="twitter:image" content="<?= $img ?>">
	<!-- Open Graph data -->
	<meta property="fb:app_id" content="<?= $set->fb_pixel ?>">
	<meta property="og:title" content="<?= $nama ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?= $url ?>" />
	<meta property="og:image" content="<?= $img ?>" />
	<meta property="og:description" content="<?= $desc ?>" />
	<meta property="og:site_name" content="<?= $nama ?>" />

	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/font-awesome.min.css') ?>">
	<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/select2/select2.min.css') ?>">
	<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/select2/select2-bootstrap4.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/slick/slick.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/slick/slick-theme.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/vendor/swal/sweetalert2.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/util.min.css') ?>">
	<!-- <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/main.css?v=' . time()) ?>"> -->
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/responsive.css?v=' . time()) ?>">
	<link href="<?= base_url('assets/css/style.css?v=' . time()); ?>" rel="stylesheet">
	<!-- <link href="<?= base_url(); ?>assets/vendor/animate/animate.min.css" rel="stylesheet"> -->

	<link href="<?= base_url(); ?>assets/vendor/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
	<link href="<?= base_url(); ?>assets/vendor/owlcarousel/assets/owl.theme.default.min.css" rel="stylesheet">
	<!-- <link href="<?= base_url(); ?>assets/vendor/lightbox/css/lightbox.min.css" rel="stylesheet"> -->
	<!--===============================================================================================-->
	<script>
		AOS.init();
	</script>
	<!-- GENERATED CUSTOM COLOR -->
</head>

<body style="background-color:white;">

	<div class="container-xxl bg-light p-0"></div>
	<!-- Spinner Start -->
	<!-- <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-grow " style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> -->
	<!-- Spinner End -->


	<!-- Navbar & Hero Start -->
	<div class="container-xxl position-relative p-0">

		<nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
			<a href="" class="navbar-brand p-0">
				<h1 data-aos-delay="600" data-aos="zoom-in" class="m-0"><?= $this->func->getSetting("nama") ?></h1>
				<!-- <img src="img/logo.png" alt="Logo"> -->
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
				<span class="fa fa-bars"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarCollapse">
				<div class="navbar-nav mx-auto py-0">
					<a data-aos-delay="600" data-aos="zoom-in" class="nav-link" href="<?= site_url() ?>"><i class="fas fa-home "></i> Home</a>
					<a data-aos-delay="600" data-aos="zoom-in" class="nav-link" href="<?= site_url("Shop") ?>"><i class="fas fa-shopping-cart "></i> Belanja</a>
					<a data-aos-delay="600" data-aos="zoom-in" class="nav-link" href="<?= site_url("blog") ?>"><i class="fas fa-comment-dots "></i> Blog</a>
					<li class="nav-item dropdown">
						<a data-aos-delay="600" data-aos="zoom-in" class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-info-circle "></i> Informasi
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<?php
							$this->db->where("status", 1);
							$page = $this->db->get("page");
							foreach ($page->result() as $pg) {
								echo '<a class="dropdown-item" href="' . site_url("page/" . $pg->slug) . '">' . $pg->nama . '</a>';
							}
							?>
						</div>
					</li>
					<?php if ($this->func->cekLogin() == true) { ?>
						<li class="nav-item">
							<a class="nav-link" href="<?= site_url('manage/pesanan') ?>"><i class="fas fa-box text-primary"></i> Pesananku</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-user-circle text-primary"></i> Akun
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="<?= site_url("manage") ?>">Pengaturan Akun</a>
								<a class="dropdown-item" href="javascript:signoutNow()">Logout</a>
							</div>
						</li>
					<?php } ?>
					</ul>

				</div>
				<ul class="navbar-nav ml-auto mt-2 mt-lg-0">
					<?php if ($this->func->cekLogin() != true) { ?>
						<li class="nav-item">

							<a data-aos-delay="600" data-aos="zoom-in" href="<?= site_url("home/signin") ?>" class="btn rounded-pill py-2 px-4 ms-3 d-none d-lg-block"><i class="fas fa-sign-in-alt"></i> Masuk / Daftar</a>
						</li>
					<?php } else { ?>
						<li class="nav-item m-r--20">
							<a class="nav-link" href="<?= site_url('home/keranjang') ?>">
								<i class="fas fa-shopping-basket "></i> <b class="badge badge-danger p-lr-8"><?= $this->func->getKeranjang() ?></b>
							</a>
						</li>
						<li class="nav-item p-all-0">
							<a class="nav-link" href="<?= site_url('home/wishlist') ?>">
								<i class="fas fa-heart "></i> <b class="badge badge-danger p-lr-8 wishlistcount"><?= $this->func->getWishlistCount() ?></b>
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</nav>