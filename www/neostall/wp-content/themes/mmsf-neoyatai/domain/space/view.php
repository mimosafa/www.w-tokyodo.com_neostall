<?php

namespace neoyatai\space;

class view {

	private static $_daynames = [
		'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
	];

	/**
	 * Singular Space
	 */
	public function _singular_init() {
		add_action( 'neoyatai_singular_contents', [ $this, '_singular_tabs' ], 15 );
		add_action( 'neoyatai_singular_contents', function() { ?>
<div class="tab-content">
<?php
		}, 18 );
		add_action( 'neoyatai_singular_contents', [ $this, '_week' ], 19 );
		add_action( 'neoyatai_singular_contents', [ $this, '_calendar' ], 20 );
		add_action( 'neoyatai_singular_contents', function() { ?>
</div><!-- /.tab-content -->
<?php
		}, 21 );
	}

	// Bootstrap tabs
	public function _singular_tabs() {
		global $neoyatai;
		$active_days = $neoyatai -> active_day; ?>
<ul id="weekly-tabs" class="nav nav-pills">
<?php
		foreach ( self::$_daynames as $_dayname ) {
			if ( in_array( $_dayname, $active_days ) ) { ?>
  <li>
    <a href="#week" data-sort="<?php echo esc_attr( $_dayname ); ?>"><?php echo esc_html( __( $_dayname ) ); ?></a>
  </li>
<?php
			}
		} ?>
  <li>
    <a href="#calendar"><i class="fa fa-calendar"></i> <?php _e( 'Calendar' ); ?></a>
  </li>
</ul>
<?php
	}

	// Space week contents
	public function _week() {
		global $neoyatai;
		$contents = $neoyatai -> contents;
		$offset = $neoyatai -> date_offset; ?>
<div class="tab-pane row" id="week">
<?php
		foreach ( $contents as $i => $content ) :

			if ( $i < $offset )
				continue;
			if ( $i > $offset + 6 )
				break;
			if ( !isset( $content['contents'] ) || empty( $content['contents'] ) )
				continue;

			$_dayname = $content['l'];
			$_attr = '';
			if ( $i !== $offset )
				$_attr .= 'style="display: none;" ';
			$_attr .= 'data-sort="' . $_dayname . '" ';

			if ( isset( $content['contents']['activity'] ) ) {

				$_ymd = $content['Ymd'];
				$_n = (int) substr( $_ymd, 4, 2 );
				$_j = (int) substr( $_ymd, 6, 2 );
				$_d = __( $_dayname ); ?>
<section class="neoyatai-teaser col-sm-6 col-md-4" <?php echo rtrim( $_attr ); ?>>
<div>
  <div>
    <p class="date">
      <span class="month"><?php echo $_n; ?>/</span>
      <span class="day"><?php echo $_j; ?></span>
      <br>
      <span class="dayname"><?php echo $_d; ?></span>
      <br>
      <span class="num"><i class="fa fa-arrow-right"></i> ネオ屋台数 <i class="fa fa-truck"></i> x <span></span></span>
    </p>
  </div>
</div>
</section>
<?php
/*

*/
			}

			$_ids = [];
			if ( isset( $content['contents']['activity'] ) ) {
				$_ids = $content['contents']['activity'];
			} elseif ( isset( $content['contents']['pending'] ) ) {
				$_ids = $content['contents']['pending'];
			}
			if ( isset( $content['contents']['absence'] ) )
				$_ids = array_merge( $_ids, $content['contents']['absence'] );
			foreach ( $_ids as $_id ) {

				$attr = $_attr;
				$attr .= sprintf( 'data-postid="%d" ', $_id );
				$attr .= sprintf( 'id="%s-%d" ', strtolower( $_dayname ), $_id );

				//if ( $i !== $offset ) { ?>
<section class="neoyatai-content col-sm-6 col-md-4 waiting" <?php echo $attr; ?>></section>
<?php
/*
				} else {
					$attr .= ' data-drawn="1"';
					$clss = 'neoyatai-content col-sm-6 col-md-4';

					$content = _menu_content_convert( $_id, true, $_dayname );
					$status = $content['status'];
					switch ( $status ) {
						case 'Active' :
							$nm_frmt = '<h4>%s</h4>';
							break;
						case 'Absence' :
							$nm_frmt = '<h4>お休みします <del>%s</del></h4>';
							$clss .= ' muted';
							break;
						case 'Pending' :
							$nm_frmt = '<h4>%s <small>(予定)</small></h4>';
							break;
					}

					// Kitchencar image
					$img1 = sprintf(
						'<img src="%s" class="%s" data-aspect="%f" />',
						esc_url( $content['image1']['src'] ),
						esc_attr( $content['image1']['class'] ),
						$content['image1']['aspect']
					);
					// Name
					$name = sprintf( $nm_frmt, esc_html( $content['name'] ) );

					if ( 'Absence' !== $status ) {

						// Food image
						$img2 = sprintf(
							'<a href="%s" class="food-thumb"><img src="%s" /></a>',
							esc_url( $content['image2']['url'] ),
							esc_url( $content['image2']['src'] )
						);
						$itms = '';
						foreach( $content['genres'] as $genre )
							$itms .= sprintf(
								'<span class="label label-default">%s</span>',
								esc_html( $genre )
							);
						foreach( $content['items'] as $item )
							$itms .= esc_html( $item ) . ', ';
						$itms = substr( $itms, 0, -2 );

					}

?>
<section class="<?php echo esc_attr( $clss ); ?>" <?php echo $attr; ?>>
  <figure>
    <?php echo $img1; ?>
    <figcaption>
      <?php echo $name; ?>
      <?php if ( 'Absence' !== $status ) { ?>
      <div>
        <?php echo $img2; ?>
        <p class="content-items"><?php echo $itms; ?></p>
        <p class="content-text"><?php echo esc_html( $content['text'] ); ?></p>
      </div>
      <?php } ?>
    </figcaption>
  </figure>
</section>
<?php

				}
*/
			}
		endforeach; ?>
</div><!-- /#week -->
<?php
	}

	public function _calendar() {
		global $neoyatai;
		$contents = $neoyatai -> contents;
		$offset = $neoyatai -> date_offset; ?>
<div class="tab-pane" id="calendar">
<div class="calendar-wrapper">
<div class="calendar-header">
<div class="calendar-row">
<?php
		foreach ( self::$_daynames as $_dayname ) {
			printf(
				'<div class="calendar-%s">%s</div>',
				strtolower( $_dayname ),
				__( $_dayname )
			);
		} ?>
</div><!-- /.calendar-row -->
</div><!-- /.calendar-header -->
<div class="calendar-body">
<?php
		$_n = 0;
		foreach ( $contents as $i => $arg ) {
			if ( 0 === $i % 7 )
				echo '<div class="calendar-row">';

			$ymd = $arg['Ymd'];
			$l = $arg['l'];

			$n = (int) substr( $ymd, 4, 2 );
			$j = (int) substr( $ymd, 6, 2 );
			$d = substr( $l, 0, 3 );
			$date = '';
			$date .= $_n === $n ? '<span>' . $n . '/</span>' : $n . '/';
			$date .= $j . ' <small>(' . __( $d ) . ')</small>';

			$_box_class = 'calendar-box';

			if ( $offset === $i )
				$_box_class .= ' calendar-today';
			if ( !isset( $arg['contents'] ) ) {
				$_box_class .= ' calendar-empty';
			} else {
				$_box_class .= ' waiting';
				if ( $offset <= $i ) {
					$_box_class .= ' calendar-present';
				} else {
					$_box_class .= ' calendar-past';
				}
				if ( $offset <= $i && $i < $offset + 7 )
					$date = '<a href="#" data-sort="' . esc_attr( $l ) . '">' . $date . '</a>';
			}

			printf(
				'<div class="%s" data-date="%s">',
				esc_attr( $_box_class ),
				esc_attr( $ymd )
			);
			printf( '<div class="calendar-date">%s</div>', $date );
			printf(
				'<div class="calendar-inner" id="calendar-%s"></div>',
				esc_attr( $ymd )
			);
			echo '</div><!-- /.calendar-box -->';

			if ( 0 === ( $i + 1 ) % 7 )
				echo '</div><!-- /.calendar-row -->';

			$_n = $n;
		} ?>
</div><!-- /.calendar-body -->
</div><!-- /.calendar-wrapper -->
</div><!-- /#calendar -->
<?php
	}

	/**
	 *
	 */
	public function location_html() {
		global $neoyatai;
		$output = '';
		$_region_array = $neoyatai -> region;
		foreach ( $_region_array as $_region ) {
			if ( is_object( $_region ) && 'region' === $_region -> taxonomy ) {
				$output .= sprintf(
					'<a href="%s">%s</a>',
					esc_url( get_term_link( $_region, 'region' ) ),
					esc_html( $_region -> name )
				);
				$output .= ' ';
			}
		}
		$_location_array = $neoyatai -> location;
		foreach ( $_location_array as $key => $var ) {
			if ( 'latlng' !== $key && '' !== $var ) {
				$output .= esc_html( $var );
				$output .= ' ';
			}
		}
		return rtrim( $output );
	}

	/**
	 *
	 */
	public function open_html() {
		global $neoyatai;
		$open_array = $neoyatai -> open;
		$output = '';
		if ( isset( $open_array['starting'] ) && !empty( $open_array['starting'] ) ) {
			$starting = $open_array['starting'];
			if ( preg_match( '/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $starting[0] ) )
				$output .= $starting[0];
			if ( isset( $starting['pending'] ) )
				$output .= ' <small>(予定)</small>';
		}
		$output .= ' ~ ';
		if ( isset( $open_array['ending'] ) && !empty( $open_array['ending'] ) ) {
			$ending = $open_array['ending'];
			if ( preg_match( '/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $ending[0] ) )
				$output .= $ending[0];
			if ( isset( $ending['pending'] ) )
				$output .= ' <small>(予定)</small>';
		}
		return trim( $output );
	}

}
