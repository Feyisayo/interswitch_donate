<?php
/**
 * @file
 * Contains \Drupal\interswitch_donate\Form\FormCredential.
 */

namespace Drupal\interswitch_donate\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\interswitch_donate\APIUrl;
use Drupal\interswitch_donate\Entity\Credential;

/**
 * Form class for adding and editing Credential entities.
 */
class FormCredential extends EntityForm{
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get anything we need from the base class.
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;
    // Putting the label before the id allows the to be auto-generated as the
    // label is entered.
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Credential Name'),
      '#default_value' => $entity->label(),
      '#required' => TRUE,
      '#maxlength' => 60,
    );
    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $entity->id(),
      '#required' => TRUE,
      '#disabled' => !$entity->isNew(),
      '#maxlength' => 64,
      '#machine_name' => array(
        'exists' => array('\Drupal\interswitch_donate\Entity\Credential', 'load')
      )
    );
    // @todo: using $entity->get eg $entity->get('server_url')->value, causes
    // a PHP notice
    $form['server_url'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Interswitch payment page URL'),
      '#options' => APIUrl::getUrls(),
      '#default_value' => $entity->server_url,
      '#required' => TRUE
    );
    $form['mac_key'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('MAC Key'),
      '#maxlength' => 150,
      '#default_value' => $entity->mac_key,
      '#required' => TRUE
    );
    $form['pay_item_id'] = array(
      '#type' => 'number',
      '#title' => $this->t('Pay Item ID'),
      '#maxlength' => 5,
      '#default_value' => $entity->pay_item_id,
      '#required' => TRUE
    );
    $form['product_id'] = array(
      '#type' => 'number',
      '#title' => $this->t('Product ID'),
      '#maxlength' => 5,
      '#default_value' => $entity->product_id,
      '#required' => TRUE
    );
    $form['is_current'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Set as current'),
      '#options' => [0 => $this->t('No'), 1 => $this->t('Yes')],
      '#default_value' => 0,
      '#description' => $this->t('Make this credential the default one')
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function actions(array $form, FormStateInterface $form_state) {
    $actions = parent::actions($form, $form_state);
    $actions['submit']['#value'] = ($this->entity->isNew()) ? $this->t('Create Credential') : $this->t('Update Credential');
    return $actions;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    // Trim text fields.
    $entity->set('id', trim($entity->id()));
    $entity->set('label', trim($entity->label()));
    $entity->set('mac_key', trim($form_state->getValue('mac_key')));
    $entity->set('pay_item_id', trim($form_state->getValue('pay_item_id')));
    $entity->set('product_id', trim($form_state->getValue('product_id')));

    // Set the currency code
    $entity->set('currency_code', 566);

    // Set the look up URL based on the server URL.
    if (APIUrl::$liveServer == $entity->server_url) {
      $entity->set('lookup_url', APIUrl::$liveLookUp);
    }
    else {
      $entity->set('lookup_url', APIUrl::$testLookUp);
    }

    // If this credential has been set to default then make sure no other
    // credential is also default.
    if ($entity->is_current) {
      // @var \Drupal\Core\Entity\Query\Sql\Query $query
      $query = \Drupal::entityQuery('interswitch_credential');
      $result = $query->condition('is_current', TRUE)->execute();
      foreach ($result as $key => $credential_id) {
        $credential = Credential::load($credential_id);
        $credential->set('is_current', FALSE);
        $credential->save();
      }
    }

    $status = $entity->save();
    $edit_link = $this->entity->link($this->t('Edit'));

    if ($status == SAVED_UPDATED) {
      // If we edited an existing entity...
      drupal_set_message($this->t('Credential %label has been updated.', array('%label' => $entity->label())));
      $this->logger('interswitch_donate')->notice('Credential %label has been updated.', ['%label' => $entity->label(), 'link' => $edit_link]);
    }
    else {
      // If we created a new entity...
      drupal_set_message($this->t('Credential %label has been added.', array('%label' => $entity->label())));
      $this->logger('interswitch_donate')->notice('Credential %label has been added.', ['%label' => $entity->label(), 'link' => $edit_link]);
    }

    // Redirect the user back to the listing route after the save operation.
    $form_state->setRedirect('entity.interswitch_credential.list');
  }
}