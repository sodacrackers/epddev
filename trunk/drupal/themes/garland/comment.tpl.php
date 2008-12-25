<?php 
 
$professional_comment = false;
$author = user_load($comment->uid); //wasn't getting a hook on $comment->user_roles so looking up user
foreach(array('auth professionals', 'content manager', 'site administrator') as $role)
{
	if(in_array($role, $author->roles)) {
		$professional_comment = true;
	}
}
?>
<div class="comment<?php print ($comment->new) ? ' comment-new' : ''; print ' '. $status; print ' '. $zebra; ?>">
  <?php if($professional_comment): ?>
    <div class="ep-auth-professional"><img src="/sites/all/themes/elephantparents/images/thumbs-up.png" /> Submitted by an Authorized Professional</div>
  <?php endif; ?>
  <div class="clear-block">
  <?php if ($submitted): ?>
    <span class="submitted"><?php print $submitted; ?></span>
  <?php endif; ?>

  <?php if ($comment->new) : ?>
    <span class="new"><?php print drupal_ucfirst($new) ?></span>
  <?php endif; ?>

  <?php print $picture ?>

    <h3><?php print $title ?></h3>

    <div class="content">
      <?php print $content ?>
      <?php if ($signature): ?>
      <div class="clear-block">
        <div>—</div>
        <?php print $signature ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($links): ?>
    <div class="links"><?php print $links ?></div>
  <?php endif; ?>
</div>

<?
/*
stdClass Object
(
    [cid] => 3
    [pid] => 0
    [nid] => 14
    [subject] => test comment two
text text
    [comment] => 

test comment two
text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text text.


    [format] => 1
    [timestamp] => 1226796939
    [name] => admin
    [mail] => 
    [homepage] => 
    [uid] => 1
    [registered_name] => admin
    [signature] => 
    [picture] => 
    [data] => a:8:{s:8:"og_email";s:1:"2";s:7:"contact";i:0;s:13:"form_build_id";s:37:"form-241298549757a57269e1b2898bbab1c8";s:27:"notifications_send_interval";s:1:"0";s:18:"notifications_auto";i:0;s:17:"messaging_default";s:4:"mail";s:14:"picture_delete";s:0:"";s:14:"picture_upload";s:0:"";}
    [thread] => 02/
    [status] => 0
    [og_email] => 2
    [contact] => 0
    [form_build_id] => form-241298549757a57269e1b2898bbab1c8
    [notifications_send_interval] => 0
    [notifications_auto] => 0
    [messaging_default] => mail
    [picture_delete] => 
    [picture_upload] => 
    [depth] => 0
    [new] => 0
)

stdClass Object
(
    [cid] => 4
    [pid] => 0
    [nid] => 14
    [subject] => Comment Three by Auth user.
    [comment] => <p>Comment Three by Auth user. string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string string.</p>

    [format] => 1
    [timestamp] => 1226799442
    [name] => EP Auth Prof
    [mail] => 
    [homepage] => 
    [uid] => 114
    [registered_name] => EP Auth Prof
    [signature] => 
    [picture] => 
    [data] => a:11:{s:10:"user_roles";s:1:"3";s:13:"form_build_id";s:37:"form-a34aa2bd59e64e693b0a81a5c2dcd67e";s:7:"contact";i:1;s:17:"messaging_default";s:5:"debug";s:27:"notifications_send_interval";s:1:"0";s:18:"notifications_auto";i:0;s:5:"Title";s:34:"EA Auth Professional Personal Blog";s:11:"Description";s:162:"My blog description includes this text. Some of it <strong>bold text</strong> and even http://yahoo.com links automatically parsed. Thanks for visiting my site.  ";s:6:"format";s:1:"1";s:14:"picture_delete";s:0:"";s:14:"picture_upload";s:0:"";}
    [thread] => 03/
    [status] => 0
    [user_roles] => 3
    [form_build_id] => form-a34aa2bd59e64e693b0a81a5c2dcd67e
    [contact] => 1
    [messaging_default] => debug
    [notifications_send_interval] => 0
    [notifications_auto] => 0
    [Title] => EA Auth Professional Personal Blog
    [Description] => My blog description includes this text. Some of it <strong>bold text</strong> and even http://yahoo.com links automatically parsed. Thanks for visiting my site.  
    [picture_delete] => 
    [picture_upload] => 
    [depth] => 0
    [new] => 0
)

*/