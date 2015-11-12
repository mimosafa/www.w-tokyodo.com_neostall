<?php
get_header();
the_post();
?>
<div id="main">
<div id="ttl" class="news">
<h1><?php the_title(); ?></h1>
</div>
<div id="newsBox" <?php post_class(); ?>>
<?php the_content(); ?>
<?php
$space_ids = get_post_meta( $post->ID, 'space_id' );
if ( !empty( $space_ids ) ) {
    foreach ( $space_ids as $space_id ) {
?>
<p class="linkMark">
  <a href="<?php echo get_permalink( $space_id ); ?>"><?php echo get_the_title( $space_id ); ?></a>
</p>
<?php
    }
}
?>
</div>
<p class="linkMark">
  <a href="<?php echo get_post_type_archive_link( 'news' ) ?>">一覧に戻る</a>
</p>
</div><!-- /main -->
<?php
get_sidebar();
get_footer();
?>

<?php
/*
get_header();
the_post();
?>
<div id="main">
<div id="ttl" class="<?php
$categories = get_the_category();
foreach($categories as $category) {
    echo $category->category_nicename.' ';
}
?>">
  <h1><?php the_title(); ?></h1>
</div>
<div>
<?php the_content(); ?>
</div>
<p class="linkMark">
  <a href="<?php echo get_category_link( $category->term_id ) ?>">一覧に戻る</a>
</p>
</div><!-- /main -->
<?php
get_sidebar();
get_footer();
*/
?>