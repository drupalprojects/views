<?php

/**
 * @file
 * Definition of Drupal\views\Plugins\views\access\AccessNone.
 */

namespace Drupal\views\Plugins\views\access;

/**
 * Access plugin that provides no access control at all.
 *
 * @ingroup views_access_plugins
 */
class AccessNone extends AccessPlugin {
  function summary_title() {
    return t('Unrestricted');
  }
}
