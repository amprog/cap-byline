<?php
/**
 * Runs the creation of database artificats as part of plugin activation.
 *
 * Here we delete the database artifacts defined in db_defintions.php.  This
 * code is called by CAP_Byline_Deactivator class or by uninstall.php.
 *
 * @link       https://github.com/amprog/cap-byline
 * @since      2.0.0
 *
 * @package    CAP_Byline
 * @subpackage CAP_Byline/includes
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

require_once(dirname(__FILE__) . "/db_definitions.php");

foreach($CAP_Byline_table_definitions as $k => $v) {
    $wpdb->query($v["drop"]);
}

foreach($CAP_Byline_view_definitions as $k => $v) {
    $wpdb->query($v["drop"]);
}


?>