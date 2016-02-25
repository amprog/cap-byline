<?php
/**
 * Defines the actions to take during plugin activation.
 *
 * @link       https://github.com/amprog/cap-byline
 * @since      1.0.0
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

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    CAP_Byline
 * @subpackage CAP_Byline/includes
 * @author     Eric Helvey <ehelvey@americanprogress.org> for The Center for American Progress
 */
class CAP_Byline_Activator
{
	/**
	 * Startup actions for CAP_Byline.
	 *
	 * Things that have to happen when CAP_Byline gets activated.  This includes:
	 *  - Create Gravity Forms
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
	  CAP_Byline_Activator::load_gravity_forms();
	}


	/**
	 * Load CAP_Byline gravity forms.
	 *
	 * @since    1.0.0
	 */
	private static function load_gravity_forms()
	{
	  require_once("../settings/CAP_Byline_gravityforms.php");

      if(function_exists('gform_notification')) {
        foreach($CAP_Byline_gravityforms as $form) {
          $form_id = GFAPI::add_form($form);
        }
      }
	}
}

?>