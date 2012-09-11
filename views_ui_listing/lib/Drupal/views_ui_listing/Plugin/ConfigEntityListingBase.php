<?php

/**
 * Definition of Drupal\views_ui_listing\Plugin\ConfigEntityListingBase.
 */

namespace Drupal\views_ui_listing\Plugin;

use Drupal\Component\Plugin\PluginBase as ComponentPluginBase;
use Drupal\Component\Plugin\Discovery\DiscoveryInterface;
use Drupal\config\ConfigStorageController;
use Drupal\entity\EntityInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Abstract base class for config entity listing plugins.
 */
abstract class ConfigEntityListingBase extends ComponentPluginBase implements ConfigEntityListingInterface {

  /**
   * The Config storage controller class.
   *
   * @var Drupal\config\ConfigStorageController
   */
  protected $controller;

  /**
   * If ajax links are used on the listing page.
   *
   * @var bool
   */
  protected $usesAJAX;

  /**
   * The plugins's definition.
   *
   * @var array
   */
  public $definition;

  public function __construct(array $configuration, $plugin_id, DiscoveryInterface $discovery) {
    parent::__construct($configuration, $plugin_id, $discovery);
    $this->definition = $this->getDefinition();
    $this->controller = entity_get_controller($this->definition['entity_type']);
  }

  /**
   * Implements Drupal\views_ui_listing\Plugin\ConfigEntityListingInterface::getList();
   */
  public function getList() {
    return $this->controller->load();
  }

  /**
   * Implements Drupal\views_ui_listing\Plugin\ConfigEntityListingInterface::getController();
   */
  public function getController() {
    return $this->controller;
  }

  public function getPath() {
    return $this->definition['path'];
  }

  /**
   * Implements Drupal\views_ui_listing\Plugin\ConfigEntityListingInterface::hookMenu();
   */
  public function hookMenu() {
    $definition = $this->definition;

    return array(
      $definition['path'] => array(
        'title' => $definition['page_title'],
        'description' => $definition['page_description'],
        'page callback' => 'views_ui_listing_entity_listing_page',
        'page arguments' => array($definition['id']),
        // @todo Add a proper access callback here.
        'access callback' => TRUE,
      ),
    );
  }

  /**
   * Implements Drupal\views_ui_listing\Plugin\ConfigEntityListingInterface::getRowData();
   */
  public function getRowData(EntityInterface $entity) {
    $row = array();

    $row['id'] = $entity->id();
    $row['label'] = $entity->label();
    $actions = $this->buildActionLinks($entity);
    $row['actions'] = drupal_render($actions);

    return $row;
  }

  /**
   * Implements Drupal\views_ui_listing\Plugin\ConfigEntityListingInterface::getHeaderData();
   */
  public function getHeaderData() {
    $row = array();
    $row['id'] = t('ID');
    $row['label'] = t('Label');
    $row['actions'] = t('Actions');
    return $row;
  }

  /**
   * Implements Drupal\views_ui_listing\Plugin\ConfigEntityListingInterface::buildActionLinks();
   */
  public function buildActionLinks(EntityInterface $entity) {
    $links = array();

    foreach ($this->defineActionLinks($entity) as $definition) {
      $attributes = array();

      if (!empty($definition['ajax'])) {
        $attributes['class'][] = 'use-ajax';
        // Set this to true if we haven't already.
        if (!isset($this->usesAJAX)) {
          $this->usesAJAX = TRUE;
        }
      }

      $links[] = array(
        'title' => $definition['title'],
        'href' => $definition['href'],
        'attributes' => $attributes,
      );
    }

    return array(
      '#theme' => 'links',
      '#links' => $links,
    );
  }

  /**
   * Implements Drupal\views_ui_listing\Plugin\ConfigEntityListingInterface::renderList();
   */
  public function renderList() {
    $rows = array();

    foreach ($this->getList() as $entity) {
      $rows[] = $this->getRowData($entity);
    }

    // Add core AJAX library if we need to.
    if (!empty($this->usesAJAX)) {
      drupal_add_library('system', 'drupal.ajax');
    }

    return array(
      '#theme' => 'table',
      '#header' => $this->getHeaderData(),
      '#rows' => $rows,
      '#attributes' => array(
        'id' => 'config-entity-listing',
      ),
    );
  }

  /**
   * Implements Drupal\config\Plugin\ConfigEntityListingInterface::renderList();
   */
  public function renderListAJAX() {
    $list = $this->renderList();
    $commands = array();
    $commands[] = ajax_command_replace('#config-entity-listing', drupal_render($list));

    return new JsonResponse(ajax_render($commands));
  }

}
