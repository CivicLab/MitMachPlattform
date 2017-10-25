<?php 

//global $wp_query;

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

 $args = array( 
     'numberposts' => 10, 
     'orderby' => 'date',
     'order' => 'DESC',
     'paged'          => $paged
);


$vars = get_query_var('my_var');
//echo "hier:".var_dump($vars);



 $postlist = get_posts( $args );
 
 $categories_used = get_categories();
 $categories_all = get_categories(array('hide_empty' => FALSE));

 global $wp_query;
 $maxPages = $wp_query->max_num_pages;
 
get_header(); 


//echo "IP:".$_SERVER['REMOTE_ADDR']; 
?>
<script>

    var App = {};
     var baseUrl =  "<?php echo get_site_url() ?>" ;
     var maxPages = "<?php echo $maxPages ?>" ;

</script>
<div class="container">
    
    <div class="row submission-input">
        <?php 
           acf_form_head(); 
           
            $new_post = array(
                    'post_id'            => 'new', // Create a new post
          
                    'field_groups'       => array(228), // Create post field group ID(s)
                    'fields'             => array('wo', 'wann', 'autor' ,'email'),
                    'form'               => false,
                    'return'             => home_url(),//'%post_url%', // Redirect to new post url
                    'html_before_fields' => '',
                    'html_after_fields'  => '',
                    'submit_value'       => 'Beitrag senden',
                    'updated_message'    => 'Alles klar!'
                );
                 
                 
        ?>
        <div class="input-box">
                <div id="close-new-submission"><a  ><i class="fa fa-times" aria-hidden="true"></i></a></div> 
    		    <div class="submission-headline"><h1>Mitmachen <i class="fa fa-chevron-right" aria-hidden="true"></i></h1></div>
    			<div class="input-extra">
    			    
    			<div class="img-preview"></div>
    				<div class="input-row">
    					<textarea id="new-submission-text"  placeholder="Schreiben Sie hier, was Sie den Brandiser/innen vorschlagen möchten."></textarea>
    				</div>
    				<div class="input-row">
    					<ul class="categorylist-input">
    					<?php
    					
    					foreach($categories_all as $tag){ ?>

            		        <li>
    							<label>
    								<input type="checkbox" name="new-submission-tags" value="<?php echo $tag->term_id ?>"> <?php echo $tag->name ?>
    							</label>
    						</li>

            	        <?php  } ?>
                        </ul>
                        <input id="autsch"  type="text" >
    				</div>
    				<div class="input-row next1">
    				   <?php  acf_form( $new_post ); ?>
    				   <div class="datenschutzinfo">
       				    <a href="<?php echo get_site_url() ?>/datenschutz" target="_blank">*Hinweise zum Datenschutz</a>
       				   </div>
    				</div>
                    
    				
    			</div><!-- input-extra -->
    	</div>
    	<div class="button-row" id="button-row-weiter-1">    	    
			<span class="button" id="weiter1">Weiter</span>
		</div>
    	<div class="button-row" id="button-row-file">    	    
			<span class="file-button">
				<label for="new-submission-file"><span class="button"> <i class="fa fa-camera" aria-hidden="true"></i> Bild anfügen</span></label>
				<input type="file" name="file" class="input-element" name="new-submission-file" id="new-submission-file" accept="image/x-png, image/gif, image/jpeg" style="display:none;">
			</span>
			<span class="button" id="new-post-button">Abschicken</span>
			<span class="button" id="loading"><img src="<?php bloginfo('stylesheet_directory') ?>/img/ajax-loading.gif" alt="loading"></span>
		</div>
    
    </div><!-- submission-input -->
</div><!-- container -->
<div class="container mainview">
    
    <div class="row " >	
        <ul class="tags">
        <?php
		
		foreach($categories_used as $tag){ 
		    echo '<li><a  class="filter" data-termId ="'.$tag->term_id.'" data-filter =".'.$tag->slug.'" >#'.$tag->name.'</a></li>';
		} 
		?>
	
        </ul>
        
    </div>
    <div class="row sorting" >	
        <div class=" col-md-12 col-sm-12 col-xs-12 ">
        
            <ul class="sort">
                <li><a class="sortorder filter-active" data-sortorder ="sortAktuell" title="nach Aktualität sortieren"><i class="fa fa-clock-o" aria-hidden="true"></i></a></li>
                <li><a class="sortorder " data-sortorder ="sortRating" title="nach Bewerung sortieren"><i class="fa fa-star-o" aria-hidden="true"></i></a></li>
            </ul>
        </div>
       
    </div>
    
	<div class="row item-wrapper" role="main">			
		<?php
		    foreach($postlist as $post){ 
		        
		        $catArrPost = get_the_category($post->ID);
                 $slugArr =[];
                 foreach($catArrPost as $postCat){
                     $slugArr[] = $postCat->slug;
                 }
		        
		        ?>
		        
		        <div class="item col-md-4 col-sm-6 col-xs-12 <?php echo implode(' ',$slugArr) ?>" data-postID ="<?php echo $post->ID ?>" data-link ="<?php echo get_permalink($post->ID) ?>">
		            <div class="item-inner">
		                <div class="post">
		                    <?php
    		                $bild = get_the_post_thumbnail($post->ID) ;
		                
    		                if($bild != ''){
    		                    echo $bild;
    		                    
    	                    }
    	                        $content = explode(" ",nl2br($post->post_content));
	                        
                        		$short = array_chunk($content, 22);
                        		$abstract = implode(" ", $short[0]);
                    		

                        		if (sizeof($short) > 0) {
                        			$abstract .= ' … ';
                        		}
    	                        ?>
		                
    		            
        		                <div class="content-show"><?php echo $abstract ?></div>
        		            <?php 
        		            $author = get_post_meta( $post->ID, 'autor', true );
        		            ?>  
        		        </div> <!--post-->
    		            <div class="meta">                    
        		            <div class="author-and-date">
        		                <span class="author-show"><?php echo $author ?></span>
            		            <?php
            		                $dateStr = 'vor '.human_time_diff( get_the_date( 'U', $post->ID ), current_time('timestamp'));
            		                if($dateStr == 'vor 1 Tag')
            		                    $dateStr = 'gestern um '.get_post_time( 'H:i', true, $post->ID ).' Uhr';
            		            ?>
            		            <span class="date-show"><?php echo  $dateStr;  ?></span>
            		            <span class="comments-show">
            		            <?php
            		                $participants = count(get_field('teilnehmer', $post->ID ));
            		                
            		                $comments = count(get_comments( array('post_id' => $post->ID)));
            		                if($comments > 0)
            		                    echo '<i class="fa fa-comment" aria-hidden="true"></i> ('.$comments.')';
            		                if($participants > 0){
            		                    if($comments > 0)
            		                        echo ' ';
            		                    echo '<i class="fa fa-user-plus" aria-hidden="true"></i></i> ('.$participants.')';
        		                    }
            		            ?>
            		            </span>
        		            </div>
        		            
        		            <div class="rating-show">
        		            <?php
        		             $rating = get_field('rating', $post->ID );
        		             if($rating){
        		                 $ratingCount = count($rating);
                                 $sum = 0;
                         
                                 foreach($rating as $r){
                                     $sum += intval($r['bewertung']);
                                    // echo "<br>sum:".$sum; 
                                 }
                         
                                 $tmp = $sum / $ratingCount; 
                                 $ratingAvrg = round($tmp, 1);
                                 $ratingVal=round($tmp);
                                 $count = $ratingVal;
                             
            		            }else{
            		                $count = 0;
        		                }
    		           
        		            for($i=0; $i< 5; $i++){
        		               //   if($i < $count)
            		           //         echo '<img src="'.get_stylesheet_directory_uri().'/img/tstar-filled-small.png">';
            		           //     else
            		           //        echo '<img src="'.get_stylesheet_directory_uri().'/img/tstar-empty-small.png">';
        		                if($i < $count)
        		                    echo '<i class="fa fa-star" aria-hidden="true"></i>';
        		                else
        		                   echo '<i class="fa fa-star-o" aria-hidden="true"></i>';
    		                }
		              
		                
    		             
        		            ?>
        		            </div>
        		            <div class="tags-show">
        		            <?php
        		            foreach($catArrPost as $cat){
        		                echo '<span ><i class="fa fa-tag" aria-hidden="true"></i> '.$cat->name.'</span>';
    		                }
        		            ?>
        		            </div>
        		            
        		            <div class="sortAktuell"><?php echo $post->post_date; ?></div>
        		            <div class="sortRating"><?php echo $count ?></div>
    		            
    		            </div> <!--meta-->
    		        </div>
		            
		        </div>
		        
	      <?php  }
		?>		
			
	</div>
	
	<div class="navigation">
        <?php 
            
            echo next_posts_link(); 
        ?>
    </div>
</div>
<?php get_footer(); ?>				