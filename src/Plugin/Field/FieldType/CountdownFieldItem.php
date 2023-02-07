<?php

namespace Drupal\countdown\Plugin\Field\FieldType;

use Drupal\Core\DateTime\DrupalDateTime;
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
  const STORAGE_FORMAT = 'Y-m-d H:i:s';
  const STORAGE_TIMEZONE = 'UTC';

  /**
   * Converts a DrupalDateTime into its storage representation.
   *
   * @param \Drupal\Core\DateTime\DrupalDateTime $date
   *  The date time to convert.
   *
   * @return string
   */
  public static function toStorageRepr(DrupalDateTime $date) : string {
    $storageTimezone = new \DateTimezone(self::STORAGE_TIMEZONE);

    $dt = clone $date;
    $dt->setTimezone($storageTimezone);
    return $dt->format(self::STORAGE_FORMAT);
  }

  /**
   * Converts the storage representation into a DrupalDateTime instance.
   *
   * @param string $repr
   *  The storage representation.
   *
   * @return \Drupal\Core\DateTime\DrupalDateTime
   */
  public static function fromStorageRepr(string $repr) : DrupalDateTime {
    $tz = new \DateTimezone(self::STORAGE_TIMEZONE);
    $dt = new DrupalDateTime($repr,$tz);

    $tz = new \DateTimezone(date_default_timezone_get());
    $dt->setTimezone($tz);

    return $dt;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_def) {
    return [
      'columns' => [
        'countdown_date' => [
          'type' => 'varchar',
          'length' => 32,
          'not null' => true,
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

  /**
   * Gets the countdown time as a local DrupalDateTime instance.
   *
   * @return \Drupal\Core\DateTime\DrupalDateTime
   */
  public function getCountdownDate() : DrupalDateTime {
    $value = $this->get('countdown_date')->getValue();
    $date = self::fromStorageRepr($value);
    return $date;
  }
}
