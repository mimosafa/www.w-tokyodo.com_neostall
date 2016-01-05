<?php

// BREADCRUMB
function mmsf_breadcrumb( $sep = ' &gt; ' ) {
	global $post;
	$str = '';
	if ( !is_admin() && !is_home() ) {
		$str .= "\n" . '<div class="breadcrumb">' . "\n";
		$str .= sprintf( '<p><a href="%s">ネオ屋台村</a>%s', home_url(), $sep );
		if ( is_post_type_archive() ) {
			$str .= esc_html( get_post_type_object( get_query_var( 'post_type' ) )->label );
		} elseif ( is_tax( 'series' ) ) {
			$link = get_post_type_archive_link( 'event' );
			$str .= sprintf( '<a href="%s">イベント</a>%s', $link, $sep );
			$str .= single_term_title( '', false );
		} elseif ( is_singular( 'event' ) ) {
			$link = get_post_type_archive_link( 'event' );
			$str .= sprintf( '<a href="%s">イベント</a>%s', $link, $sep );
			$series = get_series_tax_obj( $post );
			$series_options = get_option( 'series_' . $series->slug );
			if ( $series_options['front_end_archive'] ) {
				$link = get_term_link( $series, 'series' );
				$label = esc_html( $series->name );
				$str .= sprintf( '<a href="%s">%s</a>%s', $link, $label, $sep );
			}
			$str .= $post->name ? esc_html( $post->name ) : get_the_title();
		} elseif ( is_singular( 'space' ) || is_singular( 'news' ) || is_singular( 'kitchencar' ) ) {
			$link = get_post_type_archive_link( $post->post_type );
			$label = esc_html( get_post_type_object( get_post_type() )->label );
			$str .= '<a href="' . $link . '">' . $label . '</a>' . $sep;
			$str .= $post->name ? esc_html( $post->name ) : get_the_title();
		} elseif ( is_category() ) {
			$cat = get_queried_object();
			if ( $cat->parent != 0 ) {
				$ancestors = array_reverse( get_ancestors( $cat->cat_ID, 'category' ) );
				foreach ( $ancestors as $ancestor ) {
					$link = get_category_link( $ancestor );
					$name = get_cat_name( $ancestor );
					$str .= '<a href="' . $link . '">' . $name . '</a>' . $sep;
				}
			}
			$str .= $cat->name;
		} elseif ( is_page() ) {
			if ( $post->post_parent != 0 ) {
				$ancestors = array_reverse( $post->ancestors );
				foreach ( $ancestors as $ancestor ) {
					$link = get_permalink( $ancestor );
					$name = get_the_title( $ancestor );
					$str .= '<a href="' . $link . '">' . $name . '</a>' . $sep;
				}
			}
			$str .= $post->post_title;
		} elseif ( is_single() ) {
			$categories = get_the_category( $post->ID );
			$cat = $categories[0];
			if ( $cat->parent != 0 ) {
				$ancestors = array_reverse( get_ancestors( $cat->cat_ID, 'category' ) );
				foreach ( $ancestors as $ancestor ) {
					$link = get_category_link( $ancestor );
					$name = get_cat_name( $ancestor );
					$str .= '<a href="' . $link . '">' . $name . '</a>' . $sep;
				}
			}
			$str .= '<a href="' . get_category_link( $cat->cat_ID ) . '">' . $cat->cat_name . '</a>' . $sep;
			$str .= $post->post_title;
		}
		$str .= '</p>' . "\n";
		$str .= '</div>';
	}
	echo $str;
}