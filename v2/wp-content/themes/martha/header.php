<?php
/**
 * Header template.
 *
 * @package Martha
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header" data-site-header>
	<div class="site-header__inner">
		<a class="site-header__title" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php bloginfo( 'name' ); ?>
		</a>

		<nav class="site-header__nav" aria-label="<?php esc_attr_e( 'Primary', 'martha' ); ?>">
			<button
				class="site-header__toggle"
				type="button"
				aria-controls="primary-menu"
				aria-expanded="false"
				data-nav-toggle
			>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'martha' ); ?></span>
				<span class="site-header__toggle-bar" aria-hidden="true"></span>
				<span class="site-header__toggle-bar" aria-hidden="true"></span>
				<span class="site-header__toggle-bar" aria-hidden="true"></span>
			</button>

			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'container'      => false,
					'menu_class'     => 'site-header__menu',
					'fallback_cb'    => 'martha_default_primary_menu',
					'depth'          => 1,
				)
			);
			?>
		</nav>
	</div>
</header>
