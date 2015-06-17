<?php
/**
 * @file
 * Contains Drupal\interswitch_donate\DonationPurposeListBuilder.
 */

namespace Drupal\interswitch_donate;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for the Donation Purpose entity
 *
 */
class DonationPurposeListBuilder extends EntityListBuilder {
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['owner'] = $this->t('Owner');
    $header['label'] = $this->t('Label');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['id'] = $entity->link($entity->id());
    $row['owner'] = $entity->owner->entity->name->value;
    $row['label'] = $entity->label();
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build = parent::render();
    $build['table']['#empty'] = $this->t('No donation purposes available yet.');
    return $build;
  }
}