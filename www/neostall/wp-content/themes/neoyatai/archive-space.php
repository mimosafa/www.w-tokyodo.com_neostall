<?php

get_header();

?>

<div class="headerWrapper">
<div class="container">
<h1>ネオ屋台村のランチ</h1>
<p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique. <a href="#" class="btn btn-small">地図で見る</a></p>
</div>
</div>

<div class="container">

<ul class="nav nav-pills">
<li class="active"><a href="#">すべての地域</a></li>
<li><a href="#">東京都</a></li>
<li><a href="#">埼玉県</a></li>
<li><a href="#">神奈川県</a></li>
</ul>

<div class="row">

<?php
if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        $pref_obj = null;
        $region_obj = get_region_tax_obj();
        if ( 0 != $region_obj->parent ) {
            $pref_obj = get_term( $region_obj->parent, 'region' );
        }
?>

<div class="span4 archiveCell">
  <h4>
    <a href="<?php the_permalink(); ?>"><?php
        the_title();
    ?></a>
  </h4>
  <p><i class="icon-map-marker"></i> <?php echo esc_html( $pref_obj->name ) . esc_html( $region_obj->name ); ?></p>
</div>

<?php
    endwhile;
endif;
?>

</div><!-- /.row -->

</div><!-- /.contaniner -->

<?php get_footer(); ?>