<?php
/**
 * @file
 * Contains Drupal\interswitch_donate\Entity\DonationPurpose.
 */

namespace Drupal\interswitch_donate\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\interswitch_donate\Entity\DonationPurposeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Entity\EntityChangedTrait;

/**
 * Defines the Donation entity.
 *
 * @ContentEntityType(
 *   id = "interswitch_donation_purpose",
 *   label = @Translation("Donation Purpose"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\interswitch_donate\DonationPurposeListBuilder",
 *     "form" = {
 *        "add" = "Drupal\interswitch_donate\Form\FormDonationPurpose",
 *        "edit" = "Drupal\interswitch_donate\Form\FormDonationPurpose",
 *        "delete" = "Drupal\interswitch_donate\Form\FormDonationPurposeDelete"
 *      },
 *   },
 *   base_table = "donation_purposes",
 *   admin_permission = "administer donations",
 *   fieldable = FALSE,
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "label" = "label"
 *   },
 *  links = {
 *     "canonical" = "entity.interswitch_donation_purpose.canonical",
 *     "collection" = "entity.interswitch_donation_purpose.collection",
 *     "delete-form" = "entity.interswitch_donation_purpose.delete_form",
 *     "edit-form" = "entity.interswitch_donation_purpose.edit_form"
 *   }
 * )
 *
 */
class DonationPurpose extends ContentEntityBase implements DonationPurposeInterface {
  use EntityChangedTrait;
  /**
   * Returns the entity owner's user entity.
   *
   * @return \Drupal\user\UserInterface
   *   The owner user entity.
   */
  public function getOwner() {
    $this->owner->getEntity();
  }

  /**
   * Sets the entity owner's user entity.
   *
   * @param \Drupal\user\UserInterface $account
   *   The owner user entity.
   *
   * @return $this
   */
  public function setOwner(UserInterface $account) {
    $this->set('owner', $account->id());
    return $this;
  }

  /**
   * Returns the entity owner's user ID.
   *
   * @return int
   *   The owner user ID.
   */
  public function getOwnerId() {
    $this->owner->entity->id();
  }

  /**
   * Sets the entity owner's user ID.
   *
   * @param int $uid
   *   The owner user id.
   *
   * @return $this
   */
  public function setOwnerId($uid) {
    $this->set('owner', $uid);
    return $this;
  }

  /**
   * Provides base field definitions for an entity type.
   *
   * Implementations typically use the class
   * \Drupal\Core\Field\BaseFieldDefinition for creating the field definitions;
   * for example a 'name' field could be defined as the following:
   * @code
   * $fields['name'] = BaseFieldDefinition::create('string')
   *   ->setLabel(t('Name'));
   * @endcode
   *
   * By definition, base fields are fields that exist for every bundle. To
   * provide definitions for fields that should only exist on some bundles, use
   * \Drupal\Core\Entity\FieldableEntityInterface::bundleFieldDefinitions().
   *
   * The definitions returned by this function can be overridden for all
   * bundles by hook_entity_base_field_info_alter() or overridden on a
   * per-bundle basis via 'base_field_override' configuration entities.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition. Useful when a single class is used for multiple,
   *   possibly dynamic entity types.
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface[]
   *   An array of base field definitions for the entity type, keyed by field
   *   name.
   *
   * @see \Drupal\Core\Entity\EntityManagerInterface::getFieldDefinitions()
   * @see \Drupal\Core\Entity\FieldableEntityInterface::bundleFieldDefinitions()
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the donation purpose entity.'))
      ->setReadOnly(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'weight' => 1,
      ));

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the donation purpose entity.'))
      ->setReadOnly(TRUE);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label of the donation purpose'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string',
        'weight' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 3,
      ))
      ->setDisplayConfigurable('view', TRUE);

    // Entity reference field, holds the reference to the user object.
    // The view shows the user name field of the user.
    $fields['owner'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User'))
      ->setDescription(t('The Name of the associated user.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'entity_reference',
        'weight' => 2,
      ))
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the donation purpose was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the donation purpose was last edited.'));

    return $fields;
  }

  /**
   * Returns the timestamp of the last entity change.
   *
   * @return int
   *   The timestamp of the last entity save operation.
   */
  public function getChangedTime() {
    return $this->changed;
  }

  /**
   * Returns the entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the entity.
   */
  public function getCreatedTime() {
    return $this->created;
  }

  public static function preCreate(EntityStorageInterface $storage, array &$values) {
    parent::preCreate($storage, $values);
    $values += ['owner' => \Drupal::currentUser()->id()];
  }
}