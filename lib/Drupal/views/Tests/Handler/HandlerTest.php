<?php

/**
 * @file
 * Definition of Drupal\views\Tests\Handler\HandlerTest.
 */

namespace Drupal\views\Tests\Handler;

use stdClass;
use Drupal\views\View;
use Drupal\views\Tests\ViewTestBase;
use Drupal\views\Plugin\views\HandlerBase;

/**
 * Tests abstract handlers of views.
 */
class HandlerTest extends ViewTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('views_ui');

  public static function getInfo() {
    return array(
      'name' => 'Handler: Base',
      'description' => 'Tests abstract handler definitions.',
      'group' => 'Views Handlers',
    );
  }

  protected function setUp() {
    parent::setUp();

    $this->enableViewsTestModule();
  }

  /**
   * Overrides Drupal\views\Tests\ViewTestBase::viewsData().
   */
  protected function viewsData() {
    $data = parent::viewsData();
    // Override the name handler to be able to call placeholder() from outside.
    $data['views_test_data']['name']['field']['id'] = 'test_field';

    // Setup one field with an access callback and one with an access callback
    // and arguments.
    $data['views_test_data']['access_callback'] = $data['views_test_data']['id'];
    $data['views_test_data']['access_callback_arguments'] = $data['views_test_data']['id'];
    foreach (View::viewsHandlerTypes() as $type => $info) {
      if (isset($data['views_test_data']['access_callback'][$type]['id'])) {
        $data['views_test_data']['access_callback'][$type]['access callback'] = 'views_test_data_handler_test_access_callback';

        $data['views_test_data']['access_callback_arguments'][$type]['access callback'] = 'views_test_data_handler_test_access_callback';
        $data['views_test_data']['access_callback_arguments'][$type]['access arguments'] = array(TRUE);
      }
    }


    return $data;
  }


  /**
   * @todo
   * This should probably moved to a filter related test.
   */
  function testFilterInOperatorUi() {
    $admin_user = $this->drupalCreateUser(array('administer views', 'administer site configuration'));
    $this->drupalLogin($admin_user);
    menu_router_rebuild();

    $path = 'admin/structure/views/nojs/config-item/test_filter_in_operator_ui/default/filter/type';
    $this->drupalGet($path);
    $this->assertFieldByName('options[expose][reduce]', FALSE);

    $edit = array(
      'options[expose][reduce]' => TRUE,
    );
    $this->drupalPost($path, $edit, t('Apply'));
    $this->drupalGet($path);
    $this->assertFieldByName('options[expose][reduce]', TRUE);
  }

  /**
   * Tests views_break_phrase_string function.
   *
   * @todo
   * This should probably moved to the module test.
   */
  function test_views_break_phrase_string() {
    $empty_stdclass = new StdClass();
    $empty_stdclass->operator = 'or';
    $empty_stdclass->value = array();

    views_include('handlers');
    $null = NULL;

    // check defaults
    $this->assertEqual($empty_stdclass, views_break_phrase_string('', $null));

    $handler = views_get_handler('node', 'title', 'argument');
    $this->assertEqual($handler, views_break_phrase_string('', $handler), 'The views_break_phrase_string() works correctly.');

    // test ors
    $handler = views_break_phrase_string('word1 word2+word');
    $this->assertEqualValue(array('word1', 'word2', 'word'), $handler);
    $this->assertEqual('or', $handler->operator);
    $handler = views_break_phrase_string('word1+word2+word');
    $this->assertEqualValue(array('word1', 'word2', 'word'), $handler);
    $this->assertEqual('or', $handler->operator);
    $handler = views_break_phrase_string('word1 word2 word');
    $this->assertEqualValue(array('word1', 'word2', 'word'), $handler);
    $this->assertEqual('or', $handler->operator);
    $handler = views_break_phrase_string('word-1+word-2+word');
    $this->assertEqualValue(array('word-1', 'word-2', 'word'), $handler);
    $this->assertEqual('or', $handler->operator);
    $handler = views_break_phrase_string('wõrd1+wõrd2+wõrd');
    $this->assertEqualValue(array('wõrd1', 'wõrd2', 'wõrd'), $handler);
    $this->assertEqual('or', $handler->operator);

    // test ands.
    $handler = views_break_phrase_string('word1,word2,word');
    $this->assertEqualValue(array('word1', 'word2', 'word'), $handler);
    $this->assertEqual('and', $handler->operator);
    $handler = views_break_phrase_string('word1 word2,word');
    $this->assertEqualValue(array('word1 word2', 'word'), $handler);
    $this->assertEqual('and', $handler->operator);
    $handler = views_break_phrase_string('word1,word2 word');
    $this->assertEqualValue(array('word1', 'word2 word'), $handler);
    $this->assertEqual('and', $handler->operator);
    $handler = views_break_phrase_string('word-1,word-2,word');
    $this->assertEqualValue(array('word-1', 'word-2', 'word'), $handler);
    $this->assertEqual('and', $handler->operator);
    $handler = views_break_phrase_string('wõrd1,wõrd2,wõrd');
    $this->assertEqualValue(array('wõrd1', 'wõrd2', 'wõrd'), $handler);
    $this->assertEqual('and', $handler->operator);

    // test a single word
    $handler = views_break_phrase_string('word');
    $this->assertEqualValue(array('word'), $handler);
    $this->assertEqual('and', $handler->operator);
  }

  /**
   * Tests views_break_phrase function.
   *
   * @todo
   * This should probably moved to the module test.
   */
  function test_views_break_phrase() {
    $empty_stdclass = new StdClass();
    $empty_stdclass->operator = 'or';
    $empty_stdclass->value = array();

    $null = NULL;
    // check defaults
    $this->assertEqual($empty_stdclass, views_break_phrase('', $null));

    $handler = views_get_handler('node', 'title', 'argument');
    $this->assertEqual($handler, views_break_phrase('', $handler), 'The views_break_phrase() function works correctly.');

    // Generate three random numbers which can be used below;
    $n1 = rand(0, 100);
    $n2 = rand(0, 100);
    $n3 = rand(0, 100);
    // test ors
    $this->assertEqualValue(array($n1, $n2, $n3), views_break_phrase("$n1 $n2+$n3", $handler));
    $this->assertEqual('or', $handler->operator);
    $this->assertEqualValue(array($n1, $n2, $n3), views_break_phrase("$n1+$n2+$n3", $handler));
    $this->assertEqual('or', $handler->operator);
    $this->assertEqualValue(array($n1, $n2, $n3), views_break_phrase("$n1 $n2 $n3", $handler));
    $this->assertEqual('or', $handler->operator);
    $this->assertEqualValue(array($n1, $n2, $n3), views_break_phrase("$n1 $n2++$n3", $handler));
    $this->assertEqual('or', $handler->operator);

    // test ands.
    $this->assertEqualValue(array($n1, $n2, $n3), views_break_phrase("$n1,$n2,$n3", $handler));
    $this->assertEqual('and', $handler->operator);
    $this->assertEqualValue(array($n1, $n2, $n3), views_break_phrase("$n1,,$n2,$n3", $handler));
    $this->assertEqual('and', $handler->operator);
  }

  /**
   * Check to see if a value is the same as the value on a certain handler.
   *
   * @param $first
   *   The first value to check.
   * @param Drupal\views\Plugin\views\HandlerBase $handler
   *   The handler that has the $handler->value property to compare with first.
   * @param string $message
   *   The message to display along with the assertion.
   * @param string $group
   *   The type of assertion - examples are "Browser", "PHP".
   *
   * @return bool
   *   TRUE if the assertion succeeded, FALSE otherwise.
   */
  protected function assertEqualValue($first, $handler, $message = '', $group = 'Other') {
    return $this->assert($first == $handler->value, $message ? $message : t('First value is equal to second value'), $group);
  }

  /**
   * Tests the relationship ui for field/filter/argument/relationship.
   */
  public function testRelationshipUI() {
    $views_admin = $this->drupalCreateUser(array('administer views'));
    $this->drupalLogin($views_admin);

    // Make sure the link to the field options exists.
    $handler_options_path = 'admin/structure/views/nojs/config-item/test_handler_relationships/default/field/title';
    $view_edit_path = 'admin/structure/views/view/test_handler_relationships/edit';
    $this->drupalGet($view_edit_path);
    $this->assertLinkByHref($handler_options_path);

    // The test view has a relationship to node_revision so the field should
    // show a relationship selection.

    $this->drupalGet($handler_options_path);
    $relationship_name = 'options[relationship]';
    $this->assertFieldByName($relationship_name);

    // Check for available options.
    $xpath = $this->constructFieldXpath('name', $relationship_name);
    $fields = $this->xpath($xpath);
    $options = array();
    foreach ($fields as $field) {
      $items = $this->getAllOptions($field);
      foreach ($items as $item) {
        $options[] = $item->attributes()->value;
      }
    }
    $expected_options = array('none', 'vid');
    $this->assertEqual($options, $expected_options);

    // Remove the relationship and take sure no relationship option appears.
    $this->drupalPost('admin/structure/views/nojs/config-item/test_handler_relationships/default/relationship/vid_1', array(), t('Remove'));
    $this->drupalGet($handler_options_path);
    $this->assertNoFieldByName($relationship_name, 'Make sure that no relationship option is available');
  }

  /**
   * Tests the relationship method on the base class.
   */
  public function testSetRelationship() {
    $view = $this->createViewFromConfig('test_handler_relationships');
    // Setup a broken relationship.
    $view->addItem('default', 'relationship', $this->randomName(), $this->randomName(), array(), 'broken_relationship');
    $view->addItem('default', 'relationship', 'node', 'uid', array(), 'valid_relationship');
    // Setup a valid relationship.
    $view->initHandlers();
    $field = $view->field['title'];

    $field->options['relationship'] = NULL;
    $field->setRelationship();
    $this->assertFalse($field->relationship, 'Make sure that an empty relationship does not create a relationship on the field.');

    $field->options['relationship'] = $this->randomName();
    $field->setRelationship();
    $this->assertFalse($field->relationship, 'Make sure that a random relationship does not create a relationship on the field.');

    $field->options['relationship'] = 'broken_relationship';
    $field->setRelationship();
    $this->assertFalse($field->relationship, 'Make sure that a broken relationship does not create a relationship on the field.');

    $field->options['relationship'] = 'valid_relationship';
    $field->setRelationship();
    $this->assertFalse(!empty($field->relationship), 'Make sure that the relationship alias was not set without buildung a views query before.');

    $view->build();
    $field->setRelationship();
    $this->assertEqual($field->relationship, $view->relationship['valid_relationship']->alias, 'Make sure that a valid relationship does create the right relationship query alias.');
  }

  /**
   * Tests the placeholder function.
   *
   * @see Drupal\views\Plugin\views\HandlerBase::placeholder()
   */
  public function testPlaceholder() {
    $view = $this->getView();
    $view->initHandlers();
    $view->initQuery();

    $handler = $view->field['name'];
    $table = $handler->table;
    $field = $handler->field;
    $string = ':' . $table . '_' . $field;

    // Make sure the placeholder variables are like expected.
    $this->assertEqual($handler->getPlaceholder(), $string);
    $this->assertEqual($handler->getPlaceholder(), $string . 1);
    $this->assertEqual($handler->getPlaceholder(), $string . 2);

    // Set another table/field combination and make sure there are new
    // placeholders.
    $table = $handler->table = $this->randomName();
    $field = $handler->field = $this->randomName();
    $string = ':' . $table . '_' . $field;

    // Make sure the placeholder variables are like expected.
    $this->assertEqual($handler->getPlaceholder(), $string);
    $this->assertEqual($handler->getPlaceholder(), $string . 1);
    $this->assertEqual($handler->getPlaceholder(), $string . 2);
  }

  /**
   * Tests access to a handler.
   *
   * @see views_test_data_handler_test_access_callback
   */
  public function testAccess() {
    $view = views_get_view('test_handler_test_access');
    $views_data = $this->viewsData();
    $views_data = $views_data['views_test_data'];

    // Enable access to callback only field and deny for callback + arguments.
    variable_set('views_test_data_handler_test_access_callback', TRUE);
    variable_set('views_test_data_handler_test_access_callback_argument', FALSE);
    $view->initDisplay();
    $view->initHandlers();

    foreach ($views_data['access_callback'] as $type => $info) {
      if (!in_array($type, array('title', 'help'))) {
        $this->assertTrue($view->field['access_callback'] instanceof HandlerBase, 'Make sure the user got access to the access_callback field ');
        $this->assertFalse(isset($view->field['access_callback_arguments']), 'Make sure the user got no access to the access_callback_arguments field ');
      }
    }

    // Enable access to the callback + argument handlers and deny for callback.
    variable_set('views_test_data_handler_test_access_callback', FALSE);
    variable_set('views_test_data_handler_test_access_callback_argument', TRUE);
    $view->destroy();
    $view->initDisplay();
    $view->initHandlers();

    foreach ($views_data['access_callback'] as $type => $info) {
      if (!in_array($type, array('title', 'help'))) {
        $this->assertFalse(isset($view->field['access_callback']), 'Make sure the user got no access to the access_callback field ');
        $this->assertTrue($view->field['access_callback_arguments'] instanceof HandlerBase, 'Make sure the user got access to the access_callback_arguments field ');
      }
    }
  }

}
