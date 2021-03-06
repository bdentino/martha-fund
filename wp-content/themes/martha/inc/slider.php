<?php
/**
 * Slider template
 *
 * @package Martha
 */

//Slider template
if ( ! function_exists( 'martha_slider_template' ) ) :
function martha_slider_template() {

	if ( (get_theme_mod('front_header_type','slider') == 'slider' && is_front_page()) || (get_theme_mod('site_header_type') == 'slider' && !is_front_page()) ) {

    //Get the slider options
    $speed      = get_theme_mod('slider_speed', '0');
    $text_speed = get_theme_mod('textslider_speed', '0');
    $text_slide = get_theme_mod('textslider_slide', 1);
    if (!$text_slide) {
        $slide_toggle = true;
    } else {
        $slide_toggle = false;
    }

    //Slider text
    if ( !function_exists('pll_register_string') ) {
        $slider_title_1     = get_theme_mod('slider_title_1', 'Let\'s Make Pittsburgh Smile');
        $slider_title_2     = get_theme_mod('slider_title_2');
        $slider_title_3     = get_theme_mod('slider_title_3');
        $slider_title_4     = get_theme_mod('slider_title_4');
        $slider_title_5     = get_theme_mod('slider_title_5');
        $slider_subtitle_1  = get_theme_mod('slider_subtitle_1','Help us build a playground today');
        $slider_subtitle_2  = get_theme_mod('slider_subtitle_2');
        $slider_subtitle_3  = get_theme_mod('slider_subtitle_3');
        $slider_subtitle_4  = get_theme_mod('slider_subtitle_4');
        $slider_subtitle_5  = get_theme_mod('slider_subtitle_5');
        $slider_button      = get_theme_mod('slider_button_text', 'Become a Donor');
        $slider_button_url  = get_theme_mod('slider_button_url','#donate');
        $slider_button_caption = get_theme_mod('slider_button_caption');
    } else {
        $slider_title_1     = pll__(get_theme_mod('slider_title_1', 'Let\'s Make Pittsburgh Smile'));
        $slider_title_2     = pll__(get_theme_mod('slider_title_2'));
        $slider_title_3     = pll__(get_theme_mod('slider_title_3'));
        $slider_title_4     = pll__(get_theme_mod('slider_title_4'));
        $slider_title_5     = pll__(get_theme_mod('slider_title_5'));
        $slider_subtitle_1  = pll__(get_theme_mod('slider_subtitle_1','Help us build a playground today'));
        $slider_subtitle_2  = pll__(get_theme_mod('slider_subtitle_2'));
        $slider_subtitle_3  = pll__(get_theme_mod('slider_subtitle_3'));
        $slider_subtitle_4  = pll__(get_theme_mod('slider_subtitle_4'));
        $slider_subtitle_5  = pll__(get_theme_mod('slider_subtitle_5'));
        $slider_button      = pll__(get_theme_mod('slider_button_text', 'Become a Donor'));
        $slider_button_url  = pll__(get_theme_mod('slider_button_url','#donate'));
        $slider_button_caption = pll__(get_theme_mod('slider_button_caption'));
    }

	?>

	<div id="slideshow" class="header-slider" data-speed="<?php echo esc_attr($speed); ?>">
	    <div class="slides-container">
		    <?php
			    if ( get_theme_mod('slider_image_1', get_template_directory_uri() . '/images/runners/kids2-bw.jpeg') ) {
					echo '<div class="slide-item" style="background-image:url(' . esc_url(get_theme_mod('slider_image_1', 'https://cldup.com/lBZOyfRp5w.jpeg')) . ');"></div>';

				}
			    if ( get_theme_mod('slider_image_2') ) {
					echo '<div class="slide-item" style="background-image:url(' . esc_url(get_theme_mod('slider_image_2')) . ');"></div>';
				}
			    if ( get_theme_mod('slider_image_3') ) {
                    echo '<div class="slide-item" style="background-image:url(' . esc_url(get_theme_mod('slider_image_3')) . ');"></div>';
				}
			    if ( get_theme_mod('slider_image_4') ) {
                    echo '<div class="slide-item" style="background-image:url(' . esc_url(get_theme_mod('slider_image_4')) . ');"></div>';
				}
			    if ( get_theme_mod('slider_image_5') ) {
                    echo '<div class="slide-item" style="background-image:url(' . esc_url(get_theme_mod('slider_image_5')) . ');"></div>';
				}
			?>
	  </div>
	  	<div class="slider-overlay"></div>
        <div class="text-slider-section">
            <div class="text-slider" data-speed="<?php echo esc_attr($text_speed); ?>" data-slideshow="<?php echo esc_attr($slide_toggle); ?>">
                <ul class="slide-text slides">
                	<?php if ( get_theme_mod('slider_image_1', get_template_directory_uri() . '/images/runners/kids2-bw.jpeg') ) : ?>
                    <li>
                        <div class="contain">
                            <h2 class="maintitle"><?php echo esc_html($slider_title_1); ?></h2>
                            <p class="subtitle"><?php echo esc_html($slider_subtitle_1); ?></p>
                        </div>
                    </li>
               		<?php endif; ?>
               		<?php if ( get_theme_mod('slider_image_2') ) : ?>
                    <li>
                        <div class="contain">
                            <h2 class="maintitle"><?php echo esc_html($slider_title_2); ?></h2>
                            <p class="subtitle"><?php echo esc_html($slider_subtitle_2); ?></p>
                        </div>
                    </li>
                    <?php endif; ?>
                    <?php if ( get_theme_mod('slider_image_3') ) : ?>
                    <li>
                        <div class="contain">
                            <h2 class="maintitle"><?php echo esc_html($slider_title_3); ?></h2>
                            <p class="subtitle"><?php echo esc_html($slider_subtitle_3); ?></p>
                        </div>
                    </li>
                    <?php endif; ?>
                    <?php if ( get_theme_mod('slider_image_4') ) : ?>
                    <li>
                        <div class="contain">
                            <h2 class="maintitle"><?php echo esc_html($slider_title_4); ?></h2>
                            <p class="subtitle"><?php echo esc_html($slider_subtitle_4); ?></p>
                        </div>
                    </li>
                    <?php endif; ?>
                    <?php if ( get_theme_mod('slider_image_5') ) : ?>
                    <li>
                        <div class="contain">
                            <h2 class="maintitle"><?php echo esc_html($slider_title_5); ?></h2>
                            <p class="subtitle"><?php echo esc_html($slider_subtitle_5); ?></p>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php if ($slider_button) : ?>
                <a href="<?php echo esc_url($slider_button_url); ?>" class="roll-button button-slider"><?php echo esc_html($slider_button); ?></a>
            <?php endif; ?>
            <?php if ($slider_button_caption) : ?>
                <div class="button-slider-caption"><?php echo esc_html($slider_button_caption); ?></div>
            <?php endif; ?>
        </div>



	</div>
	<?php
	}
}
endif;
