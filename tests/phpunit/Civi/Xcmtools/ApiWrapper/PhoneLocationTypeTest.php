<?php
namespace Civi\Xcmtools\ApiWrapper;

use Civi\Api4\Contact;
use Civi\Api4\Phone;
use Civi\Api4\LocationType;
use Civi\Test;
use Civi\Test\Api3TestTrait;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test behaviour of PhoneLocationType API Wrapper
 *
 * @group headless
 */
class PhoneLocationTypeTest extends TestCase implements HeadlessInterface, HookInterface, TransactionalInterface {
  use Api3TestTrait;

  public function setUpHeadless() {
    return Test::headless()
      ->installMe(__DIR__)
      ->install('de.systopia.xcm')
      ->apply();
  }

  public function setUp() {
    // ensure location_type_id 1 is the default
    LocationType::update()
      ->addWhere('id', '=', 1)
      ->addValue('is_default', 1)
      ->execute();
    // set XCM to match via phone
    $config = \CRM_Xcm_Configuration::getConfigProfile();
    $config->setRules(['CRM_Xcm_Matcher_PhoneMatcher']);
    $options = $config->getOptions();
    $options['default_location_type'] = 2;
    $config->setOptions($options);
    $config->store();
    parent::setUp();
  }

  public function tearDown() {
    parent::tearDown();
  }

  public function testNewContactUsesXcmDefaultLocationType() {
    $contact = $this->callAPISuccess('Contact', 'getorcreate', [
      'first_name' => 'Jane',
      'last_name'  => 'Doe',
      'phone'      => '+436801234567',
    ]);
    $this->assertNotEmpty($contact['id']);
    $this->assertPrimaryPhoneLocationType($contact['id'], 2);
  }

  public function testUpdatedPhoneUsesXcmDefaultLocationType() {
    // create base contact with location_type_id = 3
    $contact = Contact::create()
      ->addValue('first_name', 'John')
      ->addValue('last_name', 'Doe')
      ->setChain([
        'phone' => [
          'Phone', 'create', [
            'values' => [
              'contact_id' => '$id',
              'phone' => '+436801234567',
              'location_type_id' => 3
            ]
          ]
        ],
      ])
      ->execute()
      ->first();
    $this->assertPrimaryPhoneLocationType($contact['id'], 3);

    // now update the contact using XCM
    $xcmContact = $this->callAPISuccess('Contact', 'getorcreate', [
      'first_name' => 'John',
      'last_name'  => 'Doe',
      'phone'      => '+436801234567',
    ]);
    $this->assertEquals(
      $xcmContact['id'],
      $contact['id'],
      'XCM should have matched contact with same phone'
    );
    // location_type_id should now be the XCM default
    $this->assertPrimaryPhoneLocationType($contact['id'], 2);
  }

  /**
   * Assert primary phone location type of $contactId matches $locationTypeId
   *
   * @param $contactId
   * @param $locationTypeId
   *
   * @throws \API_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  private function assertPrimaryPhoneLocationType($contactId, $locationTypeId) {
    $phone = Phone::get()
      ->setSelect([
        'location_type_id',
      ])
      ->addWhere('contact_id', '=', $contactId)
      ->addWhere('is_primary', '=', 1)
      ->execute()
      ->first();
    $this->assertEquals(
      $locationTypeId,
      $phone['location_type_id'],
      'Unexpected location_type_id for phone'
    );
  }

}
