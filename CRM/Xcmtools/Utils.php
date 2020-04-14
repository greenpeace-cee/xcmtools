<?php

class CRM_Xcmtools_Utils {

  /**
   * Get instances of all API Wrappers
   *
   * @todo load dynamically
   */
  public static function getApiWrappers() {
    return [
      new Civi\Xcmtools\ApiWrapper\EmailLocationType(),
      new Civi\Xcmtools\ApiWrapper\PhoneLocationType(),
    ];
  }

}
