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
            
      		<h2><img src="<?php bloginfo('template_directory');?>/images/sign.png" alt="">  <?php the_title();?>  <img src="<?php bloginfo('template_directory');?>/images/sign.png" alt=""></h2>

      	</div>
      </div>
    
      <div class="row">

       <div class="col-lg-7 col-md-7 col-sm-12">
         <div class="row">
         	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-10 col-xs-offset-1" id="">

         		  
				<div class="divider"></div> 

				<?php the_content();?>

         	</div><!-- end div col -6 -->

         	<div class="col-lg-5 col-md-5 col-sm-5">
         		<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48270.061541650975!2d-72.45340006409097!3d40.874532913318454!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89e8933ba44c012f%3A0xd7f717ec98587bc5!2sSaaz+Indian+Cuisine!5e0!3m2!1sen!2sus!4v1405224491078" width="300" height="400" frameborder="0" style="border:0"></iframe>
         	</div>

         </div>
         	
 
        
       </div><!-- end -- col-lg-9 contact form -->

        
						<div class="col-lg-4 col-lg-offset-1 col-md-4 col-md-offset-1">
    1746 County Road<br />
39 Southampton<br />
NY 11968<br />
 <i class="fa fa-phone"></i> &nbsp;&nbsp;Tel: 631-259-2222<br />
 <i class="fa fa-fax"></i> &nbsp;&nbsp;Fax: 631-259-2225 <br />
 <i class="fa fa-twitter-square fa-2x" id="twitter"></i> 
 <i class="fa fa-facebook-square fa-2x" id="facebook"></i>
 <i class="fa fa-google-plus-square fa-2x" id="google-plus"></i>
<br />
<span class="bottom_footer_h2">Working Hours:</span><br />
Mon - Thru 11:00am - 10:00pm<br />

Fri - Sat 11:30pm - 11:00pm<br />
Sun -  12:00pm - 10:00pm<br />
 <span class="bottom_footer_h2 warning">Lunch Buffet $15</span><br />
<span class="bottom_footer_h2 warning">Weekend Lunch Buffet $16</span>
  


            </div>

				
				
	
		</div><!-- end row -->
</div><!-- div container -->
				
</div><!-- end contact page -->
<?php endwhile; ?>