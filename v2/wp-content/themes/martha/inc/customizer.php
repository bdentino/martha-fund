<?php
/**
 * Customizer registration for the Martha theme.
 *
 * Adds a "Hero" section with editable copy, button, and background color,
 * plus selective refresh so changes preview live without full reloads.
 *
 * @package Martha
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default values for hero settings.
 *
 * @return array<string,string>
 */
function martha_hero_defaults() {
	return array(
		'heading'           => __( 'Run for a cause that matters.', 'martha' ),
		'subheading'        => __( 'The Martha Fund supports families and runners changing lives, one mile at a time.', 'martha' ),
		'button_text'       => __( 'Donate Now', 'martha' ),
		'button_url'        => '#donate',
		'footnote'          => __( '100% of donations go directly to support the cause.', 'martha' ),
		'background'        => '#0f1419',
		'button_color'      => '#f43d6e',
		'background_image'  => 0,
		'duotone_shadow'    => '#0b4d86',
		'duotone_highlight' => '#f43d6e',
	);
}

/**
 * Get a single hero setting with its default fallback.
 *
 * @param string $key Setting key (without the `martha_hero_` prefix).
 * @return string
 */
function martha_hero_get( $key ) {
	$defaults = martha_hero_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	$value    = get_theme_mod( 'martha_hero_' . $key, $default );

	if ( 'background_image' === $key ) {
		return (int) $value;
	}

	return (string) $value;
}

/**
 * Resolve the URL for the hero background image, if any.
 *
 * @return string Absolute URL or empty string when unset.
 */
function martha_hero_background_image_url() {
	$attachment_id = martha_hero_get( 'background_image' );

	if ( ! $attachment_id ) {
		return '';
	}

	$src = wp_get_attachment_image_src( $attachment_id, 'full' );

	return is_array( $src ) ? (string) $src[0] : '';
}

/**
 * Convert a hex color (#rrggbb / #rgb) into an array of normalized 0..1 RGB
 * components for use inside an SVG <feFuncR/G/B> tableValues attribute.
 *
 * @param string $hex Color string. Falls back to black when invalid.
 * @return array{0:float,1:float,2:float}
 */
function martha_hex_to_rgb_normalized( $hex ) {
	$hex = ltrim( (string) $hex, '#' );

	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}

	if ( 6 !== strlen( $hex ) || ! ctype_xdigit( $hex ) ) {
		return array( 0.0, 0.0, 0.0 );
	}

	return array(
		hexdec( substr( $hex, 0, 2 ) ) / 255,
		hexdec( substr( $hex, 2, 2 ) ) / 255,
		hexdec( substr( $hex, 4, 2 ) ) / 255,
	);
}

/**
 * Sanitize a hex color, falling back to the default when invalid.
 *
 * @param string $value   Submitted color.
 * @param WP_Customize_Setting $setting Setting object.
 * @return string
 */
function martha_sanitize_hex_color( $value, $setting ) {
	$sanitized = sanitize_hex_color( $value );
	return $sanitized ? $sanitized : $setting->default;
}

add_action(
	'customize_register',
	static function ( WP_Customize_Manager $wp_customize ) {
		$defaults = martha_hero_defaults();

		$wp_customize->add_section(
			'martha_hero',
			array(
				'title'       => __( 'Hero Section', 'martha' ),
				'description' => __( 'Edit the homepage hero copy, button, and background.', 'martha' ),
				'priority'    => 30,
			)
		);

		$fields = array(
			'heading' => array(
				'label'             => __( 'Heading', 'martha' ),
				'type'              => 'text',
				'sanitize_callback' => 'sanitize_text_field',
				'selector'          => '.hero__heading',
			),
			'subheading' => array(
				'label'             => __( 'Subheading', 'martha' ),
				'type'              => 'textarea',
				'sanitize_callback' => 'sanitize_textarea_field',
				'selector'          => '.hero__subheading',
			),
			'button_text' => array(
				'label'             => __( 'Button Text', 'martha' ),
				'type'              => 'text',
				'sanitize_callback' => 'sanitize_text_field',
				'selector'          => '.hero__button',
			),
			'button_url' => array(
				'label'             => __( 'Button Link URL', 'martha' ),
				'type'              => 'url',
				'sanitize_callback' => 'esc_url_raw',
				'selector'          => null,
			),
			'footnote' => array(
				'label'             => __( 'Footnote', 'martha' ),
				'type'              => 'text',
				'sanitize_callback' => 'sanitize_text_field',
				'selector'          => '.hero__footnote',
			),
		);

		foreach ( $fields as $key => $field ) {
			$setting_id = 'martha_hero_' . $key;

			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => $defaults[ $key ],
					'sanitize_callback' => $field['sanitize_callback'],
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				$setting_id,
				array(
					'label'   => $field['label'],
					'section' => 'martha_hero',
					'type'    => $field['type'],
				)
			);

			if ( ! empty( $field['selector'] ) && isset( $wp_customize->selective_refresh ) ) {
				$wp_customize->selective_refresh->add_partial(
					$setting_id,
					array(
						'selector'        => $field['selector'],
						'render_callback' => static function () use ( $key ) {
							return esc_html( martha_hero_get( $key ) );
						},
					)
				);
			}
		}

		$color_settings = array(
			'background'        => __( 'Background Color', 'martha' ),
			'button_color'      => __( 'Button & Separator Color', 'martha' ),
			'duotone_shadow'    => __( 'Background Image Duotone — Shadows', 'martha' ),
			'duotone_highlight' => __( 'Background Image Duotone — Highlights', 'martha' ),
		);

		foreach ( $color_settings as $key => $label ) {
			$setting_id = 'martha_hero_' . $key;

			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => $defaults[ $key ],
					'sanitize_callback' => 'martha_sanitize_hex_color',
					'transport'         => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					$setting_id,
					array(
						'label'   => $label,
						'section' => 'martha_hero',
					)
				)
			);
		}

		$wp_customize->add_setting(
			'martha_hero_background_image',
			array(
				'default'           => $defaults['background_image'],
				'sanitize_callback' => 'absint',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				'martha_hero_background_image',
				array(
					'label'     => __( 'Background Image', 'martha' ),
					'description' => __( 'Optional. Layered behind the hero with the duotone filter applied.', 'martha' ),
					'section'   => 'martha_hero',
					'mime_type' => 'image',
				)
			)
		);
	}
);

add_action(
	'customize_preview_init',
	static function () {
		wp_enqueue_script(
			'martha-customizer-preview',
			get_template_directory_uri() . '/assets/js/customizer-preview.js',
			array( 'customize-preview' ),
			MARTHA_VERSION,
			true
		);
	}
);

/**
 * Emit a CSS variable for the hero background and apply it to <html>.
 *
 * Setting the html background to match the hero color hides the overscroll
 * bounce ("rubber band") that would otherwise reveal the browser's default
 * white area above the hero on macOS/iOS.
 */
add_action(
	'wp_head',
	static function () {
		$bg           = martha_hero_get( 'background' );
		$button_color = martha_hero_get( 'button_color' );
		printf(
			'<style id="martha-overscroll-bg">:root{--hero-bg:%1$s;--hero-button-color:%2$s;}html{background-color:var(--hero-bg);}</style>',
			esc_attr( $bg ),
			esc_attr( $button_color )
		);
	},
	5
);
