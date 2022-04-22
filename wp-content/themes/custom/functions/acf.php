<?php

//== Add css & icon to metabox
function admin_acf_css() { ?>
    <style>
        .acf-accordion .acf-accordion-title label {
            color: #000843;
        }
        #poststuff [id^="acf-group_"] h2 {
            color: #000843;
            text-transform: uppercase;
            font-weight: bold;
            background: 8px 9px url('../wp-content/themes/tpdemain/assets/img/favicon.png') no-repeat;
            background-size: 14px;
            padding-left: 27px;
        }
    </style>
<?php }
//add_action('acf/input/admin_head', 'admin_acf_css');

function theme_add_option_menu()
{

    if ( function_exists('acf_add_options_page') ) {

        acf_add_options_page(array(
            'page_title'    => 'Options',
            'menu_title'    => 'Options',
            'menu_slug'     => 'site-options',
            'capability'    => 'edit_others_posts',
            'position'      => '16.6',
            'redirect'      => false
        ));

        if ( function_exists('add_submenu_page') ) {
            add_submenu_page( 'site-options', 'Options générales', 'Options générales', 'edit_others_posts', 'admin.php?page=site-options' );
            add_submenu_page( 'site-options', 'Identité', 'Identité', 'edit_posts', 'customize.php?return=/wp-admin/admin.php?page=site-options' );
            add_submenu_page( 'site-options', 'Blocks', 'Blocks', 'edit_posts', 'edit.php?post_type=wp_block', '' );
        }
    }
}
add_action('admin_menu', 'theme_add_option_menu');


// page options fields
function get_field_option( $field, $key = null )
{
    global $post;

    if ( function_exists('get_field')) {

        if ( !is_null( $key ) ) {
            return get_field( $field, 'option' )[$key];
        }
        else {
            return get_field( $field, 'option' );
        }
    }
}
add_action('acf/init', 'get_field_option'); // Since ACF 5.11.1

function add_acf_favicon()
{
    $upload_fav = get_field_option('img_favicon');
    $favicon = isset($upload_fav) && !empty($upload_fav) ? $upload_fav['url'] : get_img_url('favicon.png');

    echo '<link rel="icon" type="image/png" href="' . esc_attr( $favicon ) . '" />';
}
add_action( 'wp_head', 'add_acf_favicon' );
add_action( 'login_head', 'add_acf_favicon' );
add_action( 'admin_head', 'add_acf_favicon' );

// ACF header logo
function get_acf_logo_header()
{
    $logo = get_field_option('img_logo_header');
    $has_logo = isset($logo) && !empty($logo);
    $logo_alt = $has_logo && !empty($logo['alt']) ? $logo['alt'] : get_bloginfo('name');
    $logo_url = $has_logo && !empty($logo['url']) ? $logo['url'] : get_svg_url('logo.svg');

    return '<img class="img-fluid" loading="eager" src="'. esc_attr($logo_url) .'" alt="'. esc_attr($logo_alt) .'">';
}
// ACF footer logo
function get_acf_logo_footer()
{
    $logo = get_field_option('img_logo_footer');
    $has_logo = isset($logo) && !empty($logo);
    $logo_alt = $has_logo && !empty($logo['alt']) ? $logo['alt'] : get_bloginfo('name');
    $logo_url = $has_logo && !empty($logo['url']) ? $logo['url'] : get_svg_url('logo.svg');

    return '<img class="img-fluid" loading="lazy" src="'. esc_attr($logo_url) .'" alt="'. esc_attr($logo_alt) .'">';
}


/*
   Scripts
   ========================================================================== */

function get_options_script($option_field)
{
    $scripts = get_field_option($option_field);
    return null !== $scripts && !empty($scripts) ? $scripts : false;
}

function set_options_script()
{

    foreach (['scripts_head_top','scripts_head_bottom', 'scripts_body_top', 'scripts_body_bottom'] as $option_field) {

        $script = get_options_script($option_field);

        if ($script) {

            switch ($option_field) {
                case 'scripts_head_top':
                    add_action('wp_head', function () use ($script) {
                        echo $script;
                    }, -1000);
                    break;
                case 'scripts_head_bottom':
                    add_action('wp_head', function () use ($script) {
                        echo $script;
                    }, 1000);
                    break;
                case 'scripts_body_top':
                    add_action('wp_body_open', function () use ($script) {
                        echo $script;
                    }, -1000);
                    break;
                case 'scripts_body_bottom':
                    add_action('wp_footer', function () use ($script) {
                        echo $script;
                    }, 100);
                    break;
            }
        }
    }
}

/*
   Styles
   ========================================================================== */

add_action('acf/init', function()
{

    $style = get_field_option('style_head');

    if ( null !== $style && $style && ! is_admin() ) {
        add_action( 'wp_enqueue_scripts', function() use( $style )
        {
            wp_register_style( 'options-inline-style', false, [ 'theme-style' ] );
            wp_enqueue_style( 'options-inline-style' );
            wp_add_inline_style( 'options-inline-style', $style );
        });
    }
});



/*
   Brand informations
   ========================================================================== */

function get_phone_number()
{
    $phone = get_field_option( 'info_phone' );
    $phone_app = get_field_option( 'info_phone_app' );

    if ( $phone && !empty($phone) ) {

        if ( !empty($phone_app) ) {
            return sprintf('<a href="tel:%1$s">%2$s</a>', esc_attr($phone_app), esc_attr($phone));
        }
        else {
            return $phone;
        }
    }

    return false;
}

function get_brand_informations()
{
    ob_start();

    $name = get_field_option( 'info_name' );
    $phone = get_phone_number();
    $address = get_field_option( 'info_address' );
    $email = get_field_option( 'info_email');

    if ( $address || $email || $name || ( $phone && !empty($phone) ) ) { ?>
        <div class="ft-brand-desc">
            <?php echo $name && !empty($name) ? sprintf('<div class="ftb-name">%s</div>', esc_attr($name)) : ''; ?>
            <?php echo $address && !empty($address) ? sprintf('<div class="ftb-address">%s</div>', wp_kses($address,['br' => []])) : ''; ?>
            <?php echo $phone && !empty($phone) ? sprintf('<div class="ftb-phone">%s</div>', $phone) : ''; ?>
            <?php echo $email && !empty($email) ? sprintf('<div class="ftb-email"><a href="mailto:%1$s" title="%2$s">%1$s</a></div>', esc_attr($email), esc_attr__( 'Nous contacter', 'webexpr' )) : ''; ?>
        </div>
    <?php  }

    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

/*
   Google Map with infobox (empty title will hide infobox JS)
   ========================================================================== */
#TODO add custom center for map

function get_contact_map()
{
    ob_start();

    $contact_map = get_field_option('map');

    if ( $contact_map && !is_admin() ) : ?>
        <div id="c-map" style="height:400px;"></div>

        <?php function set_map($contact_map) {
            $gmap_key = get_field_option( 'map_api_key' );

            if ( $gmap_key && !empty($gmap_key) && $contact_map ) :

                $markers_length = count($contact_map);
            ?>

                <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $gmap_key; ?>&callback=initMap" type="text/javascript"></script>
                <script>
                    function initMap() {
                        var markers = [
                            <?php foreach( $contact_map as $marker ) :
                                $site_lat_lng = esc_attr($marker['lat_lng']);
                                $lat_lng = explode( ',', $site_lat_lng, 2);
                                $name = wp_kses($marker['title'],['br' => []]);
                                $address = wp_kses($marker['content'],['br' => []]);
                                $address = trim($address);
                                $phone = esc_attr($marker['phone']);
                                $phone_app = esc_attr($marker['phone_app']);
                            ?>
                            <?php if ( isset( $lat_lng ) && !empty( $lat_lng ) ) : ?>
                                {
                                    "name": "<?php echo preg_replace('/\r|\n/', '',$name); ?>",
                                    "lat": "<?php echo esc_attr($lat_lng[0]); ?>",
                                    "lng": "<?php echo esc_attr($lat_lng[1]); ?>",
                                    "address": "<?php echo preg_replace('/\r|\n/', '',$address); ?>",
                                    "phone": "<?php echo $phone; ?>",
                                    "phone2": "<?php echo $phone_app; ?>",
                                     <?php /*"img": "<?php echo isset( $img ) && !empty( $img ) ? $img : img_url( 'map-logo.png' ); ?>",  */ ?>
                                },
                            <?php endif; ?>
                        <?php endforeach; ?>
                        ];

                        const iconFillColor = getComputedStyle(document.documentElement).getPropertyValue('--we-map-fill-icon-color');
                        const iconStrokeColor = getComputedStyle(document.documentElement).getPropertyValue('--we-map-stroke-icon-color');

                        <?php
                            $map_style = get_field_option('map_style') ? 'styles: '. get_field_option('map_style') : '';
                            $map_zoom = get_field_option('map_zoom');
                            $map_center = get_field_option('map_center');
                        ?>

                        const customIcon = {
                            path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
                            fillColor: iconFillColor ? iconFillColor : '#e4282b',
                            fillOpacity: 1,
                            strokeColor: iconStrokeColor ? iconStrokeColor : '#fff',
                            strokeWeight: 2,
                            scale: 1,
                        };
                        const mapOptions = {
                            mapTypeId: google.maps.MapTypeId.ROADMAP,
                            <?php echo $map_style; ?>
                        };
                        const map = new google.maps.Map(document.getElementById('c-map'), mapOptions);
                        const bounds = new google.maps.LatLngBounds();
                        const infoWindow = new google.maps.InfoWindow();

                        for (let i = 0; i < markers.length; i++) {
                            let data = markers[i];
                            let marker = new google.maps.Marker({
                                position: new google.maps.LatLng(data.lat, data.lng),
                                icon: customIcon,
                                map: map,
                            });

                            <?php if ( $markers_length == 1 && $map_center ) : ?>
                                bounds.extend(new google.maps.LatLng(<?php echo esc_attr($map_center); ?>));
                            <?php else : ?>
                                bounds.extend(marker.position);
                            <?php endif; ?>

                            <?php if ( !empty( $name ) ) : ?>
                                (function (marker, data) {
                                    google.maps.event.addListener(marker, 'click', function (e) {
                                        var content = `
                                            <div class="ib-title">${data.name}</div>
                                            <div class="ib-content">${data.address}</div>
                                            <div class="ib-phone">
                                                <a href="tel:${data.phone2}">${data.phone}</a>
                                            </div>
                                        `;
                                        infoWindow.setContent(content);
                                        infoWindow.open(map, marker);
                                    });
                                })(marker, data);
                                google.maps.event.addListener(map, 'click', function(e) {
                                    infoWindow.close();
                                });
                            <?php endif; ?>
                        }

                        map.fitBounds(bounds);

                        <?php if ( $markers_length == 1 && $map_zoom ) : ?>
                            let listener = google.maps.event.addListener(map, 'idle', function() {
                                map.setZoom(<?php echo intval($map_zoom); ?>);
                                google.maps.event.removeListener(listener);
                            });
                        <?php endif; ?>
                    }
                </script>
            <?php endif;
        }
        add_action( 'wp_footer', function() use($contact_map) { set_map($contact_map); }, 100 );

    endif;

    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}
//add_shortcode('map', 'get_contact_map');


/*
   Social nav
   ========================================================================== */

function get_social_items($class_name = 'social-nav-header')
{
    ob_start();

    $socials = get_field_option( 'network_list' );
    $social_nav_title_field = get_field_option('network_title');
    $social_nav_title = $social_nav_title_field && !empty($social_nav_title_field) ? $social_nav_title_field : false;

    if ( $socials && !empty($socials) ) : ?>
        <div class="social-nav<?php echo isset($class_name) && !empty($class_name) ? ' ' . $class_name : ''; ?>">
            <?php if ( $social_nav_title ) :
                printf('<div class="social-nav-title">%s</div>', esc_attr($social_nav_title));
            endif; ?>
            <div class="social-nav-list list-inline" role="tablist">
                <?php foreach( $socials as $social ) :
                    $network_name = !empty($social['link']) && check_array('title',$social['link']) ? esc_attr($social['link']['title']) : false;
                    $network_icon = !empty($social['icon']) ? wp_kses_post($social['icon']) : false;
                    $network_link = !empty($social['link']) && check_array('title',$social['link']) ? esc_url($social['link']['url']) : false;
                    $network_target = !empty($social['link']) && check_array('title',$social['link']) ? esc_attr($social['link']['target']) : false;
                ?>
                <div class="list-inline-item" role="tab">
                    <a href="<?php echo $network_link; ?>" title="<?php echo $network_name; ?>" target="<?php echo $network_target; ?>">
                        <?php echo $network_icon; ?>
                    </a>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    <?php endif;

    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}


/*
   Gutenberg && ACF Block Attributes
   ========================================================================== */

/**
 * Set Gutenberg block attributes
 * in render.php => `<div<?php set_block_attr('my-class-slug-name', $block); ?>>`
 * @param array $block ACF block in render view
 * @param string $class_name class name
 * @param boolean $return echo or return content
 * @return string
*/
function set_block_attr( $block, $class_name = '', $return = false )
{

    if ( ! function_exists('acf_register_block_type') && ! $block )  {
        return;
    }

    $style = '';

    if ( !empty($block['anchor']) ) {
        $id = $block['anchor'];
    }

    if ( !empty($block['className']) ) {
        $class_name .= ' ' . $block['className'];
    }

    if ( !empty($block['align']) ) {
        $class_name .= ' align' . $block['align'];
    }

    if ( !empty($block['backgroundColor']) ) {
        $class_name .= ' has-' . $block['backgroundColor'] . '-background-color';
    }
    if ( !empty($block['textColor']) ) {
        $class_name .= ' has-text-color has-' . $block['textColor'] . '-color ';
    }
    if ( !empty($block['style']['color']['background']) ) {
        $style .= 'background-color: ' . $block['style']['color']['background'] . ';';
    }
    if ( !empty($block['style']['color']['gradient']) ) {
        $style .= 'background: ' . $block['style']['color']['gradient'] . ';';
    }
    if ( !empty($block['style']['color']['text']) ) {
        $style .= 'color: ' . $block['style']['color']['text'] . ';';
    }

    $class = !empty($class_name) ? ' class="' . trim(esc_attr($class_name)) .'"' : '';
    $id = !empty($id) ? ' id="' . esc_attr($id) . '"' : '';
    $style = isset($style) && !empty($style) ? 'style="' . esc_attr($style) . '"' : '';

    $attrs = $id . $class . $style;

    if ( $return ) {
        return $attrs;
    }
    else {
        echo $attrs;
    }
}


/**
 * Get Gutenberg block alignement value
 * Return `url`,`target`,`title`,`alt`
 * @param array $block ACF block in render view
 * @return string
*/
function get_block_alignment_attr( $block )
{
    if ( ! function_exists('acf_register_block_type') && ! $block )  {
        return;
    }

    if ( !empty($block['align']) ) {
        return $block['align'];
    }

    return false;
}

/**
 * Set container wrapper if Gutenberg block alignement value equals `full`
 * Start => function_exists('set_block_container') ? set_block_container($block) : '';
 * End => function_exists('set_block_container') ? set_block_container($block,'end') : '';
 * @param array $block ACF block in render view
 * @return string
*/
function set_block_container( $block, $dom = 'start', $return = false )
{
    if ( ! function_exists('get_block_alignment_attr') && ! $block ) {
        return;
    }
    $block_align = get_block_alignment_attr($block) && ( get_block_alignment_attr($block) === 'full' ) ? true : false;
    $container = '';

    if ( $block_align && $dom === 'start' ) {
        $container = '<div class="container">';
    }
    if ( $block_align && $dom === 'end' ) {
        $container = '</div>';
    }

    if ( $return ) {
        return $container;
    }
    else {
        echo $container;
    }
}

function set_block_content( $block, $content, $class_block = '' )
{
    if ( ! function_exists('set_block_attr') && ! function_exists('set_block_container') && ! $block && ! $content ) {
        return;
    }

    $block_attrs = set_block_attr($block,$class_block,true);
    $container_start = set_block_container($block,'start',true);
    $container_end = set_block_container($block,'end',true);

    if ( !empty($content) ) {

        echo <<<HTML
            <div${block_attrs}>
                ${container_start}
                ${content}
                ${container_end}
            </div>
        HTML;
    }
}



/*
   Sync Gutenberg && ACF colors
   ========================================================================== */

/**
 * Get gutenberg colors palettes
 * Return `string`
 * @return string
*/
function get_gutenberg_palette_colors_values()
{

    $color_palette = current( (array) get_theme_support( 'editor-color-palette' ) );

    if ( !$color_palette ) {
        return;
    }

    ob_start();

    echo '[';
        foreach ( $color_palette as $color ) {
            echo "'" . $color['color'] . "', ";
        }
    echo ']';

    return ob_get_clean();
}


/**
 * Sync Gutenberg Colors palette and ACF color picker
 * Return `array`
 * @return array
*/
function sync_gutenberg_colors_palette_with_acf_color_picker()
{

    $color_palette = function_exists('get_gutenberg_palette_colors_values') ? get_gutenberg_palette_colors_values() : false;

    if ( !$color_palette ) {
        return;
    } ?>
    <script type="text/javascript">
        (function( $ ) {
            acf.add_filter( 'color_picker_args', function( args, $field ) {
                args.palettes = <?php echo $color_palette; ?>;
                return args;
            });
        })(jQuery);
    </script>
<?php
}
add_action( 'acf/input/admin_footer', 'sync_gutenberg_colors_palette_with_acf_color_picker' );



/*
   ACF helpers
   ========================================================================== */

/**
 * Get ACF link / Img
 * Return `url`,`target`,`title`,`alt`
 * @param string $return ACF array return mode (`url`,`target`,`title`,`alt`)
 * @param string $field ACF custom_field `slug`
 * @param boolean $in_array Inside an array (`true`,`false`)
 * @param boolean|string $id post ID
 * @return string|false|empty
 */

function get_acf_array_field($field, $return, $in_array = true, $id = false)
{

    if ( $in_array ) {
        $item = $field && !empty($field) ? $field : false;
    }
    else {
        if ( function_exists('get_field') ) {
            $get_field = $id ? get_field($field,$id) : get_field($field);
            $item = null !== $get_field && !empty($get_field) ? $get_field : false;
        }
    }

    if ( $item && $return ) {

        if ( is_array($item) ) {
            $item = array_key_exists($return,$item) && !empty($item[$return]) ? $item[$return] : '';
            $item = $item === 'url' ? esc_url($item) : esc_attr($item);
        }
    }

    return $item;
}
