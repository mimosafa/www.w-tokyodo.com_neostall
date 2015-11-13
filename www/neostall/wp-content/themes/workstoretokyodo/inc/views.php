<?php
/**
 * Workstore Tokyo Do Theme Views
 *
 * @since 0.0.0
 */

/**
 * Body Class
 *
 * @since 0.0
 */
add_filter( 'body_class', 'wstd_body_class' );
function wstd_body_class( $classes ) {
	if ( is_wstd_division() ) {
		$classes[] = 'division-' . current_wstd_division( 'name' );
		if ( is_wstd_division_home() ) {
			$classes[] = 'division-home';
		}
	}
	return $classes;
}

/**
 * Header Area Views
 *
 * @since 0.0.0
 */

/**
 * Very First Logo
 *
 * @since 0.0.0
 */
function wstd_very_first_logo() {
	echo is_wstd_division() ? '<a href="' . wstd_home_url() . '"">Workstore Tokyo Do</a>' : wstd_one_phrase();
}

/**
 * Site ID
 *
 * @since 0.0.0
 */
function wstd_site_id() {
	$siteid = is_wstd_division() ? current_wstd_division( 'public_name' ) : WSTD;
	if ( is_wstd_division_home() || is_wstd_home() ) {
		$before = '<h1>';
		$after  = '</h1>';
	} else {
		$before = '<a href="' . wstd_current_home_url() . '"">';
		$after  = '</a>';
	}
	echo $before . esc_html( $siteid ) . $after;
}

/**
 * Workstore Tokyo Do Division Links
 *
 * @since 0.0.0
 */
function wstd_division_links( $where ) {
	if ( $where === 'header' ) {
		$fn = function( $division, $args, $active ) {
			if ( $active ) {
				$f = '<li id="btn-%1$s" class="active">%2$s</li>';
			} else {
				$f = '<li id="btn-%1$s"><a href="%3$s">%2$s</a></li>';
			}
			printf( $f, $division, esc_html( $args['public_name'] ), esc_url( $args['home_url'] ) );
		};
	}
	foreach ( WSTD\Divisions::init()->get_divisions() as $division => $args ) {
		$fn( $division, $args, is_wstd_division( $division ) );
	}
}

/**
 * @todo
 */
function wstd_global_nav_links() {
	/**
	 * Do Something...
	 */
	?>
<li class="active"><a href="#">ランチスペース <span class="sr-only">(current)</span></a></li>
<li><a href="#">イベント</a></li>
<li><a href="#">キッチンカー</a></li>
<?php
}

/**
 * @todo
 */
function wstd_global_nav_form() {
	/**
	 * Do Something...
	 */
	?>
<form class="navbar-form navbar-right" role="search">
	<div class="form-group">
		<input type="text" class="form-control" placeholder="Search">
	</div>
	<button type="submit" class="btn btn-default">Submit</button>
</form>
<?php
}

/**
 * Post Views
 *
 * @since 0.0.0
 */

/**
 * Extend the_title() Function
 *
 * @since 0.0.0
 */
function wstd_the_title( $before = '', $after = '', $echo = true ) {
	if ( ! is_singular() ) {
		$before .= '<a href="' . get_permalink() . '">';
		$after   = '</a>' . $after;
	}
	the_title( $before, $after, $echo );
}
