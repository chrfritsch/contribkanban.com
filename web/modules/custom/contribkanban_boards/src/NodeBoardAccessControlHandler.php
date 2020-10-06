<?php

namespace Drupal\contribkanban_boards;

use Drupal\contribkanban_boards\Entity\NodeBoard;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\entity\UncacheableEntityAccessControlHandler;
use Drupal\options\Plugin\Field\FieldType\ListStringItem;

class NodeBoardAccessControlHandler extends UncacheableEntityAccessControlHandler
{

  /**
   *
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account)
  {
    $access = parent::checkAccess($entity, $operation, $account);
    if ($access->isAllowed()) {
      return $access;
    }

    // Instance check for type hint. Also bail if delete operation and use
    // the default access, we don't want anonymous users to delete boards if
    // the setting is public.
    if (!$entity instanceof NodeBoard || $operation == 'delete') {
      return $access;
    }

    $collaboration_field = $entity->get('collaboration')->first();
    assert($collaboration_field instanceof ListStringItem);
    $collaboration = $collaboration_field->get('value')->getValue();
    // If the board is private, we only allow permission for the owner.
    if ($collaboration === NodeBoard::IS_PRIVATE) {
      $access = AccessResult::forbidden();
    }
    // If the board is shared, used default access.
    elseif ($operation === 'view' && $collaboration === NodeBoard::IS_SHARED) {
      $access = AccessResult::allowed();
    }
    // If the board is public, allow access.
    elseif ($collaboration === NodeBoard::IS_PUBLIC) {
      $access = AccessResult::allowed();
    }

    return $access;
  }
}
