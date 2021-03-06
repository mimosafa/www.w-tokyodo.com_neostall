<?php get_header(); ?>
<div id="main">
<div id="ttl" class="event"><h1><?php single_term_title(); ?></h1></div>
<?php
if ( isset( $_GET['past'] ) ) {
?>
<p class="linkMark" style="float:right;">
  <a href="<?php echo get_term_link( $term, 'series' ); ?>">本日以降に開催されるイベント</a>
</p>
<?php
}
?>
<div class="eventInfo" style="clear:right">
<?php
if ( have_posts() ) :
while ( have_posts() ):
    the_post();
    $pre_ttl = '';
    $int = strtotime( $post->day );
    $pre_ttl .= $int + ( 24 * 60 * 60 ) < time() ? '[終了] ' : '';
    $var = date( 'n', $int );








    if ($month != $var):
        $month = $var;
?>
<h2 class="inner-ttl"><?php echo $month; ?>月</h2>
<?php
    endif;
    $location = '';
    $timetable = date( 'Y年n月j日 (', strtotime( $post->day ) ) . mb_substr( __(date( 'D', strtotime( $post->day ) ) ), 0, 1 ) . ')';
    $eventsData = $post->eventsData;
    $e_data = $eventsData[0];
    if ( 1 < count( $eventsData ) ) {
        $location .= sprintf(
            '%s%s %s <small>※複数エリアで出店します</small>',
            esc_html( get_term( $e_data['region'], 'region' )->name ),
            esc_html( $e_data['address'] ),
            esc_html( $e_data['site'] )
        );
        $timetable .= ' <small>※営業時間は出店エリアごとに異なります</small>';
    } else {
        $location .= sprintf(
            '%s%s %s %s',
            esc_html( get_term( $e_data['region'], 'region' )->name ),
            esc_html( $e_data['address'] ),
            esc_html( $e_data['site'] ),
            esc_html( $e_data['areaDetail'] )
        );
        $starting = date( 'G:i', strtotime( $e_data['starting'] ) );
        $starting .= $e_data['startingPending'] ? ' <small>( 予定 )</small>' : '';
        $ending = date( 'G:i', strtotime( $e_data['ending'] ) );
        $ending .= $e_data['endingPending'] ? ' <small>( 予定 )</small>' : '';
        $timetable .= sprintf( ' %s ~ %s', $starting, $ending );
    }
?>
<div class="eventBox">
  <dl class="clearfix">
    <dt>イベント名</dt>
    <dd>
      <?php echo $pre_ttl; ?><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </dd>
    <dt>所在地</dt>
    <dd>
      <?php echo $location; ?>
    </dd>
    <dt>営業日時</dt>
    <dd>
      <?php echo $timetable; ?>
    </dd>
  </dl>
</div>
<?php
endwhile;
else :
?>
<p class="well">現在、予定されているイベント情報はありません</p>
<?php
endif;
?>
</div>

<?php if ( !isset( $_GET['past'] ) ) { ?>
<p class="nav-previous">
  <a href="<?php echo get_term_link( $term, 'series' ); ?>?past">終了イベント</a>
</p>
<?php } ?>

<!-- ページング -->
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
<div id="nav-below" class="navigation clearfix">
  <p class="nav-previous"><?php next_posts_link('過去の記事へ'); ?></p>
  <p class="nav-next"><?php previous_posts_link('新しい記事へ'); ?></p>
</div><!-- #nav-below -->
<?php endif; ?>

</div><!-- /main -->
<?php

get_sidebar();

get_footer();

?>