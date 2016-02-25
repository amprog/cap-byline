<?php
/**
 * File containing advanced custom fields required by CAP_Byline
 *
 * An array containing the specs for any advanced custom fields used by
 * the CAP_Byline plugin.
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


$CAP_Byline_acfs = array(
  "person_settings" => array(
    'id' => 'acf_person-settings',
    'title' => __('Person Settings'),
    'menu_order' => 0,

    'location' => array(
      array(
        array(
          'param' => 'ef_taxonomy',
          'operator' => '==',
          'value' => 'person',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ), # End of locations for Person Settings.

    'options' => array (
      'position' => 'normal',
      'layout' => 'no_box',
      'hide_on_screen' => array (),
    ), # End of options for Person Settings.

    'fields' => array(
      array(
        'key'          => 'field_539efe9038185',
        'label'        => __('Bio Pic'),
        'name'         => 'person_photo',
        'type'         => 'image',
        'save_format'  => 'id',
        'preview_size' => 'thumbnail',
        'library'      => 'all',
      ),
      array (
        'key'          => 'field_55197b1a6eeb8',
        'label'        => __('Hi-res Bio Pic'),
        'name'         => 'person_photo_hi_res',
        'type'         => 'image',
        'save_format'  => 'id',
        'preview_size' => 'thumbnail',
        'library'      => 'all',
      ),
      array (
        'key'           => 'field_539f068a98928',
        'label'         => __('Title'),
        'name'          => 'person_title',
        'type'          => 'text',
        'default_value' => '',
        'placeholder'   => '',
        'prepend'       => '',
        'append'        => '',
        'formatting'    => 'none',
        'maxlength'     => '',
      ),
      array (
        'key'           => 'field_539f06f598929',
        'label'         => __('Contact Email'),
        'name'          => 'person_email',
        'type'          => 'email',
        'default_value' => '',
        'placeholder'   => '',
        'prepend'       => '',
        'append'        => '',
      ),
      array (
        'key'           => 'field_539efea738186',
        'label'         => __('Twitter Handle'),
        'name'          => 'person_twitter_handle',
        'type'          => 'text',
        'default_value' => '',
        'placeholder'   => '',
        'prepend'       => '@',
        'append'        => '',
        'formatting'    => 'none',
        'maxlength'     => '',
      ),
      array(
        'key'           => 'field_560434a4d45fe',
        'label'         => __('Facebook ID'),
        'name'          => 'person_facebook_id',
        'type'          => 'text',
        'default_value' => '',
        'placeholder'   => '',
        'prepend'       => '',
        'append'        => '',
        'formatting'    => 'none',
        'maxlength'     => '',
      ),
      array (
        'key'           => 'field_53a2ff7d56f11',
        'label'         => __('Person Is Linked?'),
        'name'          => 'person_is_linked',
        'type'          => 'true_false',
        'instructions'  => __('Checking this field will enable the bio link for a person in the byline.'),
        'message'       => '',
        'default_value' => 0,
      ),
    ),  # End of Person Settings fields.
  ), # End of Person Settings Field group.


  "cap_byline_settings" => array (
    'id' => 'acf_cap-byline-settings',
    'title' => __('CAP Byline Settings'),
    'menu_order' => 0,

    'location' => array(
      array(
        array(
          'param' => 'options_page',
          'operator' => '==',
          'value' => 'acf-options',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ), # End of CAP Byline Settings locations.

    'options' => array(
      'position' => 'normal',
      'layout' => 'default',
      'hide_on_screen' => array (),
    ), # End of CAP Byline Settings options.

    'fields' => array (
      array (
        'key'           => 'field_53a069c4d2202',
        'label'         => __('Author Contact Form ID'),
        'name'          => 'author_contact_form_id',
        'type'          => 'text',
        'instructions'  => __('Enter the ID of the form titled "Contact Author" for the contact functionality to work on author bio pages.'),
        'default_value' => '',
        'placeholder'   => '',
        'prepend'       => '',
        'append'        => '',
        'formatting'    => 'html',
        'maxlength'     => '',
      ),
      array (
        'key'           => 'field_53a2ff7d56f69',
        'label'         => __('Disable Auto Author Select'),
        'name'          => 'disable_auto_author_select',
        'type'          => 'true_false',
        'instructions'  => __('Checking this field will disable the auto selection of the author when writing a post.'),
        'message'       => '',
        'default_value' => 0,
      ),
      array (
        'key'           => 'field_53a2fe7d56009',
        'label'         => __('Disable Updated Time'),
        'name'          => 'global_disable_update_time',
        'type'          => 'true_false',
        'instructions'  => __('Checking this field will disable the updated time globally.'),
        'message'       => '',
        'default_value' => 0,
      ),
      array (
        'key'           => 'field_53a2fe7d51239',
        'label'         => __('Display Post Time'),
        'name'          => 'global_display_post_time',
        'type'          => 'true_false',
        'instructions'  => __('Checking this field will display the time a post is published globally.'),
        'message'       => '',
        'default_value' => 1,
      ),
    ), # End of CAP Byline Settings fields
  ), # End of the CAP Bylne Settings field group

  "byline" => array(
    'key' => 'group_53f38caa7634f',
    'title' => 'Byline',
    'menu_order' => 15,
    'position' => 'acf_after_title',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'field',
    'hide_on_screen' => '',

    'location' => array(
      array(
        array(
          'param' => 'post_type',
          'operator' => '!=',
          'value' => 'state-year-report',
        ),
        array(
          'param' => 'post_type',
          'operator' => '!=',
          'value' => 'cd-report',
        ),
        array(
          'param' => 'post_type',
          'operator' => '!=',
          'value' => 'page',
        ),
      ),
    ), # End of Byline locations

    'fields' => array(
      array(
        'key'               => 'field_53f38cd042a42',
        'label'             => __('Byline'),
        'name'              => 'byline_array',
        'prefix'            => '',
        'type'              => 'taxonomy',
        'instructions'      => __('This field will autocomplete names. Start typing to add existing person(s) to this post.'),
        'required'          => 0,
        'conditional_logic' => 0,
        'taxonomy'          => 'person',
        'field_type'        => 'multi_select',
        'allow_null'        => 0,
        'load_save_terms'   => 0,
        'return_format'     => 'id',
        'multiple'          => 0,
      ),
    ),
  ), # End of the Byline field group.

);

?>