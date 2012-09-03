<?php

/**
 * @file
 * Definition of Drupal\views\Tests\User\ArgumentDefaultTest.
 */

namespace Drupal\views\Tests\User;

use Drupal\views\View;

/**
 * Tests views user argument default plugin.
 */
class ArgumentDefaultTest extends UserTestBase {

  public static function getInfo() {
    return array(
      'name' => 'User: Argument default',
      'description' => 'Tests user argument default plugin.',
      'group' => 'Views Plugins',
    );
  }

  public function test_plugin_argument_default_current_user() {
    // Create a user to test.
    $account = $this->drupalCreateUser();

    // Switch the user, we have to check the global user too, because drupalLogin is only for the simpletest browser.
    $this->drupalLogin($account);
    global $user;
    $admin = $user;
    drupal_save_session(FALSE);
    $user = $account;

    $view = $this->view_plugin_argument_default_current_user();

    $view->setDisplay('default');
    $view->preExecute();
    $view->initHandlers();

    $this->assertEqual($view->argument['null']->get_default_argument(), $account->uid, 'Uid of the current user is used.');
    // Switch back.
    $user = $admin;
    drupal_save_session(TRUE);
  }

  function view_plugin_argument_default_current_user() {
    return $this->createViewFromConfig('test_plugin_argument_default_current_user');
  }

}
