<?php
/**
 * Register all actions and filters for the plugin.  This class is
 * pretty generic and should not need to be changed.
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
include_once("trait_Debug.php");

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    CAP_Byline
 * @subpackage CAP_Byline/includes
 * @author     Eric Helvey <ehelvey@americanprogress.org> for The Center for American Progress
 **/
class CAP_Byline_Loader
{
    use DebugLog;

  /**
   * Actions to be registered with the WordPress core.
   *
   * @since    1.0.0
   * @access   protected
   * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
   */
  protected $actions;


  /**
   * Filters to be registered with the WordPress core.
   *
   * @since    1.0.0
   * @access   protected
   * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
   */
  protected $filters;


  /**
   * Class constructor.
   *
   * @since    1.0.0
   */
  public function __construct($args = array())
  {
    $this->actions = array();
    $this->filters = array();

    foreach($args as $k=>$v) {
        if($k == "debug") {
            self::$debug = $v;
        } else {
      $this->$k = $v;
        }
    }
  }



  /**
   * Add a new action to the collection to be registered with WordPress.
   *
   * @since    1.0.0
   * @param    string               $hook             The name of the WordPress action that is being registered.
   * @param    object               $component        A reference to the instance of the object on which the action is defined.
   * @param    string               $callback         The name of the function definition on the $component.
   * @param    int                  $priority         Optional. he priority at which the function should be fired. Default is 10.
   * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
   */
  public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1)
  {
    $this->actions[] = array(
      'hook'          => $hook,
      'component'     => $component,
      'callback'      => $callback,
      'priority'      => $priority,
      'accepted_args' => $accepted_args
    );
  }


  /**
   * Add a new filter to the collection to be registered with WordPress.
   *
   * @since    1.0.0
   * @param    string               $hook             The name of the WordPress filter that is being registered.
   * @param    object               $component        A reference to the instance of the object on which the filter is defined.
   * @param    string               $callback         The name of the function definition on the $component.
   * @param    int                  $priority         Optional. he priority at which the function should be fired. Default is 10.
   * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
   */
  public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1)
  {
    $this->filters[] = array(
      'hook'          => $hook,
      'component'     => $component,
      'callback'      => $callback,
      'priority'      => $priority,
      'accepted_args' => $accepted_args
    );
  }



  /**
   * Register the filters and actions with WordPress.
   *
   * @since    1.0.0
   */
  public function run()
  {
    foreach ($this->filters as $hook) {
      add_filter($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
    }

    foreach ($this->actions as $hook) {
      add_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
    }
  }


  /**
   * Unregister the filters and actions with WordPress.
   *
   * @since    1.0.0
   */
  public function shutdown()
  {
    foreach ($this->filters as $hook) {
      remove_filter($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority']);
    }

    foreach ($this->actions as $hook) {
      remove_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority']);
    }
  }

}

?>