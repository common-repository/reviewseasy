<?php
/**
 * @package Reviews_Easy
 * @version 1.0.8
 */
/*
Plugin Name: Reviews Easy
Plugin URI: http://wordpress.org/plugins/
Description: Some reviews on our blog
Author: Selikoff
Version: 1.0.8
Author URI: http://selikoff.ru/
License: GPLv2
*/
if ( !defined('ABSPATH') ) exit('Please do not load this file directly.');
if ( !class_exists('EasyReviews') ) {
    
    register_activation_hook( __FILE__, array( 'EasyReviews', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'EasyReviews', 'deactivate' ) );
    function load_erv() { register_widget('EasyReviews'); }
    add_action( 'widgets_init', 'load_erv' );
        
 class EasyReviews extends WP_Widget {
    
    private $version = '1.0.7';
    private $title = "Reviews";
    private $plugin_slug = 'reviewseasy';
    private $post_slug = 'reviews';
    private $current_post_id = 0;

    public function __construct() {
        parent::__construct(
            'easyreviews', // Base ID
            __( $this->title, $this->plugin_slug ), // Name
            array( 'description' => __( 'Show reviews', $this->plugin_slug ), ) // Args
        );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );
        add_action( 'init', array( $this, 'widget_textdomain' ) );
        add_action( 'init', array ($this, 'create_easy_review' ) );
        add_filter( 'template_include', array($this , 'include_template_function'), 1 );
        add_action( 'template_redirect',  array( $this, 'is_single' ) );
        add_action( 'admin_enqueue_scripts', array($this, 'upload_scripts'));
    }

    public function upload_scripts()
    {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('upload_media_widget',  plugin_dir_url(__FILE__) . 'js/upload-media.js', array('jquery'));
        wp_enqueue_style('thickbox');
    }
    
    public function widget($args, $instance) {
        extract( $args );
        extract( $instance );
        require_once plugin_dir_path( __FILE__ ) . 'reviews_widget_template.php';
    }
   
   
    public static function activate( $network_wide ) {
        flush_rewrite_rules();
    }
    public static function deactivate( $network_wide ) {
    }
    

    public function create_easy_review() {
        register_post_type( $this->post_slug,
            array(
                'labels' => array(
                    'name' => __($this->title,$this->plugin_slug),
                    'singular_name' => __('Review',$this->plugin_slug),
                    'add_new' => 'Add New',
                    'add_new_item' => 'Add New Review',
                    'edit' => 'Edit',
                    'edit_item' => 'Edit Review',
                    'new_item' => 'New Review',
                    'view' => 'View',
                    'view_item' => 'View Review',
                    'search_items' => 'Search Reviews',
                    'not_found' => 'No Reviews found',
                    'not_found_in_trash' => 'No Reviews found in Trash',
                    'parent' => __('Parent Review',$this->plugin_slug)
                ),
                'menu_icon' => 'dashicons-format-quote',
                'menu_position' => 15,
                'supports' => array( 'title', 'editor', 'thumbnail' ),
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'rewrite'            => array(
                    'slug'      => $this->post_slug,
                    'with_front'=> true,
                    'feeds'     => false,
                    'pages'     => true,
                ),
            )
        );
        //flush_rewrite_rules();
    }


    public function include_template_function( $template_path ) {
        if ( get_post_type() == $this->post_slug ) {
            if ( is_single() ) {
                // checks if the file exists in the theme first,
                // otherwise serve the file from the plugin
                if ( $theme_file = locate_template( array ( 'single-reviews.php' ) ) ) {
                    $template_path = $theme_file;
                } else {
                    $template_path = plugin_dir_path( __FILE__ ) . '/single-reviews.php';
                }
            }
        }
        return $template_path;
    }
   
    
    public function update($new_instance, $old_instance) {

      return $new_instance;
    }
    
    
    public function form($instance) {
      echo "<div>Reviews block properties</div>";  
      $defaults['title'] = $this->title;
      $defaults['scheme'] = 'dark';
      $defaults['image'] = 'https://s-media-cache-ak0.pinimg.com/originals/23/e8/3c/23e83c2159574cf23c5c617e0566aac6.jpg';
      $instance = wp_parse_args((array) $instance, $defaults);
      ?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title',$this->plugin_slug); ?></label>
      <input type="text" name="<?php echo $this->get_field_name('title'); ?>" placeholder="Title" value="<?php echo esc_attr($instance['title']); ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
      </p>
      <p>
      <label for="<?php echo $this->get_field_id('scheme'); ?>"><?php _e('Scheme',$this->plugin_slug); ?></label>
      <select name="<?php echo $this->get_field_name('scheme'); ?>" class="widefat" id="<?php echo $this->get_field_id('scheme'); ?>">
      <option<?php if ($instance['scheme'] == 'dark') echo " selected"; ?>>dark</option>
      <option<?php if ($instance['scheme'] == 'light') echo " selected"; ?>>light</option>
      <option<?php if ($instance['scheme'] == 'default') echo " selected"; ?>>default</option>
      </select>
      </p>
      <p>
      <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image',$this->plugin_slug); ?></label>
      <input type="text" name="<?php echo $this->get_field_name('image'); ?>"  placeholder="/path/" value="<?php echo esc_attr($instance['image']); ?>" class="widefat" id="<?php echo $this->get_field_id('image'); ?>" /><input class="upload_image_button button button-primary" type="button" value="Upload Image" /></p>
      <?php
    }

    
    public function widget_textdomain() {

            $domain = $this->plugin_slug;
            $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
            load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
            load_plugin_textdomain( $domain, false,  plugin_basename( dirname( __FILE__ ) ) . '/lang/');
            //load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

    } // end widget_textdomain
    
    public function register_widget_styles() {
        
        wp_enqueue_style(  'reviews-carousel-style', plugins_url( 'style/jcarousel.re_style.css', __FILE__ ), array(), $this->version, 'all');
        wp_enqueue_style(  'reviews-style', plugins_url( 'style/style.css', __FILE__ ), array(), $this->version );
        
    } // end register_widget_styles
        
    public function register_widget_scripts() {
    
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jcarousel', plugins_url( 'js/jquery.jcarousel.min.js', __FILE__ ), array ( 'jquery' ), $this->version, true);
        wp_enqueue_script( 'reviews-responsive-script', plugins_url( 'js/jcarousel.re_script.js', __FILE__ ), array ( 'jcarousel' ), $this->version, true);
    
    } // end register_widget_scripts
    
    public function is_single() {
            if ( (is_single() || is_page()) && !is_front_page() && !is_preview() && !is_trackback() && !is_feed() && !is_robots() ) {
                global $post;               
                $this->current_post_id = ( is_object($post) ) ? $post->ID : 0;
            } else {
                $this->current_post_id = 0;
            }
    } // end is_single
   
 }//end class
}//end if
