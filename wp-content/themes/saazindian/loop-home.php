<?php 
/**
 * The loop that displays a page.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.2
 */
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
   
    <div id="slideshow">  				
     
   
<?php 
 if ( function_exists( 'soliloquy' ) ) { soliloquy( '72' ); }
?>
  

</div>

						
					


<?php endwhile; ?>