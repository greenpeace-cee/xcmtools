<?php

namespace Civi\Xcmtools\ApiWrapper;

use Civi\Api4\Email;

class EmailLocationType implements \API_Wrapper {

  public function fromApiInput($apiRequest) {
    return $apiRequest;
  }

  /**
   * Change location type of any email passed to XCM to the XCM default
   */
  public function toApiOutput($apiRequest, $result) {
    if (!empty($result['id']) && !empty($apiRequest['params']['email'])) {
      $config = \CRM_Xcm_Configuration::getConfigProfile($apiRequest['params']['xcm_profile'] ?? NULL);
      Email::update()
        ->addWhere('contact_id', '=', $result['id'])
        ->addWhere('email', '=', $apiRequest['params']['email'])
        ->addValue('location_type_id', $config->defaultLocationType())
        ->setCheckPermissions(FALSE)
        ->execute();
    }
    return $result;
  }

}
