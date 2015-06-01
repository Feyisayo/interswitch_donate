<?php

/**
 * @file
 * Contains \Drupal\interswitch_donate\Form\FormDonationDelete.
 */

namespace Drupal\interswitch_donate\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a form for deleting a donation entity.
 */
class FormDonationDelete extends ContentEntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete Donation %name?', array('%name' => $this->entity->id()));
  }

  /**
   * {@inheritdoc}
   *
   * If the delete command is canceled, return to the donation list.
   */
  public function getCancelURL() {
    return new Url('entity.interswitch_donate_donation.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete Donation');
  }

  /**
   * {@inheritdoc}
   *
   * Delete the entity and log the event. log() replaces the watchdog.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $entity->delete();

    \Drupal::logger('interswitch_donate')->notice('Donation %id deleted.',
      array(
        '%id' => $this->entity->id(),
      ));
    $form_state->setRedirect('entity.interswitch_donate_donation.collection');
  }

}
