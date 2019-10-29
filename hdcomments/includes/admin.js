/* Settings page save options clicked
------------------------------------------------------- */
jQuery("#hdcomments_save_settings").click(function() {
  jQuery("#hdcomments_save_settings").fadeOut(150);

  let hdcomments_order = "";
  let hdcomments_nested = "";
  let hdcomments_love = "";
  let hdcomments_attachments = "";
  let hdcomments_reactions = "";
  let hdcomments_votes = "";

  if (jQuery("#hdcomments_settings_order").prop("checked") == true) {
    hdcomments_order = "asc";
  } else {
    hdcomments_order = "desc";
  }
  if (jQuery("#hdcomments_settings_nested").prop("checked") == true) {
    hdcomments_nested = 0;
  } else {
    hdcomments_nested = 1;
  }
  if (jQuery("#hdcomments_settings_attachments").prop("checked") == true) {
    hdcomments_attachments = "yes";
  } else {
    hdcomments_attachments = "no";
  }
  if (jQuery("#hdcomments_settings_love").prop("checked") == true) {
    hdcomments_love = "yes";
  } else {
    hdcomments_love = "no";
  }
  if (jQuery("#hdcomments_settings_reactions").prop("checked") == true) {
    hdcomments_reactions = "yes";
  } else {
    hdcomments_reactions = "no";
  }
  if (jQuery("#hdcomments_settings_votes").prop("checked") == true) {
    hdcomments_votes = "yes";
  } else {
    hdcomments_votes = "no";
  }

  jQuery.ajax({
    type: "POST",
    data: {
      action: "hdcomments_save_global_settings",
      hdcomments_option_order: hdcomments_order,
      hdcomments_option_nested: hdcomments_nested,
      hdcomments_option_attachments: hdcomments_attachments,
      hdcomments_option_love: hdcomments_love,
      hdcomments_option_reactions: hdcomments_reactions,
      hdcomments_option_votes: hdcomments_votes,
      hdcomments_about_options_nonce: jQuery(
        "#hdcomments_about_options_nonce"
      ).val()
    },
    url: ajaxurl,
    success: function(data) {
      console.log(data);
      if (data == "permission denied") {
        let data =
          "<p>Permission denied. Please refresh the page and try again</p>";
        jQuery("#hdcomments_message").html(data);
        jQuery("#hdcomments_message").fadeIn();
      } else {
        let data = "<p>HDComments settings have been saved</p>";
        jQuery("#hdcomments_message").html(data);
        jQuery("#hdcomments_message").fadeIn();
      }
    },
    error: function() {
      let data =
        "<p>Permission denied. Please refresh the page and try again</p>";
      jQuery("#hdcomments_message").html(data);
      jQuery("#hdcomments_message").fadeIn();
    },
    complete: function() {
      jQuery("#hdcomments_save_settings").fadeIn(500);
    }
  });
});
