$page = new stdClass();
$page->pid = 'new';
	$page->pid = '1';
	$page->name = 'welcome_page';
	$page->did = '1';
	$page->title = 'Welcome to Elephant Parents';
	$page->access = array();
	$page->path = 'welcome';
	$page->load_flags = 0;
	$page->css_id = 'welcome_page';
	$page->css = '';
	$page->arguments = array();
	$page->relationships = array();
	$page->no_blocks = '0';
	$page->switcher_options = array();
	$page->menu = '0';
	$page->menu_tab = '0';
	$page->menu_tab_weight = '0';
	$page->menu_title = '';
	$page->menu_tab_default = '0';
	$page->menu_tab_default_parent_type = 'tab';
	$page->menu_parent_title = '';
	$page->menu_parent_tab_weight = '0';
$page->contexts = array();
$display = new panels_display();
$display->did = 'new';
$display->layout = 'threecol_33_34_33';
$display->layout_settings = NULL;
$display->panel_settings = array(
	'style' => 'rounded_corners',
	'style_settings' => 
	array(
	),
	'individual' => 0,
	'panel' => 
	array(
		'left' => 
		array(
			'style' => -1,
		),
		'middle' => 
		array(
			'style' => -1,
		),
		'right' => 
		array(
			'style' => -1,
		),
	),
);
$display->content = array();
$display->panels = array();
	$pane = new stdClass();
		$pane->pid = 'new-1';
		$pane->panel = 'left';
		$pane->type = 'views';
		$pane->subtype = 'ep_blogs-block_1';
		$pane->access = array();
		$pane->configuration = array(
			'style' => 'default',
			'override_title' => 0,
			'override_title_text' => '',
			'css_id' => '',
			'css_class' => '',
			'link_to_view' => 0,
			'more_link' => 1,
			'feed_icons' => 0,
			'use_pager' => 0,
			'pager_id' => '',
			'nodes_per_page' => '6',
			'offset' => '0',
			'panel_args' => 0,
			'args' => '',
			'url' => '',
		);
	$display->content['new-1'] = $pane;
	$display->panels['left'][0] = 'new-1';
	$pane = new stdClass();
		$pane->pid = 'new-2';
		$pane->panel = 'left';
		$pane->type = 'views';
		$pane->subtype = 'ep_recentquestions-block_1';
		$pane->access = array();
		$pane->configuration = array(
			'style' => 'default',
			'override_title' => 0,
			'override_title_text' => '',
			'css_id' => '',
			'css_class' => '',
			'link_to_view' => 1,
			'more_link' => 1,
			'feed_icons' => 0,
			'use_pager' => 0,
			'pager_id' => '',
			'nodes_per_page' => '10',
			'offset' => '0',
			'panel_args' => 0,
			'args' => '',
			'url' => '',
		);
	$display->content['new-2'] = $pane;
	$display->panels['left'][1] = 'new-2';
	$pane = new stdClass();
		$pane->pid = 'new-3';
		$pane->panel = 'middle';
		$pane->type = 'views';
		$pane->subtype = 'ep_articlestories-block_1';
		$pane->access = array();
		$pane->configuration = array(
			'style' => 'default',
			'override_title' => 0,
			'override_title_text' => '',
			'css_id' => '',
			'css_class' => '',
			'link_to_view' => 0,
			'more_link' => 0,
			'feed_icons' => 0,
			'use_pager' => 0,
			'pager_id' => '',
			'nodes_per_page' => '6',
			'offset' => '0',
			'panel_args' => 0,
			'args' => '',
			'url' => '',
		);
	$display->content['new-3'] = $pane;
	$display->panels['middle'][0] = 'new-3';
	$pane = new stdClass();
		$pane->pid = 'new-4';
		$pane->panel = 'right';
		$pane->type = 'block';
		$pane->subtype = 'tagadelic-1';
		$pane->access = array();
		$pane->configuration = array(
			'style' => 'default',
			'override_title' => 0,
			'override_title_text' => '',
			'css_id' => '',
			'css_class' => '',
			'module' => 'tagadelic',
			'delta' => '1',
			'block_visibility' => 0,
			'block_settings' => 
			array(
				'tags' => '25',
			),
		);
	$display->content['new-4'] = $pane;
	$display->panels['right'][0] = 'new-4';
	$pane = new stdClass();
		$pane->pid = 'new-5';
		$pane->panel = 'right';
		$pane->type = 'block';
		$pane->subtype = 'search-0';
		$pane->access = array();
		$pane->configuration = array(
			'style' => 'default',
			'override_title' => 1,
			'override_title_text' => 'Search Questions, Pages or Groups',
			'css_id' => '',
			'css_class' => '',
			'module' => 'search',
			'delta' => '0',
			'block_visibility' => 0,
		);
	$display->content['new-5'] = $pane;
	$display->panels['right'][1] = 'new-5';
$page->display = $display;
$page->displays = array();
