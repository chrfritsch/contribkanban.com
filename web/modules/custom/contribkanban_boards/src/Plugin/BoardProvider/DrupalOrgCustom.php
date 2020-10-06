<?php

namespace Drupal\contribkanban_boards\Plugin\BoardProvider;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Plugin\PluginBase;

/**
 * Provides the first bundle plugin.
 *
 * @BoardProvider(
 *   id = "drupalorg_custom",
 *   label = @Translation("Custom"),
 *   description = @Translation("Add a board for issues from Drupal.org"),
 * )
 */
class DrupalOrgCustom extends PluginBase implements BoardProviderInterface {

  /**
   * {@inheritdoc}
   */
  public function buildFieldDefinitions() {
    $fields = [];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function bundleFieldDefinitionsAlter(EntityTypeInterface $entity_type, $bundle, array $base_field_definitions) {
    $fields = [];
    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $base_field_definitions */
    if (isset($base_field_definitions['tag'])) {
      $fields['tag'] = clone $base_field_definitions['tag'];
      $fields['tag']->setDisplayOptions('form', [
        'type'   => 'string_textfield',
        'weight' => -5,
      ]);
    }
    return $fields;
  }

}
