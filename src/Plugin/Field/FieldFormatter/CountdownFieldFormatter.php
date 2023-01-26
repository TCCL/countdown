<?php

/**
 * CountdownFieldFormatter.php
 *
 * countdown
 */

namespace Drupal\countdown\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @FieldFormatter(
 *   id = "countdown",
 *   label = "Countdown",
 *   field_types = {
 *     "countdown"
 *   }
 * )
 */
class CountdownFieldFormatter extends FormatterBase {
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
  public function viewElements(FieldItemListInterface $items,$langcode = null) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $countdownDate = $item->get('countdown_date')->getValue();
      $countdownDate = date_parse($countdownDate);

      $elements[$delta] = [
        '#type' => 'countdown',
        '#countdown_date' => $countdownDate,
      ];
    }

    return $elements;
  }
}
