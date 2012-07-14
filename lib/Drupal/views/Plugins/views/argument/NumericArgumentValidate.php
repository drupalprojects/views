<?php

/**
 * @file
 * Definition of Drupal\views\Plugins\views\argument\NumericArgumentValidate.
 */

namespace Drupal\views\Plugins\views\argument;

/**
 * Validate whether an argument is numeric or not.
 *
 * @ingroup views_argument_validate_plugins
 */
class NumericArgumentValidate extends ArgumentValidatePlugin {
  function validate_argument($argument) {
    return is_numeric($argument);
  }
}
