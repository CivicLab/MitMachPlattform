<?php if(has_post_thumbnail()) :?>
	<div id="featured-image"><?php the_post_thumbnail('featured'); ?></div>		
<?php endif; ?>	
<div class="col-md-4">			
    <?php
    /*
    $page = get_post();
    if(isset($page->post_name)){
        if($page->post_name == 'about'){?>
          
            <div class="side-img">
                <img src="<?php bloginfo('stylesheet_directory') ?>/img/mitmachstadt.png" alt="Logo der Stadt Brandis">
            </div>
            
          <?php  
        }elseif($page->post_name == 'impressum'){?>
            <div class="side-img">
                <img src="<?php bloginfo('stylesheet_directory') ?>/img/brandis-stadt-logo.png" alt="Logo der Stadt Brandis">
            </div>
            <div class="side-img ">
                <img src="<?php bloginfo('stylesheet_directory') ?>/img/IKS.png" alt="Logo der Stadt Brandis">
            </div>
            
        <?php    
        }  
    }
*/
    ?>
</div>

<div class="col-md-8 ">			
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php the_title('<h1 id="post-title">', '</h1>'); ?>						
		<?php the_content(); ?>				
	</article>	
	<?php comments_template(); ?>	
</div>	