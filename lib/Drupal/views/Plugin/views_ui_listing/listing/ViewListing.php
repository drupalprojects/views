<?php

/**
 * @file
 * Definition of Drupal\views\Plugin\views_ui_listing\listing\ViewListing;
 */

namespace Drupal\views\Plugin\views_ui_listing\listing;

use Drupal\views_ui_listing\Plugin\ConfigEntityListingBase;
use Drupal\Core\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;
use Drupal\entity\EntityInterface;

/**
 * Provides a listing of Views.
 *
 * @Plugin(
 *   id = "view",
 *   entity_type = "view",
 *   path = "admin/structure/views",
 *   page_title = @Translation("Views"),
 *   page_description = @Translation("Manage customized lists of content.")
 * )
 */
class ViewListing extends ConfigEntityListingBase {

  /**
   * Overrides Drupal\config\Plugin\ConfigEntityListingBase::hookMenu();
   */
  public function hookMenu() {
    $items = parent::hookMenu();
    // Override the access callback.
    // @todo Probably won't need to specify user access.
    $items[$this->definition['path']]['access callback'] = 'user_access';
    $items[$this->definition['path']]['access arguments'] = array('administer views');
    return $items;
  }

  /**
   * Overrides Drupal\config\Plugin\ConfigEntityListingBase::getList();
   */
  public function getList() {
    $list = parent::getList();
    uasort($list, function ($a, $b) {
      $a_enabled = $a->isEnabled();
      $b_enabled = $b->isEnabled();
      if ($a_enabled != $b_enabled) {
        return $a_enabled < $b_enabled;
      }
      return $a->id() > $b->id();
    });
    return $list;
  }

  /**
   * Overrides Drupal\config\Plugin\ConfigEntityListingBase::getRowData();
   */
  public function getRowData(EntityInterface $view) {
    $operations = $this->buildActionLinks($view);
    $operations['#theme'] = 'links__ctools_dropbutton';
    return array(
      'data' => array(
        'view_name' => theme('views_ui_view_info', array('view' => $view)),
        'description' => $view->description,
        'tag' => $view->tag,
        'path' => implode(', ', $view->getPaths()),
        'operations' => drupal_render($operations),
      ),
      'title' => t('Machine name: ') . $view->id(),
      'class' => array($view->isEnabled() ? 'views-ui-list-enabled' : 'views-ui-list-disabled'),
    );
  }

  /**
   * Overrides Drupal\config\Plugin\ConfigEntityListingBase::getRowData();
   */
  public function getHeaderData() {
    return array(
      'view_name' => array(
        'data' => t('View name'),
        'class' => array('views-ui-name'),
      ),
      'description' => array(
        'data' => t('Description'),
        'class' => array('views-ui-description'),
      ),
      'tag' => array(
        'data' => t('Tag'),
        'class' => array('views-ui-tag'),
      ),
      'path' => array(
        'data' => t('Path'),
        'class' => array('views-ui-path'),
      ),
      'actions' => array(
        'data' => t('Operations'),
        'class' => array('views-ui-operations'),
      ),
    );
  }

  /**
   * Implements Drupal\config\Plugin\ConfigEntityListingInterface::defineActionLinks();
   */
  public function defineActionLinks(EntityInterface $view) {
    $path = $this->definition['path'] . '/view/' . $view->id();
    $enabled = $view->isEnabled();

    if (!$enabled) {
      $definition['enable'] = array(
        'title' => t('Enable'),
        'ajax' => TRUE,
        'token' => TRUE,
        'href' => "$path/enable",
      );
    }
    $definition['edit'] = array(
      'title' => t('Edit'),
      'href' => "$path/edit",
    );
    if ($enabled) {
      $definition['disable'] = array(
        'title' => t('Disable'),
        'ajax' => TRUE,
        'token' => TRUE,
        'href' => "$path/disable",
      );
    }
    // This property doesn't exist yet.
    if (!empty($view->overridden)) {
      $definition['revert'] = array(
        'title' => t('Revert'),
        'href' => "$path/revert",
      );
    }
    else {
      $definition['delete'] = array(
        'title' => t('Delete'),
        'href' => "$path/delete",
      );
    }
    return $definition;
  }

  /**
   * Overrides Drupal\config\Plugin\ConfigEntityListingBase::renderList();
   */
  public function renderList() {
    $list = parent::renderList();
    $list['#attached']['css'] = views_ui_get_admin_css();
    return $list;
  }

}
