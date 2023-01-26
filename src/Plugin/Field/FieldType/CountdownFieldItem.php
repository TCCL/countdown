<?php

namespace Drupal\countdown\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * @FieldType(
 *   id = "countdown",
 *   label = "Countdown",
 *   category = "TCCL Custom",
 *   description = "Renders a countdown widget",
 *   default_widget = "countdown",
 *   default_formatter = "countdown"
 * )
 */
class CountdownFieldItem extends FieldItemBase {
  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_def) {
    return [
      'columns' => [
        'countdown_date' => [
          'type' => 'varchar',
          'length' => 128,
          'not null' => false,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_def) {
    $properties = [];

    $properties['countdown_date'] = DataDefinition::create('string')
                                  ->setInternal(true)
                                  ->setLabel('Countdown Date')
                                  ->setDescription(
                                    'The date to which the countdown field item is'
                                    .'descending.'
                                  );

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('countdown_date')->getValue();
    return empty($value);
  }
}
