<?php

/**
 * @file
 * Definition of Drupal\views\Plugins\views\cache\CacheNone.
 */

namespace Drupal\views\Plugins\views\cache;

/**
 * Caching plugin that provides no caching at all.
 *
 * @ingroup views_cache_plugins
 */
class CacheNone extends CachePlugin {
  function cache_start() { /* do nothing */ }

  function summary_title() {
    return t('None');
  }

  function cache_get($type) {
    return FALSE;
  }

  function cache_set($type) { }
}
