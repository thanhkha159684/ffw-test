<?php

namespace Drupal\address_book;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the address entry entity type.
 */
class AddressEntryAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view address entry');

      case 'update':
        return AccessResult::allowedIfHasPermissions($account, ['edit address entry', 'administer address entry'], 'OR');

      case 'delete':
        return AccessResult::allowedIfHasPermissions($account, ['delete address entry', 'administer address entry'], 'OR');

      default:
        // No opinion.
        return AccessResult::neutral();
    }

  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions($account, ['create address entry', 'administer address entry'], 'OR');
  }

}
