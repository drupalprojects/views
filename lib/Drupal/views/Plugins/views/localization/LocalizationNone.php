<?php

/**
 * @file
 * Definition of Drupal\views\Plugins\views\localization\LocalizationNone.
 */

namespace Drupal\views\Plugins\views\localization;

/**
 * Localization plugin for no localization.
 *
 * @ingroup views_localization_plugins
 */
class LocalizationNone extends LocalizationPlugin {
  var $translate = FALSE;

  /**
   * Translate a string; simply return the string.
   */
  function translate($source) {
    return $source['value'];
  }

  /**
   * Save a string for translation; not supported.
   */
  function save($source) {
    return FALSE;
  }

  /**
   * Delete a string; not supported.
   */
  function delete($source) {
    return FALSE;
  }
}
