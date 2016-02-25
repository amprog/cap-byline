<?php
/**
Plugin Name: CAP Byline
Plugin URI:  https://github.com/amprog/cap-byline
Description: Provides a CAP standardized method for choosing authors for posts
Version:     1.2.0
Author:      Seth Rubenstein for The Center for American Progress
Author URI:  https://github.com/orgs/amprog/people/sethrubenstein
License:     GPL3
License URI: http://www.gnu.org/licenses/gpl.html
Text Domain: cap-byline
Domain Path: /intl


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

/**
 * CAP Byline bootstrap file
 *
 * - Makes sure that we were called properly.
 * - Registers callbacks for admin functionality.
 * - Loads dependencies.
 * - Registers activation hooks.
 * - Registers deactivation hooks.
 * - Starts plugin.
 * - Registers global-scope functions
 *
 * @link              https://github.com/amprog/cap-byline
 * @since             1.0.0
 * @package           CAP_Byline
 *
 * @wordpress-plugin
 **/

# If this file is called directly, abort.
if(!defined('WPINC')) {
  die;
}

$plugin_dir = plugin_dir_path(__FILE__);

$CAP_Byline_instance = null;

# Ensure that all required plugins are in place.  Required plugins include
# AdvancedCustomFields and GravityForms.
if(   function_exists("get_field")
   && function_exists("gform_notification")) {

  # Activation callback
  if(file_exists("$plugin_dir/includes/class_CAP_Byline_activator.php")) {

    /**
     * Callback for plugin activation.
     *
     * @since    1.0.0
     **/
    function activate_CAP_Byline()
    {
      require_once("$plugin_dir/includes/class_CAP_Byline_activator.php");
      CAP_Byline_Activator::activate();
    }
    register_activation_hook(__FILE__, 'activate_CAP_Byline');
  }

  # Deactivation callback
  if(file_exists("$plugin_dir/includes/class_CAP_Byline_deactivator.php")) {

    /**
     * Callback for plugin deactivation.
     *
     * @since    1.0.0
     **/
    function deactivate_CAP_Byline()
    {
      require_once("$plugin_dir/includes/class_CAP_Byline_deactivator.php");
      CAP_Byline_Deactivator::deactivate();
    }
    register_deactivation_hook(__FILE__, 'deactivate_CAP_Byline');
  }


  # Load main CAP_Byline class
  require("$plugin_dir/includes/class_CAP_Byline.php");

  /**
   * Begins execution of the plugin.
   *
   * @since    1.0.0
   */
  if(!function_exists("run_CAP_Byline")) {
    function run_CAP_Byline()
    {
      global $CAP_Byline_instance;

      $CAP_Byline_instance = new CAP_Byline();
      $CAP_Byline_instance->run();
    }
  }
  run_CAP_Byline();


  # Global function registration.
  if(!function_exists("cap_byline")) {
    function cap_byline($type)
    {
      global $post;

      echo $CAP_Byline_instance->get_cap_byline($type, $post->ID);
    }
  }

  if(!function_exists("cap_person_bio")) {
    function cap_person_bio($style, $person = null)
    {
      echo $CAP_Byline_instance->get_cap_person_bio($style, $person);
    }
  }

  if(!function_exists("get_the_cap_author_facebook_ids")) {
    function get_the_cap_author_facebook_ids()
    {
      echo $CAP_Byline_instance->get_the_cap_author_facebook_ids();
    }
  }

}



