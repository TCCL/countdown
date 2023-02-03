<?php

/**
 * CountdownInlineRenderElement.php
 *
 * countdown
 */

namespace Drupal\countdown\Element;

use Drupal\Core\Render\Element\ElementInterface;

/**
 * @RenderElement("countdown_inline")
 */
class CountdownInlineRenderElement extends CountdownRenderElementBase implements ElementInterface {
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
    $element['#countdown_settings']['format'] = $element['#countdown_format'];
    $element['#countdown_settings']['include_seconds'] = $element['#countdown_include_seconds'];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    return [
      '#input' => true,
      '#theme' => 'countdown_inline',
      '#pre_render' => [
        [get_class(),'preRender'],
      ],
      '#attached' => [
        'library' => [
          'countdown/inline',
        ],
      ],
      '#attributes' => [
        'class' => ['countdown-inline'],
      ],

      '#countdown_date' => self::makeDefaultCountdownDate(),
      '#countdown_format' => 'clock',
      '#countdown_include_seconds' => true,
    ];
  }
}
