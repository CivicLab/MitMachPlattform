<?php 

    get_header(); 

?>
<script>

    var App = {};
     var baseUrl =  "<?php echo get_bloginfo('url'); ?>" ;

</script>
<div class="container">
    <div class="row">
        <img src="<?php bloginfo('stylesheet_directory') ?>/img/HEADER MITMACHPLATTFORM.jpg" alt="Hallo Brandis">
    </div>
</div>
<div class="container mainview" id="contentOverlay">
    
	<div class="row single" role="main">	
			
			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

			 <div class="single-item " data-postID ="<?php echo get_the_ID() ?>">
			    <div class="close_overlay"><a href="#" ><i class="fa fa-times" aria-hidden="true"></i></a></div>
		            <div class="item-inner">
		                <?php
		                
		                    $postID = get_the_ID();
		                     $catArrPost = get_the_category();
		                    $bild = get_the_post_thumbnail() ;
		                
    		                if($bild != ''){
    		                    echo $bild;
    	                    }
	                    ?>
		                
    		            <p class="content-show"><?php echo get_the_content() ?></p>
    		            <?php
    		            $author = get_post_meta( $postID, 'autor', true );
    		            ?>                      
    		            <p class="author-show"><?php echo $author ?></p>
    		            <?php
    		                $dateStr = 'vor '.human_time_diff( get_the_date( 'U', $postID ), current_time('timestamp'));
    		                if($dateStr == 'vor 1 Tag')
    		                    $dateStr = 'gestern um '.get_post_time( 'H:i', true, $postID ).' Uhr';
    		            ?>
    		            <p class="date-show"><?php echo  $dateStr;  ?></p>
    		            <p class="tags-show">
    		            <?php
    		            foreach($catArrPost as $cat){
    		                echo '<span ><i class="fa fa-tag" aria-hidden="true"></i> '.$cat->name.'</span>';
		                }
    		            ?>
    		            </p>
    		            <div class="rating-show">
    		            <?php
    		          //   $rating = get_post_meta( $postID, 'rating', true );
    		          //   //array(2) { ["avrg"]=> int(5) ["votes"]=> int(1) }
    		          //   $count = $rating["avrg"];
                      //
    		          //   for($i=0; $i< 5; $i++){
    		          //       if($i < $count)
    		          //           echo '<img src="'.get_stylesheet_directory_uri().'/img/star-filled-small.png">';
    		          //       else
    		          //          echo '<img src="'.get_stylesheet_directory_uri().'/img/star-small.png">';
		              //   }
		                
		                
    		             //if(count($rating) > 0)
    		            //echo "hier:".$rating["avrg"] ;
    		            //echo '<div class="xhidden" id="rating_'.$post->ID.'">'.get_field('rating', $post->ID ).'</div>';
    		            echo get_field('rating', $postID )
    		            ?>
    		            </div>
    		                
    		            <?php
    		            
    		                $comments = get_comments( array('post_id' => $postID));
    		                if(count($comments) == 1)
    		                    $header = '1 Kommentar';
    		                else
    		                    $header = count($comments).' Kommentare';
    		            
                            
                       echo '<h3 class="comments-header">'.$header.'</h3>';
                       echo '<div class="comments">';
      
                        foreach($comments as $commentObj){
                            echo '<div class="comment">';
                            echo '<div class="c-wrap">';
                            echo '<div class="comment-author">'.$commentObj->comment_author.'</div>';
                            echo '<div class="comment-date">'.'vor '. human_time_diff( get_comment_date( 'U', $commentObj->comment_ID ), current_time( 'timestamp' )) .'</div>';
                            echo '</div>';
                            echo '<p class="comment-content">'.$commentObj->comment_content.'</p>';
                            echo '</div>';
                            
                        }
                        echo '</div>';
                        
                        ?>
                        <div id="comment-input">
                         	<div class="input-view">
                         		<div class="new-comment">
                         			<p><textarea id="new-comment-text" maxlength="512" placeholder="Anmerkungen?"></textarea></p>
                         			<p><input id="new-comment-author" maxlength="32" type="text" placeholder="Ihr Name"></p>
                         			<input id="postID"  type="hidden" value="<?php echo $postID ?>">
                         			<input id="autsch"  type="text" >
                         		</div>
                         		<div class="button-row"><span class="button" id="new-comment-button">Kommentieren</span></div>
                         	</div>
                         </div>
                        
    		            
    		            
    		        </div>
		            
		        </div>

			<?php endwhile; ?>
			
		
			
	</div>
	
</div>
<?php get_footer(); ?>		