<?php
/*
Plugin Name: Calendar Widget For Custom Post Types
Plugin URI: http://wordpress.org/extend/plugins/post-types-calendar/
Description: A new widget that shows a calendar based on existing post types.
Author: Stas Sușcov
Version: 0.1
Author URI: http://stas.nerd.ro/
*/

define( 'CPTC_ROOT', dirname( __FILE__ ) );
define( 'CPTC_WEB_ROOT', WP_PLUGIN_URL . '/' . basename( CPTC_ROOT ) );

require_once CPTC_ROOT . '/get_calendar.php';

class CPTC_Widget extends WP_Widget {
    /**
     * Widget Namespace
     */
    static $namespace = 'cptc-widget';
    
    /**
     * Static constructor
     */
    function init() {
        add_action( 'init', array( __CLASS__, 'textdomain' ) );
        add_action( 'widgets_init', array( __CLASS__, 'register_widget' ) );
    }
    
    /**
     * Widget loader
     */
    function register_widget() {
        register_widget( __CLASS__ );
    }
    
    /**
     * Widget constructor
     */
    function CPTC_Widget() {
        $widget_name = __( 'Post Types Calendar Widget', 'cptc' );
        $widget_vars = array(
            'classname' => self::$namespace,
            'description' => __( 'Shows a calendar widget for selected post type', 'cptc' )
        );
        parent::WP_Widget( self::$namespace, $widget_name, $widget_vars );
    }
    
    /**
    * i18n
    */
    function textdomain() {
        load_plugin_textdomain( 'cptc', false, basename( CPTC_ROOT ) . '/languages' );
    }
    
    /**
     * Widget content
     */
    function widget( $args, $instance ) {
        $vars = array();
        
        $vars['title'] = '';
        $vars['type'] = '';
        
        if( isset( $instance['title'] ) )
            $vars['title'] = apply_filters( 'widget_title', $instance['title'] );
        
        if( isset( $instance['type'] ) )
            $vars['type'] = $instance['type'];
        
        self::template_render( 'widget', $vars );
    }
    
    /**
     * Widget on update handler
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        
        if ( in_array( $new_instance['type'], get_post_types( array( 'public' => true ) ) ) )
            $instance['type'] = $new_instance['type'];
        
        return $instance;
    }
    
    /**
     * Widget form
     */
    function form( $instance ) {
        $vars = array();
        
        $vars['title'] = '';
        $vars['title_id'] = $this->get_field_id( 'title' );
        $vars['title_name'] = $this->get_field_name( 'title' );
        
        $vars['type'] = '';
        $vars['type_id'] = $this->get_field_id( 'type' );
        $vars['type_name'] = $this->get_field_name( 'type' );
        
        $vars['types'] = get_post_types( array( 'public' => true ), 'objects' );
        
        if( isset( $instance['title'] ) )
            $vars['title'] = sanitize_text_field( $instance['title'] );
        
        if( isset( $instance['type'] ) )
            $vars['type'] = esc_attr( $instance['type'] );
        
        self::template_render( 'form', $vars );
    }
    
    /**
     * template_render( $name, $vars = null, $echo = true )
     *
     * Helper to load and render templates easily
     * @param String $name, the name of the template
     * @param Mixed $vars, some variables you want to pass to the template
     * @param Boolean $echo, to echo the results or return as data
     * @return String $data, the resulted data if $echo is `false`
     */
    function template_render( $_name, $vars = null, $echo = true ) {
        ob_start();
        if( !empty( $vars ) )
            extract( $vars );
        
        if( !isset( $path ) )
            $path = dirname( __FILE__ ) . '/templates/';
        
        include $path . $_name . '.php';
        
        $data = ob_get_clean();
        
        if( $echo )
            echo $data;
        else
            return $data;
    }
}

CPTC_Widget::init();

?>
