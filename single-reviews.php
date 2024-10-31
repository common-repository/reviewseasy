<?php
/*
Template Name: Single Reviews Template
*/

?>

<?php get_header(); ?>
<div id="body-wrapper" >
	<div id="main-content" class="main-content single container " >		 

		
<?php
global $post;
if (have_posts()) {
	while (have_posts()) {
		the_post();
		?>
		

			<div class="clear"></div>	 
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		    	<div class="articlecontainer">

		            <header class="entry-header">

		                <h1 class="entry-title"><?php the_title(); ?></h1>

		            </header>

		            <div class="entry-content">
		            	
		            	<?php if (has_post_thumbnail( $post->ID ) ): ?>
		            		<div style="text-align: center">
		            		<?php $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'thumbnail') ); ?>
							<img src="<?php echo $url ?>" />
		                	</div>
		                <?php endif; ?>

		                <?php the_content(); ?>

		                <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:' ), 'after' => '</div>' ) ); ?>

		            </div><!-- .entry-content -->

		            <footer class="entry-meta">

		                <?php edit_post_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>

		            </footer><!-- .entry-meta -->

		            <div class="clear"></div>

		        </div>

			</article><!-- #post -->




		<?php
		// End the loop.
	}
}
?>

	</div>	
	<div class="clear"></div>	 
</div>

<?php get_footer(); ?>
