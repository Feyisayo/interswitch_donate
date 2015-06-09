<?php
/**
 * @file
 * Contains \Drupal\interswitch_donate\Entity\Credential
 */

namespace Drupal\interswitch_donate\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\interswitch_donate\Entity\CredentialInterface;

/**
 * Defines the Example entity.
 *
 * @ConfigEntityType(
 *   id = "interswitch_credential",
 *   label = @Translation("Credential"),
 *   handlers = {
 *     "list_builder" = "Drupal\interswitch_donate\CredentialListBuilder",
 *     "form" = {
 *       "add" = "Drupal\interswitch_donate\Form\FormCredential",
 *       "edit" = "Drupal\interswitch_donate\Form\FormCredential",
 *       "delete" = "Drupal\interswitch_donate\Form\FormCredentialDelete",
 *     }
 *   },
 *   admin_permission = "administer interswitch credentials",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   links = {
 *     "edit-form" = "entity.interswitch_credential.edit_form",
 *     "delete-form" = "entity.interswitch_credential.delete_form"
 *   }
 * )
 */
class Credential extends ConfigEntityBase implements CredentialInterface {
  /**
   * The credential ID
   *
   * @var integer
   */
  public $id;

  /**
   * Human-readable name of the credential
   *
   * @var string
   */
  public $label;

  /**
   * Interswitch payment page URL
   *
   * @var string
   */
  public $server_url;

  /**
   * Product ID
   *
   * @var integer
   */
  public $product_id;

  /**
   * Pay item ID
   *
   * @var integer
   */
  public $pay_item_id;

  /**
   * The currency code
   *
   * @var integer
   */
  public $currency_code;

  /**
   * The MAC Key.
   *
   * @var string
   */
  public $mac_key;

  /**
   * Transaction look up URL.
   *
   * @var string
   */
  public $lookup_url;

  /**
   * Indicates if the credential is the one in use.
   *
   * @var boolean
   */
  public $is_current;

  public function setAsCurrent() {

  }
}