<?php
/**
 * @file
 * Contains Drupal\Tests\interswitch_donate\Unit\LeftPadTest;
 */

namespace Drupal\Tests\interswitch_donate\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\interswitch_donate\Services;

/**
 * Class LeftPadTest
 * @package Drupal\Tests\interswitch_donate\Unit
 *
 * Tests for Drupal\interswitch_donate\Services::leftPadTransactionId
 *
 * @group interswitch_donate
 */
class LeftPadTest extends UnitTestCase {
  /**
   * Tests that strings with less than 6 characters are padded to 6 characters.
   */
  public function testLessThanSixCharacters() {
    $services = new Services();
    $this->assertRegExp(
      '/^\d{6}$/',
      $services->leftPadTransactionId('123')
    );
  }
}
