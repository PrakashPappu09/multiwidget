<?php
  /*
  * Plugin Name: Multiwidget
  * Plugin URI: http://www.pswebplanet.com
  * Description: Multiwidget for sidebar and footer
  * Version: 1.0
  * Author: pswebplanet
  * Author URI: http://www.pswebplanet.com
  * Text Domain: multiwidget
  */
  // Exit if accessed directly
  if ( ! defined( 'ABSPATH' ) ) exit;
  /*
   * Init Widgets
   */
  class Multiwidget
  {
    function __construct()
    {
      $this->widget_dir = plugin_dir_path( __FILE__ ).'widgets';
      $this->css_dir = 'css/';
      add_action( 'admin_enqueue_scripts', array($this,'multi_widget_admin_script_styles'));
      add_action('wp_enqueue_scripts', array($this,'multi_widget_script_styles'));
      add_action( 'widgets_init' , array($this,'multi_widget_init'), 50 );      
    }//end of constructor
    function multi_widget_init()
    {
    // Activationg widgets Elements
      foreach(glob($this->widget_dir."/*.php") as $element)
      {
        require_once($element);
      }
    }//function end
    function multi_widget_admin_script_styles()
    {
      if(!wp_script_is( 'multi-admin-js', 'enqueued' ))
      {
        wp_enqueue_script( 'multi-admin-js', plugin_dir_url( __FILE__ ) . 'assets/js/multi-admin.js' );
        
      }
      if(!wp_style_is( 'multi-admin-style', 'enqueued' ))
      {
        wp_enqueue_style( 'multi-admin-style', plugin_dir_url( __FILE__ ) . 'assets/css/multi-admin-style.css' );
        
      }
      wp_enqueue_media();
    }//end of function
    function multi_widget_script_styles() 
    {
      wp_enqueue_style('font-awesome', plugin_dir_url( __FILE__ ) . 'assets/css/font-awesome.min.css'); 
      wp_enqueue_style( 'multi-main-style', plugin_dir_url( __FILE__ ) . 'assets/css/multi-main.css' );
      wp_enqueue_script( 'jquery' );
      if(!wp_script_is( 'calendario-js', 'enqueued' ))
      {
        wp_enqueue_script( 'calendario-js', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.calendario.js' );
      }
      if(!wp_script_is( 'slick-main-js', 'enqueued' ))
      {
        wp_enqueue_script( 'slick-main-js', plugin_dir_url( __FILE__ ) . 'assets/js/slick.js' );
      }
      wp_enqueue_script( 'vender-js', plugin_dir_url( __FILE__ ) . 'assets/js/plugins.js',array( 'jquery' ) );
      wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'assets/js/multi-main.js',array( 'jquery' ) );
      
      if(!wp_style_is( 'slick-main-css', 'enqueued' ))
      {
        wp_enqueue_style( 'slick-main-css', plugin_dir_url( __FILE__ ) . 'assets/css/slick.css' );
      }
      if(!wp_style_is( 'slick-main-theme-css', 'enqueued' ))
      {
       wp_enqueue_style( 'slick-main-theme-css', plugin_dir_url( __FILE__ ) . 'assets/css/slick-theme.css' );
      }
       wp_register_script( 'googleplusplatform', plugin_dir_url( __FILE__ ) . 'assets/js/api.platform.js', array ( 'jquery' ), '1.0', true );
    }//end of function



  }//end of class

  new Multiwidget;

  /*
  Excerpt function
  */
  function multi_excerpt($limit) 
  {
    $excerpt = explode(' ', get_the_excerpt(), $limit);
     if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
    } else {
    $excerpt = implode(" ",$excerpt);
    } 
    $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
    return $excerpt;
  }

