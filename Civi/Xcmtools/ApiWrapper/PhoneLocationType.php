<?php

namespace Civi\Xcmtools\ApiWrapper;

use Civi\Api4\Phone;

class PhoneLocationType implements \API_Wrapper {

  public function fromApiInput($apiRequest) {
    return $apiRequest;
  }

  /**
   * Change location type of any phone passed to XCM to the XCM default
   */
  public function toApiOutput($apiRequest, $result) {
    if (empty($result['id'])) {
      return $result;
    }
    foreach (['phone', 'phone2'] as $phoneKey) {
      if (empty($apiRequest['params'][$phoneKey])) {
        continue;
      }
      $config = \CRM_Xcm_Configuration::getConfigProfile($apiRequest['params']['xcm_profile'] ?? NULL);
      $normalizedPhone = $this->normalizePhone($apiRequest['params'][$phoneKey]);
      Phone::update()
        ->addWhere('contact_id', '=', $result['id'])
        ->addWhere('phone', '=', $normalizedPhone)
        ->addValue('location_type_id', $config->defaultLocationType())
        ->setCheckPermissions(FALSE)
        ->execute();
    }
    return $result;
  }

  /**
   * Normalize phone number using com.cividesk.normalize (if installed)
   *
   * @param $phone
   *
   * @return string
   */
  private function normalizePhone($phone) {
    if (class_exists('CRM_Utils_Normalize')) {
      $normalizedPhone = ['phone' => $phone];
      $normalize = \CRM_Utils_Normalize::singleton();
      if ($normalize->normalize_phone($normalizedPhone)) {
        $phone = $normalizedPhone['phone'];
      }
    }
    return $phone;
  }

}
