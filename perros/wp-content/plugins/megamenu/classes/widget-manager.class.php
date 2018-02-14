<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}

if ( ! class_exists('Mega_Menu_Widget_Manager') ) :

/**
 * Processes AJAX requests from the Mega Menu panel editor.
 * Also registers our widget sidebar.
 *
 * There is very little in WordPress core to help with listing, editing, saving,
 * deleting widgets etc so this class implements that functionality.
 */
class Mega_Menu_Widget_Manager {

    /**
     * Constructor
     *
     * @since 1.0
     */
    public function __construct() {

        add_action( 'init', array( $this, 'register_sidebar' ) );

        add_action( 'wp_ajax_mm_edit_widget', array( $this, 'ajax_show_widget_form' ) );
        add_action( 'wp_ajax_mm_save_widget', array( $this, 'ajax_save_widget' ) );
        add_action( 'wp_ajax_mm_update_widget_columns', array( $this, 'ajax_update_columns' ) );
        add_action( 'wp_ajax_mm_delete_widget', array( $this, 'ajax_delete_widget' ) );
        add_action( 'wp_ajax_mm_add_widget', array( $this, 'ajax_add_widget' ) );
        add_action( 'wp_ajax_mm_move_widget', array( $this, 'ajax_move_widget' ) );

        add_filter( 'widget_update_callback', array( $this, 'persist_mega_menu_widget_settings'), 10, 4 );

        add_action( 'megamenu_after_widget_add', array( $this, 'clear_caches' ) );
        add_action( 'megamenu_after_widget_save', array( $this, 'clear_caches' ) );
        add_action( 'megamenu_after_widget_delete', array( $this, 'clear_caches' ) );

    }


    /**
     * Depending on how a widget has been written, it may not necessarily base the new widget settings on
     * a copy the old settings. If this is the case, the mega menu data will be lost. This function
     * checks to make sure widgets persist the mega menu data when they're saved.
     *
     * @since 1.0
     */
    public function persist_mega_menu_widget_settings( $instance, $new_instance, $old_instance, $that ) {

        if ( isset( $old_instance["mega_menu_columns"] ) && ! isset( $new_instance["mega_menu_columns"] ) ) {
            $instance["mega_menu_columns"] = $old_instance["mega_menu_columns"];
        }

        if ( isset( $old_instance["mega_menu_parent_menu_id"] ) && ! isset( $new_instance["mega_menu_parent_menu_id"] ) ) {
            $instance["mega_menu_parent_menu_id"] = $old_instance["mega_menu_parent_menu_id"];
        }

        return $instance;
    }


    /**
     * Create our own widget area to store all mega menu widgets.
     * All widgets from all menus are stored here, they are filtered later
     * to ensure the correct widgets show under the correct menu item.
     *
     * @since 1.0
     */
    public function register_sidebar() {

        register_sidebar(
            array(
                'id' => 'mega-menu',
                'name' => __("Mega Menu Widgets", "megamenu"),
                'description'   => __("Do not manually edit this area.", "megamenu")
            )
        );
    }


    /**
     * Display a widget settings form
     *
     * @since 1.0
     */
    public function ajax_show_widget_form() {

        check_ajax_referer( 'megamenu_edit' );

        $widget_id = sanitize_text_field( $_POST['widget_id'] );

        if ( ob_get_contents() ) ob_clean(); // remove any warnings or output from other plugins which may corrupt the response

        wp_die( trim( $this->show_widget_form( $widget_id ) ) );

    }


    /**
     * Save a widget
     *
     * @since 1.0
     */
    public function ajax_save_widget() {

        $widget_id = sanitize_text_field( $_POST['widget_id'] );
        $id_base = sanitize_text_field( $_POST['id_base'] );

        check_ajax_referer( 'megamenu_save_widget_' . $widget_id );

        $saved = $this->save_widget( $id_base );

        if ( $saved ) {
            $this->send_json_success( sprintf( __("Saved %s", "megamenu"), $id_base ) );
        } else {
            $this->send_json_error( sprintf( __("Failed to save %s", "megamenu"), $id_base ) );
        }

    }


    /**
     * Update the number of mega columns for a widget
     *
     * @since 1.0
     */
    public function ajax_update_columns() {

        check_ajax_referer( 'megamenu_edit' );

        $widget_id = sanitize_text_field( $_POST['widget_id'] );
        $columns = absint( $_POST['columns'] );

        $updated = $this->update_columns( $widget_id, $columns );

        if ( $updated ) {
            $this->send_json_success( sprintf( __( "Updated %s (new columns: %d)", "megamenu"), $widget_id, $columns ) );
        } else {
            $this->send_json_error( sprintf( __( "Failed to update %s", "megamenu"), $widget_id ) );
        }

    }


    /**
     * Add a widget to the panel
     *
     * @since 1.0
     */
    public function ajax_add_widget() {

        check_ajax_referer( 'megamenu_edit' );

        $id_base = sanitize_text_field( $_POST['id_base'] );
        $menu_item_id = absint( $_POST['menu_item_id'] );
        $title = sanitize_text_field( $_POST['title'] );

        $added = $this->add_widget( $id_base, $menu_item_id, $title );

        if ( $added ) {
            $this->send_json_success( $added );
        } else {
            $this->send_json_error( sprintf( __("Failed to add %s to %d", "megamenu"), $id_base, $menu_item_id ) );
        }

    }


    /**
     * Deletes a widget
     *
     * @since 1.0
     */
    public function ajax_delete_widget() {

        check_ajax_referer( 'megamenu_edit' );

        $widget_id = sanitize_text_field( $_POST['widget_id'] );

        $deleted = $this->delete_widget( $widget_id );

        if ( $deleted ) {
            $this->send_json_success( sprintf( __( "Deleted %s", "megamenu"), $widget_id ) );
        } else {
            $this->send_json_error( sprintf( __( "Failed to delete %s", "megamenu"), $widget_id ) );
        }

    }


    /**
     * Moves a widget to a new position
     *
     * @since 1.0
     */
    public function ajax_move_widget() {

        check_ajax_referer( 'megamenu_edit' );

        $widget_to_move = sanitize_text_field( $_POST['widget_id'] );
        $position = absint( $_POST['position'] );
        $menu_item_id = absint( $_POST['menu_item_id'] );

        $moved = $this->move_widget( $widget_to_move, $position, $menu_item_id );

        if ( $moved ) {
            $this->send_json_success( sprintf( __( "Moved %s to %d (%s)", "megamenu"), $widget_to_move, $position, json_encode($moved) ) );
        } else {
            $this->send_json_error( sprintf( __( "Failed to move %s to %d", "megamenu"), $widget_to_move, $position ) );
        }

    }


    /**
     * Returns an object representing all widgets registered in WordPress
     *
     * @since 1.0
     */
    public function get_available_widgets() {
        global $wp_widget_factory;

        $widgets = array();

        foreach( $wp_widget_factory->widgets as $widget ) {

            $disabled_widgets = array('maxmegamenu');

            $disabled_widgets = apply_filters( "megamenu_incompatible_widgets", $disabled_widgets );

            if ( ! in_array( $widget->id_base, $disabled_widgets ) ) {

                $widgets[] = array(
                    'text' => $widget->name,
                    'value' => $widget->id_base
                );

            }

        }

        uasort( $widgets, array( $this, 'sort_by_text' ) );

        return $widgets;

    }


    /**
     * Sorts a 2d array by the 'text' key
     *
     * @since 1.2
     * @param array $a
     * @param array $b
     */
    function sort_by_text( $a, $b ) {
        return strcmp( $a['text'], $b['text'] );
    }


    /**
     * Returns an array of all widgets belonging to a specified menu item ID.
     *
     * @since 1.0
     * @param int $menu_item_id
     */
    public function get_widgets_for_menu_id( $menu_item_id ) {

        $widgets = array();

        if ( $mega_menu_widgets = $this->get_mega_menu_sidebar_widgets() ) {

            foreach ( $mega_menu_widgets as $widget_id ) {

                $settings = $this->get_settings_for_widget_id( $widget_id );

                if ( isset( $settings['mega_menu_parent_menu_id'] ) && $settings['mega_menu_parent_menu_id'] == $menu_item_id ) {

                    $name = $this->get_name_for_widget_id( $widget_id );

                    $widgets[ $widget_id ] = array(
                        'widget_id' => $widget_id,
                        'title' => $name,
                        'mega_columns' => $settings['mega_menu_columns']
                    );

                }

            }

        }

        return $widgets;

    }


    /**
     * Returns the widget data as stored in the options table
     *
     * @since 1.8.1
     * @param string $widget_id
     */
    public function get_settings_for_widget_id( $widget_id ) {

        $id_base = $this->get_id_base_for_widget_id( $widget_id );

        if ( ! $id_base ) {
            return false;
        }

        $widget_number = $this->get_widget_number_for_widget_id( $widget_id );

        $current_widgets = get_option( 'widget_' . $id_base );

        return $current_widgets[ $widget_number ];

    }


    /**
     * Returns the widget ID (number)
     *
     * @since 1.0
     * @param string $widget_id - id_base-ID (eg meta-3)
     * @return int
     */
    public function get_widget_number_for_widget_id( $widget_id ) {

        $parts = explode( "-", $widget_id );

        return absint( end( $parts ) );

    }


    /**
     * Returns the name/title of a Widget
     *
     * @since 1.0
     * @param $widget_id - id_base-ID (eg meta-3)
     */
    public function get_name_for_widget_id( $widget_id ) {
        global $wp_registered_widgets;

        $registered_widget = $wp_registered_widgets[$widget_id];

        return $registered_widget['name'];

    }


    /**
     * Returns the id_base value for a Widget ID
     *
     * @since 1.0
     */
    public function get_id_base_for_widget_id( $widget_id ) {
        global $wp_registered_widget_controls;

        if ( ! isset( $wp_registered_widget_controls[ $widget_id ] ) ) {
            return false;
        }

        $control = $wp_registered_widget_controls[ $widget_id ];

        $id_base = isset( $control['id_base'] ) ? $control['id_base'] : $control['id'];

        return $id_base;

    }


    /**
     * Returns the HTML for a single widget instance.
     *
     * @since 1.0
     * @param string widget_id Something like meta-3
     */
    public function show_widget( $id ) {
        global $wp_registered_widgets;

        $params = array_merge(
            array( array_merge( array( 'widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name'] ) ) ),
            (array) $wp_registered_widgets[$id]['params']
        );

        $params[0]['before_title'] = apply_filters( "megamenu_before_widget_title", '<h4 class="mega-block-title">', $wp_registered_widgets[$id] );
        $params[0]['after_title'] = apply_filters( "megamenu_after_widget_title", '</h4>', $wp_registered_widgets[$id] );
        $params[0]['before_widget'] = apply_filters( "megamenu_before_widget", "", $wp_registered_widgets[$id] );
        $params[0]['after_widget'] = apply_filters( "megamenu_after_widget", "", $wp_registered_widgets[$id] );

        $callback = $wp_registered_widgets[$id]['callback'];

        if ( is_callable( $callback ) ) {
            ob_start();
            call_user_func_array( $callback, $params );
            return ob_get_clean();
        }

    }


    /**
     * Returns the class name for a widget instance.
     *
     * @since 1.8.1
     * @param string widget_id Something like meta-3
     */
    public function get_widget_class( $id ) {
        global $wp_registered_widgets;

        if ( isset ( $wp_registered_widgets[$id]['classname'] ) ) {
            return $wp_registered_widgets[$id]['classname'];
        }

        return "";

    }


    /**
     * Shows the widget edit form for the specified widget.
     *
     * @since 1.0
     * @param $widget_id - id_base-ID (eg meta-3)
     */
    public function show_widget_form( $widget_id ) {
        global $wp_registered_widget_controls;

        $control = $wp_registered_widget_controls[ $widget_id ];

        $id_base = $this->get_id_base_for_widget_id( $widget_id );

        $widget_number = $this->get_widget_number_for_widget_id( $widget_id );

        $nonce = wp_create_nonce('megamenu_save_widget_' . $widget_id);

        ?>

        <div class='widget-content'>
            <form method='post'>
                <input type="hidden" name="widget-id" class="widget-id" value="<?php echo $widget_id ?>" />
                <input type='hidden' name='action'    value='mm_save_widget' />
                <input type='hidden' name='id_base'   value='<?php echo $id_base; ?>' />
                <input type='hidden' name='widget_id' value='<?php echo $widget_id ?>' />
                <input type='hidden' name='_wpnonce'  value='<?php echo $nonce ?>' />

                <?php
                    if ( is_callable( $control['callback'] ) ) {
                        call_user_func_array( $control['callback'], $control['params'] );
                    }
                ?>

                <div class='widget-controls'>
                    <a class='delete' href='#delete'><?php _e("Delete", "megamenu"); ?></a> |
                    <a class='close' href='#close'><?php _e("Close", "megamenu"); ?></a>
                </div>

                <?php
                    submit_button( __( 'Save' ), 'button-primary alignright', 'savewidget', false );
                ?>
            </form>
        </div>

        <?php
    }


    /**
     * Saves a widget. Calls the update callback on the widget.
     * The callback inspects the post values and updates all widget instances which match the base ID.
     *
     * @since 1.0
     * @param string $id_base - e.g. 'meta'
     * @return bool
     */
    public function save_widget( $id_base ) {
        global $wp_registered_widget_updates;

        $control = $wp_registered_widget_updates[$id_base];

        if ( is_callable( $control['callback'] ) ) {

            call_user_func_array( $control['callback'], $control['params'] );

            do_action( "megamenu_after_widget_save" );

            return true;
        }

        return false;

    }


    /**
     * Adds a widget to WordPress. First creates a new widget instance, then
     * adds the widget instance to the mega menu widget sidebar area.
     *
     * @since 1.0
     * @param string $id_base
     * @param int $menu_item_id
     * @param string $title
     */
    public function add_widget( $id_base, $menu_item_id, $title ) {

        require_once( ABSPATH . 'wp-admin/includes/widgets.php' );

        $next_id = next_widget_id_number( $id_base );

        $this->add_widget_instance( $id_base, $next_id, $menu_item_id );

        $widget_id = $this->add_widget_to_sidebar( $id_base, $next_id );

        $return  = '<div class="widget" data-columns="2" id="' . $widget_id . '" data-widget-id="' . $widget_id . '">';
        $return .= '    <div class="widget-top">';
        $return .= '        <div class="widget-title-action">';
        $return .= '            <a class="widget-option widget-contract"></a>';
        $return .= '            <a class="widget-option widget-expand"></a>';
        $return .= '            <a class="widget-option widget-action"></a>';
        $return .= '        </div>';
        $return .= '        <div class="widget-title">';
        $return .= '            <h4>' . $title . '</h4>';
        $return .= '        </div>';
        $return .= '    </div>';
        $return .= '    <div class="widget-inner"></div>';
        $return .= '</div>';

        return $return;

    }


    /**
     * Adds a new widget instance of the specified base ID to the database.
     *
     * @since 1.0
     * @param string $id_base
     * @param int $next_id
     * @param int $menu_item_id
     */
    private function add_widget_instance( $id_base, $next_id, $menu_item_id ) {

        $current_widgets = get_option( 'widget_' . $id_base );

        $current_widgets[ $next_id ] = array(
            "mega_menu_columns" => 2,
            "mega_menu_parent_menu_id" => $menu_item_id
        );

        update_option( 'widget_' . $id_base, $current_widgets );

    }

    /**
     * Removes a widget instance from the database
     *
     * @since 1.0
     * @param string $widget_id e.g. meta-3
     * @return bool. True if widget has been deleted.
     */
    private function remove_widget_instance( $widget_id ) {

        $id_base = $this->get_id_base_for_widget_id( $widget_id );
        $widget_number = $this->get_widget_number_for_widget_id( $widget_id );

        // add blank widget
        $current_widgets = get_option( 'widget_' . $id_base );

        if ( isset( $current_widgets[ $widget_number ] ) ) {

            unset( $current_widgets[ $widget_number ] );

            update_option( 'widget_' . $id_base, $current_widgets );

            return true;

        }

        return false;

    }


    /**
     * Updates the number of mega columns for a specified widget.
     *
     * @since 1.0
     * @param string $widget_id
     * @param int $columns
     */
    public function update_columns($widget_id, $columns) {

        $id_base = $this->get_id_base_for_widget_id( $widget_id );

        $widget_number = $this->get_widget_number_for_widget_id( $widget_id );

        $current_widgets = get_option( 'widget_' . $id_base );

        $current_widgets[ $widget_number ]["mega_menu_columns"] = $columns;

        update_option( 'widget_' . $id_base, $current_widgets );

        do_action( "megamenu_after_widget_save" );

        return true;

    }


    /**
     * Deletes a widget from WordPress
     *
     * @since 1.0
     * @param string $widget_id e.g. meta-3
     */
    public function delete_widget( $widget_id ) {

        $this->remove_widget_from_sidebar( $widget_id );
        $this->remove_widget_instance( $widget_id );

        do_action( "megamenu_after_widget_delete" );

        return true;

    }


    /**
     * Moves a widget from one position to another. The widgets are stored as an ordered
     * array in the database.
     *
     * @since 1.0
     * @param string $widget_to_move
     * @param int $new_widget_position. Zero based index.
     * @return string $widget_id. The widget that has been moved.
     */
    public function move_widget( $widget_to_move, $new_widget_position, $menu_item_id ) {

        // $new_widget_position assumes that all widgets belong to this Menu ID,
        // but widgets are stored in this area for _all_ Menu IDs.
        // Work out the new widget position taking into account that other menu IDs
        // also store their widgets here
        $menu_widgets = array();
        $non_menu_widgets = array();

        if ( $mega_menu_widgets = $this->get_mega_menu_sidebar_widgets() ) {

            foreach ( $mega_menu_widgets as $widget_id ) {

                $settings = $this->get_settings_for_widget_id( $widget_id );

                // split our widgets into arrays that belong with this menu ID, and ones that don't
                if ( $settings['mega_menu_parent_menu_id'] == $menu_item_id ) {

                    $menu_widgets[] = $widget_id;

                } else {

                    $non_menu_widgets[] = $widget_id;

                }

            }

            // find the old position of the widget
            $old_widget_position = array_search( $widget_to_move, $menu_widgets );

            // move widget from old position to new position
            $out = array_splice( $menu_widgets, $old_widget_position, 1 );
            array_splice( $menu_widgets, $new_widget_position, 0, $out );

            // merge back together the menu and non menu widgets
            $mega_menu_widgets = array_merge( $non_menu_widgets, $menu_widgets );

            $this->set_mega_menu_sidebar_widgets( $mega_menu_widgets );

        }

        do_action( "megamenu_after_widget_save" );

        return $mega_menu_widgets;

    }


    /**
     * Adds a widget to the Mega Menu widget sidebar
     *
     * @since 1.0
     */
    private function add_widget_to_sidebar( $id_base, $next_id ) {

        $widget_id = $id_base . '-' . $next_id;

        $sidebar_widgets = $this->get_mega_menu_sidebar_widgets();

        $sidebar_widgets[] = $widget_id;

        $this->set_mega_menu_sidebar_widgets($sidebar_widgets);

        do_action( "megamenu_after_widget_add" );

        return $widget_id;

    }


    /**
     * Removes a widget from the Mega Menu widget sidebar
     *
     * @since 1.0
     * @return string The widget that was removed
     */
    private function remove_widget_from_sidebar($widget_id) {

        $widgets = $this->get_mega_menu_sidebar_widgets();

        $new_mega_menu_widgets = array();

        foreach ( $widgets as $widget ) {

            if ( $widget != $widget_id )
                $new_mega_menu_widgets[] = $widget;

        }

        $this->set_mega_menu_sidebar_widgets($new_mega_menu_widgets);

        return $widget_id;

    }


    /**
     * Returns an unfiltered array of all widgets in our sidebar
     *
     * @since 1.0
     * @return array
     */
    public function get_mega_menu_sidebar_widgets() {

        $sidebar_widgets = wp_get_sidebars_widgets();

        if ( ! isset( $sidebar_widgets[ 'mega-menu'] ) ) {
            return false;
        }

        return $sidebar_widgets[ 'mega-menu' ];

    }


    /**
     * Sets the sidebar widgets
     *
     * @since 1.0
     */
    private function set_mega_menu_sidebar_widgets( $widgets ) {

        $sidebar_widgets = wp_get_sidebars_widgets();

        $sidebar_widgets[ 'mega-menu' ] = $widgets;

        wp_set_sidebars_widgets( $sidebar_widgets );

    }


    /**
     * Clear the cache when the Mega Menu is updated.
     *
     * @since 1.0
     */
    public function clear_caches() {

        // https://wordpress.org/plugins/widget-output-cache/
        if ( function_exists( 'menu_output_cache_bump' ) ) {
            menu_output_cache_bump();
        }

        // https://wordpress.org/plugins/widget-output-cache/
        if ( function_exists( 'widget_output_cache_bump' ) ) {
            widget_output_cache_bump();
        }

    }


    /**
     * Send JSON response.
     *
     * Remove any warnings or output from other plugins which may corrupt the response
     *
     * @param string $json
     * @since 1.8
     */
    public function send_json_success( $json ) {
        if ( ob_get_contents() ) ob_clean();

        wp_send_json_success( $json );
    }


    /**
     * Send JSON response.
     *
     * Remove any warnings or output from other plugins which may corrupt the response
     *
     * @param string $json
     * @since 1.8
     */
    public function send_json_error( $json ) {
        if ( ob_get_contents() ) ob_clean();

        wp_send_json_error( $json );
    }

}

endif;