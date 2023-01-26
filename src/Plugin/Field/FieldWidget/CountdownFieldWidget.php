<?php

/**
 * CountdownFieldWidget.php
 *
 * countdown
 */

namespace Drupal\countdown\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

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
    $dt = new \DateTime;
    $int = new \DateInterval('PT1D');
    $dt = $dt->add($int);

    $element += [
      '#type' => 'datetime',
      '#title' => $this->t('Countdown Date'),
      '#default_value' => $dt->format('Y-m-d H:i:s'),
    ];

    return $element;
  }
}
