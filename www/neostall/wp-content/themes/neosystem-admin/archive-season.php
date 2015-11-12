<?php

get_header();

?>

<div class="container">

<h3>Event Season</h3>

<?php
if ( have_posts() ) :
?>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Series</th>
      <th>Term</th>
    </tr>
  </thead>
  <tbody>
<?php
    while ( have_posts() ) : the_post();
?>
    <tr>
      <td>
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </td>
      <td>
        <?php echo get_series_tax_obj()->name; ?>
      </td>
    </tr>
<?php
    endwhile;
else :
?>
<p class="well">No Event Series.</p>
<?php
endif;
?>
  </tbody>
</table>

<?php
previous_posts_link();
next_posts_link();
?>

</div><!-- /.contaniner -->

<?php get_footer(); ?>