<?php
/**
 * The public-facing functionality of the CAP_Byline plugin.
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
 * The public-facing functionality of the CAP_Byline plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    CAP_Byline
 * @subpackage CAP_Byline/includes
 * @author     Eric Helvey <ehelvey@americanprogress.org> for The Center for American Progress
 */
class CAP_Byline_Public
{
  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;


  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;


  /**
   * Debugging level.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $debug    Debugging level.
   */
  private $debug;


  /**
   * Reference to the main plugin class.
   *
   * @since    1.0.0
   * @access   private
   * @var      object    $plugin_core    Reference to the core plugin object
   */
  private $plugin_core;


  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      array    $args       Array of function arguments.
   */
  public function __construct($args = array())
  {
    foreach($args as $k=>$v) {
      $this->$k = $v;
    }
  }


  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles()
  {
    $public_css_files = array(
      "cap_byline_public.css",
    );

    $css_prefix = plugin_dir_url( __FILE__ ) . 'css';

    foreach($public_css_files as $cssfile) {
      wp_enqueue_style($this->plugin_name, "{$css_prefix}/{$cssfile}", array(), $this->version, 'all');
    }
  }


  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts()
  {
    $public_js_files = array(
      "cap_byline_public.js",
    );

    $js_prefix = plugin_dir_url( __FILE__ ) . 'js';

    foreach($public_js_files as $jsfile) {
      wp_enqueue_script($this->plugin_name, "{$js_prefix}/{$jsfile}", array('jquery'), $this->version, false);
    }
  }


  /**
   * Deliver the email as a result of submitting the contact author form.
   *
   * @since    1.0.0
   */
  public function cap_byline_contact_form_email($entry, $form)
  {
    if($form["id"] == get_field('author_contact_form_id', 'options')) {
      $email_to = get_field('person_email', 'person_'.$entry[4]);
      $email_from_first = $entry['1.3'];
      $email_from_last = $entry['1.6'];
      $email_from = $entry[2];
      $email_message = '<strong>You have a new message from '.$email_from_first.' '.$email_from_last.' at '.$email_from.'</strong><br><br>';
      $email_message .= $entry[3];

      if ( !empty($email_to) ){
        $mail_headers[] = 'From: "' . $email_from_first . ' ' . $email_from_last . ' via AmericanProgress" <no-reply@americanprogress.org>';
        $mail_headers[] = "Reply-To: $email_from";
        wp_mail( $email_to, 'You have a new message from '.$email_from_first.' '.$email_from_last.'', $email_message, $mail_headers );
      }
    }
  }



  /**
   * Change authors in RSS feeds based on byline associations.
   *
   * @since    1.0.0
   */
  public function cap_rss_other_author($name)
  {
    global $post;

    if(is_feed()) {
      $authors = get_cap_authors($post->ID, true, true, false);

      if($authors !== NULL){
        $name = "";
        for($i = 0; $i < count($authors); $i++){
            $name .= $authors[$i] . ', ';
        }
        $name = rtrim($name, ', ');
      }
    }

    return $name;
  }

}

?>