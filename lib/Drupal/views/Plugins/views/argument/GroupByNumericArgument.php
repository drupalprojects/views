<?php

/**
 * @file
 * Definition of Drupal\views\Plugins\views\argument\GroupByNumericArgument.
 */

namespace Drupal\views\Plugins\views\argument;

/**
 * Simple handler for arguments using group by.
 *
 * @ingroup views_argument_handlers
 */
class GroupByNumericArgument extends ArgumentPlugin  {
  function query($group_by = FALSE) {
    $this->ensure_my_table();
    $field = $this->get_field();
    $placeholder = $this->placeholder();

    $this->query->add_having_expression(0, "$field = $placeholder", array($placeholder => $this->argument));
  }

  function ui_name($short = FALSE) {
    return $this->get_field(parent::ui_name($short));
  }

  function get_sort_name() {
    return t('Numerical', array(), array('context' => 'Sort order'));
  }
}
