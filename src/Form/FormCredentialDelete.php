<?php
/**
 * @file
 * Contains Drupal\interswitch_donate\Form\FormCredentialDelete.
 */

namespace Drupal\interswitch_donate\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller to delete an Interswitch credential entity
 */
class FormCredentialDelete extends EntityConfirmFormBase {

  /**
   * Returns the question to ask the user.
   *
   * @return string
   *   The form question. The page title will be set to this value.
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete credential %label?', array(
      '%label' => $this->entity->label(),
    ));
  }

  /**
   * Returns the route to go to if the user cancels the action.
   *
   * @return \Drupal\Core\Url
   *   A URL object.
   */
  public function getCancelUrl() {
    return new Url('entity.interswitch_donate_credential.list');
  }

  /**
   * Gather the confirmation text.
   *
   * The confirm text is used as the text in the button that confirms the
   * question posed by getQuestion().
   *
   * @return string
   *   Translated string.
   */
  public function getConfirmText() {
    return $this->t('Delete Credential');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $label = $this->entity->label();
    // Delete the entity.
    $this->entity->delete();

    // Set a message that the entity was deleted.
    drupal_set_message(t('Credential %label was deleted.', array(
      '%label' => $label,
    )));

    // Redirect the user to the list controller when complete.
    $form_state->setRedirectUrl($this->getCancelUrl());
  }
}