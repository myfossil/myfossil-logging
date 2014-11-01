<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://github.com/usbmis/myfossil
 * @since      0.0.1
 *
 * @package    myFOSSIL_Logging
 * @subpackage myFOSSIL_Logging/includes
 */

/**
 * Include partial functions as necessary.
 */
require_once 'partials/myfossil-logging-admin-display.php';

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    myFOSSIL_Logging
 * @subpackage myFOSSIL_Logging/admin
 * @author     Brandon Wood <bwood@atmoapps.com>
 */
class myFOSSIL_Logging_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.0.1
	 * @var      string    $name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;

	}

    /**
     * Register options for the plugin in admin panel.
     *
     * @since 0.0.1
     */
    public function register_settings() {
        register_setting('myfossil-logging', 'log_directory');
        register_setting('myfossil-logging', 'log_rotate');
    }

    /**
     * Register the settings pages in the admin panel.
     *
     * @since 0.0.1
     */
    public function register_menus() {
        /**
         * myFOSSIL Logging administrator settings page.
         */
        add_menu_page('Logging', 'Logging', 
            'administrator', 'myfossil-logging-home', 
            'myfossil_logging__home_page', 'dashicons-chart-line');

        add_submenu_page('myfossil-logging-home', 'myFOSSIL Logging', 'Settings',
            'administrator', 'myfossil-logging-settings',
            'myfossil_logging__settings_page');
    }


    /**
     * Create custom post type for filter hooks.
     *
     * @since 0.0.1
     */
    public function logfilterhook_post_type_init() {

        $labels = array(
            'name'                => _x( 'Log Hooks', 'Post Type General Name', 'myfossil-logging' ),
            'singular_name'       => _x( 'Log Hook', 'Post Type Singular Name', 'myfossil-logging' ),
            'menu_name'           => __( 'Log Hook', 'myfossil-logging' ),
            'parent_item_colon'   => __( 'Parent Log Hook:', 'myfossil-logging' ),
            'all_items'           => __( 'Hooks', 'myfossil-logging' ),
            'view_item'           => __( 'View Log Hook', 'myfossil-logging' ),
            'add_new_item'        => __( 'Add New Log Hook', 'myfossil-logging' ),
            'add_new'             => __( 'Add New', 'myfossil-logging' ),
            'edit_item'           => __( 'Edit Log Hook', 'myfossil-logging' ),
            'update_item'         => __( 'Update Log Hook', 'myfossil-logging' ),
            'search_items'        => __( 'Search Log Hook', 'myfossil-logging' ),
            'not_found'           => __( 'Not found', 'myfossil-logging' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'myfossil-logging' ),
        );

        $args = array(
            'label'               => __( 'log_hook', 'myfossil-logging' ),
            'description'         => __( 'Hook that will generate a log entry every time it\'s called', 'myfossil-logging' ),
            'labels'              => $labels,
            'supports'            => array( 'author', ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => 'myfossil-logging-home',
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
        );

        register_post_type( 'log_hook', $args );
    }


    /**
     * Configure path to Advanced Custom Fields in the plugin.
     *
     * @since 0.0.1
     */
    public function acf_settings_path( $path ) {
        return plugin_dir_url( __FILE__ ) . 'includes/acf/';
    }


    /**
     * Configure directory to Advanced Custom Fields in the plugin.
     *
     * @since 0.0.1
     */
    public function acf_settings_dir( $dir ) {
        return $this->acf_settings_path( $dir );
    }

    /**
     * Enable or disable showing the ACF menu in the admin panel.
     *
     * @since 0.0.1
     */
    public function acf_show_admin( $show ) {
        return false;
    }

    /**
     * Enable or disable showing the ACF menu in the admin panel.
     *
     * @since 0.0.1
     */
    public function acf_hide_admin() {
        //remove_menu_page('edit.php?post_type=acf');
    }

    
    /**
     * Change the administrative display of Log Hook post types.
     *
     * @see https://yoast.com/custom-post-type-snippets/
     * @since 0.0.1
     */
    public function change_log_hook_columns( $cols ) {
        $cols = array(
                'cb' => '<input type="checkbox" />',
                'hook_tag' => __('Hook Tag', 'myfossil-logging'),
                'hook_function' => __('PHP Function', 'myfossil-logging'),
                'enabled' => __('Enabled', 'myfossil-logging'),
                'hook_type' => __('Hook Type', 'myfossil-logging')
            );

        return $cols;
    }
    

    /**
     * Change the administrative display of Log Hook post types with content.
     *
     * @see https://yoast.com/custom-post-type-snippets/
     * @since 0.0.1
     */
    public function change_log_hook_columns_content( $col, $pid ) {
        $valid_columns = array('enabled', 'hook_type', 'hook_tag', 'hook_function' );
        if ( !in_array( $col, $valid_columns ) )
            return;
        echo get_post_meta($pid, $col, true);
    }


    /**
     * Register the Advanced Custom Fields for the custom post type.
     *
     * @since 0.0.1
     */
    public function register_acf_logfilterhook() {
        if(function_exists("register_field_group"))
        {
            register_field_group(array (
                'id' => 'acf_myfossil_logging_filter_hook',
                'title' => 'myfossil_logging_filter_hook',
                'fields' => array (
                    array (
                        'key' => 'field_541739b012ad9',
                        'label' => 'Enabled',
                        'name' => 'enabled',
                        'type' => 'true_false',
                        'instructions' => 'Whether to enable this hook',
                        'required' => 1,
                        'default_value' => 1,
                        'layout' => 'vertical',
                    ),
                    array (
                        'key' => 'field_541739b012ad1',
                        'label' => 'Hook Type',
                        'name' => 'hook_type',
                        'type' => 'radio',
                        'instructions' => 'Whether this is a hook on an Action or a Filter',
                        'required' => 1,
                        'choices' => array (
                            'action' => 'Action',
                            'filter' => 'Filter',
                        ),
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => 'filter',
                        'layout' => 'vertical',
                    ),
                    array (
                        'key' => 'field_541739f912ad2',
                        'label' => 'Hook Tag',
                        'name' => 'hook_tag',
                        'instructions' => 'Name of the hook, e.g. <span>admin_init</>',
                        'type' => 'text',
                        'required' => 1,
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'formatting' => 'none',
                        'maxlength' => '',
                    ),
                    array (
                        'key' => 'field_54173a0912ad3',
                        'label' => 'Hook Function',
                        'name' => 'hook_function',
                        'instructions' => 'Name of the PHP function to accept parameters from this filter',
                        'type' => 'text',
                        'required' => 1,
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'formatting' => 'none',
                        'maxlength' => '',
                    ),
                    array (
                        'key' => 'field_54173a5f12ad4',
                        'label' => 'Priority',
                        'name' => 'priority',
                        'instructions' => 'Higher numbers execute later than other action/filter hooks',
                        'type' => 'number',
                        'required' => 1,
                        'default_value' => 999,
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => 1,
                        'max' => '',
                        'step' => 1,
                    ),
                    array (
                        'key' => 'field_54173a7f12ad5',
                        'label' => 'Accepted Arguments',
                        'name' => 'accepted_args',
                        'instructions' => 'Number of arguments the given <span>hook_function</span> can accept',
                        'type' => 'number',
                        'default_value' => '1',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'min' => 1,
                        'max' => '',
                        'step' => 1,
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'log_hook',
                            'order_no' => 0,
                            'group_no' => 0,
                        ),
                    ),
                ),
                'options' => array (
                    'position' => 'normal',
                    'layout' => 'no_box',
                    'hide_on_screen' => array (
                    ),
                ),
                'menu_order' => 0,
            ));
        }

    }


	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in myFOSSIL_Logging_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The myFOSSIL_Logging_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) 
                . 'css/myfossil-logging-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    0.0.1
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in myFOSSIL_Logging_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The myFOSSIL_Logging_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) 
                . 'js/myfossil-logging-admin.js', array( 'jquery' ), $this->version, false );
	}

}
