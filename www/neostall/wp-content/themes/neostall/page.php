<?php get_header(); ?>
<?php the_post(); ?>
<h1><?php the_title();
echo ( $post->post_parent != '' ? ' | ' . get_the_title($post->post_parent ) : '' );
?></h1>
<?php
the_content();
if ( wp_list_pages( "title_li=&child_of=$id&echo=0" ) ) {
    echo '<ul class="listPlace">';
    wp_list_pages( 'title_li=&child_of=' . $id );
    echo '</ul>';
}
if ($parent = get_post( $post->post_parent ) ) {
?>
<p class="linkMark">
  <a href="<?php echo get_permalink( $parent->ID ); ?>">「<?php echo $parent->post_title; ?>」に戻る</a>
</p>
<?php } ?>
<?php get_footer(); ?>