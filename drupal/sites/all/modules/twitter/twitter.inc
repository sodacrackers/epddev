<?php
// $Id: twitter.inc,v 1.3.2.4 2008/09/26 15:59:21 eaton Exp $

/**
 * @file
 * A wrapper API for the Twitter microblogging service.
 *
 * Provides functions for retrieving public and authenticated messages from
 * Twitter, helper code for locally caching Twitter data and account
 * information, and utility functions for associating Drupal site users with
 * their Twitter accounts.
 */


/**
 * Generate a twitter posting form for the given user.
 *
 * @param $account
 *   A Drupal user object.
 */
function twitter_form($account = NULL) {
  drupal_add_js(drupal_get_path('module', 'twitter') .'/twitter.js', 'module');

  if (empty($account)) {
    global $user;
    $account = $user;
  }

  $twitter_accounts = drupal_map_assoc(array_keys(twitter_get_user_accounts($account->uid, TRUE)));
  if (count($twitter_accounts)) {
    $form = array();
    $form['status'] = array(
      '#type' => 'textfield',
      '#id' => 'twitter-textfield',
    );

    if (count($twitter_accounts) > 1) {
      $form['account'] = array(
        '#type' => 'select',
        '#title' => t('Account'),
        '#options' => $twitter_accounts,
        '#access' => user_access(''),
        '#id' => 'twitter-account',
      );
    }
    else {
      $form['account'] = array(
        '#type' => 'value',
        '#value' => array_pop(array_keys($twitter_accounts))
      );
    }
    return $form;
  }
}



/**
 * Twitter API functions
 */

/**
 * Fetch the public timeline for a Twitter.com account.
 *
 * Note that this function only requires a screen name, and not a password. As
 * such, it can only retrieve statuses for publically visible Twitter accounts.
 * Because it doesn't require authentication, it is also easier on the Twitter
 * servers. Be kind, use this version whenever you can.
 *
 * @param $screen_name
 *   The screen name of a Twitter.com user.
 * @param $filter_since
 *   A boolean indicating that Twitter should only return statuses that have not
 *   been locally cached. This incurs an extra database hit, to retrieve the date
 *   of the most recent locally cached twitter message for the screen name.
 * @param $cache
 *   A boolean indicating whether the statuses should be cached in the local
 *   site's database after they're retrieved.
 * @return
 *   An array of Twitter statuses.
 *
 * @see twitter_fetch_statuses()
 */
function twitter_fetch_timeline($screen_name, $filter_since = TRUE, $cache = TRUE) {
  if ($filter_since) {
    $sql  = "SELECT t.created_at FROM {twitter} t WHERE t.screen_name = '%s' ORDER BY t.created_at DESC";
    $since = db_result(db_query($sql, $screen_name));
  }

  $url = "http://twitter.com/statuses/user_timeline/$screen_name.xml";

  if (!empty($since)) {
    $url .= '?since='. urlencode($since);
  }

  $results = drupal_http_request($url, array(), 'GET');
  if (_twitter_request_failure($results)) {
    return array();
  }
  else {
    $results = _twitter_convert_xml_to_array($results->data);
    if ($cache) {
      foreach($results as $status) {
        twitter_cache_status($status);
      }
      twitter_touch_account($screen_name);
    }
    return $results;
  }
}


/**
 * Post a message to a Twitter.com account.
 *
 * @param $screen_name
 *   The screen name of a Twitter.com user.
 * @param $password
 *   The password of a Twitter.com user.
 * @param $text
 *   The text to post. Strings longer than 140 characters will be truncated by
 *   Twitter.
 * @param $source
 *   A string indicating the program or site used to post the message. Source
 *   strings should be registered with Twitter, as unrecgonized sources are
 *   ignored.
 * @return
 *   The full results of the Drupal HTTP request, including the HTTP response
 *   code returned by Twitter.com.
 */
function twitter_set_status($screen_name, $password, $text, $source = 'drupal') {
  $url = "http://twitter.com/statuses/update.xml";

  $headers = array('Authorization' => 'Basic '. base64_encode($screen_name .':'. $password),
                   'Content-type' => 'application/x-www-form-urlencoded');
  $data = 'status='. urlencode($text);
  if (!empty($source)) {
    $data .= "&source=". urlencode($source);
  }

  return drupal_http_request($url, $headers, 'POST', $data);
}

/**
 * Fetch the full information for a Twitter.com account.
 *
 * This function requires an authenticated connection for the account in
 * question.
 *
 * @param $screen_name
 *   The screen name of a Twitter.com user.
 * @param $password
 *   The password of a Twitter.com user.
 * @param $cache
 *   A boolean indicating whether the account info should be cached in the local
 *   site's database after it's retrieved.
 * @return
 *   An single Twitter account.
 */
function twitter_fetch_account_info($screen_name, $password, $cache = TRUE) {
  $url = "http://twitter.com/users/show/$screen_name.xml";
  $headers = array('Authorization' => 'Basic '. base64_encode($screen_name .':'. $password),
                   'Content-type' => 'application/x-www-form-urlencoded');
  $results = drupal_http_request($url, $headers, 'GET');

  if (_twitter_request_failure($results)) {
    return array();
  }

  if ($results = _twitter_convert_xml_to_array($results->data)) {
    if ($cache) {
      foreach($results as $user) {
        twitter_cache_account($user);
      }
    }
    return $results[0];
  }
  return array();
}

/**
 * Fetch the latest statuses for a Twitter.com account, regardless of privacy.
 *
 * This function is the authenticated version of twitter_fetch_timeline(), and
 * is the only way to retrieve statuses for a 'private' account.
 *
 * @param $screen_name
 *   The screen name of a Twitter.com user.
 * @param $password
 *   The password of a Twitter.com user.
 * @param $cache
 *   A boolean indicating whether the statuses should be cached in the local
 *   site's database after they're retrieved.
 * @return
 *   An array of Twitter statuses.
 *
 * @see twitter_fetch_timeline()
 */
function twitter_fetch_statuses($screen_name, $password, $cache = TRUE) {
  $url = "http://twitter.com/statuses/$screen_name.xml";
  $headers = array('Authorization' => 'Basic '. base64_encode($screen_name .':'. $password),
                   'Content-type' => 'application/x-www-form-urlencoded');

  $results = drupal_http_request($url, $headers, 'GET');
  if (_twitter_request_failure($results)) {
    return array();
  }
  $results = _twitter_convert_xml_to_array($results->data);

  if ($cache && !empty($results)) {
    foreach($results as $status) {
      twitter_cache_status($status);
    }
    twitter_touch_account($screen_name);
  }
  return $results;
}

/**
 * Fetch information about Twitter.com accounts followed by a given user.
 *
 * This function does not require authentication. It is mostly useful for mining
 * information about connections, and locating existing Twitter friends who have
 * signed up for the same Drupal site.
 *
 * @param $screen_name
 *   The screen name of a Twitter.com user.
 * @return
 *   An array of Twitter accounts.
 *
 * @see twitter_fetch_followers()
 */
function twitter_fetch_friends($screen_name) {
  $url = "http://twitter.com/statuses/friends/$screen_name.xml";
  $results = drupal_http_request($url, array(), 'GET');
  if (_twitter_request_failure($results)) {
    return array();
  }
  return _twitter_convert_xml_to_array($results->data);
}

/**
 * Fetch information about users following a given Twitter.com account.
 *
 * This function is mostly useful for mining information about connections, and
 * locating existing Twitter friends who have also signed up for the same Drupal
 * site.
 *
 * @param $screen_name
 *   The screen name of a Twitter.com user.
 * @param $password
 *   The password of a Twitter.com user.
 * @return
 *   An array of Twitter accounts.
 *
 * @see twitter_fetch_friends()
 */
function twitter_fetch_followers($screen_name, $password) {
  $url = "http://twitter.com/statuses/followers/$screen_name.xml";
  $headers = array('Authorization' => 'Basic '. base64_encode($screen_name .':'. $password),
                   'Content-type' => 'application/x-www-form-urlencoded');
  $results = drupal_http_request($url, $headers, 'GET');
  if (_twitter_request_failure($results)) {
    return array();
  }
  return _twitter_convert_xml_to_array($results->data);
}

/**
 * Attempts to authenticate a username/password on Twitter.com.
 *
 * @param $screen_name
 *   The screen name of a Twitter.com user.
 * @param $password
 *   The password of a Twitter.com user.
 * @return
 *   A boolean indicating success or failure.
 */
function twitter_authenticate($screen_name, $password) {
  $url = "http://twitter.com/account/verify_credentials.xml";
  $headers = array('Authorization' => 'Basic '. base64_encode($screen_name .':'. $password),
                   'Content-type' => 'application/x-www-form-urlencoded');
  $results = drupal_http_request($url, $headers, 'GET');
  drupal_http_request('http://twitter.com/account/end_session', $headers, 'GET');
  return ($results->code == '200');
}

/**
 * Internal helper function to deal cleanly with various HTTP response codes.
 */
function _twitter_request_failure($results) {
  switch ($results->code) {
    case '304':
      // Not modified, nothing to do.
      return TRUE;
    case 401:
    case 403:
      // Twitter returns both of these for different classes of auth failure.
      watchdog('twitter', 'Twitter account could not be authenticated.');
      return TRUE;
    case 404:
      // Probably a bogus username.
      watchdog('twitter', 'Twitter account could not be retrieved.');
      return TRUE;
  }
  return FALSE;
}




/**
 * Caching functions
 */


/**
 * Saves Twitter account information to the database.
 *
 * @param $twitter_account
 *   A Twitter user account in array form.
 *
 * @see twitter_touch_account()
 * @see twitter_cache_status()
 */
function twitter_cache_account($twitter_account = array()) {
  db_query("DELETE FROM {twitter_account} WHERE twitter_uid = %d", $twitter_account['twitter_uid']);
  drupal_write_record('twitter_account', $twitter_account);
}


/**
 * Updates the 'last refreshed on' timestamp of a given locally cached Twitter
 * account.
 *
 * @param $screen_name
 *   A Twitter screen name..
 *
 * @see twitter_cache_account()
 * @see twitter_cache_status()
 */
function twitter_touch_account($screen_name = '') {
  db_query("UPDATE {twitter_account} SET last_refresh = %d WHERE screen_name = '%s'", time(), $screen_name);
}


/**
 * Saves Twitter status message to the database.
 *
 * If the $silent parameter is set to TRUE, this function will also notify other
 * modules via hook_twitter_status_update() that a new stauts has been retrieved
 * and saved. This is normally set to FALSE, but may be useful when integrating
 * Twitter into complex workflows.
 *
 * @param $status
 *   A Twitter status updated in array form.
 * @param $silent
 *   A boolean indicating whether hook_twitter_status_update should be fired.
 *
 * @see twitter_touch_account()
 * @see twitter_cache_status()
 */
function twitter_cache_status($status = array(), $silent = FALSE) {
  db_query("DELETE FROM {twitter} WHERE twitter_id = %d", $status['twitter_id']);
  drupal_write_record('twitter', $status);
  if (!$silent) {
    module_invoke_all('twitter_status_update', $status);
  }
}




/**
 * User/account relationship code
 */

function twitter_get_user_accounts($uid, $only_with_passwords = FALSE) {
  $sql = "SELECT ta.*, tu.uid, tu.password, tu.import FROM {twitter_user} tu LEFT JOIN {twitter_account} ta ON (tu.screen_name = ta.screen_name) WHERE tu.uid = %d";
  if ($only_with_passwords) {
    $sql .= " AND tu.password IS NOT NULL";
  }
  $args = array($uid);
  $results = db_query($sql, $args);

  $accounts = array();
  while ($account = db_fetch_array($results)) {
    $accounts[$account['screen_name']] = $account;
  }
  return $accounts;
}

function twitter_user_save($account = array(), $force_import = FALSE) {
  $account += array(
    'screen_name' => '',
    'import'      => 1,
  );

  if (db_result(db_query("SELECT 1 FROM {twitter_user} WHERE uid = %d AND screen_name = '%s'", $account['uid'], $account['screen_name']))) {
    drupal_write_record('twitter_user', $account, array('uid', 'screen_name'));  }
  else {
    drupal_write_record('twitter_user', $account);
  }

  if ($force_import && $account['import']) {
    if (empty($account['protected']) || empty($account['password'])) {
      $statuses = twitter_fetch_timeline($account['screen_name']);
    }
    else {
      twitter_fetch_account_info($account['screen_name'], $account['password']);
      $statuses = twitter_fetch_statuses($account['screen_name'], $account['password']);
    }
    
    if (!empty($statuses)) {
      twitter_cache_account($statuses[0]['account']);
      twitter_touch_account($account['screen_name']);
    }
  }
}

function twitter_user_delete($uid, $screen_name = NULL) {
  $sql = "DELETE FROM {twitter_user} WHERE uid = %d";
  $args = array($uid);
  if (!empty($screen_name)) {
    $sql .= " AND screen_name = '%s'";
    $args[] = $screen_name;
  }
  db_query($sql, $args);
}


/**
 * Internal XML munging code
 */

function _twitter_convert_xml_to_array($data) {
  $results = array();
  $xml = new SimpleXMLElement($data);
  if (!empty($xml->name)) {
    // Top-level user information.
    $results[] = _twitter_convert_user($xml);
    return $results;
  }
  if (!empty($xml->user)) {
    foreach($xml->user as $user) {
      $results[] = _twitter_convert_user($user);
    }
  }
  elseif (!empty($xml->status)) {
    foreach($xml->status as $status) {
      $results[] = _twitter_convert_status($status);
    }
  }
  return $results;
}

function _twitter_convert_status($status) {
  $result = (array)$status;
  $result['twitter_id'] = $result['id'];
  if (!empty($result['user']) && is_object($result['user'])) {
    $result['account'] = _twitter_convert_user($result['user']);
    $result['screen_name'] = $result['account']['screen_name'];
  }
  else {
    $result['screen_name'] = NULL;
  }
  $result['created_time'] = strtotime($result['created_at']);
  return $result;
}

function _twitter_convert_user($user) {
  $result = (array)$user;
  $result['twitter_uid'] = $result['id'];
  if (!empty($result['status']) && is_object($result['status'])) {
    $result['status'] = _twitter_convert_status($result['status']);
  }
  return $result;
}

function _twitter_account_fields($user, $account = array()) {
  $form['uid'] = array(
    '#type' => 'value',
    '#value' => $user->uid,
  );
  $form['screen_name'] = array(
    '#type' => 'textfield',
    '#required' => TRUE,
    '#title' => t('Twitter user name'),
    '#default_value' => $twitter['screen_name'],
  );
  $form['password'] = array(
    '#type' => 'password',
    '#required' => TRUE,
    '#title' => t('Password'),
    '#default_value' => $twitter['password'],
  );

  return $form;
}
