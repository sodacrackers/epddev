<?php
/**
 * Implementation of hook_default_panel_pages()
 */
function foo_default_panel_pages() {
  $page = new stdClass();
    $page->pid = 'new';
    $page->did = 'new';
    $page->name = 'ep_welcome_page';
    $page->title = 'Welcome to Elephant Parents';
    $page->access = array();
    $page->path = 'welcome';
    $page->load_flags = 0;
    $page->css_id = 'ep-welcome';
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
  $display->layout = 'threecol_25_50_25_stacked';
  $display->layout_settings = NULL;
  $display->panel_settings = array(
    'style' => 'rounded_corners',
    'style_settings' => 
    array(
    ),
    'individual' => 0,
    'panel' => 
    array(
      'top' => 
      array(
        'style' => -1,
      ),
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
      'bottom' => 
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
      $pane->type = 'block';
      $pane->shown = '1';
      $pane->subtype = 'menu-primary-links';
      $pane->access = array();
      $pane->configuration = array(
        'style' => 'default',
        'override_title' => 1,
        'override_title_text' => 'Getting Around ',
        'css_id' => '',
        'css_class' => '',
        'module' => 'menu',
        'delta' => 'primary-links',
        'block_visibility' => 0,
      );
      $pane->cache = array();
    $display->content['new-1'] = $pane;
    $display->panels['left'][0] = 'new-1';
    $pane = new stdClass();
      $pane->pid = 'new-2';
      $pane->panel = 'left';
      $pane->type = 'block';
      $pane->shown = '1';
      $pane->subtype = 'user-1';
      $pane->access = array();
      $pane->configuration = array(
        'style' => 'default',
        'override_title' => 0,
        'override_title_text' => '',
        'css_id' => '',
        'css_class' => '',
        'module' => 'user',
        'delta' => '1',
        'block_visibility' => 0,
      );
      $pane->cache = array();
    $display->content['new-2'] = $pane;
    $display->panels['left'][1] = 'new-2';
    $pane = new stdClass();
      $pane->pid = 'new-3';
      $pane->panel = 'left';
      $pane->type = 'block';
      $pane->shown = '1';
      $pane->subtype = 'user-0';
      $pane->access = array();
      $pane->configuration = array(
        'style' => 'default',
        'override_title' => 0,
        'override_title_text' => '',
        'css_id' => '',
        'css_class' => '',
        'module' => 'user',
        'delta' => '0',
        'block_visibility' => 0,
      );
      $pane->cache = array();
    $display->content['new-3'] = $pane;
    $display->panels['left'][2] = 'new-3';
    $pane = new stdClass();
      $pane->pid = 'new-4';
      $pane->panel = 'middle';
      $pane->type = 'views';
      $pane->shown = '1';
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
      $pane->cache = array();
    $display->content['new-4'] = $pane;
    $display->panels['middle'][0] = 'new-4';
    $pane = new stdClass();
      $pane->pid = 'new-5';
      $pane->panel = 'middle';
      $pane->type = 'block';
      $pane->shown = '1';
      $pane->subtype = 'bloginfo-blogroll';
      $pane->access = array();
      $pane->configuration = array(
        'style' => 'default',
        'override_title' => 0,
        'override_title_text' => '',
        'css_id' => '',
        'css_class' => '',
        'module' => 'bloginfo',
        'delta' => 'blogroll',
        'block_visibility' => 0,
      );
      $pane->cache = array();
    $display->content['new-5'] = $pane;
    $display->panels['middle'][1] = 'new-5';
    $pane = new stdClass();
      $pane->pid = 'new-6';
      $pane->panel = 'middle';
      $pane->type = 'views';
      $pane->shown = '1';
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
      $pane->cache = array();
    $display->content['new-6'] = $pane;
    $display->panels['middle'][2] = 'new-6';
    $pane = new stdClass();
      $pane->pid = 'new-7';
      $pane->panel = 'right';
      $pane->type = 'block';
      $pane->shown = '1';
      $pane->subtype = 'tagadelic-1';
      $pane->access = array();
      $pane->configuration = array(
        'style' => 'default',
        'override_title' => 1,
        'override_title_text' => 'Popular Question Topics',
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
      $pane->cache = array();
    $display->content['new-7'] = $pane;
    $display->panels['right'][0] = 'new-7';
    $pane = new stdClass();
      $pane->pid = 'new-8';
      $pane->panel = 'right';
      $pane->type = 'block';
      $pane->shown = '1';
      $pane->subtype = 'search-0';
      $pane->access = array();
      $pane->configuration = array(
        'style' => 'default',
        'override_title' => 1,
        'override_title_text' => '<none>',
        'css_id' => '',
        'css_class' => '',
        'module' => 'search',
        'delta' => '0',
        'block_visibility' => 0,
      );
      $pane->cache = array();
    $display->content['new-8'] = $pane;
    $display->panels['right'][1] = 'new-8';
    $pane = new stdClass();
      $pane->pid = 'new-9';
      $pane->panel = 'right';
      $pane->type = 'block';
      $pane->shown = '1';
      $pane->subtype = 'user-3';
      $pane->access = array();
      $pane->configuration = array(
        'style' => 'default',
        'override_title' => 0,
        'override_title_text' => '',
        'css_id' => '',
        'css_class' => '',
        'module' => 'user',
        'delta' => '3',
        'block_visibility' => 0,
        'block_settings' => 
        array(
          'user_block_seconds_online' => '900',
          'user_block_max_list_count' => '10',
        ),
      );
      $pane->cache = array();
    $display->content['new-9'] = $pane;
    $display->panels['right'][2] = 'new-9';
    $pane = new stdClass();
      $pane->pid = 'new-10';
      $pane->panel = 'top';
      $pane->type = 'views';
      $pane->shown = '1';
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
      $pane->cache = array();
    $display->content['new-10'] = $pane;
    $display->panels['top'][0] = 'new-10';
  $page->display = $display;
  $page->displays = array();
  $pages['ep_welcome_page'] = $page;


  $page = new stdClass();
    $page->pid = 'new';
    $page->did = 'new';
    $page->name = 'ep_community_page';
    $page->title = 'Communities';
    $page->access = array();
    $page->path = 'community';
    $page->load_flags = 0;
    $page->css_id = 'ep-community';
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
  $display->layout = 'twocol_stacked';
  $display->layout_settings = NULL;
  $display->panel_settings = array(
    'style' => 'rounded_corners',
    'style_settings' => 
    array(
    ),
    'individual' => 0,
    'panel' => 
    array(
      'top' => 
      array(
        'style' => -1,
      ),
      'left' => 
      array(
        'style' => -1,
      ),
      'right' => 
      array(
        'style' => -1,
      ),
      'bottom' => 
      array(
        'style' => -1,
      ),
    ),
  );
  $display->content = array();
  $display->panels = array();
    $pane = new stdClass();
      $pane->pid = 'new-1';
      $pane->panel = 'bottom';
      $pane->type = 'views';
      $pane->shown = '1';
      $pane->subtype = 'og_mytracker-block_1';
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
        'nodes_per_page' => '25',
        'offset' => '0',
        'panel_args' => 0,
        'args' => '',
        'url' => '',
      );
      $pane->cache = array();
    $display->content['new-1'] = $pane;
    $display->panels['bottom'][0] = 'new-1';
    $pane = new stdClass();
      $pane->pid = 'new-2';
      $pane->panel = 'left';
      $pane->type = 'views';
      $pane->shown = '1';
      $pane->subtype = 'ep_blogs_description-block_1';
      $pane->access = array();
      $pane->configuration = array(
        'style' => 'default',
        'override_title' => 1,
        'override_title_text' => 'Blogs and Columnists',
        'css_id' => '',
        'css_class' => '',
        'link_to_view' => 0,
        'more_link' => 1,
        'feed_icons' => 0,
        'use_pager' => 0,
        'pager_id' => '',
        'nodes_per_page' => '4',
        'offset' => '0',
        'panel_args' => 0,
        'args' => '',
        'url' => '',
      );
      $pane->cache = array();
    $display->content['new-2'] = $pane;
    $display->panels['left'][0] = 'new-2';
    $pane = new stdClass();
      $pane->pid = 'new-3';
      $pane->panel = 'left';
      $pane->type = 'block';
      $pane->shown = '1';
      $pane->subtype = 'poll-0';
      $pane->access = array();
      $pane->configuration = array(
        'style' => 'default',
        'override_title' => 1,
        'override_title_text' => 'Most Recent Poll',
        'css_id' => '',
        'css_class' => '',
        'module' => 'poll',
        'delta' => '0',
        'block_visibility' => 0,
      );
      $pane->cache = array();
    $display->content['new-3'] = $pane;
    $display->panels['left'][1] = 'new-3';
    $pane = new stdClass();
      $pane->pid = 'new-4';
      $pane->panel = 'left';
      $pane->type = 'views';
      $pane->shown = '1';
      $pane->subtype = 'popular-block';
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
        'nodes_per_page' => '5',
        'offset' => '0',
        'panel_args' => 0,
        'args' => '',
        'url' => '',
      );
      $pane->cache = array();
    $display->content['new-4'] = $pane;
    $display->panels['left'][2] = 'new-4';
    $pane = new stdClass();
      $pane->pid = 'new-5';
      $pane->panel = 'right';
      $pane->type = 'views';
      $pane->shown = '1';
      $pane->subtype = 'ep_group_list-block_1';
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
        'nodes_per_page' => '12',
        'offset' => '0',
        'panel_args' => 0,
        'args' => '',
        'url' => '',
      );
      $pane->cache = array();
    $display->content['new-5'] = $pane;
    $display->panels['right'][0] = 'new-5';
    $pane = new stdClass();
      $pane->pid = 'new-6';
      $pane->panel = 'right';
      $pane->type = 'block';
      $pane->shown = '1';
      $pane->subtype = 'og_views-0';
      $pane->access = array();
      $pane->configuration = array(
        'style' => 'default',
        'override_title' => 0,
        'override_title_text' => '',
        'css_id' => '',
        'css_class' => '',
        'module' => 'og_views',
        'delta' => '0',
        'block_visibility' => 0,
      );
      $pane->cache = array();
    $display->content['new-6'] = $pane;
    $display->panels['right'][1] = 'new-6';
    $pane = new stdClass();
      $pane->pid = 'new-7';
      $pane->panel = 'right';
      $pane->type = 'views';
      $pane->shown = '1';
      $pane->subtype = 'og_recent_type_term-default';
      $pane->access = array();
      $pane->configuration = array(
        'context' => 
        array(
          0 => 'empty',
          1 => 'empty',
        ),
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
        'nodes_per_page' => '10',
        'offset' => '0',
        'panel_args' => 0,
        'args' => '',
        'url' => '',
      );
      $pane->cache = array();
    $display->content['new-7'] = $pane;
    $display->panels['right'][2] = 'new-7';
  $page->display = $display;
  $page->displays = array();
  $pages['ep_community_page'] = $page;


  return $pages;
}


