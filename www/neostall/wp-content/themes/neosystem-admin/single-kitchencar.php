<?php

get_header();

$vendorTtl = get_the_title( $post->post_parent );
$vendorUrl = get_permalink( $post->post_parent );
$vendor = sprintf( '<a href="%s">%s</a>', $vendorUrl, $vendorTtl );

?>

<h3>
  <i class="icon-truck"></i> <?php the_title(); ?>
  <small> / <?php echo $vendor; ?></small>
</h3>

<ul class="nav nav-tabs" id="kitchencarTabs">
<li><a href="#default" data-toggle="tab"><i class="icon-cog"></i> 基本情報</a></li>
<li><a href="#menu_items" data-toggle="tab"><i class="icon-food"></i> 提供メニューセット</a></li>
<!-- li><a href="#management" data-toggle="tab"><i class="icon-wrench"></i> 管理情報</a></li -->
<li><a href="#calendar" data-toggle="tab"><i class="icon-calendar"></i> カレンダー</a></li>
<li><a href="#media" data-toggle="tab"><i class="icon-picture"></i> 画像</a></li>
</ul>

<div class="tab-content">

<?php get_template_part( 'inc/default', 'kitchencar' ); // include module 'inc/default-kitchencar.php' ?>

<div id="menu_items" class="tab-pane">
<?php get_template_part( 'inc/menuitems' ); // include module 'inc/menuitems.php' ?>
</div>

<div id="calendar" class="tab-pane">
<?php //get_template_part( 'inc/cal', 'space' ); // include module 'inc/cal-space.php' ?>
</div>

<div id="media" class="tab-pane">
<?php get_template_part( 'inc/attachment' ); // include module 'inc/attachment.php' ?>
</div>

</div><!-- /.tab-content -->

<?php

get_footer();

?>