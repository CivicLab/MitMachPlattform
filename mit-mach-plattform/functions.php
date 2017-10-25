<?php

// theme setup
if (!function_exists('bCommunity_setup')):
	function bCommunity_setup() {	
	
		function bCommunity_editor_style() {
		  add_editor_style( get_template_directory_uri() . '/assets/css/editor-style.css' );
		}
		add_action('after_setup_theme', 'bCommunity_editor_style');
		// set content width  
		//if (!isset($content_width)) {$content_width = 750;}	
		add_theme_support( 'post-thumbnails' ); 
		set_post_thumbnail_size( 760, 400, array( 'left', 'top') );
		
		
		
	}
endif; 
add_action('after_setup_theme', 'bCommunity_setup');
//remove emoji
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

//next posts links
add_filter('next_posts_link_attributes', 'posts_link_attributes');
add_filter('previous_posts_link_attributes', 'posts_link_attributes');
 
function posts_link_attributes() {
    return 'class="next"';
}

add_filter('next_post_link', 'post_link_attributes');
add_filter('previous_post_link', 'post_link_attributes');
 
function post_link_attributes($output) {
    $code = 'class="next"';
    return str_replace('<a href=', '<a '.$code.' href=', $output);
}
/**
 * Filter the mail content type.
 */
function wpdocs_set_html_mail_content_type() {
    return 'text/html';
}
add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );

function the_name() {
  return 'Brandis Community';
}
add_filter('wp_mail_from_name', 'the_name');

function the_adress() {
  return get_bloginfo('admin_email');
}
add_filter('wp_mail_from', 'the_adress');

// REMOVE POST META BOXES
function remove_my_post_metaboxes() {
  //remove_meta_box( 'categorydiv','post','normal' ); // Categories Metabox
  remove_meta_box( 'tagsdiv-post_tag','post','normal' ); // Tags Metabox
}
add_action('admin_menu','remove_my_post_metaboxes');
//change acf style for honeypot
function my_acf_admin_enqueue_scripts() {
	
	// register style
    wp_register_style( 'my-acf-input-css', get_stylesheet_directory_uri() . '/assets/css/my-acf-input.css', false, '1.0.0' );
    wp_enqueue_style( 'my-acf-input-css' );
    
}

add_action( 'acf/input/admin_enqueue_scripts', 'my_acf_admin_enqueue_scripts' );

// load css 
function bCommunity_css() {	

	wp_enqueue_style('bCommunity_bootstrap_css', get_template_directory_uri() . '/assets/css/bootstrap.min.css');	   
	wp_enqueue_style('bCommunity_style', get_stylesheet_uri());
}
//add_action('wp_enqueue_scripts', 'bCommunity_css');

// load javascript
function bCommunity_javascript() {	
	wp_enqueue_script('bCommunity_bootstrap_js', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '3.1.1', true); 	
	//wp_enqueue_script('bCommunity_jquery_js', get_template_directory_uri() . '/assets/js/jquery-1.11.2.min.js', array('jquery'), '1.11.2', true); 	
	wp_enqueue_script('ajax-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0', true);
	//wp_enqueue_script('ajax-script-2', get_template_directory_uri() . '/assets/js/bCommunity-tg.js', array('jquery'), '1.0', true);
	wp_localize_script( 'ajax-script', 'my_ajax_object',
                array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('wp_enqueue_scripts', 'bCommunity_javascript');

// html5 shiv
function bCommunity_html5_shiv() {
    echo '<!--[if lt IE 9]>';
    echo '<script src="'. get_template_directory_uri() .'/assets/js/html5shiv.js"></script>';
    echo '<![endif]-->';
}
add_action('wp_head', 'bCommunity_html5_shiv');
/***************   custom query vars*****************************************/
function add_query_vars_filter( $vars ){
  $vars[] = "my_var";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );

//add_filter('acf/compatibility/field_wrapper_class', '__return_true');
/** Navigationspunkte aus dem WordPress-Dashboard entfernen */ 
//function remove_menus () {
//	global $menu;
//	global $current_user;
//	get_currentuserinfo();
//	$restricted = array(__('Kommentare'));
//	$more_restrictions = array(__('Werkzeuge'), __('Design'), __('Plugins'),  __('cpt_main_menu'), __('Eigene Felder'));
//
//	if(!in_array('administrator',$current_user->roles) ){
//	    foreach($more_restrictions as $r)
//		    array_push($restricted, $r); 
//        remove_menu_page( 'edit.php?post_type=acf-field-group' );
//	}
//	end ($menu);
//	while (prev($menu)){
//		$value = explode(' ',$menu[key($menu)][0]);
//		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){ unset($menu[key($menu)]) ;}
//	}
//}
//add_action('admin_menu', 'remove_menus');


add_action( 'wp_ajax_getPostContent', 'getPostContent' );
add_action( 'wp_ajax_nopriv_getPostContent', 'getPostContent' );

function getPostContent() {
   
    $out = [];
    $out['error'] = "N";
    

    $post = get_post($_POST['postID']);
    if($post){
        //$out['post_title']  = $post->post_title;
        $out['postID']  = $post->ID;
        $out['post_image']  = get_the_post_thumbnail($post->ID);
        $out['post_content']  = (nl2br($post->post_content)); //$post->post_content; //
      //  die("hier::".get_field('wo', $post->ID ));
        $out['post_wo']  = get_field('wo', $post->ID );
        $out['post_wann']  = get_field('wann', $post->ID );
        //$out['post_content']  = $post->post_content; 
        
        $out['post_author'] = get_field('autor', $post->ID );
        $out['post_author_email'] = get_field('email', $post->ID );
        
        $dateStr = 'vor '.human_time_diff( get_the_date( 'U', $post->ID ), current_time('timestamp'));
        if($dateStr == 'vor 1 Tag')
            $dateStr = 'gestern um '.get_post_time( 'H:i', true, $post->ID ).' Uhr';
        $out['post_date']  = $dateStr;
        
        $catArrPost = get_the_category($post->ID);
        foreach($catArrPost as $cat){
            $out['post_tags'][] = $cat->name;
           
        }
        $ratingObj = get_field('rating', $post->ID );
        $out['hasRated'] = false;
        foreach($ratingObj as $r){
            if($r['ip'] == md5($_SERVER['REMOTE_ADDR']))
                $out['hasRated'] = true;
        }
    //md5($_SERVER['REMOTE_ADDR'])   
   //  array(2) {
   //    [0]=>
   //    array(2) {
   //      ["bewertung"]=>
   //      string(1) "4"
   //      ["ip"]=>
   //      string(3) "::1"
   //    }
   //    [1]=>
   //    array(2) {
   //      ["bewertung"]=>
   //      string(3) "4.5"
   //      ["ip"]=>
   //      string(3) "::1"
   //    }
   //  }
   //  
       // $out['hasRated'] =
        $out['rating'] = get_field('rating', $post->ID );
        $participants = get_field('teilnehmer', $post->ID );
        if($participants)
            $out['participants'] = get_field('teilnehmer', $post->ID );
        else
            $out['participants'] = array();
        
        $comments = get_comments( array('post_id' => $post->ID));
        $c = 0;
        $out['comments'] = array();
        foreach($comments as $comment){

            $commentArr[$c]['author'] = $comment->comment_author;  
            $commentArr[$c]['date'] = 'vor '. human_time_diff( get_comment_date( 'U', $comment->comment_ID ), current_time( 'timestamp' )) ;
            $commentArr[$c]['txt'] = nl2br($comment->comment_content);
                
            $out['comments'][] =$commentArr[$c];
           
        }
        
   }else{
       $out['error'] = "J";
       $out['msg'] = "Fehler!? - Es gibt kein Post mit der ID:".$_POST['postID'];
   }
    //echo var_dump($post);
    echo json_encode($out);
    wp_die();
}
add_action( 'wp_ajax_saveComment', 'saveComment' );
add_action( 'wp_ajax_nopriv_saveComment', 'saveComment' );

function saveComment() {
   
    $out = [];
    $out['error'] = "N";
   
   $coment_author_email = strip_tags($_POST['author_email']);
   $coment_author_number = $_POST['author_number'];
   
    $post = get_post($_POST['postID']);
    if($post){
        $time = current_time('mysql');

        $data = array(
            'comment_author' => strip_tags($_POST['author']),
            'comment_content' => strip_tags($_POST['txt']),
            'comment_post_ID' => intval($_POST['postID']),
            //'comment_parent' => intval($_POST['postID']),
            
            'comment_date' => $time,
            'comment_approved' => 1
        );
       
        $cID = wp_insert_comment($data);
        $comment=  get_comment( $cID);
        
        $out['comment']['author'] = $comment->comment_author;  
        $out['comment']['date'] = 'vor '. human_time_diff( get_comment_date( 'U', $cID ), current_time( 'timestamp' )) ;
        $out['comment']['txt'] = (nl2br($comment->comment_content));
        $out['anzahl'] = get_comments_number($post->ID); //wp_count_comments( $post->ID);
       
       //mail an author
       $headers = array('Content-Type: text/html; charset=UTF-8');
       $email = get_field('email', $post->ID);
         $subject = 'Neuer Kommentar auf Ihren Beitrag ( Brandis Community )';

         $msgBody = '<p>Zu Ihrem Beitrag ›'.$post->post_content.'‹ wurde eine neuer Kommentar gepostet: </p>  ';
         $msgBody .= '<p>'.($comment->comment_content).'</p> ';
         $msgBody .= '<p>von: '.$comment->comment_author.'</p> ';
         if($coment_author_email != '' || $coment_author_number){
             if($coment_author_email != ''){
                 $msgBody .= '<p>Email: '.$coment_author_email.'</p> ';
             }
             if($coment_author_number != ''){
                  $msgBody .= '<p>Telefon: '.$coment_author_number.'</p> ';
              }
             
         }else{
             $msgBody .= '<p>Der Autor hat leider keine Kontaktdaten hinterlassen.</p> ';
         }
         
         $msgBody .= '<p>Ansehen auf '.get_bloginfo('url').'</p> ';
         //erstmal noch nicht versenden
         wp_mail( $email, $subject , $msgBody, $headers );
       
        
   }else{
       $out['error'] = "J";
       $out['msg'] = "Fehler beim Speichern des Kommentars!?";
   }
    //echo var_dump($post);
    echo json_encode($out);
    wp_die();
}

add_action( 'wp_ajax_saveParticipant', 'saveParticipant' );
add_action( 'wp_ajax_nopriv_saveParticipant', 'saveParticipant' );

function saveParticipant() {
   
    $out = [];
    $out['error'] = "N";
    $participant = strip_tags($_POST['participant']);
    $post_id = $_POST['postID'];
    $participant_email = strip_tags($_POST['participant_email']);
    $participant_number = $_POST['participant_number'];
    $post = get_post($post_id);
    if($post_id){
       
         $field_key = "field_57ee19356ef68";
         $value = get_field($field_key,  $post_id);
         $value[] = array("teilnehmer_name" => $participant, "teilnehmer_email" => $participant_email, "teilnehmer_number" => $participant_number);
         update_field($field_key, $value,  $post_id );
           
         $out['teilnehmer'] = $participant;
         $out['anzahl'] = count($value);
       
      //mail an author
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $email = get_field('email', $post_id);
          $subject = 'Neuer Teilnehmer - ( Brandis Community )';

          $msgBody = '<p>Zu Ihrem Vorschlag ›'.$post->post_content.'‹ hat sich ein neuer Teilnehmer eingetragen: </p>  ';
          $msgBody .= '<p>Name: '.$participant.'</p> ';
          if((isset($participant_email) && $participant_email !='') || (isset($participant_number) && $participant_number !='')){
              if(isset($participant_email) && $participant_email !='')
                  $msgBody .= '<p>Email: '.$participant_email.'</p> ';
              if(isset($participant_number) && $participant_number !='')
                $msgBody .= '<p>Telefon: '.$participant_number.'</p> ';
          }else{
              $msgBody .= '<p>Der Teilnehmer hat leider keine Kontaktdaten hinterlassen</p> ';
          }
          
          //$msgBody .= '<p>Ansehen auf '.get_bloginfo('url').'</p> ';

          wp_mail( $email, $subject , $msgBody, $headers );
        
   }else{
       $out['error'] = "J";
       $out['msg'] = "Fehler beim Speichern des Teilnehmers!?";
   }
    echo json_encode($out);
    wp_die();
}

add_action( 'wp_ajax_savePost', 'savePost' );
add_action( 'wp_ajax_nopriv_savePost', 'savePost' );

function savePost() {
   
    $out = [];
    $out['error'] = "N";
   
   $txt = strip_tags($_POST['txt']);
   $author = strip_tags($_POST['author']);
   $wo = strip_tags($_POST['wo']);
   $wann = strip_tags($_POST['wann']);
   $email = strip_tags($_POST['email']);
  // $categories = $_POST['tags'];
   $tags = explode(",",$_POST['tags']);
  //die(var_dump($tags)); 
  $metaArr = array('autor' => $author, 'wo' => $wo, 'wann' => $wann, 'email' => $email); 
   // Create a new post
   	$post = array(
   		'post_type'     => 'post', // Your post type ( post, page, custom post type )
   		'post_status'   => 'publish', // (publish, draft, private, etc.)
   		'post_title'    => 'Beitrag von '.$author,
   		'post_content'  => $txt, 
   		'meta_input' => $metaArr,
   		'post_category' => $tags
   		
   	);
	

    	$post_id = wp_insert_post( $post );
    	$post = get_post($post_id);
  
     
 $c = 0;
  if(!empty($_FILES["bild"])){

      $files = $_FILES["bild"];  
      foreach ($files['name'] as $key => $value) {            
              if ($files['name'][$key]) { 
                    $file = array( 
                     'name' => $files['name'][$key],
                     'type' => $files['type'][$key], 
                     'tmp_name' => $files['tmp_name'][$key], 
                     'error' => $files['error'][$key],
                     'size' => $files['size'][$key]
                    ); 
                  $_FILES = array ("bild" => $file); 
     
 
                foreach ($_FILES as $file => $array) {              
 
 
                 require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                 require_once(ABSPATH . "wp-admin" . '/includes/file.php');
                 require_once(ABSPATH . "wp-admin" . '/includes/media.php');
 
 
                 $attach_id = media_handle_upload( $file, $post->ID );
     
                  if ($attach_id > 0){
                       update_post_meta($post->ID,'_thumbnail_id',$attach_id);
                       set_post_thumbnail( $post->ID, $attach_id );
                   }
     
 
                }
   
            } 
  }
 }
 
//mail an Admin verschicken
// $headers = array('Content-Type: text/html; charset=UTF-8');
// $admin_mail = get_bloginfo('admin_email');
// $subject = 'Neuer Beitrag der Brandis Community';
//   
// $msgBody = '<p>Beitrag von '.$author.' ( '.$email.' ) </p>  ';
// $msgBody .= '<p>'.$txt.'</p> ';
// $msgBody .= '<p>Wo: '.$wo.'</p> ';
// $msgBody .= '<p>Wann: '.$wann.'</p> ';
// $msgBody .= '<p>Ansehen auf '.get_bloginfo('url').'</p> ';
//      
// wp_mail( $admin_mail, $subject , $msgBody );
// 
// //mail an Abonnenten
// $args = array('role' => 'subscriber');
// $subscribers = get_users( $args ); 
// foreach($subscribers as $u){ 
//     wp_mail( $u->user_email, $subject , $msgBody, $headers );
// }
  if($post_id){
      
      $out['error'] = "N";
      $out['newPostID'] = $post_id;
     
      
 }else{
     $out['error'] = "J";
     $out['msg'] = "Fehler beim Speichern des Kommentars!?";
 }
  //echo var_dump($post);
  
  // hier nun  an Mit-Mach-Stadt versenden
 //   $link = get_bloginfo('url');  
 //   
 //    $body = '<p>Vorschlag von <a href="mailto:'.$email.'">'.$author.'</a>:</p>';
 //    $body .= '<p>'.$txt.'</p>';
 //    $body .= '<p>Wo: '.$wo;
 //    $body .= '<br>Wann: '.$wann.'</p>';
 //    $body .= '<p><a href="'.$link.'">-> Auf der Community Plattform ansehen</a> </p>';
 //
 //
 //    $authcode = md5('Brandis-2017');
 //    $data = array(
 //        'title'     => 'Beitrag aus der Community Plattform',
 //         'txt'      => $body,
 //         'author'   => 8,   //BenutzerId Community Plattform
 //         'authcode' => $authcode
 //
 //    );
 //
 //
 //    $handle = curl_init('http://brandis.community-infrastructuring.org/wp-admin/admin-ajax.php?action=api-call');
 //    curl_setopt($handle, CURLOPT_POST, true);
 //    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
 //    curl_setopt ($handle, CURLOPT_RETURNTRANSFER, 1);
 //
 //    curl_exec($handle);
 //    curl_close ($handle);
    
   
    echo json_encode($out);
    wp_die();
}





add_action( 'wp_ajax_saveRating', 'saveRating' );
add_action( 'wp_ajax_nopriv_saveRating', 'saveRating' );

function saveRating() {
   
    $out = [];
    $out['error'] = "N";
   
    $post_id = $_POST['postID'];
    $userAgent = $_POST['userAgent'];
    $rating = $_POST['rating'];
    
  if($post_id){
      // if(isset($_COOKIE["bc-rated"]))
      //      die("hat schon");
       $field_key = "field_57e233d99503d";
       $value = get_field($field_key,  $post_id);
       $value[] = array("bewertung" => $rating, "ip" => md5($_SERVER['REMOTE_ADDR']));
       update_field($field_key, $value,  $post_id );
       
       //setcookie("bc-rated",$rating,time()+8640000);
       
  // die("hier::".get_field('bewertung',   $post_id));
       $out['error'] = "N";
       $out['bewertung'] = $rating;
       
      
       
  }else{
      $out['error'] = "J";
      $out['msg'] = "Fehler beim Speichern des Ratings!?";
  }
    //echo var_dump($post);
    echo json_encode($out);
    wp_die();
}


function parseLinks($text) {
    $regex = '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#';
    return preg_replace_callback($regex, function ($matches) {
        return '<a href="'.$matches[0].'">'.$matches[0].'</a>';
    }, $text);
    
}


  register_nav_menus( array(
    'primary' => __( 'Primary Menu',      'bCommunity' ),
    'social'  => __( 'Social Links Menu', 'bCommunity' ),
  ) );



/**************************************************************************** ttt ****/

?>