<?php

/**
 * @file
 * Contains \Drupal\interswitch_donate\Entity\Donation;
 */

namespace Drupal\interswitch_donate\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\interswitch_donate\DonationInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Donation entity.
 *
 * @ContentEntityType(
 *   id = "interswitch_donate_donation",
 *   label = @Translation("Donation entity"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\interswitch_donate\DonationListBuilder",
 *     "access" = "Drupal\Core\Entity\EntityAccessControlHandler",
 *   },
 *   base_table = "donations",
 *   admin_permission = "administer donations",
 *   fieldable = FALSE,
 *   entity_keys = {
 *     "id" = "transaction_id",
 *     "uuid" = "uuid",
 *     "name" = "transaction_id"
 *   },
 *  links = {
 *     "canonical" = "/isw-donate/{interswitch_donate_donation}",
 *     "collection" = "/isw-donate/list"
 *   }
 * )
 *
 */
class Donation extends ContentEntityBase implements DonationInterface{
  /**
   * {@inheritdoc}
   *
   * When a new donation is created, set the user_id entity reference to
   * the current user as the creator of the instance.
   */
  public static function preCreate(EntityStorageInterface $storage, array &$values) {
    parent::preCreate($storage, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }
 
 /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTime() {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

 /**
   * {@inheritdoc}
   *
   * Define the field properties here.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    // Standard field, used as unique if primary index.
    $fields['transaction_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Transaction ID'))
      ->setDescription(t('The ID of the donation entity.'))
      ->setReadOnly(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'weight' => 1,
      ));
    
    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the donation entity.'))
      ->setReadOnly(TRUE);
    
    // Owner field of the contact.
    // Entity reference field, holds the reference to the user object.
    // The view shows the user name field of the user.
    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User Name'))
      ->setDescription(t('The Name of the associated user.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'entity_reference',
        'weight' => 2,
      ))
      ->setDisplayConfigurable('view', TRUE);

    $fields['donation_purpose'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Purpose of Donation'))
      ->setDescription(t('The purpose of the donation.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 3,
      ))
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['amount'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Amount'))
      ->setDescription(t('The donation amount'));
    
    $fields['webpay_ref'] = BaseFieldDefinition::create('string')
      ->setLabel(t('WebPay Reference'))
      ->setDescription(t('The Interswitch WebPay Reference'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 60,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 4,
      ))
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['status_code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Status Code'))
      ->setDescription(t('Status code from Interswitch'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 5,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 5,
      ))
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['payload'] = BaseFieldDefinition::create('map')
      ->setLabel(t('Response Dump'))
      ->setDescription(t('Response dump from Interswitch'));
    
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the donation was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the donation was last edited.'));
    
    return $fields;
  }

}
