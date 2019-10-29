<?php
// HDComments functions

// replace comment template
function hdcomments_comment_template($comment_template)
{
    global $post;
    if ($post->comment_status == 'open') {
        if (!empty($post->post_password)) {
            if (post_password_required()) {
                return dirname(__FILE__) . '/includes/template_disabled.php';
            }
        }
        return dirname(__FILE__) . '/includes/template.php';
    } else {
        return dirname(__FILE__) . '/includes/template_disabled.php';
    }
}
add_filter("comments_template", "hdcomments_comment_template");

/* Save a comment
------------------------------------------------------- */
function hdcomments_submit()
{
    // sanitize data
    $comment = $_POST['comment'];
    $comment = stripslashes(html_entity_decode($comment));
    $comment = json_decode($comment);

    $comment->postID = intval($comment->postID);
    $comment->parentID = intval($comment->parentID);
    $comment->comment = wp_kses_post($comment->comment);
    $comment->email = sanitize_email($comment->email);
    $comment->name = sanitize_text_field($comment->name);
    $comment->reaction = sanitize_text_field($comment->reaction);

    $author_ID = 0;
    if (is_user_logged_in()) {
        $author_ID = get_current_user_id();
    }

    if ($comment->postID > 0 & $comment->comment != "" && $comment->comment != null && $comment->name != "" & $comment->name != null && $comment->email != "" && $comment->email != null) {
        $commentdata = array(
            'comment_post_ID' => $comment->postID,
            'comment_author' => $comment->name,
            'user_id' => $author_ID,
            'comment_author_email' => $comment->email,
            'comment_content' => $comment->comment,
            'comment_type' => '',
            'comment_parent' => $comment->parentID,
        );
        // create the new comment
        $comment_id = wp_new_comment($commentdata);

        // save custom comment meta
        add_comment_meta($comment_id, 'hdcomments_reaction', $comment->reaction, true);
        add_comment_meta($comment_id, 'hdcomments_score', 0, true);

        // figure out comment status
        $comment_status = wp_get_comment_status($comment_id);
        if ($comment_status === "approved") {
            echo 'Your comment has been submitted. Please refresh the page to view it.'; // replace wth global text
        } else {
            echo 'Your comment has been submitted and is awaiting moderation'; // replace wth global text
        }

    } else {
        echo 'invalid comment';
    }
    die();
}
add_action('wp_ajax_hdcomments_submit', 'hdcomments_submit');
add_action('wp_ajax_nopriv_hdcomments_submit', 'hdcomments_submit');

/* Vote or Die
------------------------------------------------------- */
function hdcomments_submit_vote()
{
    // sanitize data
    $vote = $_POST['data'];
    $vote = stripslashes(html_entity_decode($vote));
    $vote = json_decode($vote);
    $vote->commentID = intval($vote->commentID);
    $comment->updown = sanitize_text_field($vote->updown);
    $hdcomments_score = intval(get_comment_meta($vote->commentID, 'hdcomments_score', true));
    if ($comment->updown === "up") {
        $hdcomments_score = $hdcomments_score + 1;
    } else {
        $hdcomments_score = $hdcomments_score - 1;
    }
    update_comment_meta($vote->commentID, 'hdcomments_score', $hdcomments_score);
    die();
}
add_action('wp_ajax_hdcomments_submit_vote', 'hdcomments_submit_vote');
add_action('wp_ajax_nopriv_hdcomments_submit_vote', 'hdcomments_submit_vote');

/* Save global settings
------------------------------------------------------- */
function hdcomments_save_global_settings()
{
    if (current_user_can('edit_others_pages')) {
        $hdcomments_nonce = sanitize_text_field($_POST['hdcomments_about_options_nonce']);
        if (wp_verify_nonce($hdcomments_nonce, 'hdcomments_about_options_nonce') != false) {
            $hdcomments_option_order = sanitize_text_field($_POST['hdcomments_option_order']);
            $hdcomments_option_nested = intval($_POST['hdcomments_option_nested']);
            $hdcomments_option_attachments = sanitize_text_field($_POST['hdcomments_option_attachments']);
            $hdcomments_option_love = sanitize_text_field($_POST['hdcomments_option_love']);
            $hdcomments_option_reactions = sanitize_text_field($_POST['hdcomments_option_reactions']);
            $hdcomments_option_votes = sanitize_text_field($_POST['hdcomments_option_votes']);
            update_option("comment_order", $hdcomments_option_order);
            update_option("thread_comments", $hdcomments_option_nested);
            update_option("hdcomments_option_attachments", $hdcomments_option_attachments);
            update_option("hdcomments_option_love", $hdcomments_option_love);
            update_option("hdcomments_option_reactions", $hdcomments_option_reactions);
            update_option("hdcomments_option_votes", $hdcomments_option_votes);
        } else {
            echo 'permission denied';
        }
    } else {
        echo 'permission denied';
    }
    die();
}
add_action('wp_ajax_hdcomments_save_global_settings', 'hdcomments_save_global_settings');

/* Anti Spam
------------------------------------------------------- */
function hdcomments_preprocess_new_comment($commentdata)
{
    if (isset($_POST['hdcomments_h']) && $_POST['hdcomments_h'] === "hdcomments") {
        return $commentdata;
    } else {
        die('Go away spam');
    }
}
add_action('preprocess_comment', 'hdcomments_preprocess_new_comment');

function hdcomments_filter_media_comment_status($open, $post_id)
{
    $hdcomments_settings_attachments = sanitize_text_field(get_option("hdcomments_option_attachments"));
    if ($hdcomments_settings_attachments === "yes") {
        $post = get_post($post_id);
        if ($post->post_type == 'attachment') {
            return false;
        }
    }
    return $open;
}
add_filter('comments_open', 'hdcomments_filter_media_comment_status', 10, 2);
