<?php
namespace EATERY;

use EATERY\EAT_View as view;
use EATERY\EAT_Database as database;
use EATERY\EAT_Model_Setup as modelSetup;
use EATERY\EAT_Login as login;
use EATERY\EAT_Listing as listing;




class EAT_Setup
{
  private $postTypes = null;
  private $capability = null;
  private $parentMenuSlug = null;
  private $database = null;
  private $modelSetup = null;
  private $login = null;
  private $listing = null;


  public function __construct()
  { 

    date_default_timezone_set("Asia/Kuala_Lumpur");
    $this->postTypes = array(
    );

    $this->capability = 'manage_options';
    $this->parentMenuSlug = 'doropu-system';
    
    $this->modelSetup = new modelSetup();
    $this->login = new login();
    $this->listing = new listing();

    add_action('admin_menu', array($this, 'createMenu'));
    add_action('init', array($this, 'createPostTypes'));
    add_action('admin_enqueue_scripts', array($this, 'backend'));

    add_action('init', array($this, 'startSession'));
    add_action( 'rest_api_init', array($this, 'registerApiRoute') );

    add_action('wp_enqueue_scripts', array($this, 'frontEnd'));
    add_shortcode('listing', array($this->listing, 'displayListing'));
    add_shortcode('add_listing', array($this->listing, 'addListing'));


    add_action('wp_ajax_deleteListing', array($this->listing, 'deleteListing'));
    add_action('wp_ajax_nopriv_deleteListing', array($this->listing, 'deleteListing'));

    add_action('admin_post_addListing', array($this->listing, 'handleSubmittedListing'));
    add_action('admin_post_nopriv_addListing', array($this->listing, 'handleSubmittedListing'));
    add_action('wp_login', array($this, 'loggedIn'));
    add_action('wp_logout', array($this, 'loggedOut'));
    add_filter('login_redirect', array($this, 'defaultPage'));
  }

  public function startSession()
    { 
      if(!session_id()) {
         session_start();
        }
    }

  public function defaultPage()
  {
     return '/listing';
  }

 public function loggedIn()
 {
  $_SESSION['loggedIn'] = true;
 }  


 public function loggedOut()
 {
  $_SESSION['loggedIn'] = false;
 }  

  public function backend()
  {
        wp_enqueue_script(
          'doropu-jquery',
          'https://code.jquery.com/jquery-1.12.4.min.js', 
          array(), 
          null, 
          true );
        
         wp_enqueue_script(
          'jqueryui',
          'https://code.jquery.com/ui/1.12.1/jquery-ui.js', 
          array('jquery'), 
          null, 
          true );
          
        wp_enqueue_script(
         'print-js',
          plugins_url().'/doropu/assets/js/jQuery.print.js', 
          array('jquery'), 
          null, 
          true );
          
         
        wp_enqueue_script(
          'backend-js',
          plugins_url().'/doropu/assets/js/backend.js?v=10027', 
          array('jquery'), 
          null, 
          true );
          
       
          
        wp_enqueue_style(
          'backend-css', 
          plugins_url().'/doropu/assets/css/backend.css?v=2.15', 
          array(), 
          null, 
          'all');
          
        //   wp_enqueue_style(
        //   'jqueryui-css', 
        //   'code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', 
        //   array(), 
        //   null, 
        //   'all');
    
        $backend = array( 
          'main_site' => site_url(), 

          'ajax_url' => admin_url( 'admin-ajax.php' ),
      );
      wp_localize_script( 'backend-js', 'backend', $backend );

  }
  

  public function createMenu()
  {
    add_menu_page(

      'TEST ADMIN',

      'TEST ADMIN',

      $this->capability,

      $this->parentMenuSlug,

      array(

        $this, 'renderMainPage'),

      null,

      5);
       
       
        add_submenu_page($this->parentMenuSlug,'LISTING','LISTING',$this->capability,'listing',array($this, 'listing') );
       
   
  }


 public function listing()
 {
  echo 'listing';
 }

   public function renderMainPage()
  {
      echo "MAIN PAGE";

  }
  
  
  
  public function createPostTypes()
  {
    foreach ($this->postTypes as $key => $menu) {

      $capmenu = ucfirst($menu);
      $capmenu = str_replace('_', ' ', $capmenu);
      register_post_type(

        $menu,
        array(

          'labels' => array(

            'name' => __( $capmenu, $menu ),

            'singular_name' => __( $capmenu, $menu ),

            'add_new_item' => __( "Add New $capmenu", $menu ),

            'edit_item' => __( "Edit $capmenu", $menu ),

            'view_item' => __( "All $capmenu", $menu )

          ),

          'hierarchical' => false,

                'description' => "$capmenu full width grid",

                'supports' => array( 'editor', 'title' ),

                'public' => true,

                'show_ui' => true,

                'show_in_menu' => $this->parentMenuSlug,

                'menu_position' => 5,

                'menu_icon' => 'dashicons-grid-view',

                'show_in_nav_menus' => false,

                'publicly_queryable' => true,

                'exclude_from_search' => true,

                'has_archive' => false,

                'query_var' => true,

                'can_export' => true,

          'rewrite' => array(

          'slug' => $capmenu,

          'with_front' => true,

          'feeds' => true,

          'pages' => true

        ),

            'capability_type' => 'post'

        )

      );

    }
  }

 public function registerApiRoute()
  {
     register_rest_route( 'eatery/v1/', 'mobile', array(
        'methods' => 'POST',
        'callback' => array(new EAT_Api(), $_GET['request'])
    ));

     register_rest_route( 'eatery/v1/', 'mobile', array(
        'methods' => 'GET',
        'callback' => array(new EAT_Api(), $_GET['request'])
    ));

    
 }
 
 /*create default tables*/
 public function createDefaultTables()
 {
  $this->modelSetup->createTableUser();
  $this->modelSetup->createTableListing();

  //create default user
  $this->login->createLogin('admin@gmail.com', '12345678', 1);
  $this->login->createLogin('user1@gmail.com', '12345678', 2);

  // $this->listing->createDefaultListing();
 }

  public function frontEnd() {

    $now = strtotime('now');
     wp_enqueue_script(
          'eatery-jquery',
          'https://code.jquery.com/jquery-1.12.4.min.js', 
          array(), 
          null, 
          true );

      wp_enqueue_script(
          'frontend-js',
          plugins_url()."/eatery/assets/js/frontend.js?v=$now",
          array('eatery-jquery'), 
          null, 
          true );
        
        wp_enqueue_style(
          'frontend-css', 
          plugins_url().'/eatery/assets/css/frontend.css?v=$now',
          array(), 
          null, 
          'all');    
       $frontend = array( 
          'main_site' => site_url(), 
          'plugin_path'=>plugin_dir_url(__FILE__),
          'user_id'=> get_current_user_id(),
          'ajax_url' => admin_url( 'admin-ajax.php' ),
      );

      wp_localize_script( 'frontend-js', 'frontend', $frontend );
     
  }
  
}