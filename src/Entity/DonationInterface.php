<?php

/**
 * @file
 * Contains \Drupal\interswitch_donate\DonationInterface.
 */

namespace Drupal\interswitch_donate\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a Donation entity.
 */
interface DonationInterface extends ContentEntityInterface, EntityOwnerInterface {

}
