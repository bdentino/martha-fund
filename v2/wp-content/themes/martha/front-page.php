<?php
/**
 * The front page template.
 *
 * Renders the hero followed by a section whose content is editable in the
 * WordPress block editor. To populate that section:
 *
 *   1. Create or edit a Page in wp-admin.
 *   2. Settings → Reading → "Your homepage displays" → "A static page"
 *      → choose that page as the Homepage.
 *   3. Edit the page in the block editor; everything you add appears
 *      below the hero.
 *
 * @package Martha
 */

get_header();

get_template_part( 'template-parts/hero' );

$has_static_front = ( 'page' === get_option( 'show_on_front' ) ) && have_posts();
?>

<main id="primary" class="site-main site-main--front">
	<section class="page-section page-section--blocks">
		<?php
		if ( $has_static_front ) {
			while ( have_posts() ) {
				the_post();
				?>
				<article <?php post_class( 'entry entry--page' ); ?>>
					<div class="entry__content">
						<?php the_content(); ?>
					</div>
				</article>
				<?php
			}
		} elseif ( current_user_can( 'manage_options' ) ) {
			?>
			<div class="page-section__notice">
				<p>
					<strong><?php esc_html_e( 'No homepage page is assigned yet.', 'martha' ); ?></strong>
				</p>
				<p>
					<?php
					printf(
						/* translators: %s: URL to Settings → Reading. */
						wp_kses(
							__( 'Create a page, then go to <a href="%s">Settings → Reading</a> and set it as your static homepage. Whatever you add to that page in the block editor will appear here, beneath the hero.', 'martha' ),
							array( 'a' => array( 'href' => array() ) )
						),
						esc_url( admin_url( 'options-reading.php' ) )
					);
					?>
				</p>
			</div>
			<?php
		}
		?>
	</section>
</main>

<?php
get_footer();
