<?php
/**
 * @file
 * Contains \Drupal\interswitch_donate\CredentialListBuilder.
 */

namespace Drupal\interswitch_donate;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\interswitch_donate\APIUrl;

/**
 * Provides a listing for Credential entities.
 */
class CredentialListBuilder extends ConfigEntityListBuilder {

  /**
   * Builds the header row for the entity listing.
   *
   * @return array
   *   A render array structure of header strings.
   *
   * @see Drupal\Core\Entity\EntityListController::render()
   */
  public function buildHeader() {
    $header['label'] = $this->t('Credential Label');
    $header['machine_name'] = $this->t('Machine Name');
    $header['product_id'] = $this->t('Product ID');
    $header['pay_item_id'] = $this->t('Pay Item ID');
    $header['server_url'] = $this->t('Server URL');
    $header['default'] = $this->t('Default');

    return $header + parent::buildHeader();
  }

  /**
   * Builds a row for an entity in the entity listing.
   *
   * @param EntityInterface $entity
   *   The entity for which to build the row.
   *
   * @return array
   *   A render array of the table row for displaying the entity.
   *
   * @see Drupal\Core\Entity\EntityListController::render()
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $this->getLabel($entity);
    $row['machine_name'] = $entity->id();
    $row['product_id'] = $entity->product_id;
    $row['pay_item_id'] = $entity->pay_item_id;
    $row['server_url'] = $entity->server_url == APIUrl::$liveLookUp ? 'Live server' : 'Test server';
    $row['default'] = $entity->is_current ? 'Yes' : 'No';

    return $row + parent::buildRow($entity);
  }
}