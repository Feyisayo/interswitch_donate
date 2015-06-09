<?php
/**
 * @file
 * Contains \Drupal\interswitch_donate\Entity\CredentialInterface.
 */

namespace Drupal\interswitch_donate\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

interface CredentialInterface extends ConfigEntityInterface {
  /*
   * Makes the entity the current credential to be used.
   *
   * There can be only one current credential so this function will also disable
   * every other credential.
   *
   * @return void
   */
  public function setAsCurrent();
}