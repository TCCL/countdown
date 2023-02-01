<?php

/**
 * CountdownRenderElement.php
 *
 * countdown
 */

namespace Drupal\countdown\Element;

use Drupal\Core\DateTime\DrupalDateTime;
use Drupal\Core\Render\Element\ElementInterface;
use Drupal\Core\Render\Element\RenderElement;
use Drupal\Core\Template\Attribute;

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
    if ($countdownDate instanceof \DateTime || $countdownDate instanceof DrupalDateTime) {
      $repr = $countdownDate->format('Y-m-d\TH:i:s.000');

      $tz = $countdownDate->getTimezone();
      $offset = $tz->getOffset(new \DateTime('now',new \DateTimeZone('UTC')));

      if ($offset != 0) {
        $sign = ($offset < 0 ? '-' : '+');
        $offset = abs($offset);
        $hours = intdiv($offset,3600);
        $minutes = intdiv($offset % 3600,60);

        $repr .= $sign
              . str_pad($hours,2,'0',STR_PAD_LEFT)
              . ':'
              . str_pad($minutes,2,'0',STR_PAD_LEFT);
      }
      else {
        $repr .= 'Z';
      }

      $element['#countdown_date'] = $repr;
    }

    // Create countdown settings array.
    $element['#countdown_settings']['format'] = $element['#countdown_format'];
    $element['#countdown_settings']['include_seconds'] = $element['#countdown_include_seconds'];

    if (!($element['#attributes'] instanceof Attribute)) {
      $element['#attributes'] = new Attribute($element['#attributes']);
    }

    return $element;
  }

  /**
   * Makes a default countdown date.
   */
  public static function makeDefaultCountdownDate(int $nhours = 1) : \DateTime {
    $dt = new \DateTime;
    $int = new \DateInterval("PT${nhours}H");
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
        'class' => ['countdown-widget'],
      ],

      '#countdown_date' => self::makeDefaultCountdownDate(),
      '#countdown_format' => 'clock',
      '#countdown_include_seconds' => true,
    ];
  }
}
