<?php
/*
Plugin Name: Calendar Widget For Custom Post Types
Plugin URI: http://wordpress.org/extend/plugins/post-types-calendar/
Description: A new widget that shows a calendar based on existing post types.
Author: Stas Sușcov
Version: 0.3
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
        
        $vars = array_merge( $args,
            array(
                'title' => '',
                'type' => '',
                'count' => '',
                'prefix' => '',
                'tax' => '',
                'term' => '',
            )
        );
        
        if( isset( $instance['title'] ) )
            $vars['title'] = apply_filters( 'widget_title', $instance['title'] );
        
        if( isset( $instance['type'] ) )
            $vars['type'] = $instance['type'];
        
        if( isset( $instance['count'] ) )
            $vars['count'] = $instance['count'];
        
        if( isset( $instance['prefix'] ) )
            $vars['prefix'] = $instance['prefix'];
        
        if( isset( $instance['tax'] ) )
            $vars['tax'] = $instance['tax'];
        
        if( isset( $instance['term'] ) )
            $vars['term'] = $instance['term'];
            
        self::template_render( 'widget', $vars );
    }
    
    /**
     * Widget on update handler
     */
    function update( $new_instance, $old_instance ) {
        $term = false;
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        
        if ( in_array( $new_instance['type'], get_post_types( array( 'public' => true ) ) ) )
            $instance['type'] = $new_instance['type'];
        
        $instance['count'] = absint( $new_instance['count'] );
        $instance['prefix'] = esc_attr( $new_instance['prefix'] );
        
        if ( $new_instance['tax'] == '' || in_array( $new_instance['tax'], get_object_taxonomies( $instance['type'] ) ) )
            $instance['tax'] = $new_instance['tax'];
        
        if( !empty( $new_instance['term'] ) && !empty( $instance['tax'] ) )
            $term = get_terms( $instance['tax'], array( 'slug' => $new_instance['term'], 'hide_empty' => false ) );
        
        if( $new_instance['term'] == '' || !empty( $term ) )
            $instance['term'] = $new_instance['term'];
        
        return $instance;
    }
    
    /**
     * Widget form
     */
    function form( $instance ) {
        $vars = array();
        $vars['max_items'] = 10;
        
        $vars['title'] = '';
        $vars['title_id'] = $this->get_field_id( 'title' );
        $vars['title_name'] = $this->get_field_name( 'title' );
        
        $vars['type'] = '';
        $vars['type_id'] = $this->get_field_id( 'type' );
        $vars['type_name'] = $this->get_field_name( 'type' );
        
        $vars['count'] = '';
        $vars['count_id'] = $this->get_field_id( 'count' );
        $vars['count_name'] = $this->get_field_name( 'count' );
        
        $vars['prefix'] = '';
        $vars['prefix_id'] = $this->get_field_id( 'prefix' );
        $vars['prefix_name'] = $this->get_field_name( 'prefix' );
        
        $vars['tax'] = '';
        $vars['taxs'] = array();
        $vars['tax_id'] = $this->get_field_id( 'tax' );
        $vars['tax_name'] = $this->get_field_name( 'tax' );
        
        $vars['term'] = '';
        $vars['terms'] = array();
        $vars['term_id'] = $this->get_field_id( 'term' );
        $vars['term_name'] = $this->get_field_name( 'term' );
        
        $vars['types'] = get_post_types( array( 'public' => true ), 'objects' );
        
        if( isset( $instance['title'] ) )
            $vars['title'] = sanitize_text_field( $instance['title'] );
        
        if( isset( $instance['type'] ) )
            $vars['type'] = esc_attr( $instance['type'] );
        
        if( isset( $instance['count'] ) )
            $vars['count'] = esc_attr( $instance['count'] );
        
        if( isset( $instance['prefix'] ) )
            $vars['prefix'] = esc_attr( $instance['prefix'] );
        
        if( isset( $instance['tax'] ) )
            $vars['tax'] = esc_attr( $instance['tax'] );
        
        if( isset( $instance['term'] ) )
            $vars['term'] = esc_attr( $instance['term'] );
        
        if( !empty( $vars['type'] ) )
            $vars['taxs'] = get_object_taxonomies( $vars['type'], 'objects' );
        
        if( !empty( $vars['tax'] ) )
            $vars['terms'] = get_terms( $vars['tax'], array( 'hide_empty' => false ) );
        
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
