<?php

/**
 * @file
 * Definition of Drupal\views\Plugins\views\sort\RandomSort.
 */

namespace Drupal\views\Plugins\views\sort;

/**
 * Handle a random sort.
 *
 * @ingroup views_sort_handlers
 */
class RandomSort extends SortPlugin {
  function query() {
    $this->query->add_orderby('rand');
  }

  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $form['order']['#access'] = FALSE;
  }
}
