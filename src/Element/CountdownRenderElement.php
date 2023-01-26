<?php

/**
 * CountdownRenderElement.php
 *
 * countdown
 */

namespace Drupal\countdown\Element;

use Drupal\Core\Render\Element\ElementInterface;
use Drupal\Core\Render\Element\RenderElement;

/**
 * @RenderElement("countdown")
 */
class CountdownRenderElement extends RenderElement implements ElementInterface {
  /**
   * Pre-renders the render element.
   *
   * @param array $element
   *
   * @return array
   */
  public static function preRender(array $element) {
    // Convert Date instance to string in ISO 8601 format.
    $countdownDate
      = $element['#countdown_date'] ?? self::makeDefaultCountdownDate();
    if ($countdownDate instanceof \DateTime) {
      $element['#countdown_date'] = $countdownDate->format('Y-m-d\TH:i:s.Z\Z');
    }
  }

  /**
   * Makes a default countdown date.
   */
  protected static function makeDefaultCountdownDate(int $ndays = 1) : \DateTime {
    $dt = new \DateTime;
    $int = new \DateInterval("PT${ndays}D");
    $dt = $dt->add($int);

    return $dt;
  }

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    return [
      '#input' => true,
      '#theme' => 'countdown_widget',
      '#pre_render' => [
        [get_class(),'preRender'],
      ],
      '#attached' => [
        'library' => [
          'countdown/widget',
        ],
      ],
      '#attributes' => [
        'class' => 'countdown-widget',
      ],

      '#countdown_date' => self::makeDefaultCountdownDate(),
    ];
  }
}
