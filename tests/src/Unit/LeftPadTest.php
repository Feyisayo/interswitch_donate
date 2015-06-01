<?php
/**
 * @file
 * Contains Drupal\Tests\interswitch_donate\Unit\LeftPadTest;
 */

namespace Drupal\Tests\interswitch_donate\Unit;

use Drupal\Tests\UnitTestCase;

require_once __DIR__ . '/../../../interswitch_donate.module';

/**
 * Class LeftPadTest
 * @package Drupal\Tests\interswitch_donate\Unit
 *
 * Tests for interswitch_donate_left_pad_transaction_id()
 *
 * @group interswitch_donate
 */
class LeftPadTest extends UnitTestCase {
  /**
   * Tests that strings with less than 6 characters are padded to 6 characters.
   */
  public function testLessThanSixCharacters() {
    $this->assertRegExp(
      '/^\d{6}$/',
      interswitch_donate_left_pad_transaction_id('123')
    );
  }
}
