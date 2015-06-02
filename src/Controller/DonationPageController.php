<?php

/**
 * @file
 * Contains \Drupal\interswitch_donate\Controller\DonationPageController.
 */

namespace Drupal\interswitch_donate\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;
use Drupal\interswitch_donate\Entity\Donation;

/**
 * Controller routines for the user facing aspect of Interswitch Donate.
 *
 */
class DonationPageController {
  /**
   * Callback that creates the page the user is returned to from Interswitch.
   *
   * TODO: this controller should redirect to another page where the status will
   * be displayed ala Commerce. This is to prevent the URL from being displayed.
   */
  public function donationRedirectPage($entity_id, $redirect_key) {
    // Load the donation entity.
    //$donation = entity_load('interswitch_donate_donation', $entity_id, TRUE);
    $donation = Donation::load($entity_id);

    // Keeping it DRY.
    $error = FALSE;
    if (NULL == $donation) {
      // TODO: log this
      $error = TRUE;
    } else if ($donation->redirect_key->value != $redirect_key) {
      // TODO: log this
      $error = TRUE;
    }
    
    if ($error) {
      //return new RedirectResponse(Url::fromUri('internal:/')->toString());
      return new RedirectResponse(Url::fromRoute('interswitch_donate.donation_page')->toString());
    }

    // Get status from ISW.
    $services = \Drupal::service('interswitch_donate.services');
    $response = $services->lookUpTransaction($donation);
    $page = array();
    // Display status to user with link to donate again.
    if (FALSE == $response) {
      drupal_set_message(t('Unable to look up transaction status. Please contact the site administrator'), 'error');
      $page = array('#markup' => t('<p>Transaction look up failed.</p>'));
    } elseif ('00' == $response['ResponseCode']) {
      drupal_set_message(t('Payment successful'), 'status');
      $page = array(
        '#markup' => '<p>'. t('Payment for donation successful') . '<br/>' . t('Payment Transaction ID:') . ' '
          . $services->leftPadTransactionId($donation->id())
          . '<br/>' . t('WebPay Reference:') . ' ' . $response['PaymentReference'] . '</p>'
      );
    } else {
      drupal_set_message(t('Your payment was not successful'), 'error');
      $page = array(
        '#markup' => '<p>'. t('Payment for donation NOT successful') . '<br/>' . t('Payment Transaction ID:') . ' '
          . $services->leftPadTransactionId($donation->id())
          . '<br/>' . t('Reason:') . ' ' . $response['ResponseDescription'] . '</p>'
      );
    }

    if ($response) {
      $donation->set('payload', $response);
      $donation->set('webpay_ref', $response['PaymentReference']);
      $donation->set('status_code', $response['ResponseCode']);
      $donation->save();
    }

    return $page;
  }
}
