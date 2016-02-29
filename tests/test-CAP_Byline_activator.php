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
	  require(plugin_dir_path(__FILE__) . "/../settings/CAP_Byline_gravityforms.php");

	  $this->assertEquals(count($CAP_Byline_gravityforms), $wpdb->get_var("SELECT count(1) FROM " . $wpdb->prefix . "rg_form;"));

	  foreach($CAP_Byline_gravityforms as $v) {
	    $this->assertEquals(
	}
}

