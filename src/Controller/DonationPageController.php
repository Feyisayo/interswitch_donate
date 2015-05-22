<?php

/**
 * @file
 * Contains \Drupal\interswitch_donate\Controller\UserEndPageController.
 */

namespace Drupal\interswitch_donate\Controller;

/**
 * Controller routines for the user facing aspect of Interswitch Donate.
 *
 */
class DonationPageController {
  /**
   * Constructs the main donation for the user to get started.
   */
  public function donationPage (){
    //$form = \Drupal::formBuilder()->getForm('Drupal\interswitch_donate\Form\FormDonate');
    //return $form;
    $donation = entity_create('interswitch_donate_donation', array(
      'donation_purpose' => 'demo donation',
      'amount' => 10000,
      'webpay_ref' =>  'demo_ref',
      'status_code' => '00',
      'payload' => array(),
      'created' => time(),
      'changed' => time()
    ));
    
    $donation->save();
    
    return array (
      '#type' => 'markup',
      '#markup' => t('Donation created'),
    );
  }
}
