<?php
/**
 * Database artifacts required by the CAP_Byline plugin.
 *
 * Here we can define:
 *  - Tables
 *  - Views
 *  - Indexes
 *
 * The artifacts defined here will be created using the db_install.php
 * script when the plugin is activated.  Deactivating the plugin will not
 * trigger artifact deletion.  The DB artificats will only be deleted on
 * uninstall (via the uninstall.php file, or via the WordPress backend).
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

global $wpdb;
require(dirname(__FILE__) . "/../constants/constants.php");

$fqdb_prefix = $wpdb->prefix . $CAP_Byline_constants["prefix"] . "_";

$CAP_Byline_table_definitions = array(
    "table_1" => array(
        "create" => "CREATE TABLE IF NOT EXISTS {$fqdb_prefix}table_1 (id BIGINT(20) PRIMARY KEY, col2 TEXT);",
        "drop"   => "DROP TABLE {$fqdb_prefix}table_1;",
        "name"   => "{$fqdb_prefix}table_1",
    ),
);
$CAP_Byline_view_definitions = array(
    "view_1" => array(
        "create" => "CREATE OR REPLACE VIEW {$fqdb_prefix}view_1 AS SELECT * FROM {$fqdb_prefix}table_1;",
        "drop"   => "DROP VIEW IF EXISTS {$fqdb_prefix}view_1;",
        "name"   => "{$fqdb_prefix}view_1",
    ),
);
?>