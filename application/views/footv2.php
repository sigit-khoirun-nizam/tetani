<!-- Footer -->
<!-- Footer Start -->
<?php $set = $this->func->getSetting("semua"); ?>
<div class="container-fluid  text-light footer wow fadeIn" data-wow-delay="0.1s" style="background-color:#FBA504;">
	<div class="container py-5 px-lg-5">
		<div class="row g-5">
			<div class="col-md-6 col-lg-3">
				<p class="section-title text-white h5 mb-4"  data-aos-delay="" data-aos="zoom-in" >Hubungi Kami<span></span></p>
				<p  data-aos-delay="" data-aos="zoom-in"><i class="fa fa-map-marker-alt me-3"></i><?= $set->alamat ?></p>
				<p  data-aos-delay="" data-aos="zoom-in"><i class="fa fa-phone-alt me-3"></i><?= $set->wasap ?></p>
				<p  data-aos-delay="" data-aos="zoom-in"><i class="fa fa-envelope me-3"></i><?= $set->email ?></p>
				<div class="d-flex pt-2">
					<a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
					<a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
					<a class="btn btn-outline-light btn-social" href=""><i class="fab fa-instagram"></i></a>
					<a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
				</div>
			</div>
			<?php
			$this->db->where("parent", 0);
			$kategori = $this->db->get("kategori");

			?>
			<div class="col-md-6 col-lg-3">
				<p data-aos-delay="300" data-aos="zoom-in" class="section-title text-white h5 mb-4">Kategori<span></span></p>
				<?php foreach ($kategori->result() as $r) : ?>
					<a   data-aos-delay="300" data-aos="zoom-in" class="btn btn-link" href="<?= site_url("kategori/" . $r->url) ?>"> <?= ucwords(strtolower($r->nama)) ?></a>
				<?php endforeach; ?>
			</div>
			<div class="col-md-6 col-lg-3">
				<p   data-aos-delay="600" data-aos="zoom-in" class="section-title text-white h5 mb-4">Narahubung<span></span></p>
				<div class="row g-2">
					<a  data-aos-delay="600" data-aos="zoom-in" target="_blank" onclick="fbq('track','Contact')" href="https://wa.me/<?= $this->func->getRandomWasap() ?>/?text=Halo,%20mohon%20infonya%20untuk%20menjadi%20reseller%20*<?= $this->func->getSetting("nama") ?>*%20caranya%20bagaimana%20ya?%20dan%20syaratnya%20apa%20saja,%20terima%20kasih" class="btn btn-success btn-block"><i class="fab fa-whatsapp"></i> Hubungi Admin</a>
					&nbsp;<p  data-aos-delay="600" data-aos="zoom-in" >Dapatkan potongan harga khusus untuk reseller.
				</div>
				<div class="row g-2">
				<div class="flex-m p-t-10">
					<a  data-aos-delay="600" data-aos="zoom-in" onclick="fbq('track','Contact')" href="<?=$set->facebook?>" style="color: #2980b9;" class="fs-32 color1 p-r-20 fab fa-facebook-square"></a>
					<a   data-aos-delay="600" data-aos="zoom-in" onclick="fbq('track','Contact')" href="<?=$set->instagram?>" style="color: #dd2a7b;" class="fs-32 color1 p-r-20 fab fa-instagram"></a>
					<a  data-aos-delay="600" data-aos="zoom-in" onclick="fbq('track','Contact')" href="mailto:<?=$set->email?>" class="color1 fs-32 color1 p-r-20 fas fa-envelope"></a>
				</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-3">
				<p  data-aos-delay="800" data-aos="zoom-in" class="section-title text-white h5 mb-4">Pembayaran<span></span></p>
				<div class="position-relative w-100 mt-3">
				<div class="flex-m p-t-10">
					<img  data-aos-delay="800" data-aos="zoom-in" style="width:100%;" src="<?=base_url("assets/images/ipaymu.png")?>" />
				</div>

				<h4  data-aos-delay="800" data-aos="zoom-in" class="font-medium foot-title p-b-10 p-t-30">
					Pengiriman
				</h4>

				<div class="p-t-10">
					<?php
						$kurir = explode("|",$set->kurir);
						for($i=0; $i<count($kurir); $i++){
							$kur = $this->func->getKurir($kurir[$i],"halaman");
							echo '<img   data-aos-delay="800" data-aos="zoom-in" style="width:28%;margin:2%;" src="'.base_url("cdn/assets/img/kurir/".$kur.".png").'" />';
						}
					?>
				</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container px-lg-5">
		<div class="copyright">
			<div class="row">
				<div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
				</div>
				<div class="col-md-6 text-center text-md-end">
					<div class="footer-menu">
						<a href="">Home</a>
						<a href="">Cookies</a>
						<a href="">Help</a>
						<a href="">FQAs</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Footer End -->

<!-- Back to Top -->
<a href="https://wa.me/<?= $this->func->getRandomWasap() ?>"  target="_blank"class="btn btn-lg btn-success btn-lg-square back-to-top"><i class="fab fa-whatsapp"></i></a>
</div>

<!-- Back to top
	<div class="btn-back-to-top bg0-hov" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="fa fa-angle-double-up" aria-hidden="true"></i>
		</span>
	</div> -->
<input type="hidden" id="names" value="<?= $this->security->get_csrf_token_name() ?>" />
<input type="hidden" id="tokens" value="<?= $this->security->get_csrf_hash(); ?>" />

<?php if ($this->func->cekLogin() == true) { ?>
	<script type="text/javascript">
		$(function() {
			//$("#modalpesan").modal();
			$("#modalpilihpesan,#modalpesan").on('shown.bs.modal', function() {
				$(".chat-sticky").hide();
			});
			$("#modalpilihpesan,#modalpesan").on('hidden.bs.modal', function() {
				$(".chat-sticky").show();
			});
			$("#modalpesan").on('shown.bs.modal', function() {
				fbq("track", "Contact");
				loadPesan(0);
				var seti = setInterval(() => {
					loadPesan(1);
				}, 3000);
				$("#modalpesan").on('hidden.bs.modal', function() {
					clearInterval(seti);
				});
			});

			$("#kirimpesan").on("submit", function(e) {
				e.preventDefault();
				var datar = $(this).serialize();
				datar = datar + "&" + $("#names").val() + "=" + $("#tokens").val();
				$.post("<?= site_url("assync/kirimpesan") ?>", datar, function(s) {
					fbq("track", "Contact");
					var data = eval("(" + s + ")");
					updateToken(data.token);
					$("#kirimpesan input").val("");
					if (data.success == true) {
						$("#pesan").html('<div class="isipesan"><i class="fas fa-spin fa-compact-disc text-success"></i> memuat pesan...</div>');
						loadPesan(0);
					} else {
						swal("GAGAL!", "terjadi kendala saat mengirim pesan, coba ulangi beberapa saat lagi", "error");
					}
				});
			});

			//$("#modalpilihpesan").modal();

			function loadPesan(nul) {
				$("#pesan").load("<?= site_url("assync/pesanmasuk") ?>", function() {
					if (nul != 1) {
						$("#pesan").animate({
							scrollTop: $("#pesan").prop('scrollHeight')
						}, 1000);
					}
				});
			}

		});
	</script>
	<div class="modal fade" id="modalpesan" tabindex="-1" role="dialog" style="background: rgba(0,0,0,.5);" style="bottom:0;right:0;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-primary font-medium"><i class="fa fa-comments"></i> Live Chat</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body pesan" id="pesan">
					<div class="pesanwrap center">
						<div class="isipesan"><i class="fas fa-spin fa-compact-disc text-success"></i> memuat pesan...</div>
					</div>
				</div>
				<form id="kirimpesan" method="POST">
					<div class="modal-footer">
						<div class="input-group">
							<input type="text" class="form-control" placeholder="ketik pesan..." name="isipesan" required />
							<div class="input-group-append">
								<button type="submit" id="submit" class="btn btn-success"><i class="fa fa-paper-plane"></i> KIRIM</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalpilihpesan" tabindex="-1" role="dialog" style="background: rgba(0,0,0,.5);" style="bottom:0;right:0;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content p-lr-30 p-tb-40 text-center">
				<h3 class="text-primary font-bold">Hubungi Admin</h3><br />
				<a href="https://wa.me/<?= $this->func->getRandomWasap() ?>" target="_blank" class="btn btn-lg btn-block btn-success m-b-10"><i class="fab fa-whatsapp"></i> &nbsp;Hubungi via Whatsapp</a>
				<button onclick="$('#modalpilihpesan').modal('hide');$('#modalpesan').modal()" class="btn btn-lg btn-block btn-primary"><i class="fas fa-comments"></i> &nbsp;Live Chat</button>
			</div>
		</div>
	</div>
	<a href="javascript:void(0)" class="chat-sticky" onclick='$("#modalpilihpesan").modal()'><i class="fas fa-comment-dots"></i> Live Chat</a>
<?php } else { ?>
	<!-- <a href="https://wa.me/<?= $this->func->getRandomWasap() ?>" target="_blank" class="whatsapp-sticky"><i class="fab fa-whatsapp"></i></a> -->
<?php } ?>


<script type="text/javascript" src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/vendor/select2/select2.min.js') ?>"></script>
<script type="text/javascript">
	$(".js-select2").each(function() {
		$(this).select2({
			theme: 'bootstrap4',
			minimumResultsForSearch: 20,
			dropdownParent: $(this).next('.dropDownSelect2')
		});
	});
</script>
<script type="text/javascript" src="<?= base_url('assets/vendor/slick/slick.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/vendor/swal/sweetalert2.min.js') ?>"></script>
<!-- <script type="text/javascript" src="<?= base_url('assets/js/aos.js') ?>"></script> -->
<script type="text/javascript" src="<?= base_url('assets/js/main.js') ?>"></script>
<script type="text/javascript">
	AOS.init();

	function formUang(data) {
		return data.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
	}

	function signoutNow() {
		swal.fire({
				title: "Logout",
				text: "yakin akan logout dari akun anda?",
				icon: "warning",
				showDenyButton: true,
				confirmButtonText: "Oke",
				denyButtonText: "Batal"
			})
			.then((willDelete) => {
				if (willDelete.isConfirmed) {
					window.location.href = "<?= site_url("home/signout") ?>";
				}
			});
	}

	function tambahWishlist(id, nama) {
		$.post("<?php echo site_url("assync/tambahwishlist/"); ?>" + id, {
			[$("#names").val()]: $("#tokens").val()
		}, function(msg) {
			var data = eval("(" + msg + ")");
			var wish = parseInt($(".wishlistcount").html());
			updateToken(data.token);
			if (data.success == true) {
				$(".wishlistcount").html(wish + 1);
				swal.fire(nama, "berhasil ditambahkan ke wishlist", "success");
			} else {
				swal.fire("Gagal", data.msg, "error");
			}
		});
	}

	function updateToken(token) {
		$("#tokens,.tokens").val(token);
	}

	$(".block2-wishlist .fas").on("click", function() {
		$(this).removeClass("active");
		$(this).addClass("active");
	});
</script>


<!-- Facebook Pixel Code -->
<script>
	! function(f, b, e, v, n, t, s) {
		if (f.fbq) return;
		n = f.fbq = function() {
			n.callMethod ?
				n.callMethod.apply(n, arguments) : n.queue.push(arguments)
		};
		if (!f._fbq) f._fbq = n;
		n.push = n;
		n.loaded = !0;
		n.version = '2.0';
		n.queue = [];
		t = b.createElement(e);
		t.async = !0;
		t.src = v;
		s = b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t, s)
	}(window, document, 'script',
		'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '<?= $set->fb_pixel ?>');
	fbq('track', 'PageView');
</script>
<noscript>
	<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?= $set->fb_pixel ?>&ev=PageView&noscript=1" />
</noscript>
<!-- End Facebook Pixel Code -->

<script src="<?= base_url(); ?>assets/vendor/wow/wow.min.js"></script>
<script src="<?= base_url(); ?>assets/vendor/easing/easing.min.js"></script>
<script src="<?= base_url(); ?>assets/vendor/waypoints/waypoints.min.js"></script>
<script src="<?= base_url(); ?>assets/vendor/counterup/counterup.min.js"></script>
<script src="<?= base_url(); ?>assets/vendor/owlcarousel/owl.carousel.min.js"></script>
<script src="<?= base_url(); ?>assets/vendor/isotope/isotope.pkgd.min.js"></script>
<script src="<?= base_url(); ?>assets/vendor/lightbox/js/lightbox.min.js"></script>
<script src="<?= base_url(); ?>assets/js/main.js"></script>
<script>
	$(document).ready(function(){
  		$(".owl-carousel").owlCarousel();
  		$(".owlCarousel").owlCarousel();
		
	});

	$(document).ready(function(){
  $(".owl-carousel").owlCarousel();
});
</script>
</body>

</html>

