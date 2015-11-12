<?php

// Lazyload Image
function mmsf_get_lazyload_image( $attachment_id, $size = 'thumbnail', $str = 'lazy' ) {
    $html = '';
    $image = wp_get_attachment_image_src( $attachment_id, $size );
    $class   = esc_attr( $str );
    if ( $image ) {
        list( $src, $width, $height ) = $image;
        $hrstring = image_hwstring( $width, $height );
        $html = rtrim( "<img {$hrstring}" );
        $html .= ' src="' . get_stylesheet_directory_uri() . '/images/white.gif"';
        $html .= ' data-original="' . $src . '" class="' . $class . '" />' . "\n";
        $html .= '<noscript>' . "\n";
        $html .= rtrim( "<img {$hrstring}" );
        $html .= ' src="' . $src . '" />' . "\n";
        $html .= '</noscript>';
    } else { // if no image
        if ( is_array( $size ) ) {
            list( $width, $height ) = $size;
            $src = esc_url( sprintf( 'http://fakeimg.pl/%dx%d/?text=No Image', $width, $height ) );
        } elseif ( 'thumbnail' == $size ) {
            $width = 150;
            $height = 150;
            $src = get_template_directory_uri() . '/images/noimage-150x150.png';
        } elseif ( 'medium' == $size ) {
            $width = 300;
            $height = 225;
            $src = get_template_directory_uri() . '/images/noimage-300x225.png';
        }
        $hrstring = image_hwstring( $width, $height );
        $html = rtrim( "<img {$hrstring}" );
        $html .= ' src="' . get_stylesheet_directory_uri() . '/images/white.gif"';
        $html .= ' data-original="' . $src . '" class="' . $class . '" />' . "\n";
        $html .= '<noscript>' . "\n";
        $html .= rtrim( "<img {$hrstring}" );
        $html .= ' src="' . $src . '" />' . "\n";
        $html .= '</noscript>';
    }
    return $html;
}

// Location Str
function get_location_str( $post_id = 0 ) {
    $str = '';
    $region_obj = get_region_tax_obj( $post_id );
    if ( 0 != $region_obj->parent ) {
        $pref_obj = get_term( $region_obj->parent, 'region' );
        $str .= esc_html( $pref_obj->name );
    }
    $str .= esc_html( $region_obj->name );
    if ( $address = get_post( $post_id )->address )
        $str .= $address;
    if ( $site = get_post( $post_id )->site )
        $str .= ' ' . esc_html( $site );
    if ( $area = get_post( $post_id )->areaDetail )
        $str .= ' ' . esc_html( $area );
    return $str;
}

// Opening Time Str
function get_opening_time_str( $post_id = 0 ) {
    $post = get_post( $post_id );
    if ( $post->starting ) {
        $start = date( 'G:i', strtotime( esc_html( $post->starting ) ) );
        if ( $post->startingPending )
            $start .= '<small> (予定)</small>';
    }
    if ( $post->ending ) {
        $end = date( 'G:i', strtotime( esc_html( $post->ending ) ) );
        if ( $post->endingPending )
            $end .= '<small> (予定)</small>';
    }
    if ( $start || $end )
        $str = "{$start} ~ {$end}";
    return $str;
}