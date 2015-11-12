<?php get_header(); ?>
<?php do_action( 'neostall_before_contents' ); ?>
<h1><?php echo esc_html( get_post_type_object( get_post_type() )->label ); ?></h1>
<?php while ( have_posts() ) : the_post(); ?>

<?php if ( is_post_type_archive( 'space' ) ) { ?>
<?php
$region_obj = array_pop( get_the_terms( $post, 'region' ) );
if ( isset( $region_obj->parent ) && 0 !== $region_obj->parent ) {
    $pref_obj = get_term( $region_obj->parent, 'region' );
    $pref = esc_html( $pref_obj->slug );
} else {
    $pref = esc_html( $region_obj->slug );
}
?>

<section id="<?php echo get_post_type() . '-' . get_the_ID(); ?>" <?php post_class(); ?> data-pref="<?php echo esc_attr( $pref ); ?>">
<header>
<h3><?php the_title(); ?></h3>
</header>
<div>
<?php //the_location(); ?>
</div>
</section>

<?php } else { ?>

<section id="<?php echo get_post_type() . '-' . get_the_ID(); ?>" <?php post_class(); ?>>
<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
</section>

<?php } ?>

<?php endwhile; ?>
<?php do_action( 'neostall_after_contents' ); ?>
<?php get_footer(); ?>