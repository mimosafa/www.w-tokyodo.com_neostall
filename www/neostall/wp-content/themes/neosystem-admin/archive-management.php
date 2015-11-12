<?php

get_header();

?>
<h3>管理情報</h3>
<div class="clearfix">
  <ul class="inline pull-right">
    <li>
      <button class="btn btn-link" type="button" data-filter-toggle>
        <i class="icon icon-filter"></i> FILTER
      </button>
    </li>
    <li>
      <button class="btn btn-link" type="button" data-mmsf-feemi-toggle>
        <i class="icon-plus"></i> 管理情報を追加
      </button>
    </li>
  </ul>
</div>
<?php
if ( have_posts() ) :
?>
<table class="table table-hover">
  <thead>
    <tr>
      <th>Post Date</th>
      <th>Type</th>
      <th>Title</th>
    </tr>
  </thead>
  <tbody>
<?php
    while ( have_posts() ) : the_post();
        $type = esc_html( $post->type );
?>
    <tr>
      <td>
        <?php the_time( 'Y/n/j (D)' ); ?>
      </td>
      <td>
        <?php echo $type; ?>
      </td>
      <td>
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </td>
    </tr>
<?php
    endwhile;
else :
?>
<p class="well">No Managements.</p>
<?php
endif;
?>
  </tbody>
</table>

<?php
previous_posts_link();
next_posts_link();

?>
<?php get_footer(); ?>