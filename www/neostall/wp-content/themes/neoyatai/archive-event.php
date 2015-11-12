<?php get_header(); ?>

<div id="header" class="headerWrapper">
  <div class="container">
    <h1>ネオ屋台村のイベント</h1>
    <p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique. <a href="#" class="btn btn-small">カレンダーで見る</a></p>
  </div>
</div>

<div class="container">

<?php if ( have_posts() ) : ?>

  <div class="nytTimeLine">

<?php

    $y = '';
    $m = '';
    $d = '';
    $s = array();
    while ( have_posts() ) : the_post();
        // Day & Time
        $dayCf = esc_html( $post->day );
        $date['Y'] = substr( $dayCf, 0, 4 );
        $date['m'] = substr( $dayCf, 4, 2 );
        $date['d'] = substr( $dayCf, -2 );
        $dayname = __( date( 'D', strtotime( $dayCf ) ) );
        $daytime = $post->starting ? esc_html( $post->starting ) : '';
        //Check Series
        $series = '';
        if ( $series_obj = get_series_tax_obj() ) :
            $series = $series_obj->name;
        endif;
        // Pref
        $region = '';
        $region_obj = get_region_tax_obj();
        if ( 0 != $region_obj->parent ) {
            $pref_obj = get_term( $region_obj->parent, 'region' );
            $region .= $pref_obj->name;
        }
        $region .= $region_obj->name;
        //
        $nytTimeCellId = '';
        if ( $y != $date['Y'] || $y == $date['Y'] and $m != $date['m'] ) {
            $nytTimeCellId = $date['Y'] . '-' . $date['m'];
            $dateNav[] = array(
                'id' => $nytTimeCellId,
                's' => $date['Y'] . '年' . $date['m'] . '月'
            );
        }

?>

    <div class="row nytTimeCell<?php if ( $nytTimeCellId ) echo ' dateHead" id="' . $nytTimeCellId; ?>">
      <time class="span3" datetime="<?php echo implode( '-', $date ); if ( $daytime ) echo 'T' . $daytime; ?>">
        <span class="nytTimeY<?php if ( $y == $date['Y'] ) echo ' hidden'; ?>"><?php echo (int)$date['Y'] . '年'; ?></span>
        <span class="nytTimeM<?php if ( $m == $date['m'] ) echo ' hidden'; ?>"><?php echo (int)$date['m'] . '月'; ?></span>
        <span class="nytTimeD<?php if ( $d == $date['d'] ) echo ' hidden'; ?>"><?php echo (int)$date['d'] . '日'; ?></span>
        <span class="nytTimeDD<?php if ( $d == $date['d'] ) echo ' hidden'; ?>"><?php echo $dayname; ?></span>
      </time>
      <div class="span9">
        <h4><?php
            if ( $series ) {
        ?><a href="<?php the_permalink(); ?>">[<?php echo $series; ?>] <?php the_title(); ?></a><?php
            } else {
        ?><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><?php
            }
        ?>
        </h4>
        <p><i class="icon-map-marker"></i> <?php echo $region; ?></p>
      </div>
    </div>

<?php

        $y = $date['Y'];
        $m = $date['m'];
        $d = $date['d'];
    endwhile;

?>
  </div><!-- /.nytTimeLine -->

<?php else : ?>

  <p class="well lead text-center">現在予定されている ネオ屋台村のイベント はありません</p>

<?php endif; ?>

</div><!-- /.contaniner -->

<?php get_footer(); ?>