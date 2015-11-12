<?php

get_header();

the_post();

// Season title
$season_title = get_the_title();

// Taxonomy: series
$series_slug = esc_html( get_series_tax_obj()->slug );

// Season Schedule
$schedule = (array)get_post_meta( $post->ID, 'schedule', true );

// Date
if ( isset( $_GET['date'] ) ) {
    $day_value = esc_html( $_GET['date'] );
} else {
    $today = date( 'Ymd' );
    foreach ( $schedule as $one_day ) {
        $schedule_day = str_replace( '-', '', esc_html( $one_day['day'] ) );
        $diff = $schedule_day - $today;
        if ( 0 > $diff ) {
            continue;
        } else {
            $day_value = $schedule_day;
            break;
        }
    }
}

// set Obj $season_posts
if ( isset( $series_slug ) && isset( $day_value ) ) {
    $query_args = array(
        'post_type' => 'event',
        'tax_query' => array(
            array(
                'taxonomy' => 'series',
                'terms' => $series_slug,
                'field' => 'slug'
            )
        ),
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'day',
                'value' => $day_value,
                'compare' => '=',
                'type' => 'DATE',
            ),
            array(
                'key' => 'publication',
                'value' => 1,
                'compare' => '='
            )
        ),
        'posts_per_page' => -1,
        'orderby' => 'meta_value',
        'meta_key' => 'starting',
        'order' => 'ASC'
    );
    $season_posts = new WP_Query( $query_args );
}

/********************
 * Main の条件分岐
 * - EVENTがない場合
 ********************/
if ( !isset( $season_posts ) || 0 === count( $season_posts->posts ) ) :
?>

<div class="headerWrapper">
<div class="container">
<h2><?php echo $season_title; ?></h2>
<p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
</div>
</div>

<div class="container">

<?php
    if ( !isset( $season_posts ) ) {
        echo '<p class="well">申し訳ございません。ただいま準備中です</p>';
    } else {
        if ( $_GET['date'] ) {
            echo '<p class="well">申し訳ございません。選択いただいた日程の予定がまだ準備できておりません</p>';
        } else {
            echo '<p class="well">申し訳ございません。予定がまだご用意できておりません</p>';
        }
    }

    // 日程全体とか...
?>

</div><!-- /.container -->

<?php

/***********************************************************
 * Main の条件分岐
 * - EVENTが一個だけの場合（ネオ屋台村スーパーナイト、大宮アルディージャ など）
 ***********************************************************/
elseif ( 1 === count( $season_posts->posts ) ) :

    // Date
    $dayStr = date( 'Y/n/j D', strtotime( $day_value ) );

    $season_posts->the_post();

?>

<div class="headerWrapper">
<div class="container">
<h2><?php echo $season_title; ?></h2>
<h3><i class="icon-calendar"></i> <?php echo $dayStr; ?>, <?php the_title(); ?></h3>
<p>
  <i class="icon-map-marker"></i> <?php echo get_location_str(); ?>&ensp;
  <i class="icon-time"></i> <?php echo get_opening_time_str(); ?>
</p>
</div>
</div>

<div class="container">
<div class="row">

<?php

    // include module 'inc/evt.php'
    // This module needs EVENT post data as $post, and $series_slug.
    get_template_part( 'inc/evt' );
?>

</div><!-- /.row -->
</div><!-- /.container -->

<div class="container" style="height:400px;margin-bottom:50px;margin-top:50px;">
    <?php get_template_part( 'inc/map' ); ?>
</div>

<?php

    // reset Query
    wp_reset_query();

/***********************************************************
 * Main の条件分岐
 * - EVENTが二個以上の場合（二箇所以上で開催される場合） （レッズ... ね）
 ***********************************************************/
elseif ( 1 < count( $season_posts->posts ) ) :

    // Date
    $dayStr = date( 'Y/n/j D', strtotime( $day_value ) );

    // 1st Loop
    while ( $season_posts->have_posts() ) {

        $season_posts->the_post();

        $tabs[] = array(
            'event_id' => $post->ID,
            'location' => get_location_str(),
            'area' => esc_html( $post->areaDetail ),
            'open' => get_opening_time_str()
        );
    }
    rewind_posts();
?>

<div class="headerWrapper">
<div class="container">
<h2><?php echo $season_title; ?></h2>
<h3><i class="icon-calendar"></i> <?php echo $dayStr; ?>, <?php the_title(); ?></h3>
<p>
  <i class="icon-map-marker"></i> <span id="span-location"><?php echo $tabs[0]['location']; ?></span>&ensp;
  <i class="icon-time"></i> <span id="span-timetable"><?php echo $tabs[0]['open']; ?></span>
</p>
</div>
</div>

<div class="container">

<ul class="nav nav-pills" id="nytTab">
<?php
    foreach ( (array)$tabs as $arr ) {
        $html = '<li><a href="#tab-' . $arr['event_id'] . '" ';
        if ( $arr['location'] )
            $html .= 'data-location="' . $arr['location'] . '" ';
        if ( $arr['open'] )
            $html .= 'data-timetable="' . $arr['open'] . '" ';
        $html .= 'data-toggle="pill">' . $arr['area'] . '</a></li>';
        echo $html;
    }
?>
</ul>

<div class="tab-content">
<?php
    // 2nd Loop
    while ( $season_posts->have_posts() ) :
        $season_posts->the_post();
?>
<div class="row tab-pane" id="tab-<?php echo $post->ID; ?>">
<?php

        // include module 'inc/evt.php'
        // This module needs EVENT post data as $post.
        get_template_part( 'inc/evt' );

?>

<div class="span12" style="height:400px;margin-bottom:50px;margin-top:50px;">
    <?php get_template_part( 'inc/map' ); ?>
</div>

</div><!-- /.row /.tab-pane -->
<?php
    endwhile;
    // reset Query
    wp_reset_query();
endif;
?>

</div><!-- /.contaniner -->

<?php

get_footer();

?>