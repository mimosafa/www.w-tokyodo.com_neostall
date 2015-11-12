<?php
get_header();
the_post();
?>
<div id="main">
  <div id="ttl" class="<?php echo post_custom('カテゴリー'); ?>">
    <h1>
<?php
the_title();
echo ( $post->post_parent != '' ? ' | ' . get_the_title($post->post_parent ) : '' );
?>
    </h1>
  </div>
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
<?php
}
?>
  <p class="linkMark"><a href="<?php echo home_url( '/' ); ?>">トップページに戻る</a></p>
</div><!-- /main -->
<?php
get_sidebar();
get_footer();
?>