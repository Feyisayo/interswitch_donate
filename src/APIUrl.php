<?php
/**
 * @file
 * Contains Drupal\interswitch_donate\APIUrl.
 */

namespace Drupal\interswitch_donate;

/**
 * Provides the various URLs from Interswitch
 */
class APIUrl {
  public static $liveServer = 'https://webpay.interswitchng.com/paydirect/pay';
  public static $liveLookUp = 'https://webpay.interswitchng.com/paydirect/api/v1/gettransaction.json';

  public static $testServer = 'https://stageserv.interswitchng.com/test_paydirect/pay';
  public static $testLookUp = 'https://stageserv.interswitchng.com/test_paydirect/api/v1/gettransaction.json';

  /**
   * Returns the Interswitch servers URLs an array.
   *
   * @return array
   *  An array of the server URLs.
   */
  public static function getUrls() {
    return array(
      static::$testServer => 'Test server - ' . static::$testServer,
      static::$liveServer => 'Live server - ' . static::$liveServer
    );
  }
}