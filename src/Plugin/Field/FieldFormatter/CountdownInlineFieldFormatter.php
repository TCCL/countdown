<?php

/**
 * CountdownInlineFieldFormatter.php
 *
 * countdown
 */

namespace Drupal\countdown\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @FieldFormatter(
 *   id = "countdown_inline",
 *   label = "Inline Countdown",
 *   field_types = {
 *     "countdown"
 *   }
 * )
 */
class CountdownInlineFieldFormatter extends FormatterBase {
  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $settings = parent::defaultSettings();

    $settings['format'] = 'clock';
    $settings['include_seconds'] = true;

    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form,FormStateInterface $form_state) {
    $form = parent::settingsForm($form,$form_state);

    $form['format'] = [
      '#type' => 'select',
      '#title' => $this->t('Format'),
      '#description' => $this->t(
        'Configure the countdown format used to render the remaining time.'
      ),
      '#default_value' => $this->getSetting('format'),
      '#options' => [
        'raw' => 'Raw Properties',
        'clock' => 'Clock',
        'words' => 'Words',
      ],
    ];

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
      'format' => $this->getSetting('format'),
      'include_seconds' => $this->getSetting('include_seconds'),
    ];

    $elements = [];
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'countdown_inline',
        '#countdown_date' => $item->getCountdownDate(),
        '#countdown_format' => $settings['format'],
        '#countdown_include_seconds' => $settings['include_seconds'],
      ];
    }

    return $elements;
  }
}
