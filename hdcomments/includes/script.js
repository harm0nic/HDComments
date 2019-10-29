const HDC_EL = {
  submit: document.getElementById("hdcomments_submit"),
  comment: document.getElementById("hdcomments_comment_input"),
  email: document.getElementById("hdcomments_email_input"),
  name: document.getElementById("hdcomments_name_input"),
  reactions: document.getElementsByClassName("hdcomments_reaction"),
  upvotes: document.getElementsByClassName("hdcomments_upvote"),
  downvotes: document.getElementsByClassName("hdcomments_downvote"),
  notice: document.getElementById("hdcomments_notice"),
  commentCreate: document.getElementById("hdcomments_create_comment"),
  reply: document.getElementsByClassName("hdcomments_reply_link"),
  respond: document.getElementById("respond")
};

const hdcomments_ID = parseInt(hdcomments_postID[0]); // stupid scalar wp_localize_script

let canSubmit = false;
let isSubmitting = false;
let reaction = null;
let commentParent = 0;

function hdcomments_submit() {
  if (canSubmit) {
    let comment = {
      postID: hdcomments_ID,
      parentID: commentParent,
      comment: HDC_EL.comment.value.trim(),
      email: HDC_EL.email.value.trim(),
      name: HDC_EL.name.value.trim(),
      reaction: reaction
    };
    hdcomments_disable_submit();
    isSubmitting = true;
    hdcomments_submit_comment(comment);
  }
}

function hdcomments_submit_comment(comment) {
  let comments = JSON.stringify(comment);
  jQuery.ajax({
    type: "POST",
    data: {
      action: "hdcomments_submit",
      comment: comments,
      hdcomments_h: "hdcomments"
    },
    url: hdcomments_ajax,
    success: function(data) {
      hdcomments_reset_form(data);
    }
  });
}

function hdcomments_submit_vote(commentID, vote) {
  let updown = null;
  if (vote) {
    updown = "up";
  } else {
    updown = "down";
  }
  let data = {
    commentID: parseInt(commentID),
    updown: updown
  };
  data = JSON.stringify(data);
  jQuery.ajax({
    type: "POST",
    data: {
      action: "hdcomments_submit_vote",
      data: data
    },
    url: hdcomments_ajax,
    success: function(data) {
      console.log(data);
    }
  });
}

function hdcomments_reset_form(data) {
  HDC_EL.commentCreate.remove();
  HDC_EL.notice.innerHTML = "<p>" + data + "</p>";
  HDC_EL.notice.style.display = "block";
}

function hdcomments_can_submit() {
  if (!isSubmitting) {
    let comment = HDC_EL.comment.value.trim();
    let email = HDC_EL.email.value.trim();
    let name = HDC_EL.name.value.trim();
    if (comment.length > 9 && email.length > 4 && name.length > 3) {
      if (hdcomments_validate_email_address(email)) {
        HDC_EL.submit.classList.add("hdcomments_submit_enabled");
        HDC_EL.submit.disabled = false;
        canSubmit = true;
      } else {
        Hhdcomments_disable_submit();
      }
    } else {
      hdcomments_disable_submit();
    }
  }
}

function hdcomments_disable_submit() {
  HDC_EL.submit.classList.remove("hdcomments_submit_enabled");
  HDC_EL.submit.disabled = true;
  canSubmit = false;
}

function hdcomments_select_reaction() {
  reaction = this.getAttribute("data-reaction");
  let prev = document.getElementsByClassName("hdcomments_reaction_selected")[0];
  if (prev) {
    prev.classList.remove("hdcomments_reaction_selected");
  }
  this.classList.add("hdcomments_reaction_selected");
}

function hdcomments_vote(el, vote) {
  let commentID = el.getAttribute("data-id");
  let score = document.getElementsByClassName("hdcomments_vote_" + commentID)[0]
    .innerText;
  score = parseInt(score);

  // if first time vote
  if (!el.classList.contains("hdcomments_vote_disabled")) {
    if (vote) {
      score = score + 1;
    } else {
      score = score - 1;
    }

    if (score > 0) {
      score = "+" + score;
    }
    document.getElementsByClassName(
      "hdcomments_vote_" + commentID
    )[0].innerText = score;

    el.classList.add("hdcomments_vote_selected");
    elVotes = document.querySelectorAll(
      "#hdcomment_" + commentID + " .hdcomments_vote_options span"
    );
    for (let i = 0; i < elVotes.length; i++) {
      elVotes[i].classList.add("hdcomments_vote_disabled");
    }
    hdcomments_submit_vote(commentID, vote);
  } else if (el.classList.contains("hdcomments_vote_selected")) {
    // unvote
    if (vote) {
      score = score - 1;
    } else {
      score = score + 1;
    }

    if (score > 0) {
      score = "+" + score;
    }
    document.getElementsByClassName(
      "hdcomments_vote_" + commentID
    )[0].innerText = score;

    el.classList.remove("hdcomments_vote_selected");
    elVotes = document.querySelectorAll(
      "#hdcomment_" + commentID + " .hdcomments_vote_options span"
    );
    for (let i = 0; i < elVotes.length; i++) {
      elVotes[i].classList.remove("hdcomments_vote_disabled");
    }
    hdcomments_submit_vote(commentID, !vote);
  }
}

function hdcomments_reply() {
  commentParent = this.getAttribute("data-id");
  let replyTo = document.getElementsByClassName(
    "hdcomments_meta_author_" + commentParent
  )[0].innerText;
  HDC_EL.respond.innerHTML =
    "Replying to " +
    replyTo +
    '<span class = "hdcomments_remove_reply" id = "hdcomments_remove_reply" title = "cancel reply"></span>';
  document
    .getElementById("hdcomments_remove_reply")
    .addEventListener("click", hdcomments_remove_reply);
}

function hdcomments_remove_reply() {
  HDC_EL.respond.innerHTML = "Leave a Reply";
  commentParent = 0;
}

function hdcomments_set_event_listeners() {
  HDC_EL.submit.addEventListener("click", hdcomments_submit);
  HDC_EL.comment.addEventListener("keyup", hdcomments_can_submit);
  HDC_EL.email.addEventListener("keyup", hdcomments_can_submit);
  HDC_EL.name.addEventListener("keyup", hdcomments_can_submit);
  for (let i = 0; i < HDC_EL.reactions.length; i++) {
    HDC_EL.reactions[i].addEventListener("click", hdcomments_select_reaction);
  }
  for (let i = 0; i < HDC_EL.upvotes.length; i++) {
    HDC_EL.upvotes[i].addEventListener("click", function() {
      hdcomments_vote(this, true);
    });
    HDC_EL.downvotes[i].addEventListener("click", function() {
      hdcomments_vote(this, false);
    });
  }
  for (let i = 0; i < HDC_EL.reply.length; i++) {
    HDC_EL.reply[i].addEventListener("click", hdcomments_reply);
  }
}
hdcomments_set_event_listeners();

function hdcomments_validate_email_address(email) {
  var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
}
