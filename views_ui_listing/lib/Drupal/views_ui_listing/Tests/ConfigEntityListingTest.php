<?php

/**
 * @file
 * Definition of Drupal\views_ui_listing\Tests\ConfigEntityListingTest.
 */

namespace Drupal\config\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\views_ui_listing\Plugin\Type\ConfigEntityListingManager;
use Drupal\views_ui_listing\Plugin\views_ui_listing\listing\ConfigTestConfigEntityListing;
use Drupal\config\ConfigEntityBase;

/**
 * Tests configuration entities.
 */
class ConfigEntityListingTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('views_ui_listing_test');

  public static function getInfo() {
    return array(
      'name' => 'Views configuration entity listing',
      'description' => 'Tests configuration entity listing plugins.',
      'group' => 'Views',
    );
  }

  /**
   * Tests basic listing plugin functionilty.
   */
  function testListingPlugin() {
    // Test we can get a new manager instance.
    $listing_manager = new ConfigEntityListingManager();
    $this->assertTrue($listing_manager instanceof ConfigEntityListingManager, 'ConfigEntityListingManager class created.');

    // Test the definition data.
    $definitions = $listing_manager->getDefinitions();

    $this->assertTrue(array_key_exists('config_test', $definitions), 'Config test definition exists.');

    $expected = array(
      'id' => 'config_test',
      'entity_type' => 'config_test',
      'path' => 'config-listing-test',
      'page_title' => 'Config test',
      'page_description' => 'Config test listing page',
      'class' => 'Drupal\\config_test\\Plugin\\config\\listing\\ConfigTestConfigEntityListing',
    );
    $this->assertEqual($definitions['config_test'], $expected, 'Plugin definition matches the expected definition.');

    $instance = $listing_manager->createInstance('config_test');
    $this->assertTrue($instance instanceof ConfigTestConfigEntityListing, 'ConfigTestConfigEntityListing plugin instance created.');

    // Get a list of Config entities.
    $list = $instance->getList();
    $this->assertEqual(count($list), 1, 'Correct number of plugins found.');
    $this->assertTrue(!empty($list['default']), '"Default" config entity key found in list.');
    $this->assertTrue($list['default'] instanceof ConfigEntityBase, '"Default" config entity is an instance of ConfigEntityBase');
  }

  /**
   * Tests the listing UI.
   */
  function testListingUI() {
    $page = $this->drupalGet('config-listing-test');

    // Test that the page exists.
    $this->assertText('Config test', 'Config test listing page title found.');

    // Check we have the default id and label on the page too.
    $this->assertText('default', '"default" ID found.');
    $this->assertText('Default', '"Default" label found');

    // Check each link.
    foreach (array('edit', 'add', 'delete') as $link) {
      $this->drupalSetContent($page);
      $this->assertLink($link);
      $this->clickLink($link);
      $this->assertResponse(200);
    }

    // @todo Test AJAX links.
  }

}
