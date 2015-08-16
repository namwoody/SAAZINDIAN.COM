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
				

				<div id="contact_page"><!-- start contact page -->


      <div class="container">
      <div class="row">
      <div class="col-lg-12 text-center" id="contact_heading">
      		<h2><img src="<?php bloginfo('template_directory');?>/images/sign.png" alt="">  <?php the_title();?>  <img src="<?php bloginfo('template_directory');?>/images/sign.png" alt=""></h2></div>
        <div class="col-lg-12">
        <?php the_content(); ?>	
        </div>

      </div>
      </div>
				</div>

				
						
				
			

<?php endwhile; ?>