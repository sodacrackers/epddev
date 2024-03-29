<?php
// $Id: amazon.views.inc,v 1.1.2.2 2008/11/06 08:29:45 eaton Exp $
/**
 * @file
 * Provide views data and handlers for amazon.module
 */

/**
 * @defgroup views_amazon_module amazon.module handlers
 *
 * Includes the tables 'amazon_item', 'amazon_item_participant',
 * 'amazon_item_image', 'amazon_item_editorial_review', and 'amazon_item_node'.
 * @{
 */

/**
 * Implementation of hook_views_plugins
 */
function amazon_views_plugins() {
  return array(
    'module' => 'amazon', // This just tells our themes are elsewhere.
    'row' => array(
      'amazon_item' => array(
        'title' => t('Amazon item'),
        'help' => t('Display the item as a fully themed Amazon product.'),
        'handler' => 'amazon_views_plugin_row_amazon_view',
        'theme' => 'amazon_views_view_row_item',
        'theme file' => 'amazon.theme.inc',
        'theme path' => drupal_get_path('module', 'amazon'),
        'base' => array('amazon_item'),
        'uses options' => TRUE,
        'type' => 'normal',
        'parent' => 'views_plugin_row',
      ),
    ),
  );
}

/**
 * Implementation of hook_views_handlers()
 */
function amazon_views_handlers() {
  return array(
    'info' => array(
      'path' => drupal_get_path('module', 'amazon') . '/includes',
    ),
    'handlers' => array(
      'views_handler_field_amazon_image' => array(
        'parent' => 'views_handler_field',
      ),
      'views_handler_field_amazon_participant' => array(
        'parent' => 'views_handler_field_prerender_list',
      ),
      'views_handler_field_amazon_title' => array(
        'parent' => 'views_handler_field',
      ),
      'views_handler_filter_amazon_node_module' => array(
        'parent' => 'views_handler_filter_in_operator',
      ),
    ),
  );
}



function amazon_views_data() {
  // Basic table information.

  // ----------------------------------------------------------------
  // Core amazon_item table

  // Define the base group of this table. Fields that don't
  // have a group defined will go into this field by default.
  $data['amazon_item']['table']['group']  = t('Amazon');

  // Advertise this table as a possible base table
  $data['amazon_item']['table']['base'] = array(
    'field' => 'asin',
    'title' => t('Amazon item'),
    'help' => t('The Amazon item type allows views of Amazon products saved on your site.'),
    'weight' => 10,
  );

  // ----------------------------------------------------------------
  // Fields
  _amazon_make_simple_text_field($data, 'amazon_item', 'asin', 'ASIN', 'The product ID of the item.');

  // title
  $data['amazon_item']['title'] = array(
    'title' => t('Title'), // The item it appears as on the UI,
    'help' => t('The name of the product.'), // The help that appears on the UI,
     // Information for displaying a title as a field
    'field' => array(
      'field' => 'title', // the real field
      'handler' => 'views_handler_field_amazon_title',
      'click sortable' => TRUE,
     ),
  );

  // detailpageurl
  $data['amazon_item']['detailpageurl'] = array(
    'title' => t('Detail page URL'), // The item it appears as on the UI,
    'help' => t("The URL of the product's page on Amazon.com."), // The help that appears on the UI,
     // Information for displaying a title as a field
    'field' => array(
      'field' => 'detailpageurl', // the real field
      'handler' => 'views_handler_field',
      'click sortable' => FALSE,
     ),
  );

  _amazon_make_simple_number_field($data, 'amazon_item', 'salesrank', 'Sales rank', 'The current sales ranking of the product on Amazon.com.');
  _amazon_make_simple_text_field($data, 'amazon_item', 'publisher', 'Publisher', 'The publisher of the product.');
  _amazon_make_simple_text_field($data, 'amazon_item', 'manufacturer', 'Manufacturer', 'The manufacturer of the product.');
  _amazon_make_simple_text_field($data, 'amazon_item', 'mpn', "Part number", "The Manufacturer's own part number for the product.");
  _amazon_make_simple_text_field($data, 'amazon_item', 'studio', 'Studio', 'The name of the studio that published the product.');
  _amazon_make_simple_text_field($data, 'amazon_item', 'label', 'Label', 'The name of the label that published the product.');
  _amazon_make_simple_text_field($data, 'amazon_item', 'binding', 'Binding', 'The style of binding used for the product.');
  _amazon_make_simple_text_field($data, 'amazon_item', 'productgroup', 'Product group', 'The Amazon grouping the product is categorized in.');
  _amazon_make_simple_text_field($data, 'amazon_item', 'producttypename', 'Product type name', 'The Amazon internal product-type code for the product.');

  // We probably want to convert this to a real date field.
  _amazon_make_simple_text_field($data, 'amazon_item', 'releasedate', 'Release date', 'The release date of the product.');
  _amazon_make_simple_text_field($data, 'amazon_item', 'listpriceformattedprice', 'List price', 'The current list price of the item. (Unformatted)');
  unset($data['amazon_item']['listpriceformattedprice']['argument']);

  // Define the base group of this table. Fields that don't
  // have a group defined will go into this field by default.
  $data['amazon_item_participant']['table']['group']  = t('Amazon');

  $data['amazon_item_participant']['table']['join'] = array(
    // participant links to amazon_item directly via asin.
    'amazon_item' => array(
      'left_field' => 'asin',
      'field' => 'asin',
    ),
  );

  _amazon_make_simple_text_field($data, 'amazon_item_participant', 'participant', 'Participant name', 'The name of an individual who participated in the creation of a product.');
  _amazon_make_simple_text_field($data, 'amazon_item_participant', 'type', 'Participant role', 'The role the participant had in creating the product (author, artist, etc).');
  //$data['amazon_item_participant']['participant']['argument']['handler'] = 'views_handler_argument_many_to_one';
  unset($data['amazon_item_participant']['type']['argument']);

  // participants_all
  $data['amazon_item_participant']['participants_all'] = array(
    'title' => t('All participants'), // The item it appears as on the UI,
    'help' => t("The names of all individuals who participated in the creation of a product, grouped as a single field."), // The help that appears on the UI,
     // Information for displaying a title as a field
    'field' => array(
      'field' => 'participant', // the real field
      'handler' => 'views_handler_field_amazon_participant',
      'click sortable' => FALSE,
     ),
  );


  // Define the base group of this table. Fields that don't
  // have a group defined will go into this field by default.
  $data['amazon_item_image']['table']['group']  = t('Amazon');

  $data['amazon_item_image']['table']['join'] = array(
    // participant links to amazon_item directly via asin.
    'amazon_item' => array(
      'left_field' => 'asin',
      'field' => 'asin',
    ),
  );

  // image
  $data['amazon_item_image']['product_image'] = array(
    'title' => t('Product image'), // The item it appears as on the UI,
    'help' => t("A photo of the Amazon product in question."), // The help that appears on the UI,
     // Information for displaying a title as a field
    'field' => array(
      'field' => 'url', // the real field
      'handler' => 'views_handler_field_amazon_image',
      'click sortable' => FALSE,
     ),
  );

  return $data;
}


function _amazon_make_simple_text_field(&$data, $table, $column, $title, $help = '') {
  $data[$table][$column] = array(
    'title' => t($title), // The item it appears as on the UI,
    'help' => t($help), // The help that appears on the UI,
     // Information for displaying a title as a field
    'field' => array(
      'field' => $column, // the real field
      'handler' => 'views_handler_field',
      'click sortable' => TRUE,
     ),
    'argument' => array('handler' => 'views_handler_argument_string'),
    'filter' => array('handler' => 'views_handler_filter_string'),
    'sort' => array('handler' => 'views_handler_sort'),
  );
}

function _amazon_make_simple_number_field(&$data, $table, $column, $title, $help = '') {
  $data[$table][$column] = array(
    'title' => t($title), // The item it appears as on the UI,
    'help' => t($help), // The help that appears on the UI,
     // Information for displaying a title as a field
    'field' => array(
      'field' => $column, // the real field
      'handler' => 'views_handler_field_numeric',
      'click sortable' => TRUE,
     ),
    'filter' => array('handler' => 'views_handler_filter_numeric'),
    'sort' => array('handler' => 'views_handler_sort'),
  );
}



/**
 * @}
 */
