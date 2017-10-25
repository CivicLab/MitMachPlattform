<?php if (post_password_required()) { return; } ?>
<?php if (have_comments()) : ?>
	<div id="comments">
		<h4 id="comments-title">Comments</h4>		
		<ol class="comment-list">
			<?php wp_list_comments(array('type' => 'comment', 'callback' => 'crawford_comment')); ?>
		</ol>
		<div class="page-links"><?php paginate_comments_links(); ?></div> 
		<?php if (!comments_open()) : ?>
			<p class="no-comments"><?php _e('Comments are closed.', 'crawford'); ?></p>
		<?php endif; ?>
	</div>	
<?php endif; ?>	
<?php 
$fields = array(	
	'comment_notes_before' => '<p class="comment-notes">' . __('Your email address will not be published / Required fields are marked *', 'crawford') . '</p>',		
	'fields' => apply_filters( 'comment_form_default_fields', array(		
		'author' => '<label for="author">Name</label><input id="author" name="author" type="text" placeholder="' . __('Name*','crawford') . '" value="' . esc_attr($commenter['comment_author']) . '" />',			
		'email' => '<label for="email">Email</label><input id="email" name="email" type="text" placeholder="' . __('Email*','crawford') . '" value="' . esc_attr($commenter['comment_author_email']) . '" />',
		'url' => '<label for="url">Website</label><input id="url" name="url" type="text" placeholder="' . __('Website','crawford') . '" value="' . esc_attr($commenter['comment_author_url'] ) . '" />')
	),
);	
comment_form($fields); 
?>