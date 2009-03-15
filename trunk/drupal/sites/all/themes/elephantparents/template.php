<?php
// $Id: template.php,v 1.16 2007/10/11 09:51:29 goba Exp $
// Dup of Garland 11/2008 with Drupal 6.4

drupal_add_css(path_to_theme().'/includes/elephant-parents.css');
drupal_add_js(path_to_theme().'/includes/elephant-parents.js');


function phptemplate_preprocess(&$variables, $hook) 
{
	global $user;
	if($hook == 'page' && strpos(drupal_set_header(), '404 Not Found') !== false) 
	{ // set a more useful error message and enable blocks
		drupal_set_message('The page you requested can not be found. Please recheck the address (<tt>url</tt>) and try again.');
		$variables['show_blocks'] = $variables['show_blocks'] ? $variables['show_blocks'] : true; // !! not getting a hook on this
		$html = '<h3>Sorry!</h3> <p>We were unable to find the page you were looking for. Please try browsing '. l('popular content', 'popular') .' or '. l('searching', 'search') .' for a specific page with the form below.</p>';
		$html .= drupal_get_form('search_form') .'<p>&nbsp;</p><p>For other help, please '. l('contact Elephant Parents', 'contact') .'.</p>';
		$variables['content'] = $html;
	}
	
	if($hook == 'page' && strpos(drupal_set_header(), '403 Forbidden') !== false) 
	{ // provide access information
		drupal_set_message('Access denied for the resource or path given.');
		$html = '<h3>Sorry!</h3> <p>Access is not allowed to the page your requested. Some sections of our site can only be visited by administrators, trusted columnists or community professionals.</p>';
		$html .= '<p>To upgrade your account or request special permissions, please fill out our '. l('request form', 'contact/get-professional-status') .'.</p>';
		$html .= empty($user->uid) ? '<p>Existing users may sign-in below or '. l('register', 'user/register') .' for an account now.' : ''; 
		$html .= empty($user->uid) ? drupal_get_form('user_login') : '';
		$html .= '<p>&nbsp;</p><p>For other help, please '. l('contact Elephant Parents', 'contact') .'.</p>';
		$variables['content'] = $html;
	}

	if(isset($variables['node']) && $variables['node']->type == 'question') 
	{ // fill comment variable for our template. hide comments for some users.  http://drupal.org/node/122240#comment-272809
		$variables['title'] = 'Q/A: '. $variables['title'];
		if($hook == 'node') {
			if(!$user->uid) {
				$variables['node']->comment = 0;
				$html = '<div class="ep-comments-notice"><p>Some sections of our website require special permissions. To view question answers or participate in the discussion, you will have to '. l('register for an account', 'user/register') .'</p><p><em>This question has '. (int)$variables['node']->comment_count .' answers or replys posted online.</em><p></div>';
				$variables['content'] = $variables['content'] . $html;
			} 
			else {
				$variables['comments'] = comment_render($variables['node']); 
				$variables['comment_form'] = drupal_get_form('comment_form', array('nid' => $variables['node']->nid));
			}
		}
	}
	
	if($hook == 'page' && arg(0) == 'taxonomy') 
	{ // http://drupal.org/node/191959
		$variables['title'] = 'Articles Related to: '. $variables['title'];
		$variables['term_description'] = $vars['vocab_description'] = '';  //create template vars
		$a2 = arg(2);  
		if(is_numeric($a2)) {
			$r = db_query(db_rewrite_sql("SELECT vid, description FROM {term_data} td WHERE td.tid = %d"), $a2);
			$this_term = db_fetch_object($r);
			$variables['term_description'] = $this_term->description;
			
			$r = db_query(db_rewrite_sql("SELECT description FROM {vocabulary} WHERE vid = %d LIMIT 1"), $this_term->vid);
			$this_vocab = db_fetch_object($r);
			$variables['vocab_description'] = $this_vocab->description;
		}
	}
}

/**
 * Sets the body-tag class attribute.
 * Adds 'sidebar-left', 'sidebar-right' or 'sidebars' classes as needed.
 */
function phptemplate_body_class($left, $right) {
	if ($left != '' && $right != '') {
		$class = 'sidebars';
	}
	else {
		if ($left != '') {
			$class = 'sidebar-left';
		}
		if ($right != '') {
			$class = 'sidebar-right';
		}
	}
	if (isset($class)) {
		print ' class="'. $class .'"';
	}
}

/**
 * Generates IE CSS links for LTR and RTL languages.
 */
function phptemplate_get_ie_styles() {
	global $language;
	$iecss = '<link type="text/css" rel="stylesheet" media="all" href="'. base_path() . path_to_theme() .'/fix-ie.css" />';
	if (defined('LANGUAGE_RTL') && $language->direction == LANGUAGE_RTL) {
		$iecss .= '<style type="text/css" media="all">@import "'. base_path() . path_to_theme() .'/fix-ie-rtl.css";</style>';
	}
	return $iecss;
}

/**
 * Override or insert PHPTemplate variables into the templates.
 */
function phptemplate_preprocess_page(&$vars) {
	$vars['tabs2'] = menu_secondary_local_tasks();
	// Hook into color.module
	if (module_exists('color')) {
		_color_page_alter($vars);
	}
}

/**
 * Return a themed breadcrumb trail.
 */
function phptemplate_breadcrumb($breadcrumb) {
	if (!empty($breadcrumb)) {
		return '<div class="breadcrumb">'. implode(' › ', $breadcrumb) .'</div>';
	}
}
/**
 * Return a themed link or list of links.
 */
function phptemplate_links($links, $attributes = array('class' => 'links')) {
	return theme_links($links, $attributes = array('class' => 'links'));
}

function phptemplate_comment_submitted($comment) {
	return t('!datetime — !username',
		array(
			'!username' => theme('username', $comment),
			'!datetime' => format_date($comment->timestamp)
		));
}
function phptemplate_node_submitted($node) {
	return t('!datetime — !username',
		array(
			'!username' => theme('username', $node),
			'!datetime' => format_date($node->created),
		));
}


/**
 * Allow themable wrapping of all comments.
 */
function phptemplate_comment_wrapper($content, $node) {
	global $user;
	if (!$content || $node->type == 'forum') {
		return '<div id="comments">'. $content .'</div>';
	}
	if($node->type == 'question') {
		return '<div id="comments"><h1 class="comments">'. t('Question Comments & Answers') .'</h1>'. $content .'</div>';	
	}
	return '<div id="comments"><h2 class="comments">'. t('Comments') .'</h2>'. $content .'</div>';
}
/**
 * Hide the node comment box for most user-roles
 */
function phptemplate_box($title = '', $content = '', $region = 'main') {
	// hide the page comment form for non-admins, anyone can reply to a comment
	if($title == 'Post new comment') {
		if(!user_access('administer comments') && !(arg(0) == 'comment' && arg(1) == 'reply')) { 
			return ''; 
		}
		$title = 'Submit a New Answer or Comment';
	}
	return '<h2 class="title">'. $title .'</h2><div>'. $content .'</div>';
}

/*
 * Allow resizing of user images for pages. 
 * Adapted from http://2bits.com/articles/different-size-user-pictures-avatars-different-pages.html
 */
function phptemplate_user_picture($account, $size = array('height'=>120,'width'=>120))
{
	// show larger image on page
	if(arg(0) == 'user' && arg(2) == '') {
		$size = array('height'=>400,'width'=>400);
	}
	
	$new_image = variable_get('user_picture_default', '');
	if($account->picture && file_exists($account->picture)) 
	{
		$new_image = $account->picture;
		$info = image_get_info($account->picture);
		// resize the image
		if($info['width'] > $size['width'] && $info['height'] > $size['height']) {
			$new_image = dirname($account->picture) . '/picture-' . $account->uid .'.'. $size['width'] .'x'. $size['height'] .'.'. $info['extension'];
		}
		if(!file_exists($new_image) || (filectime($new_image) < filectime($account->picture))) {
			image_scale($account->picture, $new_image, $size['width'], $size['height']);
			$new_image = file_create_url($new_image);
		}
	}
	if($new_image) 
	{
		$name = $account->name ? $account->name : variable_get('anonymous', 'Anonymous');
		$alt = t('View @name\'s profile.', array('@name'=>$name));
		$image = theme('image', $new_image, $alt);
		if(!empty($account->uid) && user_access('access user profiles')) {
			$image = l($image, 'user/'. $account->uid, array('html'=>true, 'attributes'=>array('title'=>$alt)));
		}
		return "<div class=\"ep-avatar user-picture\">$image</div>";
	}
} 






/**
 * Returns the rendered local tasks. The default implementation renders
 * them as tabs. Overridden to split the secondary tasks.
 */
function phptemplate_menu_local_tasks() {
	return menu_primary_local_tasks();
}

/**
 * Hide static menue items for people without permissions
 */
function phptemplate_menu_item($link, $has_children, $menu = '', $in_active_trail = FALSE, $extra_class = NULL) {
	// hide these links
	if(stripos($link, 'node/add/grouplanding') && !user_access('create grouplanding content')) {
		return; 
	}	
	// return default themeing 
	$class = ($menu ? 'expanded' : ($has_children ? 'collapsed' : 'leaf'));
	if (!empty($extra_class)) {
		$class .= ' '. $extra_class;
	}
	if ($in_active_trail) {
		$class .= ' active-trail';
	}
	return '<li class="'. $class .'">'. $link . $menu ."</li>\n";
}

function phptemplate_form_alter(&$form, $form_state, $form_id) {
}

// quick hack for no function bug. http://drupal.org/node/261902
if(!function_exists('array_diff_key')) 
{
	function array_diff_key() {
		foreach (func_get_args() as $array) {
			foreach (array_shift(func_get_args()) as $key => $v) {
				if (array_key_exists($key, $array)) { 
					unset($result[$key]); }
			}
		}
		return $result;
	}
}


// quick hack for block with recent unanswered questions
function ep_recent_qquestions()
{
	$q = "SELECT n.* from {node} n WHERE n.status = 1 and n.comment = 0 and n.type = 'question' ORDER BY n.sticky desc, n.changed desc"; // need to order comments in the rollup
	$r = db_query_range(db_rewrite_sql($q), null, 0, 10);
	while($data = db_fetch_object($r)) 
	{
		$n = node_load($data->nid);
		$html = '<div class="title">'. l($n->title, 'node/'. $n->nid) .' <span class="date">'. format_date($n->changed, 'small') .'</span></div>';
		$html .= '<div class="teaser" style="display:none">'. node_teaser($n->teaser) .' '. theme_more_link('node/'. $n->uid, 'Read more of this post') .'</div>'; 
		$list[] = $html;
	}
	$js = "
ep.ep_recent_qquestions = function() 
{
	$('#ep_recent_qquestions .title a').each(function()
	{
		var link = $(this).attr('href');
		var info = $(this).parent('.title').next('.teaser');
		$(this).click(function() {
			$(info).slideToggle('slow');
			$(info).click(function() { document.location = link; });
			return false;
		});
	});
}
$(document).ready(function() { ep.ep_recent_qquestions(); });
";
	drupal_add_js($js, 'inline');
	return '<div id="ep_recent_qquestions">'. theme('item_list', $list) .'<div class="more-link">'. l('Archive', 'questions-answers') .'</div></div>';
}


function ep_recent_qanswers()
{
	global $user;
	$q = "SELECT n.*, u.*, c.* from {node} n
JOIN {comments} c on c.nid = n.nid
JOIN {users} u on u.uid = c.uid
JOIN {users_roles} r on r.uid = u.uid
WHERE n.status = 1 and n.type = 'question' and r.rid in (3,4,5,6)
GROUP BY n.nid
ORDER BY n.sticky desc, c.timestamp desc, n.changed desc"; // need to order comments in the rollup
	$r = db_query_range(db_rewrite_sql($q), null, 0, 6);
	while($data = db_fetch_object($r)) 
	{
		$n = node_load($data->nid);
		$u = user_load($data->uid);
		$html = $u->picture ? theme_image($u->picture, '', $u->name.'\'s Picture', array('height'=>100,'width'=>100), false) : '';
		$html .= '<div class="title">'. l($n->title, 'node/'. $n->nid) .'</div>';
		if($user->uid) {
			$html .= '<div class="teaser answer">'. node_teaser($data->comment) .'</div>'; 
		}
		else {
			$html .= '<div class="teaser">'. node_teaser($n->teaser) .'</div>'; 
			$html .= '<span>'. l('Upgrade', 'contact/get-professional-status') .' or '. l('register', 'user/register') .' to view the latest answer.</span>';
		}
		$html .= '<div class="trailer">Answer Submitted By: '. l($u->name, 'user/'. $u->uid) .'</div>';
		$list[] = $html;
	}
	return '<div id="ep_recent_qanswers">'. theme('item_list', $list) .'<div class="more-link">'. l('Archive', 'questions-answers') .'</div></div>';
}

// Block for author information on node pages
function ep_author_badge_block() 
{
	if(arg(0) != 'node' || !is_numeric(arg(1))) {
		return;
	}
	$node = node_load(arg(1));
	
	// profile information
	$profile = array();
	$u = user_load($node->uid);
	$profile[] = $u->picture ? theme_image($u->picture, '', $u->name .'\'s Picture', array('height'=>150,'width'=>150), false) : '';
	$profile[] = check_markup($u->profile_biography);
	$profile[] = $u->profile_freeform ? 'Interest areas: '. l($u->profile_freeform, 'professional-search') : '';  
	$profile[] = '<em>Contact or learn more about '. l($u->name, 'user/'. $u->uid) .'</em>';  
	$profile = theme_box('About '.$u->name, implode("\n<p>", $profile));
	
	// blog posts
	$blogs = array();
	$q = "SELECT n.* FROM {node} n WHERE n.status=1 AND n.type='blog' AND n.uid=%d ORDER BY n.sticky desc, n.changed desc, n.created desc"; 
	$r = db_query_range(db_rewrite_sql($q), $node->uid, 0, 10);
	while($d = db_fetch_object($r)) 
	{
		$blogs[] = l($d->title, 'node/'.$d->nid, array('alt' => $d->title));
	}
	$blogs = count($blogs) ? theme_item_list($blogs, 'My Blog Posts') : '';
	
	// other posts
	$posts = array();
	$q = "SELECT n.* FROM {node} n WHERE n.status=1 AND n.type NOT IN ('blog', 'question') AND n.uid=%d ORDER BY n.sticky desc, n.changed desc, n.created desc"; 
	$r = db_query_range(db_rewrite_sql($q), $node->uid, 0, 10);
	while($d = db_fetch_object($r)) 
	{
		$posts[] = l($d->title, 'node/'. $d->nid, array('alt' => $d->title)) .' <small><em>'. format_date($d->created, 'small') .', '. (int)$d->comments .' comments</em></small>';
	}
	$posts = count($posts) ? theme_item_list($posts, 'Articles and Other Posts') : '';
	
	// answered questions
	$questions = array();
	$q = "SELECT n.*, c.* FROM {comments} c JOIN {node} n ON c.nid=n.nid WHERE c.status=1 AND n.status=1 AND n.type='question' AND c.uid=%d ORDER BY n.sticky desc, c.timestamp desc"; 
	$r = db_query_range(db_rewrite_sql($q), $node->uid, 0, 5);
	while($d = db_fetch_object($r)) 
	{
		$questions[] = '<strong class="title">'. l($d->subject, 'node/'. $d->nid, array('alt' => 'Re. '. $d->title)) .'</strong> <span class="teaser">'. node_teaser($d->comment, null, 220) .'</span> <span class="more-link"><em>'. format_date($d->timestamp, 'small') .' '. l('Read More', 'node/'. $d->nid) .'</em></span>';
	}
	$questions = count($questions) ? theme_item_list($questions, 'My Question Answers') : '';
	
	return $profile . $blogs . $posts . $questions;
}






