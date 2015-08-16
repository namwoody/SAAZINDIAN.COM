<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="google-site-verification" content="Q2-87ZVy0NZrXTw1RBJ2P_S91XH4OH8C9obHb8mvXfc" />
<title><?php
 
    global $page, $paged;
 
    wp_title( '|', true, 'right' );
 
    bloginfo( 'name' );
 
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
        echo " | $site_description";
 
    if ( $paged >= 2 || $page >= 2 )
        echo ' | ' . sprintf( __( 'Page %s', 'starkers' ), max( $paged, $page ) );
 
    ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
 <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'template_directory' ); ?>/css/style.css" />
<script src="<?php bloginfo('template_directory'); ?>/js/modernizr-1.6.min.js"></script>
 <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- start vegas plugin -->


  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


<meta name=viewport content="width=device-width, initial-scale=1">

<script src="http://code.jquery.com/jquery-1.11.1.js"></script>







<!-- end start vegas plugin -->



<link href='http://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet' type='text/css'>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">



<?php
    /* We add some JavaScript to pages with the comment form
     * to support sites with threaded comments (when in use).
     */
    if ( is_singular() && get_option( 'thread_comments' ) )
        wp_enqueue_script( 'comment-reply' );
 
    /* Always have wp_head() just before the closing </head>
     * tag of your theme, or you will break many plugins, which
     * generally use this hook to add elements to <head> such
     * as styles, scripts, and meta tags.
     */
    wp_head();
?>
</head>
 
<body>
 
  <div class="top_header">
      <div class="container">
          <div class="row">
              <div class="col-lg-6 col-lg-push-7 col-md-push-7 col-sm-push-7 " id="top_text">
                  <p><span>Saaz Indian Cuisine</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="" data-toggle="modal" data-target=".our-restaurant">Restaurants</a> | <a href="#">About us</a> | <a href="#" data-toggle="modal" data-target=".mailpoet-newletter">E-Mail Sign-up</a></p>
                
              <div class="modal fade our-restaurant" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
      <h2 class="restaurant_h2">Restaurants</h2>
      <button id="close" type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>

    </div><!-- modal-header -->
    <div class="modal-body">
      <div class="row">
        <div class="col-lg-4 col-lg-offset-1" id="restaurant_img">
           <img src="http://curryclubli.com/wp-content/themes/curryclub/images/logo.png" alt="curry club logo">
        </div>
        <div class="col-lg-7" id="restaurant_content">
          <p>
            “The curry club” inherits this rich tradition of preparing these exquiste delicacies of curries and Kebabs from its experience in experience in operating other such locations in the East as well as in the west.

The main secret, for the succulent taste of the kebabs. comes from special marination overnight and roasting in a unique clay oven called the “tandoor”.
In this the Kebabs are broiled tenderly. The same oven is used to bake naans
(leavened bread) and roti (hand ground whole wheat bread) which compliment the variety of main dishes.

          <br /> <a href="http://curryclubli.com/"><button type="button" class="btn btn-danger">Visit website</button></a>


          </p>
         </div><!-- second col -->
      </div>
    </div>
      
    </div>
  </div>
</div>
                <!-- start mail poet -->





              <div class="modal fade mailpoet-newletter" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content text-center" id="model-content"><br />
     <div class="model-header">
     <img src="<?php bloginfo('template_directory');?>/images/logo.png" alt="saaz_logo"> 
      <button id="close" type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
    </div>

    <h2 id="contact_page_h2"><img src="<?php bloginfo('template_directory');?>/images/sign.png" alt="">  Subscriber</h2>
      <?php 
  $widgetNL = new WYSIJA_NL_Widget(true);
echo $widgetNL->widget(array('form' => 1, 'form_type' => 'php'));
   

      ?>

    </div>
  </div>
</div>



                <!-- end mailpoet -->
                 

              </div>
          </div>
      </div>
  </div>
 <!-- start header -->

 <div id="header">

  <div class="container">
  <div class="row">
    <div class="col-lg-3 col-lg-push-9 col-md-3 col-md-push-9 col-sm-3 col-sm-push-9 col-xs-3 col-xs-push-7 " style="position:absolute; margin-top:14px;"><span id="reservation"><a href="https://www.grubhub.com/nyc/saaz-indian-restaurant-country-road-39/" target="_new">ORDER ONLINE</a></span></div>
  </div>


        <div class="row">
          <!-- start navigation -->
<nav class="navbar navbar-default" role="navigation" id="navbar">
  <!-- Brand and toggle get grouped for better mobile display -->
 

  <!-- Collect the nav links, forms, and other content for toggling -->
  
  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-2" id="logo">
   <p class="navbar-text navbar-left">  
    <a href="/"><img src="<?php bloginfo('template_directory');?>/images/logo.png" alt="saaz_logo">
     </a>
   </p>
   </div><!-- end logo div -->
   <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    
  </div>




  <div class="collapse navbar-collapse navbar-ex1-collapse" id="collapse">
   <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" style="margin-left:30px;">
    <?php /* Primary navigation */
wp_nav_menu( array(
  'menu' => 'top_menu',
  'depth' => 2,
  'container' => false,
  'id' => 'nav-1',
  'menu_class' => 'nav navbar-nav navbar-left',
  //Process nav menu using our custom nav walker
  'walker' => new wp_bootstrap_navwalker())
);
?>
    </div><!-- end col-lg-4 -->

</div>
   

  
</nav>









        
        </div><!-- end row -->

  </div><!-- end container -->





</div>
 <!-- end header -->
 
  
