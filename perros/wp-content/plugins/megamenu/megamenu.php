<?php

/*
 * Plugin Name: Max Mega Menu
 * Plugin URI:  http://www.maxmegamenu.com
 * Description: Mega Menu for WordPress.
 * Version:     1.9
 * Author:      Tom Hemsley
 * Author URI:  http://www.maxmegamenu.com
 * License:     GPL-2.0+
 * Copyright:   2015 Tom Hemsley (http://www.maxmegamenu.com)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

if ( ! class_exists( 'Mega_Menu' ) ) :

/**
 * Main plugin class
 */
final class Mega_Menu {


	/**
	 * @var string
	 */
	public $version = '1.9';


	/**
	 * Init
	 *
	 * @since 1.0
	 */
	public static function init() {
		$plugin = new self();
	}


	/**
	 * Constructor
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->define_constants();
		$this->includes();

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'admin_init', array( $this, 'install_upgrade_check' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
        add_action( 'widgets_init', array( $this, 'register_widget' ) );
		add_action( 'after_setup_theme', array( $this, 'register_nav_menus' ) );

		add_filter( 'wp_nav_menu_args', array( $this, 'modify_nav_menu_args' ), 9999 );
		add_filter( 'wp_nav_menu', array( $this, 'add_responsive_toggle' ), 10, 2 );
		add_filter( 'wp_nav_menu_objects', array( $this, 'add_widgets_to_menu' ), 10, 2 );

		add_filter( 'megamenu_nav_menu_objects_before', array( $this, 'apply_depth_to_menu_items' ), 5, 2 );
		add_filter( 'megamenu_nav_menu_objects_before', array( $this, 'apply_megamenu_settings_to_menu_items' ), 6, 2 );
		add_filter( 'megamenu_nav_menu_objects_before', array( $this, 'apply_megamenu_classes_to_menu_items' ), 7, 2 );

		add_filter( 'megamenu_nav_menu_css_class', array( $this, 'prefix_menu_classes' ) );
		add_filter( 'black_studio_tinymce_enable_pages' , array($this, 'megamenu_blackstudio_tinymce' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts'), 9 );

        // add 'go pro' link to plugin options
        $plugin = plugin_basename( __FILE__ );

        add_filter( "plugin_action_links_{$plugin}", array( $this, 'upgrade_to_pro_link' ) );

		register_deactivation_hook( __FILE__, array( $this, 'delete_version_number') );

		add_shortcode( 'maxmenu', array( $this, 'register_shortcode' ) );
		add_shortcode( 'maxmegamenu', array( $this, 'register_shortcode' ) );

		if ( is_admin() ) {

			new Mega_Menu_Nav_Menus();
			new Mega_Menu_Widget_Manager();
			new Mega_Menu_Menu_Item_Manager();
			new Mega_Menu_Settings();

		}

		$mega_menu_style_manager = new Mega_Menu_Style_Manager();
		$mega_menu_style_manager->setup_actions();

	}


	/**
	 * Add custom actions to allow enqueuing scripts on specific pages
	 *
	 * @since 1.8.3
	 */
	public function admin_enqueue_scripts( $hook ) {

        wp_enqueue_style( 'maxmegamenu-global', MEGAMENU_BASE_URL . 'css/admin/global.css', array(), MEGAMENU_VERSION );

        if ( 'nav-menus.php' == $hook ) {
        	do_action("megamenu_nav_menus_scripts", $hook );
        }

        if ( 'toplevel_page_maxmegamenu' == $hook ) {
        	do_action("megamenu_admin_scripts", $hook );
        }

	}


	/**
	 * Register menu locations created within Max Mega Menu.
	 *
	 * @since 1.8
	 */
	public function register_nav_menus() {

		$locations = get_option('megamenu_locations');

		if ( is_array( $locations ) && count( $locations ) ) {

			foreach ( $locations as $key => $val ) {

			  register_nav_menu( $key, $val );

			}

		}
	}


    /**
     * Add go pro link on plugin page
     *
     * @since 1.8.3
     */
    public function upgrade_to_pro_link( $links ) {

        if ( function_exists( 'is_plugin_active' ) && ! is_plugin_active( 'megamenu-pro/megamenu-pro.php' ) ) {
            $links[] = '<a href="http://www.maxmegamenu.com/upgrade/?utm_source=free&amp;utm_medium=link&amp;utm_campaign=pro" target="_blank"><b>' . __( "Go Pro", "megamenu" ) . '</b></a>';
        }

        return $links;

    }


	/**
	 * Black Studio TinyMCE Compatibility.
	 * Load TinyMCE assets on nav-menus.php page.
	 *
	 * @since 1.8
	 * @param array $pages
	 * @return array $pages
	 */
	public function megamenu_blackstudio_tinymce( $pages ) {
	    $pages[] = 'nav-menus.php';
	    return $pages;
	}


	/**
	 * Detect new or updated installations and run actions accordingly.
	 *
	 * @since 1.3
	 */
	public function install_upgrade_check() {

		if ( $version = get_option( "megamenu_version" ) ) {

			if ( version_compare( $this->version, $version, '!=' ) ) {

				update_option( "megamenu_version", $this->version );

				do_action( "megamenu_after_update" );

			}

		} else {

			add_option( "megamenu_version", $this->version );

			do_action( "megamenu_after_install" );

		}

	}


	/**
	 * Delete the current version number
	 *
	 * @since 1.3
	 */
	public function delete_version_number() {

		delete_option( "megamenu_version", $this->version );

	}

    /**
     * Register widget
     *
     * @since 1.7.4
     */
    public function register_widget() {

        register_widget( 'Mega_Menu_Widget' );

    }

    /**
     * Shortcode used to display a menu
     *
     * @since 1.3
     * @return string
     */
    public function register_shortcode( $atts ) {

        if ( ! isset( $atts['location'] ) ) {
            return false;
        }

        if ( has_nav_menu( $atts['location'] ) ) {
		    return wp_nav_menu( array( 'theme_location' => $atts['location'], 'echo' => false ) );
		}

        return "<!-- menu not found [maxmegamenu location={$atts['location']}] -->";

    }


    /**
     * Initialise translations
	 *
	 * @since 1.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain( 'megamenu', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    }


	/**
	 * Define Mega Menu constants
	 *
	 * @since 1.0
	 */
	private function define_constants() {

		define( 'MEGAMENU_VERSION',    $this->version );
		define( 'MEGAMENU_BASE_URL',   trailingslashit( plugins_url( 'megamenu' ) ) );
		define( 'MEGAMENU_PATH',       plugin_dir_path( __FILE__ ) );

	}


	/**
	 * All Mega Menu classes
	 *
	 * @since 1.0
	 */
	private function plugin_classes() {

		return array(
			'mega_menu_walker'            => MEGAMENU_PATH . 'classes/walker.class.php',
			'mega_menu_widget_manager'    => MEGAMENU_PATH . 'classes/widget-manager.class.php',
			'mega_menu_menu_item_manager' => MEGAMENU_PATH . 'classes/menu-item-manager.class.php',
			'mega_menu_nav_menus'         => MEGAMENU_PATH . 'classes/nav-menus.class.php',
			'mega_menu_style_manager'     => MEGAMENU_PATH . 'classes/style-manager.class.php',
			'mega_menu_settings'          => MEGAMENU_PATH . 'classes/settings.class.php',
			'mega_menu_widget'            => MEGAMENU_PATH . 'classes/widget.class.php',
			'scssc'                       => MEGAMENU_PATH . 'classes/scssc.inc.php',

		);

	}


	/**
	 * Load required classes
	 *
	 * @since 1.0
	 */
	private function includes() {

		$autoload_is_disabled = defined( 'MEGAMENU_AUTOLOAD_CLASSES' ) && MEGAMENU_AUTOLOAD_CLASSES === false;

		if ( function_exists( "spl_autoload_register" ) && ! $autoload_is_disabled ) {

			// >= PHP 5.2 - Use auto loading
			if ( function_exists( "__autoload" ) ) {
				spl_autoload_register( "__autoload" );
			}

			spl_autoload_register( array( $this, 'autoload' ) );

		} else {

			// < PHP5.2 - Require all classes
			foreach ( $this->plugin_classes() as $id => $path ) {
				if ( is_readable( $path ) && ! class_exists( $id ) ) {
					require_once $path;
				}
			}

		}

	}


	/**
	 * Autoload classes to reduce memory consumption
	 *
	 * @since 1.0
	 * @param string $class
	 */
	public function autoload( $class ) {

		$classes = $this->plugin_classes();

		$class_name = strtolower( $class );

		if ( isset( $classes[ $class_name ] ) && is_readable( $classes[ $class_name ] ) ) {
			require_once $classes[ $class_name ];
		}

	}


	/**
	 * Appends "mega-" to all menu classes.
	 * This is to help avoid theme CSS conflicts.
	 *
	 * @since 1.0
	 * @param array $classes
	 * @return array
	 */
	public function prefix_menu_classes( $classes ) {
		$return = array();

		foreach ( $classes as $class ) {
			$return[] = 'mega-' . $class;
		}

		return $return;
	}


	/**
	 * Add the html for the responsive toggle box to the menu
	 *
	 * @param string $nav_menu
	 * @param object $args
	 * @return string
	 * @since 1.3
	 */
	public function add_responsive_toggle( $nav_menu, $args ) {

		// make sure we're working with a Mega Menu
		if ( ! is_a( $args->walker, 'Mega_Menu_Walker' ) )
			return $nav_menu;

		$toggle_id = apply_filters("megamenu_toggle_id", "mega-menu-toggle-{$args->theme_location}", $args->menu, $args->theme_location );

		$toggle_class = 'mega-menu-toggle';

		$find = 'class="' . $args->container_class . '">';

		$replace = $find . '<div class="' . $toggle_class . '"></div>';

		return str_replace( $find, $replace, $nav_menu );
	}


   	/**
   	 * Apply a depth to each item in the menu
   	 *
   	 * @since 1.8.2
   	 * @param array $items - All menu item objects
	 * @param object $args
	 * @return array - Menu objects including widgets
   	 */
	public function apply_depth_to_menu_items( $items, $args ) {

		// We can't assume the menu items are in any particular order.
		// (for example, the  "Add Descendants as sub menu items" adds all items to the 'bottom' of the menu)
		// We only need to know about top level menu items and their immediate children

	    $parents = array();

	    foreach ( $items as $key => $item ) {
	        if ( $item->menu_item_parent == 0 ) {
	            $parents[] = $item->ID;
	            $item->depth = 0;
	        }
	    }

	    if ( count( $parents ) ) {
		    foreach ( $items as $key => $item ) {
		        if ( in_array( $item->menu_item_parent, $parents ) ) {
		            $item->depth = 1;
		        }
		    }
	    }

	    return $items;
	}


   	/**
   	 * Store the mega menu settings for each menu item
   	 *
   	 * @since 1.8.2
   	 * @param array $items - All menu item objects
	 * @param object $args
	 * @return array - Menu objects including widgets
   	 */
	public function apply_megamenu_settings_to_menu_items( $items, $args ) {

		// apply saved metadata to each menu item
		foreach ( $items as $item ) {

	        $saved_settings = array_filter( (array) get_post_meta( $item->ID, '_megamenu', true ) );

	        $item->megamenu_settings = array_merge( Mega_Menu_Nav_Menus::get_menu_item_defaults(), $saved_settings );

	    }

	    return $items;
	}

   	/**
   	 * Apply classes to nav menu items
   	 *
   	 * @since 1.8.2
   	 * @param array $items - All menu item objects
	 * @param object $args
	 * @return array - Menu objects including widgets
   	 */
	public function apply_megamenu_classes_to_menu_items( $items, $args ) {

		$parents = array();


	    foreach ( $items as $item ) {

	        if ( $item->depth === 0 ) {
				$item->classes[] = 'align-' . $item->megamenu_settings['align'];
				$item->classes[] = 'menu-' . $item->megamenu_settings['type'];
			}

	        if ( $item->megamenu_settings['hide_arrow'] == 'true') {
	        	$item->classes[] = 'hide-arrow';
	        }

	        if ( $item->megamenu_settings['hide_text'] == 'true' && $item->depth === 0 ) {
	        	$item->classes[] = 'hide-text';
	        }

	        if ( $item->megamenu_settings['item_align'] != 'left' && $item->depth === 0 ) {
	        	$item->classes[] = 'item-align-' . $item->megamenu_settings['item_align'];
	        }

			if ( $item->megamenu_settings['disable_link'] == 'true') {
			    $item->classes[] = 'disable-link';
			}

	        // add column classes for second level menu items displayed in mega menus
	        if ( $item->depth == 1 ) {

	        	$parent_settings = array_filter( (array) get_post_meta( $item->menu_item_parent, '_megamenu', true ) );

	        	if ( isset( $parent_settings['type'] ) && $parent_settings['type'] == 'megamenu' ) {

					$parent_settings = array_merge( Mega_Menu_Nav_Menus::get_menu_item_defaults(), $parent_settings );

					$span = $item->megamenu_settings['mega_menu_columns'];
					$total_columns = $parent_settings['panel_columns'];

					if ( $total_columns >= $span ) {
						$item->classes[] = "menu-columns-{$span}-of-{$total_columns}";
						$column_count = $span;
					} else {
						$item->classes[] = "menu-columns-{$total_columns}-of-{$total_columns}";
						$column_count = $total_columns;
					}

					if ( ! isset( $parents[ $item->menu_item_parent ] ) ) {
						$parents[ $item->menu_item_parent ] = $column_count;
					} else {
						$parents[ $item->menu_item_parent ] = $parents[ $item->menu_item_parent ] + $column_count;

						if ( $parents[ $item->menu_item_parent ] > $total_columns ) {
							$parents[ $item->menu_item_parent ] = $column_count;
							$item->classes[] = 'menu-clear';
						}
					}

				}

	        }

	    }

	    return $items;
	}


   	/**
   	 * Append the widget objects to the menu array before the
   	 * menu is processed by the walker.
   	 *
   	 * @since 1.0
   	 * @param array $items - All menu item objects
	 * @param object $args
	 * @return array - Menu objects including widgets
   	 */
	public function add_widgets_to_menu( $items, $args ) {

		// make sure we're working with a Mega Menu
		if ( ! is_a( $args->walker, 'Mega_Menu_Walker' ) )
			return $items;

	    $items = apply_filters( "megamenu_nav_menu_objects_before", $items, $args );

		$widget_manager = new Mega_Menu_Widget_Manager();

		$default_columns = apply_filters("megamenu_default_columns", 1);

	    foreach ( $items as $item ) {

			// only look for widgets on top level items
			if ( $item->depth === 0 && $item->megamenu_settings['type'] == 'megamenu' ) {

				$panel_widgets = $widget_manager->get_widgets_for_menu_id( $item->ID );

				if ( count( $panel_widgets) ) {

					if ( ! in_array( 'menu-item-has-children', $item->classes ) ) {
						$item->classes[] = 'menu-item-has-children';
					}

					$cols = 0;

					foreach ( $panel_widgets as $widget ) {

						$menu_item = array(
							'type'              => 'widget',
							'title'             => '',
							'content'           => $widget_manager->show_widget( $widget['widget_id'] ),
							'menu_item_parent'  => $item->ID,
							'db_id'             => 0, // This menu item does not have any childen
							'ID'                => $widget['widget_id'],
							'menu_order'        => $item->menu_order + 1,
							'classes'           => array(
								"menu-item",
								"menu-item-type-widget",
								"menu-columns-{$widget['mega_columns']}-of-{$item->megamenu_settings['panel_columns']}",
								"menu-widget-class-" . $widget_manager->get_widget_class( $widget['widget_id'] )
							)
						);

						if ( $cols == 0 ) {
							$menu_item['classes'][] = "menu-clear";
						}

						$cols = $cols + $widget['mega_columns'];

						if ( $cols > $item->megamenu_settings['panel_columns'] ) {
							$menu_item['classes'][] = "menu-clear";
							$cols = $widget['mega_columns'];
						}

						$items[] = (object) $menu_item;
					}
				}
			}
		}

	    $items = apply_filters( "megamenu_nav_menu_objects_after", $items, $args );

		return $items;
	}


	/**
	 * Use the Mega Menu walker to output the menu
	 * Resets all parameters used in the wp_nav_menu call
	 * Wraps the menu in mega-menu IDs and classes
	 *
	 * @since 1.0
	 * @param $args array
	 * @return array
	 */
	public function modify_nav_menu_args( $args ) {

		$settings = get_option( 'megamenu_settings' );
		$current_theme_location = $args['theme_location'];

		$locations = get_nav_menu_locations();

		if ( isset ( $settings[ $current_theme_location ]['enabled'] ) && $settings[ $current_theme_location ]['enabled'] == true ) {

			if ( ! isset( $locations[ $current_theme_location ] ) ) {
				return $args;
			}

			$menu_id = $locations[ $current_theme_location ];

			if ( ! $menu_id ) {
				return $args;
			}

			$style_manager = new Mega_Menu_Style_Manager();
			$themes = $style_manager->get_themes();

			$menu_theme = isset( $themes[ $settings[ $current_theme_location ]['theme'] ] ) ? $themes[ $settings[ $current_theme_location ]['theme'] ] : $themes['default'];

			$menu_settings = $settings[ $current_theme_location ];

			$wrap_attributes = apply_filters("megamenu_wrap_attributes", array(
				"id" => '%1$s',
				"class" => '%2$s mega-no-js',
				"data-event" => isset( $menu_settings['event'] ) ? $menu_settings['event'] : 'hover',
				"data-effect" => isset( $menu_settings['effect'] ) ? $menu_settings['effect'] : 'disabled',
				"data-panel-width" => preg_match('/^\d/', $menu_theme['panel_width']) !== 1 ? $menu_theme['panel_width'] : '',
				"data-second-click" => isset( $settings['second_click'] ) ? $settings['second_click'] : 'close',
				"data-document-click" => 'collapse',
				"data-vertical-behaviour" => isset( $settings['mobile_behaviour'] ) ? $settings['mobile_behaviour'] : 'standard',
				"data-breakpoint" => absint( $menu_theme['responsive_breakpoint'] )
			), $menu_id, $menu_settings, $settings, $current_theme_location );

			$attributes = "";

			foreach( $wrap_attributes as $attribute => $value ) {
				if ( strlen( $value ) ) {
					$attributes .= " " . $attribute . '="' . $value . '"';
				}
			}

			$sanitized_location = str_replace( apply_filters("megamenu_location_replacements", array("-", " ") ), "-", $current_theme_location );

			$defaults = array(
				'menu'            => $menu_id,
				'container'       => 'div',
				'container_class' => 'mega-menu-wrap',
				'container_id'    => 'mega-menu-wrap-' . $sanitized_location,
				'menu_class'      => 'mega-menu mega-menu-horizontal',
				'menu_id'         => 'mega-menu-' . $sanitized_location,
				'fallback_cb'     => 'wp_page_menu',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'items_wrap'      => '<ul' . $attributes . '>%3$s</ul>',
				'depth'           => 0,
				'walker'          => new Mega_Menu_Walker()
			);

			$args = array_merge( $args, apply_filters( "megamenu_nav_menu_args", $defaults, $menu_id, $current_theme_location ) );
		}

		return $args;
	}


	/**
	 * Display admin notices.
	 */
	public function admin_notices() {

		if ( ! $this->is_compatible_wordpress_version() ) :

	    ?>
	    <div class="error">
	        <p><?php _e( 'Max Mega Menu is not compatible with your version of WordPress. Please upgrade WordPress to the latest version or disable Max Mega Menu.', 'megamenu' ); ?></p>
	    </div>
	    <?php

	    endif;


		if ( is_plugin_active('ubermenu/ubermenu.php') ) :

	    ?>
	    <div class="error">
	        <p><?php _e( 'Max Mega Menu is not compatible with Uber Menu. Please disable Uber Menu.', 'megamenu' ); ?></p>
	    </div>
	    <?php

	    endif;


		if ( did_action('megamenu_after_install') === 1 ) :

	    ?>
	    <div class="updated">
	    	<?php

	    		$link = "<a href='" . admin_url("admin.php?page=maxmegamenu") . "'>" . __( "click here", 'megamenu' ) . "</a>";

	    	?>
	        <p><?php echo sprintf( __( 'Thanks for installing Max Mega Menu! Please %s to get started.', 'megamenu' ), $link); ?></p>
	    </div>
	    <?php

	    endif;

	    $css_version = get_transient("megamenu_css_version");
	    $css = get_transient("megamenu_css");

		if ( $css && version_compare( $this->version, $css_version, '!=' ) ) :

	    ?>
	    <div class="updated">
	    	<?php

	    		$link = "<a href='" . admin_url("admin.php?page=maxmegamenu&tab=tools") . "'>" . __( "clear the CSS cache", 'megamenu' ) . "</a>";

	    	?>
	        <p><?php echo sprintf( __( 'Max Mega Menu has been updated. Please %s to ensure maximum compatibility with the latest version.', 'megamenu' ), $link); ?></p>
	    </div>
	    <?php

	    endif;
	}


	/**
	 * Checks this WordPress installation is v3.8 or above.
	 * 3.8 is needed for dashicons.
	 */
	public function is_compatible_wordpress_version() {
		global $wp_version;

		return $wp_version >= 3.8;
	}


}

add_action( 'plugins_loaded', array( 'Mega_Menu', 'init' ), 10 );

endif;


if ( ! function_exists( 'max_mega_menu_is_enabled' ) ) {

	/**
	 * Determines if Max Mega Menu has been enabled for a given menu location.
	 *
	 * Usage:
	 *
	 * Max Mega Menu is enabled:
	 * function_exists( 'max_mega_menu_is_enabled' )
	 *
	 * Max Mega Menu has been enabled for a theme location:
	 * function_exists( 'max_mega_menu_is_enabled' ) && max_mega_menu_is_enabled( $location )
	 *
	 * @since 1.8
	 * @param string $location - theme location identifier
	 */
	function max_mega_menu_is_enabled( $location = false ) {

		if ( ! $location ) {
			return true; // the plugin is enabled
		}

		// if a location has been passed, check to see if MMM has been enabled for the location
		$settings = get_option( 'megamenu_settings' );

		return is_array( $settings ) && isset( $settings[ $location ]['enabled'] ) && $settings[ $location ]['enabled'] == true;
	}
}