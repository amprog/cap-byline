<?php
/**
 * File containing gravityforms required by CAP_Byline
 *
 * An array containing the specs for any gravity forms used by
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


$CAP_Byline_gravityforms = array(
  array(
    'labelPlacement' => 'top_label',
    'useCurrentUserAsAuthor' => 1,
    'title' => __('Contact Author'),
    'description' => __('Fill out the form below to contact this author'),
    'descriptionPlacement' => 'below',
    'button' => array('type' => 'text', 'text' => __('Submit')),
    'enableHoneypot' => '1',
    'enableAnimation' => '1',
    'id' => '2',
    'is_active' => '1',
    'date_created' => '2014-06-17 15:17:18',
    'is_trash' => '0',

    'fields' => array(
      array(
        # Name of the person contacting the author.
        'id' => '1',
        'isRequired' => '1',
        'size' => 'medium',
        'type' => 'name',
        'label' => __('Name'),
        'inputs' => array(
          array('id' => '1.3', 'label' => __('First')),
          array('id' => '1.6', 'label' => __('Last')),
        ),
        'formId' => '2',
        'pageNumber' => '1',
        'descriptionPlacement' => 'below',
      ),
      array(
        # Email address of the person contacting the author.
        'id' => '2',
        'isRequired' => '1',
        'size' => 'medium',
        'type' => 'email',
        'label' => __('Email'),
        'formId' => '2',
        'pageNumber' => '1',
        'descriptionPlacement' => 'below',
      ),
      array(
        # Message to be sent to the author.
        'id' => '3',
        'isRequired' => '1',
        'size' => 'medium',
        'type' => 'textarea',
        'label' => __('Message'),
        'formId' => '2',
        'pageNumber' => '1',
        'descriptionPlacement' => 'below',
      ),
      array(
        # Hidden field containing the author's email.
        'allowsPrepopulate' => 1,
        'id' => 4,
        'size' => 'medium',
        'type' => 'hidden',
        'inputName' => 'author_contact_email',
        'label' => __('To'),
        'formId' => 2,
        'pageNumber' => 1,
        'descriptionPlacement' => 'below'
      ),
    ), # End of Contact Author Form fields.

    'notifications' => array(
      '53a057ebea107' => array(
        'id' => '53a057ebea107',
        'to' => '{admin_email}',
        'name' => 'Admin Notification',
        'event' => 'form_submission',
        'toType' => 'email',
        'subject' => 'You have received a message from '.get_bloginfo('name').'',
        'message' => '{all_fields}'
      ),
    ), # End of Contact Author Form Notifications.

    'confirmations' => array(
      '53a057ebeadd6' => array(
        'id' => '53a057ebeadd6',
        'isDefault' => '1',
        'type' => 'message',
        'name' => 'Default Confirmation',
        'message' => __('Thank you for contacting me.'),
        'disableAutoformat' => null,
        'pageId' => null,
        'url' => null,
        'queryString' => null,
        'conditionalLogic' => array (),
      ),
    ), # End of Contact Author Form Confirmations.
  ), # End of Contact the Author form.
);

?>