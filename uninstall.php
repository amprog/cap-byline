<?php
/**
 * Uninstall script for CAP_Byline
 *
 * Removes:
 *  - Database artifacts created on installation
 *  - Options used
 *
 * @link       https://github.com/amprog/cap-byline
 * @since      2.0.0
 *
 * @package    CAP_Byline
 **/


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

// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

$plugin_dir = plugin_dir_path( __FILE__ );

require_once($plugin_dir . "/constants/constants.php");
require_once($plugin_dir . "/db/db_uninstall.php");

$option_name = 'plugin_option_name';

delete_option( $option_name );

// For site options in Multisite
delete_site_option( $option_name );


?>