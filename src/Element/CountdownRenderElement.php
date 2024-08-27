<?php

/**
 * CountdownRenderElement.php
 *
 * countdown
 */

namespace Drupal\countdown\Element;

use Drupal\Core\Render\Element\ElementInterface;

/**
 * @RenderElement("countdown")
 */
class CountdownRenderElement extends CountdownRenderElementBase implements ElementInterface {
  /**
   * Pre-renders the render element.
   *
   * @param array $element
   *
   * @return array
   */
  public static function preRender(array $element) {
    $element = parent::preRender($element);

    // Create countdown settings array.
    $element['#countdown_settings']['include_seconds'] = $element['#countdown_include_seconds'];

    return $element;
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
      '#countdown_include_seconds' => true,
    ];
  }
}
