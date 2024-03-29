<?php
// $Id: flickr.admin.inc,v 1.1.2.1 2009/01/30 10:39:01 paulbooker Exp $

/**
 * Implementation of hook_settings
 */
function flickr_admin_settings() {
  $form['flickr_api_key'] = array(
    '#type' => 'textfield',
    '#title' => t('API Key'),
    '#required' => TRUE,
    '#default_value' => variable_get('flickr_api_key', ''),
    '#description' => t('API Key from Flickr'),
  );
  $form['flickr_api_secret'] = array(
    '#type' => 'textfield',
    '#title' => t('API Shared Secret'),
    '#required' => TRUE,
    '#default_value' => variable_get('flickr_api_secret', ''),
    '#description' => t("API key's secret from Flickr."),
  );
  $form['flickr_default_userid'] = array(
    '#type' => 'textfield',
    '#title' => t('Default Flickr User Id'),
    '#default_value' => variable_get('flickr_default_userid', ''),
    '#description' => t("An, optional, default Flickr username or user id. This will be used when no user is specified."),
  );
  $times = array(900, 1800, 2700, 3600, 7200, 10800, 14400, 18000, 21600, 43200, 86400);
  $ageoptions = drupal_map_assoc($times, 'format_interval');
  $form['flickr_cache_duration'] = array(
    '#type' => 'select',
    '#title' => t('Update interval'),
    '#options' => $ageoptions,
    '#default_value' => variable_get('flickr_cache_duration', 3600),
    '#description' => t("The refresh interval indicating how often you want to check cached Flickr API calls are up to date."),
  );

  // we need an api key before we can verify usernames
  if (!$form['flickr_api_key']['#default_value']) {
    $form['flickr_default_userid']['#disabled'] = TRUE;
    $form['flickr_default_userid']['#description'] .= t(" Disabled until a valid API Key is set.");
  }

  return system_settings_form($form);
}

function flickr_admin_settings_validate($form, &$form_state) {
  $key = trim($form_state['values']['flickr_api_key']);
  $sec = trim($form_state['values']['flickr_api_secret']);
  $uid = trim($form_state['values']['flickr_default_userid']);

  if ($key && (preg_match('/^[A-Fa-f\d]{32}$/', $key) != 1)) {
    form_set_error('flickr_api_key', t('This does not appear to be a Flickr API key.'));
  }
  if ($sec && (preg_match('/^[A-Fa-f\d]{16}$/', $sec) != 1)) {
    form_set_error('flickr_api_secret', t('This does not appear to be a Flickr API secret.'));
  }
  if ($uid) {
    if (flickr_is_nsid($uid)) {
      // it's already a uid
    }
    else {
      $user = flickr_user_find_by_username($uid);
      if (!$user) {
        form_set_error('flickr_default_userid', t('%uid is not a Flickr user id and it does not appear to be a valid user name.', array('%uid' => $uid)));
      }
    }
  }
}

function flickr_admin_settings_submit($form, &$form_state) {
  // clean up the data ...
  $form_state['values']['flickr_api_key'] = trim($form_state['values']['flickr_api_key']);
  $form_state['values']['flickr_api_secret'] = trim($form_state['values']['flickr_api_secret']);
  $form_state['values']['flickr_default_userid'] = trim($form_state['values']['flickr_default_userid']);

  // ... replace the usernames with a user id ...
  if (!flickr_is_nsid($form_state['values']['flickr_default_userid'])) {
    $username = $form_state['values']['flickr_default_userid'];
    if ($user = flickr_user_find_by_username($username)) {
      drupal_set_message(t("The Flickr username %username has been replaced with the corresponding user id %uid.", array('%username' => $form_state['values']['flickr_default_userid'], '%uid' => $user['id'])));
      $form_state['values']['flickr_default_userid'] = $user['id'];
    }
  }

  // ... and save the settings
  system_settings_form_submit($form, $form_state);
}

