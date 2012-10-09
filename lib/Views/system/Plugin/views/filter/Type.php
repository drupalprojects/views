<?php

/**
 * @file
 * Definition of Views\system\Plugin\views\filter\Type.
 */

namespace Views\system\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\InOperator;
use Drupal\Core\Annotation\Plugin;

/**
 * Filter by system type.
 *
 * @Plugin(
 *   id = "system_type",
 *   module = "system"
 * )
 */
class Type extends InOperator {

  function get_value_options() {
    if (!isset($this->value_options)) {
      $this->value_title = t('Type');
      // Enable filtering by type.
      $this->value_options = array('module', 'theme', 'engine');
    }
  }

}
