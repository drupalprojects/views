<?php

/**
 * @file
 * Definition of Drupal\views\Plugins\views\style\DefaultStyle.
 */

namespace Drupal\views\Plugins\views\style;

/**
 * Default style plugin to render rows one after another with no
 * decorations.
 *
 * @ingroup views_style_plugins
 */
class DefaultStyle extends StylePlugin {
  /**
   * Set default options
   */
  function options(&$options) {
    parent::options($options);
  }

  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
  }
}
