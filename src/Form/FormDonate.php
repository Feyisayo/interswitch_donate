<?php

/**
 * @file
 * Contains \Drupal\interswitch_donate\Form\FormDonate.
 */

namespace Drupal\interswitch_donate\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class for rendering the main donation form.
 */
class FormDonate extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // TODO: form parametres should be loaded from a config entity.
    $form['about'] = array(
      '#type' => 'item',
      '#title' => 'NOTE:',
      '#markup' => t("Make a donation using your Interswitch ATM bank card.<br/>Enter the amount you want to donate. Thereafter you will be taken to Interswitch's website to conclude the payment.")
    );

    $form['donation_purpose'] = array(
      '#type' => 'radios',
      '#options' => array('Maintenance' => 'Maintenace', 'Water' => 'Water'),
      '#title' => t('Select the area you want to donate to.'),
      '#required' => TRUE,
    );

    $form['amount'] = array(
      '#type' => 'number',
      '#title' => t('Enter donation amount'),
      '#required' => TRUE,
      '#default_value' => 0,
      '#maxlength' => 7,
      '#description' => t('Enter the amount without any symbols eg instead of N1,000 enter 1000')
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Donate')
    );
    
    // Add Interswitch logo.
    $form['#suffix'] = "<img src = '"
    . drupal_get_path('module', 'interswitch_donate')
    . "/images/logo.png'/>";
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'interswitch_donate_form_donate';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $donation = entity_create('interswitch_donate_donation', array(
      'donation_purpose' => $form_state->getValue('donation_purpose'),
      'amount' => $form_state->getValue('amount') * 100,
      'created' => time()
    ));
    $donation->save();

    \Drupal::service('session')->set('interswitch_donate_donation_id', $donation->id());

    $form_state->setRedirect('interswitch_donate.donation_review');
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // TODO: The minimum amount should be in a config entity.
    if ($form_state->getValue('amount') < 25) {
      $form_state->setErrorByName('amount', $this->t('Please enter a minimum of 25'));
    }
  }
}
