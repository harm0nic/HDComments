<?php
/*
HDComments About / options page
 */

wp_nonce_field('hdcomments_about_options_nonce', 'hdcomments_about_options_nonce');
$hdcomments_settings_love = sanitize_text_field(get_option("hdcomments_option_love"));
$hdcomments_settings_order = sanitize_text_field(get_option("comment_order"));
$hdcomments_settings_nested = intval(get_option("thread_comments"));
$hdcomments_settings_attachments = sanitize_text_field(get_option("hdcomments_option_attachments"));
$hdcomments_settings_reactions = sanitize_text_field(get_option("hdcomments_option_reactions"));
$hdcomments_settings_votes = sanitize_text_field(get_option("hdcomments_option_votes"));

?>
<div id="hdcomments_meta_forms">
	<div id="hdcomments_wrapper">
		<div id="hdcomments_form_wrapper">
			<div class="hdcomments_tab">
				<h1>
					HDComments
				</h1>
				<p>
					HDComments was designed and built to be a super easy system to replace your theme's default comment system.
				</p>
				<p>
					As I continue to develop HDComments for my own needs, more features, options, customizations, and settings will be introduced. If you have enjoyed HDComments (it sure makes <em>my</em> life easier!), then I would appreciate it if you could <a href="https://wordpress.org/support/plugin/hdcomments/reviews/#new-post"
					    target="_blank">leave an honest review</a>. It's the little things that make building systems like this worthwhile.
				</p>
				<br />
				<div id = "hdcomments_message"></div>
				<p style = "text-align:right"><button class = "hdcomments_button2" id = "hdcomments_save_settings">SAVE</button></p>


				<h3>HDComments Settings</h3>
				<div id = "hdcomments_settings">

					<div class="hdcomments_row hdcomments_checkbox">
						<label class="hdcomments_label_title" for="hdcomments_settings_order"> Comment Order - Most recent comment at bottom <span class="hdcomments_tooltip hdcomments_tooltip_question">?<span class="hdcomments_tooltip_content"><span>By default, the most recent comment shows first in the list. Enabling this will reverse the order so that comments appear in the order they are submitted.</span></span></span></label>
						<div class="hdcomments_check_row"><label class="non-block" for="hdcomments_settings_order"></label>
							<div class="hdf-options-check">
								<input type="checkbox" id="hdcomments_settings_order" value="yes" name="hdcomments_settings_order"  <?php if ($hdcomments_settings_order == "asc") {echo 'checked = "true"';}?>>
								<label for="hdcomments_settings_order"></label>
							</div>
						</div>
					</div>
					<div class="hdcomments_row hdcomments_checkbox">
						<label class="hdcomments_label_title" for="hdcomments_settings_attachments"> Disable comments on attachments (media) <span class="hdcomments_tooltip hdcomments_tooltip_question">?<span class="hdcomments_tooltip_content"><span> Most WordPress spam is when bots try to post comments on attachment pages such as images and pdfs. Enable this to add an extra layer of spam protection if you do not want or need attachment comments.</span></span></span></label>
						<div class="hdcomments_check_row"><label class="non-block" for="hdcomments_settings_attachments"></label>
							<div class="hdf-options-check">
								<input type="checkbox" id="hdcomments_settings_attachments" value="yes" name="hdcomments_settings_attachments"  <?php if ($hdcomments_settings_attachments == "yes") {echo 'checked = "true"';}?>>
								<label for="hdcomments_settings_attachments"></label>
							</div>
						</div>
					</div>

					<div class="hdcomments_row hdcomments_checkbox">
						<label class="hdcomments_label_title" for="hdcomments_settings_nested"> Disable Nested Comments <span class="hdcomments_tooltip hdcomments_tooltip_question">?<span class="hdcomments_tooltip_content"><span> Nested comments allows a user to reply directly to another comment.</span></span></span></label>
						<div class="hdcomments_check_row"><label class="non-block" for="hdcomments_settings_nested"></label>
							<div class="hdf-options-check">
								<input type="checkbox" id="hdcomments_settings_nested" value="yes" name="hdcomments_settings_nested"  <?php if ($hdcomments_settings_nested == "0") {echo 'checked = "true"';}?>>
								<label for="hdcomments_settings_nested"></label>
							</div>
						</div>
					</div>

					<div class="hdcomments_row hdcomments_checkbox">
						<label class="hdcomments_label_title" for="hdcomments_settings_love"> I ❤ HDComments <span class="hdcomments_tooltip hdcomments_tooltip_question">?<span class="hdcomments_tooltip_content"><span>Show your love! Enabling this will add a descrete link to the bottom the submit comment form letting users know that the that your site uses HDComments</span></span></span></label>
						<div class="hdcomments_check_row"><label class="non-block" for="hdcomments_settings_love"></label>
							<div class="hdf-options-check">
								<input type="checkbox" id="hdcomments_settings_love" value="yes" name="hdcomments_settings_love"  <?php if ($hdcomments_settings_love == "yes") {echo 'checked = "true"';}?>>
								<label for="hdcomments_settings_love"></label>
							</div>
						</div>
					</div>

					<div class="hdcomments_row hdcomments_checkbox">
						<label class="hdcomments_label_title" for="hdcomments_settings_reactions"> Disable Reactions </label>
						<div class="hdcomments_check_row"><label class="non-block" for="hdcomments_settings_reactions"></label>
							<div class="hdf-options-check">
								<input type="checkbox" id="hdcomments_settings_reactions" value="yes" name="hdcomments_settings_reactions"  <?php if ($hdcomments_settings_reactions == "yes") {echo 'checked = "true"';}?>>
								<label for="hdcomments_settings_reactions"></label>
							</div>
						</div>
					</div>

					<div class="hdcomments_row hdcomments_checkbox">
						<label class="hdcomments_label_title" for="hdcomments_settings_votes"> Disable Votes </label>
						<div class="hdcomments_check_row"><label class="non-block" for="hdcomments_settings_votes"></label>
							<div class="hdf-options-check">
								<input type="checkbox" id="hdcomments_settings_votes" value="yes" name="hdcomments_settings_votes"  <?php if ($hdcomments_settings_votes == "yes") {echo 'checked = "true"';}?>>
								<label for="hdcomments_settings_votes"></label>
							</div>
						</div>
					</div>

				</div>

				<h2>Upcoming Features</h2>
				<p>I am developing HDForms in my spare time, but still plan to add the following features at some point</p>
				<ul class="hdcomments_list">
					<li>Save cookies so that once a vote has been cast, it cannot be recast</li>
					<li> - Make it easier to be GDPR complaint if the above feature is enabled</li>
					<li>[complete] <s>Only show reaction and comment field for logged in users</s></li>
					<li>Translation ready. Sorry! English only for now 🇨🇦</li>
					<li>Enable and disable reactions (and reaction picker)</li>
					<li>[complete] <s>Enable and disable comment votes</s></li>
					<li>Highlight site admin and other authors, not just current author</li>
				</ul>
				<br />



				<br />
				<h2>Other Harmonic Design Plugins</h2>

				<div id = "hdcomments_admin_plugins">
				<?php
$data = wp_remote_get("https://harmonicdesign.ca/plugins/additional_plugins.txt");
if (is_array($data)) {

    $data = $data["body"];
    $data = stripslashes(html_entity_decode($data));
    $data = json_decode($data);

    foreach ($data as $value) {
        $title = sanitize_text_field($value[0]);
        $subtitle = sanitize_text_field($value[1]);
        $image = sanitize_text_field($value[2]);
        $link = sanitize_text_field($value[3]);
        $description = sanitize_text_field($value[4]);
        ?>

										<div class="product">
											<h3><?php echo $title; ?></h3>
											<p class="tagline"><?php echo $subtitle; ?></p>
											<img src="<?php echo $image; ?>" alt="<?php echo $title; ?>">
											<p><?php echo $description; ?></p>
											<p><a href="<?php echo $link; ?>" class="btn">download</a></p>
										</div>


				<?php
}
} else {
    echo '<h2>There was an error loading additional Harmonic Design Plugins.</h2>';
}
?>

			</div>

			</div>
		</div>
	</div>
</div>
