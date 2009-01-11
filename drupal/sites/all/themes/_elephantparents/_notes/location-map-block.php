<?php
if (arg(0)=='user' && is_numeric(arg(1))) {
   $block = array();
    $result = db_fetch_array(db_query("SELECT latitude, longitude FROM {location} WHERE eid = %d AND type = 'user'", arg(1)));
    if (!$result) {
      return;
    }
    $macro = variable_get('gmap_location_block_macro_'. $node->type, FALSE);
    if (!$macro) {
      $macro = variable_get('gmap_location_block_macro', '[gmap |zoom=10 |width=100% |height=200px |control=Small |type=Map]');
    }
    $map = array(
      '#map' => 'gmap_location_authorblock',
      '#settings' => gmap_parse_macro($macro),
    );
    $map['#settings']['behavior']['notype'] = TRUE;
    $map['#settings']['markers'] = array();
    $map['#settings']['markers'][] = array(
      'latitude' => $result['latitude'],
      'longitude' => $result['longitude'],
      'markername' => variable_get('gmap_user_map_marker', 'drupal'),
      'label' => check_plain($node->name),
    );
    $map['#settings']['latitude'] = $result['latitude'];
    $map['#settings']['longitude'] = $result['longitude'];

    print theme('gmap', $map);
}
