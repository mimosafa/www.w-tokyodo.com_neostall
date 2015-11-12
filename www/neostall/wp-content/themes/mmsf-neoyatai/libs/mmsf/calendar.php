<?php

namespace mmsf;

/**
 * @param array $args [ [ 'Ymd' => '19700101', 'l' => 'Thursday', 'contents' => (array) ], [], ... ]
 * @param int $offset 0(Monday) - 6(Sunday)
 */
class calendar {

	public $args = [];
	public $week_offset = 0;
	public $date_offset = 0;

	private static $_daynames = [
		'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
	];

	/**
	 *
	 */
	public function output() {
		if ( empty( $this -> args ) )
			return false; ?>
<div class="calendar-wrapper">
<div class="calendar-header">
<div class="calendar-row">
<?php
		foreach ( self::$_daynames as $_dayname ) { ?>
<div class="calendar-<?php echo strtolower( $_dayname ); ?>">
  <?php _e( $_dayname ); ?>
</div>
<?php
		} ?>
</div><!-- /.calendar-row -->
</div><!-- /.calendar-header -->
<div class="calendar-body">
<?php
		$_month = 0;
		foreach ( $this -> $args as $i => $arg ) {
			if ( 0 === $i % 7 ) { ?>
<div class="calendar-row">
<?php
			}
			$ymd = $arg['Ymd'];
			$l = $arg['l'];

			$n = (int) substr( $ymd, 4, 2 );
			$j = (int) substr( $ymd, 6, 2 );
			$d = substr( $l, 0, 3 );
			$date = '';
			$date .= $_month === $n ? '<span>' . $n . '/</span>' : $n . '/';
			$date .= $j . ' <small>(' . __( $d ) . ')</small>';

			$_box_class = '';
			$_inner_class = '';

			if ( $offset === $i )
				$_box_class .= ' calendar-today';
			if ( !isset( $arg['contents'] ) ) {
				$_box_class .= ' calendar-empty';
			} else {
				$_inner_class .= ' waiting';
				if ( $offset <= $i ) {
					$_box_class .= ' calendar-present';
				} else {
					$_box_class .= ' calendar-past';
				}
				if ( $offset <= $i && $i < $offset + 7 )
					$date = '<a href="#" data-sort="' . esc_attr( $l ) . '">' . $date . '</a>';
			} ?>
<div class="calendar-box<?php echo $_box_class; ?>" data-date="<?php echo esc_attr( $ymd ); ?>">
<div class="calendar-date"><?php echo $date; ?></div>
<div class="calendar-inner<?php echo esc_attr( $_inner_class ); ?>" id="calendar-<?php echo esc_attr( $ymd ); ?>">
</div>
</div><!-- /.calendar-box -->
<?php
			if ( 0 === ( $i + 1 ) % 7 ) { ?>
</div><!-- /.calendar-row -->
<?php
			}
			$_month = $n;
		}
	} ?>
</div><!-- /.calendar-body -->
</div><!-- /.calendar-wrapper -->
<?php
}