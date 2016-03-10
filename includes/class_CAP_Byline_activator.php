<?php
/**
 * Defines the actions to take during plugin activation.
 *
 * @link https://github.com/amprog/cap-byline
 * @since 2.0.0
 *       
 * @package CAP_Byline
 * @subpackage CAP_Byline/includes
 */

/**
 * Copyright (C) 2013 - 2016 The Center for American Progress
 *
 * CAP_Byline is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CAP_Byline is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with CAP_Byline. If not, see <http://www.gnu.org/licenses/gpl.html>.
 */
require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
include_once ('trait_Debug.php');



/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's
 * activation.
 *
 * @since 2.0.0
 * @package CAP_Byline
 * @subpackage CAP_Byline/includes
 * @author Eric Helvey <ehelvey@americanprogress.org> for The Center for
 *         American Progress
 */
class CAP_Byline_Activator
{
    use DebugLog;

    /**
     * Debugging output level.
     *
     * @since 1.0.0
     */
    private static $attrs_loaded = array();


    /**
     * Startup actions for CAP_Byline.
     *
     * Things that have to happen when CAP_Byline gets activated. This
     * includes:
     * - Create Gravity Forms
     *
     * @since 1.0.0
     */
    public static function activate($debug = 0)
    {
        self::$debug = $debug;
        self::$display = true;
        
        self::debug_out("Entering CAP_Byline_Activator::activate", 4);
        
        CAP_Byline_Activator::setup_gravity_forms();
        CAP_Byline_Activator::setup_database();
        
        self::debug_out("Leaving CAP_Byline_Activator::activate", 4);
    }


    /**
     * Load CAP_Byline gravity forms.
     *
     * @since 1.0.0
     */
    private static function setup_gravity_forms()
    {
        self::debug_out("Entering CAP_Byline_Activator::setup_gravity_forms", 4);
        
        self::$attrs_loaded["gravity_forms"] = 1;
        
        if (class_exists('GFCommon')) {
            self::$attrs_loaded["gravity_forms"] = 2;
            
            $plugin_dir = plugin_dir_path(__FILE__);
            require ("$plugin_dir/../settings/CAP_Byline_gravityforms.php");
            
            foreach ($CAP_Byline_gravityforms as $form) {
                $form_id = GFAPI::add_form($form);
            }
        }
        
        self::debug_out("Leaving CAP_Byline_Activator::setup_gravity_forms", 4);
    }


    /**
     * Load CAP_Byline database artifacts.
     *
     * @since 1.0.0
     */
    private static function setup_database()
    {
        self::debug_out("Entering CAP_Byline_Activator::setup_database", 4);
        
        global $wpdb;
        self::$attrs_loaded["database"] = 1;
        
        $plugin_dir = plugin_dir_path(__FILE__);
        require (dirname(__FILE__) . "/../constants/db_definitions.php");
        
        foreach ($CAP_Byline_table_definitions as $k=>$v) {
            self::debug_out("Creating table $k using '" . $v["create"] . "'", 3);
            dbDelta($v["create"]);
        }
        
        foreach ($CAP_Byline_view_definitions as $k=>$v) {
            self::debug_out("Creating view $k using '" . $v["create"] . "'", 3);
            $wpdb->query($v["create"]);
        }
        
        self::debug_out("Leaving CAP_Byline_Activator::setup_database", 4);
    }


    public static function get_attr_status($attr)
    {
        self::debug_out("Entering/Leaving CAP_Byline_Activator::get_attr_status", 4);
        return self::$attrs_loaded[$attr];
    }
}

?>