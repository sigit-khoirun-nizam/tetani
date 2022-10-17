(function ($) {
    'use strict';
    
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
        $(this).toggleClass('active');
    });

    $('#menu-wrap').prepend('<div id="menu-trigger">Menu</div>');		
		$("#menu-trigger").on("click", function(){
			$("#menu").slideToggle();
		});

		// iPad
		var isiPad = navigator.userAgent.match(/iPad/i) != null;
		if (isiPad) $('#menu ul').addClass('no-transition'); 

    var browserWindow = $(window);

    // :: 1.0 Preloader Active Code
    browserWindow.on('load', function () {
        $('.preloader').fadeOut('slow', function () {
            $(this).remove();
        });
    });
    
    // :: 2.0 Nav Active Code
    if ($.fn.classyNav) {
        $('#musicaNav').classyNav();
    }
    
    // :: 3.0 Sliders Active Code
    if ($.fn.owlCarousel) {
        var welcomeSlide = $('.hero-slides');
        var featured_shows = $('.featured-shows-slides');
        var music_player = $('.music-player-slides');
        var discography = $('.discography-slides');
        var programSlides =$('.program-slides');
        var playlistSlides =$('.vid-list-container')

        welcomeSlide.owlCarousel({
            loop: true,
            nav: true,
            dots: false,
            mouseDrag: false,
            navText:['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            items: 1,
            autoplay: true
        });

        welcomeSlide.on('translate.owl.carousel', function () {
            var slideLayer = $("[data-animation]");
            slideLayer.each(function () {
                var anim_name = $(this).data('animation');
                $(this).removeClass('animated ' + anim_name).css('opacity', '0');
            });
        });

        welcomeSlide.on('translated.owl.carousel', function () {
            var slideLayer = welcomeSlide.find('.owl-item.active').find("[data-animation]");
            slideLayer.each(function () {
                var anim_name = $(this).data('animation');
                $(this).addClass('animated ' + anim_name).css('opacity', '1');
            });
        });

        $("[data-delay]").each(function () {
            var anim_del = $(this).data('delay');
            $(this).css('animation-delay', anim_del);
        });

        $("[data-duration]").each(function () {
            var anim_dur = $(this).data('duration');
            $(this).css('animation-duration', anim_dur);
        });

        featured_shows.owlCarousel({
            items: 3,
            margin: 30,
            loop: true,
            nav: true,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            dots: false,
            autoplay: true,
            autoplayTimeout: 5000,
            smartSpeed: 600,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                }
            }
        });

        playlistSlides.owlCarousel({
            items: 4,
            loop: true,
            nav: false,
            margin:10,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            dots: false,
            autoplay: false,
            responsive: {
                0: {
                    items: 2
                },
                992: {
                    items: 4
                },
                1200: {
                    items: 4
                }
            }
        });

        discography.owlCarousel({
            items: 6,
            margin: 30,
            loop: true,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 5000,
            smartSpeed: 600,
            responsive: {
                0: {
                    items: 2
                },
                768: {
                    items: 4
                },
                992: {
                    items: 6
                }
            }
        });

        programSlides.owlCarousel({
           
            autoplay:false,
            autoplayTimeout: 3000,
            smartSpeed: 750,
            loop:true,
            center: true,
            autoWidth: true,
            nav:true,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            items:1,
            lazyLoad: true,
            nav:false,
            dots: true,
            responsive:{
                0:{
                    items:1,
                    stagePadding: 60,
                    autoWidth: false
                },
                600:{
                    items:1,
                    stagePadding: 100
                },
                1000:{
                    items:1,
                    stagePadding: 200
                },
                1200:{
                    items:1,
                    stagePadding: 250,
                    autoWidth: true
                },
                1400:{
                    items:1,
                    stagePadding: 300
                },
                1600:{
                    items:1,
                    stagePadding: 350
                },
                1800:{
                    items:1,
                    stagePadding: 400
                }
            }
        });
    }

    // :: 4.0 ScrollUp Active Code
    if ($.fn.scrollUp) {
        browserWindow.scrollUp({
            scrollSpeed: 1500,
            scrollText: '<i class="fa fa-angle-up"></i>'
        });
    }

    // :: 5.0 CounterUp Active Code
    if ($.fn.counterUp) {
        $('.counter').counterUp({
            delay: 10,
            time: 2000
        });
    }

    // :: 6.0 Sticky Active Code
    if ($.fn.sticky) {
        $(".musica-main-menu").sticky({
            topSpacing: 0
        });
    }

    // :: 7.0 Progress Bar Active Code
    if ($.fn.circleProgress) {
        $('#circle').circleProgress({
            size: 160,
            emptyFill: "rgba(0, 0, 0, .0)",
            fill: '#cc1573',
            thickness: '4',
            reverse: true
        });
        $('#circle2').circleProgress({
            size: 160,
            emptyFill: "rgba(0, 0, 0, .0)",
            fill: '#cc1573',
            thickness: '4',
            reverse: true
        });
        $('#circle3').circleProgress({
            size: 160,
            emptyFill: "rgba(0, 0, 0, .0)",
            fill: '#cc1573',
            thickness: '4',
            reverse: true
        });
        $('#circle4').circleProgress({
            size: 160,
            emptyFill: "rgba(0, 0, 0, .0)",
            fill: '#cc1573',
            thickness: '4',
            reverse: true
        });
    }

    // :: 8.0 audioPlayer Active Code
    if ($.fn.audioPlayer) {
        $('.audio').audioPlayer();
    }

    // :: 9.0 Tooltip Active Code
    if ($.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip()
    }

    // :: 10.0 niceScroll Active Code
    if ($.fn.niceScroll) {
        $(".album-all-songs").niceScroll({
            background: "#fff"
        });
    }

    // :: 11.0 ScrollDown Active Code
    $("#scrollDown").on('click', function () {
        $('html, body').animate({
            scrollTop: $("#about").offset().top - 85
        }, 1500);
    });

    // :: 12.0 prevent default a click
    $('a[href="#"]').on('click', function ($) {
        $.preventDefault();
    });

    // :: 13.0 wow Active Code
    // if (browserWindow.width() > 767) {
    //     new WOW().init();
    // }
    
})(jQuery);