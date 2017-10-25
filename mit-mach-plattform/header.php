<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8" /> 
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title( '|', true, 'right' ); ?><?php bloginfo('name') ?></title>              
    <link rel="profile" href="http://gmpg.org/xfn/11" />        
    <script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/assets/js/jquery-1.11.2.min.js"></script>
    <script src="<?php bloginfo("stylesheet_directory") ?>/assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="<?php bloginfo("stylesheet_directory") ?>/assets/js/isotope.pkgd.min.js"></script>
    <script src="<?php bloginfo("stylesheet_directory") ?>/assets/js/jquery.infinitescroll.min.js"></script>
    <script type="text/javascript" src="<?php bloginfo("stylesheet_directory") ?>/assets/js/jquery.rateit.js"></script>
    
    <?php 
        wp_head(); 
       // show_admin_bar( false );
    ?>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory') ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory') ?>/assets/css/font-awesome.min.css">


    <?php /*<script type="text/javascript" src="<?php bloginfo('stylesheet_directory') ?>/assets/js/jquery-1.11.2.min.js"></script>  */ ?>
  
   
    <link rel="stylesheet" charset='utf-8' href="<?php bloginfo('stylesheet_directory') ?>/style.css"  />
    
    <?php
    $page = get_post();
    
    
    global $wp;
    $current_url = home_url(add_query_arg(array(),$wp->request));
   
    ?>
    
</head>
<body >
<header role="banner">
<div class="container">
    <div class="row">
        <?php
        
            if($current_url != home_url()){ ?>
                <a href="<?php bloginfo('url') ?>" title="home"><img src="<?php bloginfo('stylesheet_directory') ?>/img/HEADER MITMACHPLATTFORM.jpg" alt="Hallo Brandis"></a> 
           <?php }else{ ?>
               <img src="<?php bloginfo('stylesheet_directory') ?>/img/HEADER MITMACHPLATTFORM.jpg" alt="Hallo Brandis">
              <?php } 
        ?>
       
    </div>
</div>
<?php /*
	<div class="container">
	<!--
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		-->
		<div class="row">			
			<div id="logo" class="col-md-6 col-sm-6 col-xs-9">				
				
			        <a  href="<?php echo esc_url(home_url('/')); ?>about">
			            <h1><?php bloginfo('name'); ?></h1>
			        </a>
			    
			</div>		
			<div class="col-md-4 col-sm-4 col-xs-3 pull-right" >				

				    <ul id="switch-view">
				        <li><a href="<?php echo home_url('/'); ?>" title="zur Map-Ansicht" ><i class="mdi mdi-google-maps icon"></i></a></li>
				        <li><a href="<?php echo home_url('/'); ?>/list" title="zur Listen-Ansicht"><i class="mdi mdi-view-list icon"></i></a></li>
				    </ul>
				
			</div>
			<!--
			<nav class="col-md-4 pull-right" role="navigation">				
				<div class="collapse navbar-collapse">
				    <ul id="main-navigation">
				        <li>Über uns</li>
				        <li>Impressum</li>
				    </ul>
				</div>
			</nav>
			-->
		</div>
	</div>
*/ ?>
</header>