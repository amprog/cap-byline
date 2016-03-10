<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both
 * the
 * public-facing side of the site and the admin area.
 *
 * @link https://github.com/amprog/cap-byline
 * @since 2.0.0
 *
 * @package CAP_Byline
 * @subpackage CAP_Byline/includes
 *
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
include_once ("trait_Debug.php");



/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since 2.0.0
 * @package CAP_Byline
 * @subpackage CAP_Byline/includes
 * @author Eric Helvey <ehelvey@americanprogress.org> for The Center for
 *         American Progress
 *
 */
class CAP_Byline
{
    use DebugLog;

    /**
     * The loader that's responsible for maintaining and registering all hooks
     * that power
     * the plugin.
     *
     * @since 2.0.0
     * @access protected
     * @var Plugin_Name_Loader $loader Maintains and registers all hooks for
     *      the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since 2.0.0
     * @access protected
     * @var string $plugin_name The string used to uniquely identify this
     *      plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since 2.0.0
     * @access protected
     * @var string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Which subclasses have been loaded.
     * This correlates to which functionality has been defined.
     *
     * @since 2.0.0
     * @access private
     * @var array $subclasses_loaded Which subclasses have been loaded.
     */
    private $subclasses_loaded;


    /**
     * ACF Menu definitions.
     *
     * @since 2.0.0
     * @access private
     * @var array $acf_field_settings ACF Menu strings.
     */
    private $acf_field_settings = array();


    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout
     * the plugin. Load the dependencies, define the locale, and set the hooks
     * for the admin area and the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        include_once ("../settings/constants.php");

        $this->subclasses_loaded = array(
            "loader" => false,
            "i18n" => false,
            "admin" => false,
            "public" => false
        );

        $this->acf_field_settings = array(
            "page_title" => __("CAP Byline Options"),
            "menu_title" => __("Byline"),
            "menu_slug" => __("cap-byline-menu"),
        );

        $this->plugin_name = $CAP_Byline_constants["slug"];

        foreach ($CAP_Byline_constants as $k=>$v) {
            if("debug" == $k) {
                self::$debug = $v;
            } else {
                $this->$k = $v;
            }
        }

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_general_hooks();
    }


    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
     * - Plugin_Name_i18n. Defines internationalization functionality.
     * - Plugin_Name_Admin. Defines all hooks for the admin area.
     * - Plugin_Name_Public. Defines all hooks for the public side of the
     * site.
     *
     * Create an instance of the loader which will be used to register the
     * hooks with WordPress.
     *
     * @since 1.0.0
     * @access private
     */
    private function load_dependencies()
    {
        foreach ($this->subclasses_loaded as $tclass=>$v) {
            $subclass_file = plugin_dir_path(dirname(__FILE__)) . 'includes/class_' . $this->class_label . "_{$tclass}.php";

            if (file_exists($subclass_file)) {
                require_once ($subclass_file);
                $this->subclasses_loaded[$tclass] = true;
            }
        }

        if ($this->subclasses_loaded["loader"]) {
            $this->loader = new CAP_Byline_Loader(array(
                "debug" => $this->debug
            ));
        }
    }


    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Plugin_Name_i18n class in order to set the domain and to
     * register the hook
     * with WordPress.
     *
     * @since 1.0.0
     * @access private
     */
    private function set_locale()
    {
        if ($this->subclasses_loaded["i18n"]) {
            $plugin_i18n = new CAP_Byline_i18n(array(
                "debug" => $this->debug
            ));

            if ($this->subclasses_loaded["loader"]) {
                $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
            }
        }
    }


    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since 1.0.0
     * @access private
     */
    private function define_admin_hooks()
    {
        if ($this->subclasses_loaded["admin"]) {
            $plugin_admin = new CAP_Byline_Admin(array(
                "debug" => $this->debug,
                "plugin_name" => $this->plugin_name,
                "version" => $this->version,
                "plugin_core" => $this
            ));

            if ($this->subclasses_loaded["loader"]) {
                $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
                $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
                $this->loader->add_action('manage_posts_custom_column', $plugin_admin, 'show_persons_column');
                $this->loader->add_action('admin_menu', $plugin_admin, 'remove_person_meta_box');

                $this->loader->add_filter('manage_posts_columns', $plugin_admin, 'persons_column');
                // Add hooks for actions and filters for the backend here.
                // Those functions should
                // be defined in the XX_Admin class.
            }
        }
    }


    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since 1.0.0
     * @access private
     */
    private function define_public_hooks()
    {
        if ($this->subclasses_loaded["public"]) {
            $plugin_public = new CAP_Byline_Public(array(
                "debug" => $this->debug,
                "plugin_name" => $this->plugin_name,
                "version" => $this->version,
                "plugin_core" => $this
            ));

            if ($this->subclasses_loaded["loader"]) {
                $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
                $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
                $this->loader->add_action('gform_after_submission', $plugin_public, 'cap_byline_contact_form_email', 10, 2);
                $this->loader->add_filter('the_author', $plugin_public, 'cap_rss_other_author');
                $this->loader->add_filter('get_the_author_display_name', $plugin_public, 'cap_rss_other_author');

                // Add hooks for actions and filters for the backend here.
                // Those functions should
                // be defined in the XX_Public class.
            }
        }
    }


    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since 1.0.0
     * @access private
     */
    private function define_general_hooks()
    {
        if ($this->subclasses_loaded["loader"]) {
            $this->loader->add_action('init', $this, 'register_taxonomies');
            $this->loader->add_action('acf/save_post', $this, 'set_terms', 20);

            // Add hooks here. Those functions should be defined in this
            // class.
        }
    }


    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since 1.0.0
     * @access private
     */
    private function remove_admin_hooks()
    {
        if ($this->subclasses_loaded["admin"]) {
        }
    }


    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since 1.0.0
     * @access private
     */
    private function remove_public_hooks()
    {
        if ($this->subclasses_loaded["public"]) {
        }
    }


    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since 1.0.0
     * @access private
     */
    private function remove_general_hooks()
    {
        $this->unregister_taxonomies();
    }


    /**
     * Register taxonomies.
     *
     * @since 2.0.0
     * @access private
     */
    private function register_taxonomies()
    {
        require_once ("../settings/CAP_Byline_taxonomies.php");

        if (isset($CAP_Byline_taxonomies) && is_array($CAP_Byline_taxonomies) && count(array_keys($CAP_Byline_taxonomies)) > 0) {
            foreach ($CAP_Byline_taxonomies as $label=>$tax) {
                register_taxonomy($label, $tax["post_types"], $tax["configs"]);
            }
        }
    }


    /**
     * Unregister taxonomies.
     *
     * @since 2.0.0
     * @access private
     */
    private function unregister_taxonomies()
    {
        require_once ("../settings/CAP_Byline_taxonomies.php");

        if (isset($CAP_Byline_taxonomies) && is_array($CAP_Byline_taxonomies) && count(array_keys($CAP_Byline_taxonomies)) > 0) {
            foreach ($CAP_Byline_taxonomies as $label=>$tax) {
                register_taxonomy($label, array(), array(
                    'show_in_nav_menus' => false
                ));
            }
        }
    }


    /**
     * Register Advanced Custom Fields.
     *
     * @since 2.0.0
     * @access private
     */
    private function register_acfs()
    {
        require_once ("../settings/CAP_Byline_acfs.php");

        if (isset($CAP_Byline_acfs) && is_array($CAP_Byline_acfs) && count(array_keys($CAP_Byline_acfs)) > 0) {
            foreach ($CAP_Byline_acfs as $label=>$acf) {
                register_field_group($acf);
            }
        }
    }


    /**
     * Set the term to post mapping relationships for byline authors
     * to posts when the post gets saved.
     *
     * @since 1.0.0
     * @access private
     * @param string $post_id
     *            The ID of the post being saved.
     */
    private function set_terms($post_id)
    {
        global $post;
        // Dont run this on the state-year-report post type

        // TODO: Setup a configurable list of posts for which we should not
        // set terms.
        if (is_singular('state-year-report')) {return;}

        $field_data = get_field('byline_array');
        $persons = array();

        // Check to see if this post has any authors if it does not, proceed
        // with auto selection. We do this check becuase we don't want to
        // continue to autoselect if they've removed the autoselect author in
        // one-off cases. Also we're presuming to autoselect as a function
        // only if no authors are present.
        if (empty($field_data)) {
            // Get the author information
            $author_slug = get_the_author_meta('user_login', $post->post_author);
            $author_data = get_term_by('slug', $author_slug, 'person');
            $author_id = $author_data->term_id;

            // Check for an author byline override. Basically this is a intern
            // function.
            $default_byline_override = get_user_meta($post->post_author, '_default_byline', true);
            $default_byline = get_term_by('id', $default_byline_override, 'person');
            $default_byline_id = $default_byline->term_id;

            // If a override is present use that first
            if (!empty($default_byline_override) && false === get_field('disable_auto_author_select', 'options')) {
                $persons[] = $default_byline_id;

                // If a person exists with the slug of the author then auto
                // add it.
            } elseif (term_exists($author_slug, 'person') && false === get_field('disable_auto_author_select', 'options')) {
                $persons[] = $author_id;
            }
        } else {
            // Go through the persons from the field add them to the persons
            // array.
            foreach ($field_data as $data) {
                $persons[] = $data;
            }
        }

        // Go back and update the field with the new data
        update_field('field_53f38cd042a42', $persons);

        // Set this posts person terms to the persons array
        wp_set_post_terms($post_id, $persons, 'person', false);
    }


    /**
     * Returns the post's list of authors based on various criteria.
     *
     * @since 1.0.0
     *
     * @param $post_id is
     *            the post id, we should pass this in always.
     * @param $disable_link defaults
     *            to false, if set to true the output is only the name
     * @param $as_array defaults
     *            to false, if set to true returns as an array of persons
     *            either as slug or name
     * @param $return_slugs defaults
     *            to true, if $as_array is set to true return person slugs if
     *            set to false then return names
     * @param $byline_field defaults
     *            to byline_array. This allows you to create identical other
     *            fields such as "with" for American Progress and check for
     *            that new field.
     *
     */
    public function get_cap_authors($post_id, $disable_link = false, $as_array = false, $return_slugs = true, $byline_field = 'byline_array')
    {
        $people = get_field($byline_field, $post_id);
        $byline_array = array();

        if (!empty($people)) {
            // let's setup an array to organize these people based on some
            // conditions below
            foreach ($people as $person) {
                $get_byline = get_term_by('id', $person, 'person');
                $byline_array[] = $get_byline->slug;
            }
        }

        // Check for the display function, if as_array is set to true then
        // just
        // return the array... if not then proceed with the listing function.
        if (true == $as_array && true == $return_slugs) {return $byline_array;}

        // Because we're setting to return as an array but not to return slugs
        // we'll return the full name of the persons in an array.
        if (true == $as_array && false == $return_slugs) {
            $return_names_array = array();
            foreach ($byline_array as $author) {
                $data = get_term_by('slug', $author, 'person', 'ARRAY_A');
                $name = $data['name'];
                $return_names_array[] = $name;
            }
            return $return_names_array;
        }

        // We're compiling a byline list of the authors of this post
        $i = 0;
        $total_num_people = count($byline_array);
        $output = array();

        if (!empty($byline_array)) {
            foreach ($byline_array as $author) {

                // We're past the first person which means we need to join the
                // names
                // together.
                if ($i > 0) {
                    // If we only have two people, use an ampersand to join
                    // them.
                    // Allow an override via the 'cap_byline_and' filter.
                    if ($total_num_people == 2) {
                        if (has_filter('cap_byline_and')) {
                            $output[] = apply_filters('cap_byline_and', $content);
                        } else {
                            $output[] = ' & ';
                        }

                        // We have more than two people total so we'll use
                        // commas instead.
                    } else {
                        $output[] = ', ';
                    }
                }

                $data = get_term_by('slug', $author, 'person', 'ARRAY_A');
                $person_twitter_handle = get_field('person_twitter_handle', 'person_' . $data["term_id"]);

                // If disable links is set to true or if this person
                // specifically has no linked bio, display name only.
                if (true == $disable_link || false == get_field('person_is_linked', 'person_' . $data["term_id"])) {
                    $output[] = $data["name"];
                } else {
                    $output[] = '<a href="/?person=' . $data["slug"] . '">' . $data["name"] . '</a>';

                    // Checks for single instance of any post type, not just
                    // Wordpress defaults
                    if (!empty($person_twitter_handle) && is_singular(get_post_type())) {
                        $output[] = "<a href=\"https://twitter.com/intent/user?screen_name=" . $person_twitter_handle . "\"><img src=\"" . content_url() . "/plugins/cap-byline/bird_blue_16.png\" class=\"twitter-bird\"></a>";
                    }

                    // TODO: Add Other Social Media links here.
                }

                $i++;
            }

            // Our author list is empty. Fill the output with filler text.
        } else {
            // TODO: Replace with wp_error
            $output[] = "<!--Found No Data, Check CAP Byline Plugin-->";
        }

        return implode('', $output);
    }


    /**
     * Returns the post's list of authors based on various criteria.
     *
     * @since 1.0.0
     *
     * @param $type defines
     *            the types of byline available.
     * @param $post_id is
     *            the post id, we should pass this in always.
     *
     */
    private function generate_timestamp($post_id)
    {
        // If is a single post page display the time, otherwise just display
        // only the date.
        if (is_singular() && true === get_field('global_display_post_time', 'options')) {
            $time_format = 'F j, Y \a\t g:i a';
        } else {
            $time_format = 'F j, Y';
        }

        $time_string = array(
            '<time class="published" datetime="%1$s">%2$s</time>'
        );

        // if the post time is not within one hour of the updated time...
        if (get_the_modified_time('jnyH') != get_the_time('jnyH') && true == get_post_meta($post_id, 'cap_enable_updated_time', true) && false == get_field('global_disable_update_time', 'options')) {
            $time_string[] = '&nbsp;<time class="updated" datetime="%3$s">Updated: %4$s</time>';
        }

        return sprintf(implode('', $time_string), esc_attr(get_the_date($time_format, $post_id)), // %1$s
esc_html(get_the_date($time_format, $post_id)), // %2$s
esc_attr(get_the_modified_date($time_format, $post_id)), // %3$s
esc_html(get_the_modified_date($time_format, $post_id))); // %4$s
    }


    /**
     * Returns byline content HTML for the default byline format.
     *
     * Location of the filter points:
     * - cap_full_byline_open
     * - cap_full_byline_persons
     * - cap_full_byline_time
     * - cap_full_byline_close
     *
     * @since 1.0.0
     *
     * @param $post_id is
     *            the post id. We'll need this to pull all post authors.
     * @param $time_string is
     *            the formatted date/timestamp.
     *
     */
    private function __generate_byline_prefix($post_id, $time_string)
    {
        $markup = array();
        if (has_filter('cap_full_byline_open')) {
            $content = "";
            $markup[] = apply_filters('cap_full_byline_open', $content);
        }

        if (has_filter('cap_full_byline_persons')) {
            $content = "";
            $markup[] = apply_filters('cap_full_byline_persons', $content, $post_id);
        } else {
            // TODO: Don't show the 'by' unless there is at least one author.
            $markup[] = '<span class="byline"> ' . __('by') . ' ';
        }

        return implode('', $markup);
    }


    /**
     * Returns byline content HTML for the default byline format.
     *
     * Location of the filter points:
     * - cap_full_byline_open
     * - cap_full_byline_persons
     * - cap_full_byline_time
     * - cap_full_byline_close
     *
     * @since 1.0.0
     *
     * @param $post_id is
     *            the post id. We'll need this to pull all post authors.
     * @param $time_string is
     *            the formatted date/timestamp.
     *
     */
    private function __generate_byline_postfix($post_id, $time_string)
    {
        $markup = array();

        if (!has_filter('cap_full_byline_persons')) {
            $markup[] = '</span>';
        }

        if (has_filter('cap_full_byline_time')) {
            $content = "";
            $markup[] = apply_filters('cap_full_byline_time', $content, $post_id);
        } else {
            $markup[] = ' <span class="posted-on">' . __('Posted on') . ' ' . $time_string . '</span>';
        }

        if (has_filter('cap_full_byline_close')) {
            $content = "";
            $markup[] = apply_filters('cap_full_byline_close', $content);
        }

        return implode('', $markup);
    }


    /**
     * Returns byline content HTML when the 'dateonly' byline format
     * has been chosen.
     *
     * @since 1.0.0
     *
     * @param $time_string is
     *            the formatted last update timestamp.
     *
     */
    private function generate_dateonly_byline_text($time_string)
    {
        return '<span class="posted-on">' . $time_string . '</span>';
    }


    /**
     * Returns byline content HTML when the 'bylineonly' byline format
     * has been chosen.
     *
     * @since 1.0.0
     *
     * @param $post_id is
     *            the post id. We'll need this to pull all post authors.
     *
     */
    private function generate_bylineonly_byline_text($post_id)
    {
        // TODO: don't show the 'by' unless there is at least one author.
        return ' ' . __('by') . ' ' . get_cap_authors($post_id, null, null, null);
    }


    /**
     * Returns byline content HTML for the default byline format.
     *
     * Location of the filter points:
     * - cap_full_byline_open
     * - cap_full_byline_persons
     * - cap_full_byline_time
     * - cap_full_byline_close
     *
     * @since 1.0.0
     *
     * @param $post_id is
     *            the post id. We'll need this to pull all post authors.
     * @param $time_string is
     *            the formatted date/timestamp.
     *
     */
    private function generate_default_byline_text($post_id, $time_string)
    {
        $markup = array();

        $markup[] = $this->__generate_byline_prefix($post_id, $time_string);

        if (!has_filter('cap_full_byline_persons')) {
            $markup[] = get_cap_authors($post_id, null, null, null);
        }

        $markup[] = $this->__generate_byline_postfix($post_id, $time_string);

        return implode('', $markup);
    }


    /**
     * Returns byline content HTML for the default byline format.
     *
     * Location of the filter points:
     * - cap_full_byline_open
     * - cap_full_byline_persons
     * - cap_full_byline_time
     * - cap_full_byline_close
     *
     * @since 1.0.0
     *
     * @param $post_id is
     *            the post id. We'll need this to pull all post authors.
     * @param $time_string is
     *            the formatted date/timestamp.
     *
     */
    private function generate_nolinks_byline_text($post_id, $time_string)
    {
        $markup = array();

        $markup[] = $this->__generate_byline_prefix($post_id, $time_string);

        if (!has_filter('cap_full_byline_persons')) {
            $markup[] = get_cap_authors($post_id, true, null, null);
        }

        $markup[] = $this->__generate_byline_postfix($post_id, $time_string);

        return implode('', $markup);
    }


    /**
     * Returns the post's list of authors based on various criteria.
     *
     * @since 1.0.0
     *
     * @param $type defines
     *            the types of byline available.
     * @param $post_id is
     *            the post id, we should pass this in always.
     *
     */
    public function get_cap_byline($type, $post_id)
    {
        $time_string = $this->generate_timestamp($post_id);

        $markup = array();

        switch ($type) {
            case 'dateonly':
                $markup[] = $this->generate_dateonly_byline_text($time_string);
                break;

            case 'bylineonly':
                $markup[] = $this->generate_bylnieonly_byline_text($post_id);
                break;

            case 'nolinks':
                $markup[] = $this->generate_nolinks_byline_text($post_id, $time_string);
                break;

            default:
                $markup[] = $this->generate_default_byline_text($post_id, $time_string);
                break;
        }

        return implode('', $markup);
    }


    /**
     * Returns HTML to generate an email contact form.
     *
     * @since 1.0.0
     *
     * @param $person is
     *            a person object.
     *
     */
    private function contact_form($person)
    {
        $markup = array();

        $person_email = get_field('person_email', 'person_' . $person->term_id);

        // if the bio has an email associated add a contact modal form to
        // $markup also check the form ID is present
        if (!empty($person_email)) {
            $markup[] = '<a id="contact-modal-link" class="cap-contact-modal-link" href="javascript:void(0);">';
            $markup[] = '<img src="' . plugin_dir_url('cap-byline.php') . '/cap-byline/mail.png" width="18px"> ';
            $markup[] = __('Contact') . ' ' . $person->name . '</a>';

            $markup[] = '<div id="contact-modal" class="modal"><div class="modal-wrapper"><div class="close-modal"><img src="';
            $markup[] = plugin_dir_url('cap-byline.php');
            $markup[] = '/cap-byline/close_circle.png"></div><div class="modal-window">';

            $markup[] = gravity_form(get_field('author_contact_form_id', 'options'), false, false, false, array(
                'author_contact_email' => $person->term_id
            ), true, 25, false);

            gravity_form_enqueue_scripts(get_field('author_contact_form_id', 'options'), true);

            $markup[] = '</div></div></div>';
        }

        return implode('', $markup);
    }


    /**
     * Returns HTML needed to follow a person on twitter
     *
     * @since 1.0.0
     *
     * @param $person is
     *            a person object
     *
     */
    private function send_tweet($person_twitter_handle)
    {
        $markup = array();

        $person_twitter_handle = get_field('person_twitter_handle', 'person_' . $person->term_id);

        // if the bio has a twitter handle associated add the follow button to
        // $markup
        if (!empty($person_twitter_handle)) {
            $markup[] = '<span id="twitter-follow"><a href="https://twitter.com/' . $person_twitter_handle;
            $markup[] = '" class="twitter-follow-button" data-show-count="false" data-lang="en">';
            $markup[] = __("Follow") . ' @' . $person_twitter_handle;
            $markup[] = '</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
            $markup[] = '</span>';
        }

        return implode('', $markup);
    }


    /**
     * Returns HTML for displaying a person's pictures
     *
     * @since 1.0.0
     *
     * @param $person is
     *            a person object
     *
     */
    private function pictures($person)
    {
        $person_photo = get_field('person_photo', 'person_' . $person->term_id);
        $person_photo_hi_res = get_field('person_photo_hi_res', 'person_' . $person->term_id);

        // This field is only being used by ThinkProgress post ACF migration.
        // The field itself is registered only in the TP theme in fields.php
        $person_photo_legacy = get_field('person_photo_legacy', 'person_' . $person->term_id);

        $markup = array();

        if (!empty($person_photo)) {
            $markup[] = '<div class="bio-pic">' . $person_photo_output;

            // optional hi res photo
            if (!empty($person_photo_hi_res_output)) {
                $markup[] = '<div class="bio-pic-hi-res"><a href="' . $person_photo_hi_res_output[0] . '">' . __("Download hi-res") . '</a></div>';
            }

            $markup[] = '</div>';
        } elseif (!empty($person_photo_legacy)) {
            $markup[] = '<div class="bio-pic"><img src="' . $person_photo_legacy . '"></div>';
        }

        return implode('', $markup);
    }


    /**
     * Returns a person's biographical information
     *
     * @since 1.0.0
     *
     * @param $style defines
     *            the types of byline available.
     * @param $post_id is
     *            the post id, we should pass this in always.
     *
     */
    public function get_cap_person_bio($style, $person = null)
    {
        if (empty($person)) {
            global $wp_query;
            $person = $wp_query->get_queried_object();
        }

        $person_title = get_field('person_title', 'person_' . $person->term_id);

        if (!empty($person_photo)) {
            $person_photo_output = wp_get_attachment_image($person_photo, 'medium');
            $person_photo_hi_res_output = wp_get_attachment_image_src($person_photo_hi_res, 'full');
        }

        $markup = array();

        $markup[] = '<div class="person ' . $style . '">';

        // if the "full" style is being displayed then add the person name and
        // title atop the bio
        if ('full' == $style) {
            $markup[] = '<div class="person-title"><h1>' . $person->name . '<br><small>';
            $markup[] = $person_title . '</small></h1></div>';
        }

        // begin the actual bio area
        $markup[] = '<div class="person-bio">';
        $markup[] = $this->pictures($person);

        // get the bio and add it to $markup
        $markup[] = '<div class="bio">';
        if (empty($style)) {
            $markup[] = '<strong>' . $person->name . '</strong> ';
        }

        $markup[] = $person->description;

        $gform_markup = $this->contact_form($person);
        $send_tweet_markup = $this->send_tweet($person);

        // if either an email addy or twitter handle are present lets add a
        // hard line break for spacing
        if (!empty($gform_markup) || !empty($send_tweet_markup)) {
            $markup[] = '<div id="contact-button-seperator"></div>';
        }

        $markup[] = $gform_markup;
        $markup[] = $send_tweet_markup;

        $markup[] = '</div>'; // close out .bio
        $markup[] = '</div>'; // close out .person-bio
        $markup[] = '</div>'; // close out .person .$style

        return implode('', $markup);
    }


    /**
     * Get author facebook IDs.
     *
     * @since 1.0.0
     */
    public function get_the_cap_author_facebook_ids()
    {
        global $post;
        $facebook_ids = array();

        $authors = get_cap_authors($post->ID, true, true, true);

        if (!empty($authors) && is_array($authors)) {
            foreach ($authors as $author) {
                $data = get_term_by('slug', $author, 'person', 'ARRAY_A');
                $facebook_id = get_field('person_facebook_id', 'person_' . $data['term_id']);

                if (!empty($facebook_id)) {
                    $facebook_ids[] = esc_attr($facebook_id);
                }
            }
        }

        return $facebook_ids;
    }


    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 2.0.0
     */
    public function run()
    {
        $this->loader->run();

        if (function_exists("register_field_group")) {
            $this->register_acfs();
        }

        if (function_exists('acf_add_options_page')) {
            acf_add_options_page($this->acf_field_settings);
        }
    }


    /**
     * Shutdown the loader to remove all of the hooks from WordPress.
     *
     * @since 2.0.0
     */
    public function shutdown()
    {
        $this->loader->shutdown();

        if (function_exists('acf_add_options_page')) {
            remove_menu_page($this->acf_field_settings["menu_slug"]);
        }

        $this->remove_admin_hooks();
        $this->remove_public_hooks();
        $this->remove_general_hooks();
    }


    /**
     * Plugin name getter
     *
     * @since 1.0.0
     * @return string The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }


    /**
     * Loader object getter.
     *
     * @since 1.0.0
     * @return CAP_PLUGIN_TEMPLATE_Loader Orchestrates the hooks of the
     *         plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }


    /**
     * Version getter.
     *
     * @since 1.0.0
     * @return string The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}

?>