<?php

get_header();
the_post();

$children = get_children(
    array(
        'post_parent' => $post->ID,
        'numberposts' => -1,
        'post_type' => array( 'kitchencar', 'menu_item' ),
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ),
    OBJECT
);
foreach ( $children as $obj ) {
    if ( 'kitchencar' == get_post_type( $obj ) )
        $kitchencars[] = $obj;
    elseif ( 'menu_item' == get_post_type( $obj ) )
        $menuItems[] = $obj;
}
if ( $numK = count( $kitchencars ) )
    $badgeK = sprintf( ' <span id="numKitchencars" class="badge">%d</span>', $numK );
if ( $numM = count( $menuItems ) )
    $badgeM = sprintf( ' <span id="numMenuItems" class="badge">%d</span>', $numM );

?>
  <h3><i class="icon-building"></i> <?php the_title(); ?></h3>

  <ul class="nav nav-tabs" id="kitchencarTabs">
    <li><a href="#default" data-toggle="tab"><i class="icon-wrench"></i> 管理情報</a></li>
    <li><a href="#kitchencars" data-toggle="tab"><i class="icon-truck"></i> 登録キッチンカー<?php echo $badgeK; ?></a></li>
    <li><a href="#menu_items" data-toggle="tab"><i class="icon-food"></i> 提供メニュー<?php echo $badgeM; ?></a></li>
    <li><a href="#staff" data-toggle="tab"><i class="icon-user"></i> スタッフ</a></li>
    <li><a href="#media" data-toggle="tab"><i class="icon-picture"></i> 画像</a></li>
  </ul>

  <div class="tab-content">
<?php

get_template_part( 'inc/default', 'vendor' ); // include module 'inc/default-vendor.php'

get_template_part( 'inc/kitchencars', 'vendor' ); // include module 'inc/kitchencarsList.php'

get_template_part( 'inc/menuitems', 'vendor' ); // include module 'inc/menuItemsList.php'

?>

<div id="calendar" class="tab-pane">
<?php // get_template_part( 'inc/cal', 'space' ); // include module 'inc/cal-space.php' ?>
</div>

<div id="staff" class="tab-pane">
<?php //get_template_part( 'inc/default', 'space' ); // include module 'inc/default-space.php' ?>
</div>

<div id="media" class="tab-pane">
<?php get_template_part( 'inc/attachment' ); // include module 'inc/attachment.php' ?>
</div>

</div><!-- /.tab-content -->

<?php

get_footer();

?>