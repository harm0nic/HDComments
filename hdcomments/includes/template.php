<?php
// HDComments template

global $post;
$hdcomments_settings_love = sanitize_text_field(get_option("hdcomments_option_love"));
$hdcomments_settings_reactions = sanitize_text_field(get_option("hdcomments_option_reactions"));
if ($hdcomments_settings_reactions == "" || $hdcomments_settings_reactions == null) {
    $hdcomments_settings_reactions = "no";
}
wp_enqueue_style(
    'hdcomment_style',
    plugin_dir_url(__FILE__) . 'style.css?v=' . HDCOMMENTS_PLUGIN_VERSION
);

wp_enqueue_script(
    'hdcomment_script',
    plugins_url('script.js?v=' . HDCOMMENTS_PLUGIN_VERSION, __FILE__),
    array('jquery'),
    '1.0',
    true
);

wp_localize_script('hdcomment_script', 'hdcomments_ajax', admin_url('admin-ajax.php'));
wp_localize_script('hdcomment_script', 'hdcomments_postID', [$post->ID]);

$hdcomments_name = "";
$hdcomments_email = "";
$hdcomments_userID = 0;
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $hdcomments_name = $current_user->display_name;
    $hdcomments_email = $current_user->user_email;
    $hdcomments_userID = $current_user->ID;
}
?>

<div id="hdcomments">
  <h3 id = "respond">Leave a Reply</h3>
  <div id="hdcomments_create_comment">
    <!-- comment form -->
    <label for="hdcomments_comment_input" class="hdcomments_label">Leave a Comment</label>
    <textarea
      id="hdcomments_comment_input"
      rows="6"
      class="hdcomments_input"
    ></textarea>

    <div id="hdcomments_comment_contact" <?php if ($hdcomments_name != "" && $hdcomments_email != "") {echo 'style = "display:none;"';}?>>
      <div>
        <label for="hdcomments_email_input" class="hdcomments_label">Your Email</label>
        <input
          type="email"
          id="hdcomments_email_input"
          class="hdcomments_input"
		  value = "<?php echo $hdcomments_email; ?>"
        />
      </div>
      <div>
        <label for="hdcomments_name_input" class="hdcomments_label">Your Name</label>
        <input
          type="text"
          id="hdcomments_name_input"
          class="hdcomments_input"
		  value = "<?php echo $hdcomments_name; ?>"
        />
      </div>
    </div>
	<!-- <input type = "hidden" style = "" id = "hdcomments_userID" value = "<?php echo $hdcomments_userID; ?>"/> -->

    <div id="hdcomments_comment_footer" class = "<?php if ($hdcomments_settings_reactions != "no") {echo "hdcomments_no_reactions";}?>">
		<?php
if ($hdcomments_settings_reactions === "no") {
    ?>
      <div id="hdcomments_comment_reactions">
        <h4 id="hdcomments_reaction_title">Reactions</h4>
        <span class="hdcomments_reaction" data-reaction="like">👍</span>
        <span class="hdcomments_reaction" data-reaction="laugh">😆</span>
        <span class="hdcomments_reaction" data-reaction="angry">😠</span>
        <span class="hdcomments_reaction" data-reaction="sad">😢</span>
        <span class="hdcomments_reaction" data-reaction="love">😍</span>
      </div>
		<?php
}
?>
      <button id="hdcomments_submit" disabled="true">Submit</button>
		<?php
if ($hdcomments_settings_love === "yes") {
    echo '<p style = "text-align:right; grid-column: 1 / -1;"><a href = "http://harmonicdesign.ca" title = "HDComments: Elegant WordPress Comments" style = "font-size:0.8em; text-decoration:none;">powered by HDComments</a></p>';
}
?>
    </div>
  </div>
  <div id = "hdcomments_notice"></div>
  <div id="hdcomments_comments">
    <h3>Comments</h3>
	<?php wp_list_comments('callback=hdcomments_print_comments&style=ul');?>
  </div>
</div>



<?php

function hdcomments_print_comments($comment, $args, $depth)
{
    global $post;
    $hdcomments_settings_nested = intval(get_option("thread_comments"));
    $hdcomments_settings_reactions = sanitize_text_field(get_option("hdcomments_option_reactions"));
    if ($hdcomments_settings_reactions == "" || $hdcomments_settings_reactions == null) {
        $hdcomments_settings_reactions = "no";
    }
    $hdcomments_settings_votes = sanitize_text_field(get_option("hdcomments_option_votes"));
    if ($hdcomments_settings_votes == "" || $hdcomments_settings_votes == null) {
        $hdcomments_settings_votes = "no";
    }
    $hdcomments_reaction = sanitize_text_field(get_comment_meta($comment->comment_ID, 'hdcomments_reaction', true));
    if ($hdcomments_reaction == null || $hdcomments_reaction == "") {
        $hdcomments_reaction = "none";
    }
    $hdcomments_score = intval(get_comment_meta($comment->comment_ID, 'hdcomments_score', true));
    if ($hdcomments_score == null || $hdcomments_score == "") {
        $hdcomments_score = 0;
    }

    ?>

    <div class="hdcomment <?php if ($comment->user_id === $post->post_author) {echo 'hdcomment_post_author';}?>" id="hdcomment_<?php echo $comment->comment_ID; ?>">
      <div class="hdcomments_comment">
        <?php
$content = apply_filters('the_content', wp_kses_post($comment->comment_content));
    $content = str_replace(']]>', ']]&gt;', $content);
    echo $content;?>
      </div>
      <div class="hdcomments_meta <?php if ($hdcomments_settings_votes != "no") {echo "hdcomments_no_votes";}?>">
		  <div>
			<?php if ($hdcomments_settings_nested === 1) {?>
			<a href = "#respond" class = "hdcomments_reply_link" data-id = "<?php echo $comment->comment_ID; ?>">reply</a>
			<?php }?>
			<div class="hdcomments_meta_date_author">
			  <?php
$hdcomments_publish_date = get_comment_date("", $comment->comment_ID);
    echo $hdcomments_publish_date;
    //$hdcomments_publish_time = get_comment_date("g:iA", $comment->comment_ID); // keep for time
    ?>
			  <span class="hdcomments_meta_author hdcomments_meta_author_<?php echo $comment->comment_ID; ?>">~<?php echo $comment->comment_author; ?></span>
	<?php
if ($hdcomments_settings_reactions === "no") {
        if ($hdcomments_reaction == "like") {
            echo '👍';
        } else if ($hdcomments_reaction == "laugh") {
            echo '😆';
        } else if ($hdcomments_reaction == "angry") {
            echo '😠';
        } else if ($hdcomments_reaction == "sad") {
            echo '😢';
        } else if ($hdcomments_reaction == "love") {
            echo '😍';
        }
    }
    ?>

			</div>
		  </div>
	<?php
if ($hdcomments_settings_votes === "no") {
        ?>
        <div class="hdcomments_vote">
          <span class="hdcomments_vote_<?php echo $comment->comment_ID; ?>"><?php echo $hdcomments_score; ?></span>
          <div class="hdcomments_vote_options">
            <span class="hdcomments_upvote"
              data-id="<?php echo $comment->comment_ID; ?>">▲</span>
            <span
              class="hdcomments_downvote"
              data-id="<?php echo $comment->comment_ID; ?>"
              >▼</span>
          </div>
        </div>
		  <?php }?>
      </div>
    </div>

<?php
}
?>