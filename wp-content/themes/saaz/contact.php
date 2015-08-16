<?php
/**
 * Template Name: Contact template
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.0
 */

get_header(); ?>

	<?php get_template_part( 'loop', 'contact' ); ?>

		
     	

		




<div id="restaurants_footer">

  <div class="container">
  	<div class="row" id="footer">
  		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
  			
  		</div>

  		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="our-partner">
  			<ul>
  				<li><a href="http://www.curryclubli.com/" target="_new">
  				<img src="<?php bloginfo('template_directory' );?>/images/curryclub_logo.png" alt="">
  				</a></li>
  				<li><a href=""><img src="<?php bloginfo('template_directory' );?>/images/medow.png" alt=""></a></li>
  			</ul>
  		</div>



  	</div>
  </div>
</div><!-- end footer -->

<div id="last_bottom_footer">
<div class="container">
	<div class="row">
		<div class="col-lg-6">
			
		</div>
	</div>
</div>

</div>


<?php wp_footer(); ?>


</body>
</html>