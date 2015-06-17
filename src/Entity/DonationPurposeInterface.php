<?php
/**
 * @file
 * Contains Drupal\interswitch_donate\Entity\DonationPurposeInterface.
 */

namespace Drupal\interswitch_donate\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;
/**
 * Provides an interface defining a Donation Purpose entity.
 */
interface DonationPurposeInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {
  /**
   * Returns the entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the entity.
   */
  public function getCreatedTime();
}