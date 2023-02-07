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

    $settings['include_seconds'] = true;

    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form,FormStateInterface $form_state) {
    $form = parent::settingsForm($form,$form_state);

    $form['include_seconds'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Include Seconds'),
      '#description' => $this->t(
        'Configure whether seconds are included in the countdown.'
      ),
      '#return_value' => 1,
      '#default_value' => $this->getSetting('include_seconds'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items,$langcode = null) {
    $settings = [
      'include_seconds' => $this->getSetting('include_seconds'),
    ];

    $elements = [];
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'countdown',
        '#countdown_date' => $item->getCountdownDate(),
        '#countdown_include_seconds' => $settings['include_seconds'],
      ];
    }

    return $elements;
  }
}
