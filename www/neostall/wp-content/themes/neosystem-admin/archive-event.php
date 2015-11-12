<?php get_header(); ?>
<h3>Events</h3>
<div class="row">
<div id="events" class="span9">
<?php
if ( have_posts() ) :
?>
<table class="table table-hover">
  <thead>
    <tr>
      <th>開催日</th>
      <th>シリーズ</th>
      <th>イベント名</th>
      <th>地域</th>
    </tr>
  </thead>
  <tbody>
<?php
    while ( have_posts() ) : the_post();
        $day = date( 'n/j D.', strtotime( esc_html( $post->day ) ) );
        $series = '';
        if ( $series_obj = get_series_tax_obj() ) {
            $series = sprintf( '<a href="%s">%s</a>', get_term_link( $series_obj, 'series' ), esc_html( $series_obj->name ) );
        }
        // Region
        $region = esc_html( get_region_tax_obj()->name );
?>
    <tr>
      <td>
        <?php echo $day; ?>
      </td>
      <td>
        <?php echo $series; ?>
      </td>
      <td>
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </td>
      <td>
        <?php echo $region; ?>
      </td>
    </tr>
<?php
    endwhile;
?>
  </tbody>
</table>
<?php
else :
?>
<p class="well">No Events.</p>
<?php

endif;

previous_posts_link();
next_posts_link();

?>
    </div><!-- /#events -->
    <div id="series" class="span3">
      <h4>Event Series</h4>
      <ul class="nav nav-list">
<?php
$series_array = get_terms( 'series', 'orderby=count&order=DESC&get=all' );
foreach ( $series_array as $series_arg ) {
    $series_link = get_term_link( $series_arg, 'series' );
    $series_name = esc_html( $series_arg->name );
    $series_options = get_option( 'series_' . $series_arg->slug );
    if ( $series_options['front_end_archive'] )
        $series_name = '<i class="icon icon-rss-sign"></i> ' . $series_name;
?>
        <li><a href="<?php echo $series_link; ?>"><?php echo $series_name; ?></a></li>
<?php
}
?>
        <li style="margin-top:1em;"><a href="#" data-add-series><i class="icon icon-plus"></i> Add Series</a></li>
      </ul>
      <form id="add-new-series" action="" method="post" class="hide">
        <?php wp_nonce_field( 'add_new_series', '_neosystem_admin_nonce' ); ?>
        <legend>シリーズ追加</legend>
        <label for="series_name">シリーズ名</label>
        <input type="text" id="series_name" name="series_name" required />
        <label for="series_slug">シリーズID (半角英数字)</label>
        <input type="text" id="series_slug" name="series_slug" required />
        <label class="checkbox">
          <input type="checkbox" name="front_end_archive" value="1" /> WEBサイト上でアーカイブする
        </label>
        <hr>
        <div class="btn-group pull-right">
          <button type="button" class="btn btn-small" data-cancel><i class="icon icon-remove"></i> キャンセル</button>
          <button type="submit" class="btn btn-small" disabled="disabled"><i class="icon icon-ok"></i> 追加</button>
        </div>
      </form>
</div><!-- /#series -->
</div><!-- /.row -->
<?php get_footer(); ?>