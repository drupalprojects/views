<?php

/**
 * @file
 * Definition of Drupal\views_ui_listing\Plugin\ConfigEntityListingInterface.
 */

namespace Drupal\views_ui_listing\Plugin;

use Drupal\entity\EntityInterface;

/**
 * Defines an interface for Configuration entity listing plugins.
 */
interface ConfigEntityListingInterface {

  /*
   * Returns a list of all available config entites of this type.
   */
  public function getList();

  /**
   * Gets the ConfigEntityController.
   *
   * @todo Put in correct namespace and docs here.
   */
  public function getController();

  /**
   * Gets the hook_menu array item.
   *
   * @todo Put in correct docs here.
   */
  public function hookMenu();

  /**
   * Builds an array of data for each row.
   *
   * @param EntityInterface $entity
   *
   * @return array
   *   An array of fields to use for this entity.
   */
  public function getRowData(EntityInterface $entity);

  /**
   * Builds the header row.
   *
   * @return array
   *   An array of header strings.
   */
  public function getHeaderData();

  /**
   * Renders the list page markup to be output.
   *
   * @return string
   *   The output markup for the listing page.
   */
  public function renderList();

  /**
   * Renders a list of action links.
   *
   * @return array
   */
  public function buildActionLinks(EntityInterface $entity);

  /**
   * Provides an array of information to render action links.
   *
   * @return array
   */
  public function defineActionLinks(EntityInterface $entity);

}
