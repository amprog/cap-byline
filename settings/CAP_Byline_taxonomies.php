<?php
/**
 * File containing an array of taxonomies required by CAP Byline
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/amprog/cap-byline
 * @since      1.3.0
 *
 * @package    CAP_Byline
 * @subpackage CAP_Byline/settings
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


$CAP_Byline_taxonomies = array(
  "person" => array(
    "post_types" => get_post_types(),
    "configs" => array(
      'label' => __('Person'),
      'rewrite' => array(
        'slug' => 'person',
        'with_front' => false,
      ),
      'hierarchical' => false,
      'show_admin_column' => false,
      'capabilities' => array(
        'manage_terms' => 'edit_others_posts',
        'edit_terms' => 'edit_others_posts',
        'delete_terms' => 'edit_others_posts',
      ),
    ),
  ), # End of Person Taxonomy
);

?>