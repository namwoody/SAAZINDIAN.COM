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
        <div class="col-lg-5 col-lg-offset-1">
         <h2 id="contact_page_h2"><img src="<?php bloginfo('template_directory');?>/images/sign.png" alt="" /> Desserts</h2>
KHEER ....................................................$7<br />
RASMALAI ...............................................$7<br />
GULAB JAMUN ..........................................$7<br />
KULFI - an Indian style ice cream ...............$7<br />
FLAN - with apricot coulis ..........................$7<br />
INDIAN SMOOTHIES<br />
MANGO LASSI ........................................ $5<br />
STRAWBERRY LASSI ...............................$5<br />
SWEET LASSI .........................................$5<br />



        </div>
        <div class="col-lg-5">
          <h2 id="contact_page_h2"><img src="<?php bloginfo('template_directory');?>/images/sign.png" alt="sign" /> Drinks</h2><br />
TEA ........................................................ $2<br />
COFFEE ...................................................$2<br />
ESPRESSO ...............................................$3<br />
CAPPUCCINO ...........................................$4<br />
ORGANIC HERBAL TEAS ............................$3<br />
MASALA CHAI ..........................................$3<br />
        </div>

      </div>
      </div>
				</div>

				
						
				
			

<?php endwhile; ?>