<?php
/**
 * @file
 * Contains \Drupal\interswitch_donate\Services.
 */

namespace Drupal\interswitch_donate;

/**
 * Contains helper functions for use during the donation workflow
 */
class Services {
  /**
   * Returns Interswitch credentials.
   */
  public function getCredentials() {
    return array(
      'product_id' => 4220,
      'pay_item_id' => 101,
      'currency' => 566,
      'site_name' => 'd8.dev',
      'mac_key' => '199F6031F20C63C18E2DC6F9CBA7689137661A05ADD4114ED10F5AFB64BE625B6A9993A634F590B64887EEB93FCFECB513EF9DE1C0B53FA33D287221D75643AB'
    );
  }

  /**
   * Left-pads transaction ID with zeroes.
   *
   * Apparently Interswitch requires transaction references (ID) to between 6 and
   * 50 characters long. Commerce, on the other hand, serially numbers all
   * transaction IDs ie 1, 2, 3, 4...etc. So this function will left-pad a
   * transaction ID with zeroes to meet Interswitch's conditions.
   *
   * @param string $txn_id
   *   The transaction to be left-padded.
   *
   * @return string
   *   Left-padded transaction with a minimum of 6 characters.
   */
  public function leftPadTransactionId($txn_id) {
    return strlen($txn_id) < 6 ? str_pad($txn_id, 6, '0', STR_PAD_LEFT) : $txn_id;
  }

  /**
   * Looks up transaction information from Interswitch.
   *
   * Returns an array containing transaction information on success,
   * FALSE otherwise.
   *
   * @param \Drupal\interswitch_donate\DonationInterface $donation
   *   The donation entity.
   *
   * @return array
   *   Array containing transaction information
   */
  public function lookUpTransaction(DonationInterface $donation) {
    // TODO: add logging to this
    $url = 'https://stageserv.interswitchng.com/test_paydirect/api/v1/gettransaction.json';

    $isw_config = $this->getCredentials();
    $amount = $donation->amount->value;
    $product_id = $isw_config['product_id'];
    $txn_id = $this->leftPadTransactionId($donation->id());

    $hash = hash('sha512', $product_id . $txn_id . $isw_config['mac_key']);
    $url .= "?productid=$product_id&transactionreference=$txn_id&amount=$amount";

    $client = \Drupal::httpClient();
    $response = $client->get($url, ['headers' => ['Hash' => $hash]]);

    if (200 != $response->getStatusCode()) {
      return FALSE;
    } else {
      return $response->json();
    }
  }
}