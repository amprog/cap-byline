<?php

class CAP_Byline_Activator_Test extends WP_UnitTestCase
{
    private $act = null;
    private $gform_available = false;

    function setUp()
    {
      $plugin_dir = plugin_dir_path(__FILE__);

      require_once("$plugin_dir/../includes/class_CAP_Byline_activator.php");

      $theme = wp_get_theme();

      if(class_exists('GFCommon')) {
        $this->gform_available = true;
      }
    }


	function test_activator_without_gravity_forms()
	{
	  CAP_Byline_Activator::$display = false;
	  CAP_Byline_Activator::activate();
	  $this->assertEquals(1, CAP_Byline_Activator::get_attr_status('gravity_forms'));
	}

    /**
     * @depends test_activator_without_gravity_forms
     */
	function test_activator_with_gravity_forms()
	{
	  require dirname(dirname(dirname(__FILE__))) . '/gravity-forms/gravityforms.php';

	  RGFormsModel::drop_tables();
	  GFForms::setup_database();

	  CAP_Byline_Activator::activate();
	  $this->assertEquals(2, CAP_Byline_Activator::get_attr_status('gravity_forms'));
	}

    /**
     * @depends test_activator_with_gravity_forms
     */
	function test_gravity_forms_load()
	{
	  global $wpdb;
	  require(plugin_dir_path(__FILE__) . "/../settings/CAP_Byline_gravityforms.php");

	  $this->assertEquals(count($CAP_Byline_gravityforms), $wpdb->get_var("SELECT count(1) FROM " . $wpdb->prefix . "rg_form;"));

	  foreach($CAP_Byline_gravityforms as $v) {
	    $this->assertEquals($v["title"], $wpdb->get_var("SELECT title FROM " . $wpdb->prefix . "rg_form WHERE id = " . $v["id"] . ";"));
	  }
	}


    /**
     * @depends test_activator_without_gravity_forms
     */
	function test_activator_database()
	{
	  global $wpdb;

	  $this->assertEquals(1, CAP_Byline_Activator::get_attr_status('database'));

	  $plugin_dir = plugin_dir_path(__FILE__);
      require(dirname(__FILE__) . "/../constants/db_definitions.php");

      foreach($CAP_Byline_table_definitions as $k => $v) {
	    $this->assertEquals(1, $wpdb->get_var("SELECT COUNT(1) FROM information_schema.TABLES WHERE TABLE_NAME = '" . $v["name"] . "' AND TABLE_SCHEMA = '" . DB_NAME . "';"));
      }

      foreach($CAP_Byline_view_definitions as $k => $v) {
	    $this->assertEquals(1, $wpdb->get_var("SELECT COUNT(1) FROM information_schema.VIEWS WHERE TABLE_NAME = '" . $v["name"] . "' AND TABLE_SCHEMA = '" . DB_NAME . "';"));
      }

	}


}

