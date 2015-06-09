<?php
/**
 * @file
 * Contains \Drupal\interswitch_donate\Form\FormDonationReview.
 */

namespace Drupal\interswitch_donate\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Component\Utility\Crypt;

/**
 * Provides a form for reviewing a donation.
 */
class FormDonationReview extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Ensure that the user has already filled the donation form.
    $donation_id = \Drupal::service('session')->get('interswitch_donate_donation_id');

    $form = array();
    if ($donation_id == NULL) {
      $form['info'] = array(
        '#type' => 'item',
        '#title' => 'No Donation Specified',
        '#markup' => $this->t('You have not specified a donation. Please <a href ="@donationlink">click here</a> to fill the donation form', array(
          '@donationlink' => \Drupal::urlGenerator()->generateFromRoute('interswitch_donate.donation_page')
          )),
      );
    } else {
      $donation = entity_load('interswitch_donate_donation', $donation_id);
      // Load Interswitch credentials and parametres.
      // TODO: this should come from a configuration entity.
      $services = \Drupal::service('interswitch_donate.services');
      $isw_config = $services->getCredentials();
      $isw_config['amount'] = $donation->amount->value;
      $redirect_key = Crypt::hashBase64(time());
      $isw_config['site_redirect_url'] = Url::fromRoute(
        'interswitch_donate.redirect_page',
        array('entity_id' => $donation->id(), 'redirect_key' => $redirect_key),
        array('absolute' => TRUE)
      )->toString();
      $isw_config['cust_name'] = $donation->get('user_id')->entity->name->value;
      $isw_config['cust_id'] = $donation->get('user_id')->entity->id();
      $isw_config['txn_ref'] = $services->leftPadTransactionId($donation->id());
      $hash = hash(
        'sha512',
        $isw_config['txn_ref']
        . $isw_config['product_id']
        . $isw_config['pay_item_id']
        . $isw_config['amount']
        . $isw_config['site_redirect_url']
        . $isw_config['mac_key']
      );
      $isw_config['hash'] = $hash;
      // Do not send the MAC Key.
      unset($isw_config['mac_key']);

      $donation->set('redirect_key', $redirect_key);
      $donation->save();

      $form['#action'] = 'https://stageserv.interswitchng.com/test_paydirect/pay';

      $form['info'] = array(
        '#type' => 'item',
        '#title' => $this->t('See the details of your donation below:'),
      );

      $form['transaction_id'] = array(
        '#type' => 'item',
        '#title' => $this->t('Transaction ID'),
        '#markup' => $isw_config['txn_ref']
      );

      $form['donation_purpose'] = array(
        '#type' => 'item',
        '#title' => $this->t('Donation Purpose'),
        '#markup' => $donation->donation_purpose->value
      );

      $form['donation_amount'] = array(
        '#type' => 'item',
        '#title' => $this->t('Amount'),
        '#markup' => number_format($donation->amount->value / 100, 2)
      );

      // Add the Interswitch credentials to the form.
      foreach ($isw_config as $name => $value) {
      if (!empty($value)) {
          $form[$name] = array('#type' => 'hidden', '#value' => $value);
        }
      }

      $form['submit'] = array(
        '#type' => 'submit',
        '#value' => $this->t('Proceed to Payment')
      );
      
      $form['cancel'] = array(
        '#markup' => \Drupal::l($this->t('Cancel'), Url::fromRoute('interswitch_donate.donation_page'))
      );
    }
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'interswitch_donate_form_review';
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {    
    if (NULL == \Drupal::service('session')->remove('interswitch_donate_donation_id')) {
      $form_state->setErrorByName('amount', 'Could not clear session');
    }
  }
  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
