<?php
/**
 * @file
 * Contains Drupal\interswitch_donate\Form\FormDonationPurposeDelete.
 */

namespace Drupal\interswitch_donate\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Form controller for deleting Donation Purpose entity
 */
class FormDonationPurposeDelete extends ContentEntityConfirmFormBase {

  /**
   * Returns the question to ask the user.
   *
   * @return string
   *   The form question. The page title will be set to this value.
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete "%name"?', array('%name' => $this->entity->label()));
  }

  /**
   * Returns the route to go to if the user cancels the action.
   *
   * @return \Drupal\Core\Url
   *   A URL object.
   */
  public function getCancelUrl() {
    return new Url('entity.interswitch_donation_purpose.collection');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete Donation Purpose');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $entity->delete();

    \Drupal::logger('interswitch_donate')->notice('Donation Purpose %label deleted.',
      array(
        '%id' => $this->entity->label(),
      ));
    $form_state->setRedirect('entity.interswitch_donation_purpose.collection');
  }
}