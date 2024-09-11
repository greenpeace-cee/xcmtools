<?php

require_once 'xcmtools.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function xcmtools_civicrm_config(&$config) {
  _xcmtools_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function xcmtools_civicrm_install() {
  _xcmtools_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function xcmtools_civicrm_enable() {
  _xcmtools_civix_civicrm_enable();
}

/**
 * Register XCM API Wrappers
 *
 * @param $wrappers
 * @param $apiRequest
 */
function xcmtools_civicrm_apiWrappers(&$wrappers, $apiRequest) {
  if ($apiRequest['entity'] == 'Contact' && $apiRequest['action'] == 'getorcreate') {
    $wrappers = array_merge($wrappers, CRM_Xcmtools_Utils::getApiWrappers());
  }
}
