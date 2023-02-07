<?php

/**
 * CountdownFieldWidget.php
 *
 * countdown
 */

namespace Drupal\countdown\Plugin\Field\FieldWidget;

use Drupal\Core\DateTime\DrupalDateTime;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\countdown\Plugin\Field\FieldType\CountdownFieldItem;

/**
 * @FieldWidget(
 *   id = "countdown",
 *   label = "Countdown Widget",
 *   description = "Configures the countdown field",
 *   field_types = {
 *     "countdown"
 *   }
 * )
 */
class CountdownFieldWidget extends WidgetBase {
  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $settings = parent::defaultSettings();

    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form,FormStateInterface $form_state) {
    $form = parent::settingsForm($form,$form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(
    FieldItemListInterface $items,
    $delta,
    array $element,
    array &$form,
    FormStateInterface $form_state)
  {
    if (isset($items[$delta]->countdown_date)) {
      $dt = CountdownFieldItem::fromStorageRepr($items[$delta]->countdown_date);
    }
    else {
      // Let the default countdown date be one hour from the current moment.
      $dt = new DrupalDateTime;
      $int = new \DateInterval('PT1H');
      $dt = $dt->add($int);
    }

    $element['countdown_date'] = $element + [
      '#type' => 'datetime',
      '#title' => $this->t('Countdown Date'),
      '#default_value' => $dt,
      '#description' => (
        'The date to which the timer will count down.'
      ),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values,array $form,FormStateInterface $form_state) {
    foreach ($values as &$item) {
      $dateStorage = CountdownFieldItem::toStorageRepr($item['countdown_date']);
      $item['countdown_date'] = $dateStorage;
    }
    unset($item);

    return $values;
  }
}
