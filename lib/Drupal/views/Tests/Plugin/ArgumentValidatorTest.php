<?php

/**
 * @file
 * Definition of Drupal\views\Tests\Plugin\ArgumentValidatorTest.
 */

namespace Drupal\views\Tests\Plugin;

/**
 * Tests Views argument validators.
 */
class ArgumentValidatorTest extends PluginTestBase {

  public static function getInfo() {
    return array(
      'name' => 'Argument validator',
      'group' => 'Views Plugins',
      'description' => 'Test argument validator tests.',
    );
  }

  function testArgumentValidatePhp() {
    $string = $this->randomName();
    $view = $this->view_test_argument_validate_php($string);
    $view->setDisplay('default');
    $view->preExecute();
    $view->initHandlers();
    $this->assertTrue($view->argument['null']->validateArgument($string));
    // Reset safed argument validation.
    $view->argument['null']->argument_validated = NULL;
    $this->assertFalse($view->argument['null']->validateArgument($this->randomName()));
  }

  function testArgumentValidateNumeric() {
    $view = $this->view_argument_validate_numeric();
    $view->setDisplay('default');
    $view->preExecute();
    $view->initHandlers();
    $this->assertFalse($view->argument['null']->validateArgument($this->randomString()));
    // Reset safed argument validation.
    $view->argument['null']->argument_validated = NULL;
    $this->assertTrue($view->argument['null']->validateArgument(12));
  }

  function view_test_argument_validate_php($string) {
    $code = 'return $argument == \''. $string .'\';';
    $this->createViewFromConfig('test_view_argument_validate_numeric');
    $view->display_handler->display->display_options['arguments']['null']['validate_options']['code'] = $code;

    return $view;
  }

  function view_argument_validate_numeric() {
    return $this->createViewFromConfig('test_view_argument_validate_numeric');
  }

}
