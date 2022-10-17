<?php
	if(isset($headerbg)){
		$header = "global-header ";
	}else{
		$header = "";
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo (isset($titel)) ? $titel." | " : ""; ?>Solo Radio</title>
    <link rel="icon" href="<?php echo base_url("assets/img/smilly.png"); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/themev2/css/style.css"); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/themev2/css/util.css"); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/themev2/css/bootstrap.min.css"); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/themev2/css/owl.carousel.min.css"); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/themev2/fonts/Linearicons-Free-v1.0.0/icon-font.min.css"); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/themev2/css/font-awesome.min.css"); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/style.css?v=".time()); ?>">
	
	<!-- IMPORTANT -->
    <script src="<?php echo base_url("assets/themev2/js/jquery/jquery-3.4.1.min.js"); ?>"></script>
</head>

<body>
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="circle-preloader">
            <img src="<?php echo base_url("assets/themev2/img/core-img/compact-disc.png"); ?>" alt="">
        </div>
    </div>
    
    <header class="header-area">
        <div class="<?php echo $header; ?>musica-main-menu">
            <div class="container-fluid">
                <div class="wrapper-sidebar">
                    <nav id="sidebar" class="active">
                        <ul class="list-unstyled components m-t-40">
                            <li class="active">
                                <a href="<?php echo site_url(); ?>">Home</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url("playlist"); ?>">Playlist</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url("program"); ?>">Program</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url("makeitdigital"); ?>">Make It Digital</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url("event"); ?>">Event</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url("aboutus"); ?>">About Us</a>
                            </li>
                        </ul>
                    </nav>
                    
                    <div id="content">
                        <button type="button" id="sidebarCollapse" class="navbar-btn">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
				
				<div class="page-title p-t-20 p-l-40">
					<?php if(isset($titel)){ ?>
						<h3 class="l-text2"><?=$titel?></h3>
					<?php }/*else{ ?>
						<img class="logo" src="<?php echo base_url("assets/img/logo.png"); ?>" />
					<?php }*/ ?>
                </div>

                <div class="box-news">
                    <div class="dropdown">
                        <button class="btn" id="menu1" type="button" data-toggle="dropdown">
                            <span>What's New</span>
                            <i class="lnr lnr-chevron-down p-l-10"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                            <li>
                                <a href="<?=site_url("kategori/famous")?>">Famous Update
                                    <p class="fs-12 p-t-5" style="border-top:1px solid #777;text-transform: capitalize;">
                                        <?=$this->func->getFamousUpdate()?>
                                    </p>
                                </a>		
                            </li>
                            <li>
                                <a href="<?=site_url("kategori/fit")?>">FIT Update
                                    <p class="fs-12 p-t-5" style="border-top:1px solid #777;text-transform: capitalize;">
                                        <?=$this->func->getFitUpdate()?>
                                    </p>
                                </a>		
                            </li>
                            <li>
                                <a href="<?=site_url("kategori/fashionable")?>">Fashionable Update
                                    <p class="fs-12 p-t-5" style="border-top:1px solid #777;text-transform: capitalize;">
                                        <?=$this->func->getFashionableUpdate()?>
                                    </p>
                                </a>		
                            </li>
                        </ul>
                    </div>
                </div>
               
            </div>
        </div>
    </header>