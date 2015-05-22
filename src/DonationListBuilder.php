<?php

/*
 * @file
 * Contains code for Drupal\interswitch_donate\DonationListBuilder
 */

namespace Drupal\interswitch_donate;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for the interswitch_donate donation entity
 *
 */
class DonationListBuilder extends EntityListBuilder {
  /**
   * {@inheritdoc}
   *
   * Building the header and content lines for the contact list.
   *
   * Calling the parent::buildHeader() adds a column for the possible actions
   * and inserts the 'edit' and 'delete' links as defined for the entity type.
   */
  public function buildHeader() {
    $header['id'] = $this->t('Transaction ID');
    $header['webpay_ref'] = $this->t('Webpay Ref');
    $header['amount'] = $this->t('Amount');
    $header['donation_purpose'] = $this->t('Donation Purpose');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['id'] = $entity->link($entity->id());
    $row['webpay_ref'] = $entity->webpay_ref->value;
    $row['amount'] = $entity->amount->value;
    $row['donation_purpose'] = $entity->donation_purpose->value;
    return $row + parent::buildRow($entity);
  }
}
