<?php
get_header();

the_post();
// cf (array)'schedule'
$schedule = (array)get_post_meta( $post->ID, 'schedule', true );
// cf (array)'webSite'
$webSites_series = (array)get_post_meta( $post->ID, 'webSites', true );
// term 'series_name'
$the_term = get_the_series_term_slug();
// GET 'date', set var $day_value
$day = esc_html( $_GET['date'] );
if ( $day ) {
    $day_value = $day;
} else {
    $today = date( 'Ymd', time() );
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
// set Obj $series_posts
if ( isset( $day_value ) ) {
    $query_args = array(
        'post_type' => 'event',
        'tax_query' => array(
            array(
                'taxonomy' => 'series_name',
                'terms' => $the_term,
                'field' => 'slug'
            )
        ),
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'publication',
                'value' => 1,
                'compare' => '='
            ),
            array(
                'key' => 'day',
                'value' => $day_value,
                'compare' => '=',
                'type' => 'DATE',
            )
        ),
        'posts_per_page' => -1,
        'orderby' => 'meta_value',
        'meta_key' => 'starting',
        'order' => 'ASC'
    );
    $series_posts = new WP_Query( $query_args );
}

get_sidebar();

?>

<!-- main -->
<div id="main">
<!-- schedule -->
<div id="ttl" class="shopcar">
<h1><?php the_title(); ?></h1>
</div>

<?php
if ( !isset( $series_posts ) || 0 === count( $series_posts->posts ) ) {
    if ( !isset( $series_posts ) ) {
        echo '<p class="well">申し訳ございません。ただいま準備中です</p>';
    } else {
        if ( $_GET['date'] ) {
            echo '<p class="well">申し訳ございません。選択いただいた日程の予定がまだ準備できておりません</p>';
        } else {
            echo '<p class="well">申し訳ございません。予定がまだご用意できておりません</p>';
        }
    }
?>
<div id="placeInfo" class="clearfix">
<dl class="clearfix">
<dt>イベント開催日</dt>
<dd>
<select id="date-change">
<?php
    foreach ( $schedule as $one_day ) {
        $valdate = str_replace( '-', '', esc_html( $one_day['day'] ) );
        $dayformat = nyt_date_format( esc_html( $one_day['day'] ) );
        echo '<option value="' . $valdate . '"';
        if ( $day_value == $valdate )
            echo ' selected="selected"';
        echo '>' . esc_html( $dayformat ) . '</option>';
    }
?>
</select>
<a href="#" class="view-other">日程を選択</a>
<a href="#" class="view-other-cancel">キャンセル</a>
</dd>
</dl>
</div>
<?php
} elseif ( 1 === count( $series_posts->posts ) ) {
    while ( $series_posts->have_posts() ) : $series_posts->the_post();
?>
<div id="placeInfo" class="clearfix">
<dl class="clearfix">
<dt>イベント名</dt>
<dd><b><?php the_title(); ?></b></dd>
<dt>イベント開催日</dt>
<dd><span id="this-day"><?php the_cf_day_text(); ?></span>
<select id="date-change">
<?php
        foreach ( $schedule as $one_day ) {
            $valdate = str_replace( '-', '', esc_html( $one_day['day'] ) );
            $dayformat = nyt_date_format( esc_html( $one_day['day'] ) );
            echo '<option value="' . $valdate . '"';
            if ( $day_value == $valdate )
                echo ' selected="selected"';
            echo '>' . esc_html( $dayformat ) . '</option>';
        }
?>
</select>
<a href="#" class="view-other">ほかの日程をみる</a>
<a href="#" class="view-other-cancel">キャンセル</a>
</dd>
<dt>所在地</dt>
<dd><?php the_site_name(); ?> (<?php the_address_text(); ?>)</dd>
<?php
        $webSites_event = (array)$post->webSites;
        $webSites = (array) array_merge( $webSites_event, $webSites_series );
        $webSites = array_filter( $webSites, 'strlen' );
        if ( $webSites && !empty( $webSites ) ) {
?>
<dt>関連WEBサイト</dt>
<?php
            foreach ( $webSites as $webSite ) {
                echo '<dd><a href="' . esc_html( $webSite['url'] ) . '" target="_blank">' . esc_html( $webSite['name'] ) . '</a></dd>';
            }
        }
?>
</dl>
</div>
<div class="panel loading">
<div class="panel-inner" id="panel-<?php echo $post->ID; ?>">
<div class="day">
<p class="inner-ttl"><?php echo esc_html( $post->areaDetail ); ?>  <span style="font-weight:normal;">( 営業時間: <?php the_opening_text(); ?> )</span></p>
<?php
        $child_query = array(
            'numberposts' => -1,
            'post_type' => 'activity',
            'post_status' => 'publish',
            'post_parent' => $post->ID,
            'orderby' => 'rand'
        );
        $activities = get_children( $child_query, OBJECT );
        foreach ($activities as $activity ) {
            if ( 2 != $activity->phase )
                continue;
            $the_menu = array();
            $the_menu['item'] = $activity->item;
            $the_menu['text'] = $activity->text;
            $the_menu['img'] = $activity->img;
            $the_menu['genre'] = $activity->genre;
            $post = get_post( (int)$activity->actOf );
            setup_postdata( $post );
            get_template_part( 'inc/subloop', 'event' );
            wp_reset_postdata();
        }
?>
</div><!-- /.day -->
</div>
<?php
    endwhile;
    wp_reset_postdata();
?>
</div>
<?php
} elseif ( 1 < count( $series_posts->posts ) ) {
    $tabs = array();
    $webSites_event = array();
    // loop 1st
    while ( $series_posts->have_posts() ) {
        $series_posts->the_post();
        $tabs[$post->ID] = esc_html( $post->areaDetail );
        $webSites_event = array_merge( (array)$post->webSites );
    }
    rewind_posts();
?>
<div id="placeInfo" class="clearfix">
<dl class="clearfix">
<dt>イベント名</dt>
<dd><b><?php the_title(); ?></b></dd>
<dt>イベント開催日</dt>
<dd><span id="this-day"><?php the_cf_day_text(); ?></span>
<select id="date-change">
<?php
    foreach ( $schedule as $one_day ) {
        $valdate = str_replace( '-', '', esc_html( $one_day['day'] ) );
        $dayformat = nyt_date_format( esc_html( $one_day['day'] ) );
        echo '<option value="' . $valdate . '"';
        if ( $day_value == $valdate )
            echo ' selected="selected"';
        echo '>' . esc_html( $dayformat ) . '</option>';
    }
?>
</select>
<a href="#" class="view-other">ほかの日程をみる</a>
<a href="#" class="view-other-cancel">キャンセル</a>
</dd>
<dt>所在地</dt>
<dd><?php the_site_name(); ?> (<?php the_address_text(); ?>)</dd>
<?php
    $webSites = array_merge( $webSites_event, $webSites_series );
    $webSites = array_filter( $webSites, 'strlen' );
    if ( $webSites && !empty( $webSites ) ) {
?>
<dt>関連WEBサイト</dt>
<?php
        foreach ( $webSites as $webSite ) {
            echo '<dd><a href="' . esc_html( $webSite['url'] ) . '" target="_blank">' . esc_html( $webSite['name'] ) . '</a></dd>';
        }
    }
?>
</dl>
</div>
<ul class="tab-ul">
<?php
    foreach ( $tabs as $num => $area ) {
        echo '<li><a href="#panel-' . $num . '">' . $area . '</a></li>';
    }
?>
</ul>
<div class="panel loading">
<?php
    // loop 2nd
    while ( $series_posts->have_posts() ) : $series_posts->the_post();
?>
<div class="panel-inner multi-area" id="panel-<?php echo $post->ID; ?>">
<div class="day">
<p class="inner-ttl"><?php echo esc_html( $post->areaDetail ); ?>  <span style="font-weight:normal;">( 営業時間: <?php the_opening_text(); ?> )</span></p>
<?php

        $child_query = array(
            'numberposts' => -1,
            'post_type' => 'activity',
            'post_status' => 'publish',
            'post_parent' => $post->ID,
            'orderby' => 'rand'
        );
        $activities = get_children( $child_query, OBJECT );
        foreach ($activities as $activity ) {
            if ( 2 != $activity->phase )
                continue;
            $the_menu = array();
            $the_menu['item'] = $activity->item;
            $the_menu['text'] = $activity->text;
            $the_menu['img'] = $activity->img;
            $the_menu['genre'] = $activity->genre;
            $post = get_post( (int)$activity->actOf );
            setup_postdata( $post );
            get_template_part( 'inc/subloop', 'event' );
            wp_reset_postdata();
        }
?>
</div><!-- /.day -->
</div>
<?php
    endwhile;
    wp_reset_postdata();
?>
</div>
<?php
}
?>
<p class="linkMark">
  <a href="<?php echo get_post_type_archive_link( 'event' ); ?>">一覧に戻る</a>
</p>
<p>
  <a href="<?php echo home_url( '/' ); ?>" class="linkMark">トップページに戻る</a>
</p>
</div><!-- /main -->

<script>
(function($) {
  $('.panel').removeClass('loading');
  // date changer
  $('.view-other').fadeIn();
  $('.view-other').click(function(){
    $('#date-change').show();
    $(this).hide();
    $('#this-day').hide();
    $('.view-other-cancel').show();
    return false;
  });
  $('.view-other-cancel').click(function(){
    $('#date-change').hide();
    $(this).hide();
    $('.view-other').show();
    $('#this-day').show();
    return false;
  });
  $('#date-change').change(function(){
    var url = '<?php echo get_permalink() . '?date='; ?>' + $(':selected').val();
    location.href = url;
  });
  // Tab
  $('.panel-inner:first').fadeIn();
  $('.tab-ul li:first').addClass('active');
  $('.tab-ul').fadeIn();
  $('.tab-ul li').click(function() {
      $('.tab-ul li').removeClass('active');
      $(this).addClass('active');
      $('.panel-inner').hide();
      $($(this).find('a').attr('href')).fadeIn();
      window.scrollBy(0,1); // for lazyload.
      return false;
  });
})(jQuery);
</script>

<?php

get_footer();

?>