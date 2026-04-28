<?php
/**
 * Main template file.
 *
 * @package Martha
 */

get_header();
?>

<main id="primary" class="site-main">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			the_title( '<h1>', '</h1>' );
			the_content();
		endwhile;
	else :
		echo '<p>' . esc_html__( 'Nothing here yet.', 'martha' ) . '</p>';
	endif;
	?>
</main>

<?php
get_footer();
