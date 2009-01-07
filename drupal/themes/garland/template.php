<?php
// $Id: template.php,v 1.16 2007/10/11 09:51:29 goba Exp $
// Dup of Garland 11/2008 with Drupal 6.4

drupal_add_css(path_to_theme().'/includes/elephant-parents.css');
drupal_add_js(path_to_theme().'/includes/jquery-accordion/jquery.accordion.js'); //jquery.accordion.min.js');
drupal_add_js(path_to_theme().'/includes/elephant-parents.js');


function phptemplate_preprocess(&$variables, $hook) 
{
	global $user;
	// give questions a friendly title and preproc comments below
	if(($hook == 'node' || $hook == 'page') && $variables['node']->type == 'question') {
		$variables['title'] = 'Q/A: '. $variables['title'];
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
 * Return a themed breadcrumb trail.
 */
function phptemplate_breadcrumb($breadcrumb) {
	if (!empty($breadcrumb)) {
		return '<div class="breadcrumb">'. implode(' › ', $breadcrumb) .'</div>';
	}
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
	// collect comments for themeing, unset them in the preprocess_node
	// http://drupal.org/node/161139 //http://drupal.org/node/122240#comment-272809
}

function phptemplate_preprocess_node(&$vars) {
	// $vars['node']->comment = 0;
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
	if (!$content || $node->type == 'forum') {
		return '<div id="comments">'. $content .'</div>';
	}
	if($node->type == 'question') {
		return '<div id="comments"><h2 class="comments">'. t('Question Comments & Answers') .'</h2>'. $content .'</div>';	
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

