<?php

/**
 * Definition of Drupal\views_ui_listing\Plugin\Type\ConfigEntityListingManager.
 */

namespace Drupal\views_ui_listing\Plugin\Type;

use Drupal\Component\Plugin\PluginManagerBase;
use Drupal\Component\Plugin\Factory\DefaultFactory;
use Drupal\Core\Plugin\Discovery\AnnotatedClassDiscovery;
use Drupal\Core\Plugin\Discovery\CacheDecorator;
use Drupal\Component\Plugin\Exception\PluginException;

/**
 * Plugin manager for configuration entity listings.
 */
class ConfigEntityListingManager extends PluginManagerBase {

  public function __construct() {
    $this->discovery = new CacheDecorator(new AnnotatedClassDiscovery('views_ui_listing', 'listing'), 'views:listing', 'cache');
    $this->factory = new DefaultFactory($this);
  }

}
