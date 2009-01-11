<?php 

$view = new view;
$view->name = 'ep_professionals_maplist';
$view->description = 'EP Professionals Listing';
$view->tag = 'ep';
$view->view_php = '';
$view->base_table = 'users';
$view->is_cacheable = FALSE;
$view->api_version = 2;
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */
$handler = $view->new_display('default', 'Defaults', 'default');
$handler->override_option('fields', array(
  'picture' => array(
    'label' => '',
    'exclude' => 0,
    'id' => 'picture',
    'table' => 'users',
    'field' => 'picture',
    'relationship' => 'none',
  ),
  'name' => array(
    'label' => 'Name',
    'link_to_user' => 1,
    'exclude' => 0,
    'id' => 'name',
    'table' => 'users',
    'field' => 'name',
    'relationship' => 'none',
  ),
  'value' => array(
    'label' => 'Interests and Skills',
    'type' => 'separator',
    'separator' => ', ',
    'empty' => 'N/A',
    'exclude' => 0,
    'id' => 'value',
    'table' => 'profile_values_ep_profile_freeform',
    'field' => 'value',
    'relationship' => 'none',
  ),
  'city' => array(
    'label' => 'City',
    'exclude' => 0,
    'id' => 'city',
    'table' => 'location',
    'field' => 'city',
    'relationship' => 'none',
  ),
));
$handler->override_option('filters', array(
  'status' => array(
    'operator' => '=',
    'value' => '1',
    'group' => '0',
    'exposed' => FALSE,
    'expose' => array(
      'operator' => FALSE,
      'label' => '',
    ),
    'id' => 'status',
    'table' => 'users',
    'field' => 'status',
    'relationship' => 'none',
  ),
  'rid' => array(
    'operator' => 'or',
    'value' => array(
      '3' => '3',
      '4' => '4',
    ),
    'group' => '0',
    'exposed' => FALSE,
    'expose' => array(
      'operator' => FALSE,
      'label' => '',
    ),
    'id' => 'rid',
    'table' => 'users_roles',
    'field' => 'rid',
    'relationship' => 'none',
    'reduce_duplicates' => 0,
  ),
  'value' => array(
    'operator' => 'word',
    'value' => '',
    'group' => '0',
    'exposed' => TRUE,
    'expose' => array(
      'use_operator' => 0,
      'operator' => 'value_op',
      'identifier' => 'keywords',
      'label' => 'Profile Interests and Skills:',
      'optional' => 1,
      'remember' => 0,
    ),
    'case' => 0,
    'id' => 'value',
    'table' => 'profile_values_ep_profile_freeform',
    'field' => 'value',
    'relationship' => 'none',
  ),
  'city' => array(
    'operator' => 'word',
    'value' => '',
    'group' => '0',
    'exposed' => TRUE,
    'expose' => array(
      'use_operator' => 0,
      'operator' => 'city_op',
      'identifier' => 'city',
      'label' => 'Location City:',
      'optional' => 1,
      'remember' => 0,
    ),
    'case' => 0,
    'id' => 'city',
    'table' => 'location',
    'field' => 'city',
    'relationship' => 'none',
    'override' => array(
      'button' => 'Override',
    ),
  ),
  'distance' => array(
    'operator' => 'mbr',
    'value' => array(
      'latitude' => '',
      'longitude' => '',
      'postal_code' => '',
      'country' => '',
      'search_distance' => '',
      'search_units' => 'mile',
    ),
    'group' => '0',
    'exposed' => TRUE,
    'expose' => array(
      'use_operator' => 0,
      'operator' => 'distance_op',
      'identifier' => 'distance',
      'label' => 'Location Distance / Proximity:',
      'optional' => 1,
      'remember' => 0,
    ),
    'type' => 'postal_default',
    'identifier' => 'dist',
    'id' => 'distance',
    'table' => 'location',
    'field' => 'distance',
    'relationship' => 'none',
  ),
));
$handler->override_option('access', array(
  'type' => 'none',
));
$handler->override_option('use_pager', 'mini');
$handler->override_option('style_plugin', 'table');
$handler->override_option('style_options', array(
  'grouping' => '',
  'override' => 1,
  'sticky' => 0,
  'order' => 'asc',
  'columns' => array(
    'picture' => 'picture',
    'name' => 'name',
    'value' => 'value',
    'city' => 'city',
  ),
  'info' => array(
    'picture' => array(
      'sortable' => 1,
      'separator' => '',
    ),
    'name' => array(
      'sortable' => 1,
      'separator' => '',
    ),
    'value' => array(
      'separator' => '',
    ),
    'city' => array(
      'sortable' => 1,
      'separator' => '',
    ),
  ),
  'default' => 'name',
));
$handler = $view->new_display('page', 'Page', 'page_1');
$handler->override_option('path', 'professional-search');
$handler->override_option('menu', array(
  'type' => 'none',
  'title' => '',
  'weight' => 0,
  'name' => 'navigation',
));
$handler->override_option('tab_options', array(
  'type' => 'none',
  'title' => '',
  'weight' => 0,
));
$handler = $view->new_display('block', 'Block', 'block_1');
$handler->override_option('style_plugin', 'gmap');
$handler->override_option('style_options', array(
  'grouping' => '',
  'macro' => '[gmap |id=usermap|center=47.680737,-122.370766|zoom=5|width=100%|height=300px]',
  'datasource' => 'location',
  'markers' => 'static',
  'markertype' => 'small bred',
));
$handler->override_option('block_description', '');
$handler->override_option('block_caching', -1);
