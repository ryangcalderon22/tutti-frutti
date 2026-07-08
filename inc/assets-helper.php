<?php
/**
 * Client assets and image helpers.
 *
 * @package Tutti_Frutti_Cafe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Ensure every <img> tag in a chunk of HTML has meaningful alt text.
 *
 * Useful for output coming from plugins/shortcodes we don't control (e.g.
 * the Tutti Frutti Slider plugin's [tutti_slider] shortcode) where we can't
 * edit the markup directly. For any <img> missing alt text, this tries —
 * in order — the image's own title attribute, a wrapping <a> title
 * attribute, common data-* attributes (data-title/data-caption/data-alt/
 * data-name), then a nearby heading or title/caption/name-classed element
 * within the same slide block. Falls back to $fallback_alt (empty by
 * default) if nothing usable is found. Existing non-empty alt text is
 * always left untouched.
 *
 * @param string $html         HTML to process.
 * @param string $fallback_alt Alt text to use when no title can be found.
 * @return string
 */
function tutti_frutti_ensure_img_alt( $html, $fallback_alt = '' ) {
    if ( ! $html || false === stripos( $html, '<img' ) || ! class_exists( 'DOMDocument' ) ) {
        return $html;
    }

    $doc = new DOMDocument();
    $prev_errors = libxml_use_internal_errors( true );
    $doc->loadHTML(
        '<?xml encoding="UTF-8"><div id="tf-alt-wrap">' . $html . '</div>',
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
    );
    libxml_clear_errors();
    libxml_use_internal_errors( $prev_errors );

    $xpath  = new DOMXPath( $doc );
    $images = $xpath->query( '//img' );

    foreach ( $images as $img ) {
        if ( ! $img instanceof DOMElement ) {
            continue;
        }
        if ( '' !== trim( $img->getAttribute( 'alt' ) ) ) {
            continue; // Already has real alt text.
        }

        $title = tutti_frutti_find_nearby_title( $img, $xpath );
        $img->setAttribute( 'alt', $title ? $title : $fallback_alt );
    }

    $wrapper = $doc->getElementById( 'tf-alt-wrap' );
    if ( ! $wrapper ) {
        return $html;
    }

    $out = '';
    foreach ( $wrapper->childNodes as $child ) {
        $out .= $doc->saveHTML( $child );
    }

    return $out;
}

/**
 * Try to find a title/caption for an <img> from nearby markup.
 *
 * @param DOMElement $img   The image element.
 * @param DOMXPath   $xpath XPath helper bound to the same document.
 * @return string
 */
function tutti_frutti_find_nearby_title( DOMElement $img, DOMXPath $xpath ) {
    // 1. The image's own title attribute.
    $title_attr = trim( $img->getAttribute( 'title' ) );
    if ( $title_attr ) {
        return $title_attr;
    }

    // 2. A wrapping <a title="...">.
    $parent = $img->parentNode;
    if ( $parent instanceof DOMElement && 'a' === strtolower( $parent->nodeName ) ) {
        $a_title = trim( $parent->getAttribute( 'title' ) );
        if ( $a_title ) {
            return $a_title;
        }
    }

    // 3. Common data-* attributes used by slider/gallery plugins.
    foreach ( array( 'data-title', 'data-caption', 'data-alt', 'data-name' ) as $data_attr ) {
        $val = trim( $img->getAttribute( $data_attr ) );
        if ( $val ) {
            return $val;
        }
    }

    // 4. Walk up a few ancestor levels looking for a heading, or an element
    // whose class suggests it holds the slide's title/caption/name.
    $node  = $img->parentNode;
    $depth = 0;
    while ( $node instanceof DOMElement && $depth < 4 ) {
        $heading = $xpath->query( './/h1 | .//h2 | .//h3 | .//h4 | .//h5 | .//h6', $node );
        if ( $heading->length ) {
            $text = trim( $heading->item( 0 )->textContent );
            if ( $text ) {
                return $text;
            }
        }

        $captioned = $xpath->query(
            './/*[contains(concat(" ", normalize-space(@class), " "), " title ") '
            . 'or contains(concat(" ", normalize-space(@class), " "), " caption ") '
            . 'or contains(concat(" ", normalize-space(@class), " "), " name ")]',
            $node
        );
        if ( $captioned->length ) {
            $text = trim( $captioned->item( 0 )->textContent );
            if ( $text ) {
                return $text;
            }
        }

        $node = $node->parentNode;
        $depth++;
    }

    return '';
}

/**
 * Theme asset URI for a file under assets/images/client/.
 *
 * @param string $filename Filename.
 * @return string
 */
function tutti_frutti_client_asset_uri( $filename ) {
    $path = get_template_directory() . '/assets/images/client/' . $filename;
    if ( file_exists( $path ) ) {
        return get_template_directory_uri() . '/assets/images/client/' . $filename;
    }
    return '';
}

/**
 * Logo URL by context: dark (hero), light (header inner/scroll).
 *
 * @param string $context dark|light.
 * @return string
 */
function tutti_frutti_get_logo_url( $context = 'light' ) {
    if ( 'dark' === $context ) {
        $mod = get_theme_mod( 'tf_logo_on_dark' );
        if ( $mod ) {
            return $mod;
        }
        $client = tutti_frutti_client_asset_uri( 'logo-white.png' );
        if ( $client ) {
            return $client;
        }
        return tutti_frutti_get_image( 'logo_fallback' );
    }

    $mod = get_theme_mod( 'tf_logo_on_light' );
    if ( $mod ) {
        return $mod;
    }

    $custom = get_theme_mod( 'custom_logo' );
    if ( $custom ) {
        $url = wp_get_attachment_image_url( $custom, 'full' );
        if ( $url ) {
            return $url;
        }
    }

    $client = tutti_frutti_client_asset_uri( 'logo.png' );
    if ( $client ) {
        return $client;
    }

    return tutti_frutti_get_image( 'logo_fallback' );
}

/**
 * Page banner: Customizer, client file, or demo URL.
 *
 * @param string $key Setting key.
 * @return string
 */
function tutti_frutti_get_page_banner( $key ) {
    $mod = get_theme_mod( 'tf_banner_' . $key );
    if ( $mod ) {
        return $mod;
    }

    $map = array(
        'home'      => 'homepage.jpg',
        'about'     => 'about.jpg',
        'pio'       => 'pio.jpg',
        'order'     => 'order.jpg',
        'rewards'   => 'rewards.jpg',
        'careers'   => 'careers.jpg',
        'franchise' => 'franchise.jpg',
        'contact'   => 'contact.jpg',
        'brands'    => 'brands.jpg',
    );

    if ( isset( $map[ $key ] ) ) {
        $client = tutti_frutti_client_asset_uri( $map[ $key ] );
        if ( $client ) {
            return $client;
        }
    }

    $fallback_keys = array(
        'home'      => 'hero',
        'about'     => 'about_interior',
        'pio'       => 'pio_hero',
        'order'     => 'order_phone',
        'rewards'   => 'rewards_phone',
        'careers'   => 'careers_team',
        'franchise' => 'franchise',
        'contact'   => 'about_interior',
        'brands'    => 'brand_tutti',
    );

    $img_key = isset( $fallback_keys[ $key ] ) ? $fallback_keys[ $key ] : 'placeholder';
    return tutti_frutti_get_image( $img_key );
}

/**
 * Body classes: transparent header + stable page slugs (not page-id-*).
 *
 * @param array $classes Body classes.
 * @return array
 */
function tutti_frutti_body_classes( $classes ) {
    if ( is_front_page() ) {
        $classes[] = 'has-transparent-header';
    }

    if ( is_page() ) {
        $post = get_queried_object();
        if ( $post instanceof WP_Post ) {
            $slug_map = array(
                'order-online' => 'page-order',
                'rewards'      => 'page-rewards',
                'careers'      => 'page-careers',
                'faqs'         => 'page-faqs',
            );
            if ( isset( $slug_map[ $post->post_name ] ) ) {
                $classes[] = $slug_map[ $post->post_name ];
            }
        }
    }

    if ( is_singular( 'tf_brand' ) ) {
        $classes[] = 'page-brand-detail';
    }

    return $classes;
}
add_filter( 'body_class', 'tutti_frutti_body_classes' );

/**
 * Output site logo markup.
 *
 * @param string $context header-home|header-light|hero|footer.
 */
function tutti_frutti_the_logo( $context = 'header-light' ) {
    if ( 'hero' === $context ) {
        $url = tutti_frutti_get_logo_url( 'dark' );
//         printf(
//             '<img src="%1$s" class="home-hero__logo" alt="%2$s" width="220" height="60">',
//             esc_url( $url ),
//             esc_attr( get_bloginfo( 'name' ) )
//         );
        return;
    }

    $logo_context = ( 'header-home' === $context && is_front_page() ) ? 'light' : ( 'footer' === $context ? 'light' : 'light' );
    $url          = tutti_frutti_get_logo_url( $logo_context );
    $dark_url     = tutti_frutti_get_logo_url( 'dark' );
    $class        = 'custom-logo';

    if ( 'header-home' === $context && is_front_page() ) {
        $class .= ' custom-logo--header-home';
    }

    printf(
        '<a href="%1$s" class="custom-logo-link" rel="home" data-logo-light="%2$s" data-logo-dark="%3$s"><img src="%2$s" class="%4$s" alt="%5$s" width="180" height="50"></a>',
        esc_url( home_url( '/' ) ),
        esc_url( $url ),
        esc_url( $dark_url ),
        esc_attr( $class ),
        esc_attr( get_bloginfo( 'name' ) )
    );
}

/**
 * Brand-colored social icon SVG.
 *
 * @param string $network Network slug.
 * @return string
 */
function tutti_frutti_social_icon_svg( $network ) {
    $icons = array(
        'facebook'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path fill="#1877F2" d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>',
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path fill="#E4405F" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>',
        'tiktok'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path fill="#000000" d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.76a4.85 4.85 0 01-1.01-.07z"/></svg>',
        'yelp'      => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path fill="#D32323" d="M20.16 12.594l-4.995 1.431c-.96.276-1.74-.015-1.998-.832l-1.584-5.168c-.258-.817.262-1.707 1.222-1.983l4.995-1.431c.96-.276 1.74.015 1.998.832l1.584 5.168c.258.817-.262 1.707-1.222 1.983zM9.003 8.023l-4.995-1.431C3.048 6.316 2.268 6.607 2.01 7.424L.426 12.592c-.258.817.262 1.707 1.222 1.983l4.995 1.431c.96.276 1.74-.015 1.998-.832l1.584-5.168c.258-.817-.262-1.707-1.222-1.983zM9.003 15.977l-4.995 1.431c-.96.276-1.74-.015-1.998-.832l-1.584-5.168c-.258-.817.262-1.707 1.222-1.983l4.995-1.431c.96-.276 1.74.015 1.998.832l1.584 5.168c.258.817-.262 1.707-1.222 1.983zM20.16 11.406l-4.995-1.431c-.96-.276-1.74.015-1.998.832l-1.584 5.168c-.258.817.262 1.707 1.222 1.983l4.995 1.431c.96.276 1.74-.015 1.998-.832l1.584-5.168c.258-.817-.262-1.707-1.222-1.983z"/></svg>',
        'x'         => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path fill="#000000" d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        'nextdoor'  => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path fill="#8ED500" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15H8v-6h3v6zm5 0h-3v-6h3v6z"/></svg>',
    );

    return isset( $icons[ $network ] ) ? $icons[ $network ] : '';
}
