<?php
    $page= get_the_title();
?>
<footer>


	<div id="footer-meta" class="container ">
		<div class="row">
	        
	
        	<div class="col-md-3 col-sm-3 col-xs-3 textright">
        	    <a href="<?php echo home_url('/'); ?>/impressum">Impressum</a>

	            &copy; <?php echo date("Y"); ?> 
	         </div>
	  </div>
	</div>
		
</footer>
<?php wp_footer(); ?>  


   <?php /*

   <script src="<?php bloginfo("stylesheet_directory") ?>/assets/js/masonry.pkgd.min.js"></script>
   <script src="<?php bloginfo("stylesheet_directory") ?>/assets/js/imagesloaded.pkgd.min.js"></script>
   <script src="<?php bloginfo("stylesheet_directory") ?>/assets/js/jquery.infinitescroll.min.js"></script>
   
   */ ?>
   
   
  
   
  
  
</body>
</html>