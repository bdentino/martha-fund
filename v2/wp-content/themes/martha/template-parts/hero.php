<?php
/**
 * Hero section template part.
 *
 * @package Martha
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading       = martha_hero_get( 'heading' );
$subheading    = martha_hero_get( 'subheading' );
$button_text   = martha_hero_get( 'button_text' );
$button_url    = martha_hero_get( 'button_url' );
$footnote      = martha_hero_get( 'footnote' );
$background    = martha_hero_get( 'background' );
$bg_image_url  = martha_hero_background_image_url();
$shadow_rgb    = martha_hex_to_rgb_normalized( martha_hero_get( 'duotone_shadow' ) );
$highlight_rgb = martha_hex_to_rgb_normalized( martha_hero_get( 'duotone_highlight' ) );

/*
 * --hero-bg and --hero-button-color are emitted globally on :root from
 * inc/customizer.php (wp_head). We only set the bg-image variable here
 * since it's hero-scoped.
 */
$hero_styles = sprintf(
	'--hero-bg: %1$s; background-color: var(--hero-bg);',
	esc_attr( $background )
);

if ( '' !== $bg_image_url ) {
	$hero_styles .= ' --hero-bg-image: url(' . esc_url( $bg_image_url ) . ');';
}
?>

<?php /* SVG duotone filter referenced via filter: url(#martha-hero-duotone). Hidden but present in the DOM. */ ?>
<svg class="martha-hero-duotone-svg" aria-hidden="true" focusable="false" width="0" height="0" style="position:absolute;width:0;height:0;overflow:hidden;">
	<filter id="martha-hero-duotone" color-interpolation-filters="sRGB">
		<feColorMatrix type="matrix" values="
			.299 .587 .114 0 0
			.299 .587 .114 0 0
			.299 .587 .114 0 0
			0    0    0    1 0
		"/>
		<feComponentTransfer color-interpolation-filters="sRGB">
			<feFuncR type="table" tableValues="<?php echo esc_attr( sprintf( '%F %F', $shadow_rgb[0], $highlight_rgb[0] ) ); ?>"/>
			<feFuncG type="table" tableValues="<?php echo esc_attr( sprintf( '%F %F', $shadow_rgb[1], $highlight_rgb[1] ) ); ?>"/>
			<feFuncB type="table" tableValues="<?php echo esc_attr( sprintf( '%F %F', $shadow_rgb[2], $highlight_rgb[2] ) ); ?>"/>
			<feFuncA type="table" tableValues="1 1"/>
		</feComponentTransfer>
	</filter>
</svg>

<section
	class="hero<?php echo '' !== $bg_image_url ? ' hero--has-image' : ''; ?>"
	data-hero
	style="<?php echo esc_attr( $hero_styles ); ?>"
>
	<?php if ( '' !== $bg_image_url ) : ?>
		<div class="hero__background" aria-hidden="true"></div>
	<?php endif; ?>

	<div class="hero__inner">
		<?php if ( '' !== $heading ) : ?>
			<h1 class="hero__heading"><?php echo esc_html( $heading ); ?></h1>
		<?php endif; ?>

		<span class="hero__separator" aria-hidden="true"></span>

		<?php if ( '' !== $subheading ) : ?>
			<p class="hero__subheading"><?php echo esc_html( $subheading ); ?></p>
		<?php endif; ?>

		<?php if ( '' !== $button_text && '' !== $button_url ) : ?>
			<a
				class="hero__button"
				href="<?php echo esc_url( $button_url ); ?>"
			>
				<?php echo esc_html( $button_text ); ?>
			</a>
		<?php endif; ?>

		<?php if ( '' !== $footnote ) : ?>
			<p class="hero__footnote"><?php echo esc_html( $footnote ); ?></p>
		<?php endif; ?>
	</div>
</section>
