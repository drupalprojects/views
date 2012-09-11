<?php

/**
 * Definition of Drupal\views_ui_listing_test\Plugin\views_ui_listing\listing\ConfigTestConfigEntityListing.
 */

namespace Drupal\views_ui_listing_test\Plugin\views_ui_listing\listing;

use Drupal\views_ui_listing\Plugin\ConfigEntityListingBase;
use Drupal\Core\Annotation\Plugin;
use Drupal\entity\EntityInterface;

/**
 * Views config entity listing plugin.
 *
 * @Plugin(
 *   id = "config_test",
 *   entity_type = "config_test",
 *   path = "config-listing-test",
 *   page_title = "Config test",
 *   page_description = "Config test listing page"
 * )
 */
class ConfigTestConfigEntityListing extends ConfigEntityListingBase {

  /**
   * Implements Drupal\views_ui_listing\Plugin\ConfigEntityListingInterface::actionLinkMappings().
   *
   * @return array
   *   An array of action links.
   *   @see theme_links
   */
  public function defineActionLinks(EntityInterface $entity) {
    $id = $entity->id();

    // @todo Add AJAX link to test.
    return array(
      'edit' => array(
        'title' => 'edit',
        'href' => "admin/structure/config_test/manage/$id/edit",
        'ajax' => FALSE,
      ),
      'add' => array(
        'title' => 'add',
        'href' => "admin/structure/config_test/add",
        'ajax' => FALSE,
      ),
      'delete' => array(
        'title' => 'delete',
        'href' => "admin/structure/config_test/manage/$id/delete",
        'ajax' => FALSE,
      ),
    );
  }

}
