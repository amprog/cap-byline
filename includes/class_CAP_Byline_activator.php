<?php
/**
 * Defines the actions to take during plugin activation.
 *
 * @link       https://github.com/amprog/cap-byline
 * @since      2.0.0
 *
 * @package    CAP_Byline
 * @subpackage CAP_Byline/includes
 */

/**
 * Copyright (C) 2013 - 2016  The Center for American Progress
 *
 * CAP_Byline is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CAP_Byline is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with CAP_Byline.  If not, see <http://www.gnu.org/licenses/gpl.html>.
 **/

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.0.0
 * @package    CAP_Byline
 * @subpackage CAP_Byline/includes
 * @author     Eric Helvey <ehelvey@americanprogress.org> for The Center for American Progress
 */
class CAP_Byline_Activator
{
  /**
   * Debugging output level.
   *
   * @since    1.0.0
   */
  private static $debug = 0;

  /**
   * Debugging output level.
   *
   * @since    1.0.0
   */
  private static $attrs_loaded = array();

  /**
   * Startup actions for CAP_Byline.
   *
   * Things that have to happen when CAP_Byline gets activated.  This includes:
   *  - Create Gravity Forms
   *
   * @since    1.0.0
   */
  public static function activate($debug = 0)
  {
    self::$debug = $debug;

    if(self::$debug >= 4) { trigger_error("Entering CAP_Byline_Activator::activate", E_USER_NOTICE); }

    CAP_Byline_Activator::setup_gravity_forms();
    CAP_Byline_Activator::setup_database();

    if(self::$debug >= 4) { trigger_error("Leaving CAP_Byline_Activator::activate", E_USER_NOTICE); }
  }


  /**
   * Load CAP_Byline gravity forms.
   *
   * @since    1.0.0
   */
  private static function setup_gravity_forms()
  {
    if(self::$debug >= 4) { trigger_error("Entering CAP_Byline_Activator::setup_gravity_forms", E_USER_NOTICE); }

    self::$attrs_loaded["gravity_forms"] = 1;

    if(class_exists('GFCommon')) {
      self::$attrs_loaded["gravity_forms"] = 2;

      $plugin_dir = plugin_dir_path(__FILE__);
      require("$plugin_dir/../settings/CAP_Byline_gravityforms.php");

      foreach($CAP_Byline_gravityforms as $form) {
        $form_id = GFAPI::add_form($form);
      }
    }

    if(self::$debug >= 4) { trigger_error("Leaving CAP_Byline_Activator::setup_gravity_forms", E_USER_NOTICE); }
  }

  /**
   * Load CAP_Byline database artifacts.
   *
   * @since    1.0.0
   */
  private static function setup_database()
  {
    if(self::$debug >= 4) { trigger_error("Entering CAP_Byline_Activator::setup_database", E_USER_NOTICE); }

    global $wpdb;
    self::$attrs_loaded["database"] = 1;

    $plugin_dir = plugin_dir_path(__FILE__);
    require(dirname(__FILE__) . "/../constants/db_definitions.php");

    foreach($CAP_Byline_table_definitions as $k => $v) {
      if(self::$debug >= 3) { trigger_error("Creating table $k using '" . $v["create"] . "'", E_USER_NOTICE); }
      dbDelta($v["create"]);
    }

    foreach($CAP_Byline_view_definitions as $k => $v) {
      if(self::$debug >= 3) { trigger_error("Creating view $k using '" . $v["create"] . "'", E_USER_NOTICE); }
      dbDelta($v["create"]);
    }

    if(self::$debug >= 4) { trigger_error("Leaving CAP_Byline_Activator::setup_database", E_USER_NOTICE); }
  }

  public static function get_attr_status($attr)
  {
    if(self::$debug >= 4) { trigger_error("Entering/Leaving CAP_Byline_Activator::get_attr_status", E_USER_NOTICE); }
    return self::$attrs_loaded[$attr];
  }
}

?>