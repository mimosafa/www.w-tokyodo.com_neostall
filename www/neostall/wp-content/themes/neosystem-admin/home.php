<?php

get_header();

?>

<ul class="nav nav-tabs" id="kitchencarTabs">
<li><a href="#news" data-toggle="tab"><i class="icon-info"></i> NEWS</a></li>
<li><a href="#media" data-toggle="tab"><i class="icon-picture"></i> 画像</a></li>
</ul>

<div class="tab-content">

<div id="news" class="tab-pane">
<?php
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
?>

<h3><?php
        if ( is_archive() ) {
            echo '<a href="';
            the_permalink();
            echo '">';
        }
        the_title();
        echo ($post->post_parent != '' ? ' | ' . get_the_title($post->post_parent) : '');
        if ( is_archive() ) {
            echo '</a>';
        }
?></h3>

<?php the_content(); ?>

<?php
    endwhile;
endif;
?>
</div><!-- /#news -->

<div id="media" class="tab-pane">
<?php /*
$arg = array(
    'post_type' => 'attachment',
    'post_mime_type' => 'image/jpeg',
    'posts_per_page' => -1,
    'post_status' => 'inherit'
);
$medias = get_posts( $arg );

if ( $medias ) :
    foreach ( $medias as $post ) {
        setup_postdata( $post );
        printf( '<h4>%s <small>ID: %d</small></h4>', get_the_title(), get_the_ID() );
        the_content();
    }
    wp_reset_postdata();
endif; */
?>
</div><!-- /#media -->

</div><!-- /.tab-content -->

<?php

get_footer();

?>