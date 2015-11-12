<?php

get_header();

// Setting Scheduled Kitchencars List
if ( get_field( 'list' ) ) :
    while ( has_sub_field( 'list' ) ) {
        $dayname = esc_html( get_sub_field( 'dayname' ) );
        $tabs[] = $dayname;
        $lists[$dayname] = get_sub_field( 'kitchencars' );
    }
endif;

// Var Today
$today = strtolower( date('l') );

?>

<div class="headerWrapper">
<div class="container">
<h2><i class="icon-food"></i> <?php the_title(); ?></h2>
<p>
  <a href="#map"><i class="icon-map-marker"></i> <?php echo get_location_str(); ?></a>&ensp;
  <i class="icon-time"></i> <?php echo get_opening_time_str(); ?>
</p>
</div>
</div>

<div class="container">

<ul class="nav nav-pills" id="nytTab">
<?php
$done = false;
foreach ( (array)$tabs as $dayname ) {
    $tab = '<li>';
    $tab .= '<a href="#' . $dayname . '"';
    if ( $dayname == $today ) {
        $tab .= ' id="visible-tab"';
        $done = true;
    }
    $tab .= ' data-toggle="pill">';
    $tab .= __( ucwords( $dayname ) );
    $tab .= "</a></li>\n";
    echo $tab;
}
?>
<li><a <?php if ( !$done ) echo 'id="visible-tab" '; ?>href="#calendar" data-toggle="pill"><i class="icon-calendar"></i> カレンダー</a></li>
</ul>

<div class="tab-content">

<div class="tab-pane" id="admin">
  Admin
</div>

<?php
foreach ( (array)$lists as $dayname => $kitchencars ) :
    $tempKey = 'weekly_' . $dayname;
?>
<div class="row tab-pane" id="<?php echo $dayname; ?>">
<?php
    if ( $kitchencars ) :
        foreach( $kitchencars as $kitchencar ) :
            // name
            $name = $kitchencar->name ? esc_html( $kitchencar->name ) : esc_html( $kitchencar->post_title );
            // Menu Item Tmplate by CF
            $ckeys = get_post_custom_keys( $kitchencar->ID );
            if ( in_array( $tempKey, $ckeys ) ) {
                $menuTemp = get_post_meta( $kitchencar->ID, $tempKey, true );
            } elseif ( in_array( 'weekly_0', $ckeys ) ) {
                $menuTemp = get_post_meta( $kitchencar->ID, 'weekly_0', true );
            } else {
                $menuTemp = get_post_meta( $kitchencar->ID, 'default_0', true );
            }
            // Menu Items
            $item_id_arr = $menuTemp['item'];
            $items = '';
            if ( !empty( $item_id_arr ) ) {
                foreach ( $item_id_arr as $item_id ) {
                    $items .= get_the_title( $item_id ) . ', ';
                }
                $items = substr( $items, 0, -2 );
            }
            // Genre
            $genre_id_arr = $menuTemp['genre'];
            $genre = '';
            if ( !empty( $genre_id_arr ) ) {
                foreach ( $genre_id_arr as $genre_id ) {
                    $genre .= get_term( $genre_id, 'genre' )->name . ', ';
                }
                $genre = substr( $genre, 0, -2 );
            }
            // Text
            $text = $menuTemp['text'];
            // img1 ... Kitchencar
            $img1_id = $menuTemp['img1'] ? $menuTemp['img1'] : get_post_thumbnail_id( $kitchencar->ID ); // featured image
            // img2 ... Menu
            $img2_id = $menuTemp['img2'];

            // If no $menuTemp
            // Repeat Fields by Advanced Custom Fields Plugin ...不要になったら削除する
            if ( empty( $items ) || empty( $genre ) || !$text || !$img2_id ) {
                $menus = get_field( 'menu', $kitchencar->ID );
                if ( $menus ) {
                    foreach ( $menus as $menu ) {
                        if ( $menu['space'] ) {
                            foreach ( $menu['space'] as $space ) {
                                if ( $post->ID != $space->ID ) {
                                    continue;
                                } else {
                                    $items = $menu['item'];
                                    $genre = $menu['genre'];
                                    $text = $menu['text'];
                                    $img2_id = $menu['img'];
                                    break 2;
                                }
                            }
                        } else {
                            if ( 2 == $menu['case'] ) { // Event 用だったら...
                                continue;               // スキップ
                            } else {
                                $items = $menu['item'];
                                $genre = $menu['genre'];
                                $text = $menu['text'];
                                $img2_id = $menu['img'];
                            }
                        }
                    }
                }
            }

            // Add ID for HoverCaption (jQuery plugin)
            $capID = 'nytDesc-' . $dayname . '-' . $kitchencar->ID;

            // include module 'inc/nyt-span4.php'
            // This module needs difined var $name, $items, $genre, $text, $img1_id, $img2_id, $capID.
            get_template_part( 'inc/nyt', 'span4' );

        endforeach;
    endif;
?>
</div>
<?php
endforeach;
?>

<div class="tab-pane" id="calendar">
<?php get_template_part( 'inc/cal', 'space' ); // include module 'mod/cal.php' ?>
</div>

</div><!-- /.tab-content -->

</div><!-- /.contaniner -->

<div class="container" style="height:400px;margin-bottom:50px;margin-top:50px;">
    <?php get_template_part( 'inc/map' ); ?>
</div>

<?php

get_footer();

?>