<?php
/**
 * @file
 * Contains Drupal\interswitch_donate\Form\FormDonationPurpose.
 */

namespace Drupal\interswitch_donate\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;

/*
 * Form controller for the DonationPurpose entity.
 */
class FormDonationPurpose extends ContentEntityForm {
  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    // Trim the label
    $this->entity->set('label', trim($this->entity->label()));
    $this->entity->save();
    // Set the redirection page
    $form_state->setRedirect('entity.interswitch_donation_purpose.collection');
  }
}