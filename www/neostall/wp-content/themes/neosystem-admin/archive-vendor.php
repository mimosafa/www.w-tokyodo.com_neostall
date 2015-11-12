<?php

get_header();

?>
<h3><i class="icon-building"></i> Vendors</h3>

<div class="clearfix">
  <ul class="inline pull-right">
    <li>
      <button class="btn btn-link" type="button" data-mmsf-feemi-toggle>
        <i class="icon-plus"></i> 事業者を追加
      </button>
    </li>
  </ul>
</div>

<div style="display:none;" data-mmsf-feemi>

  <?php wp_nonce_field( 'add_vendor', '_neosystem_admin_nonce' ); ?>
  <legend>事業者を追加</legend>
  <div class="row">

    <div class="span3">
      <label for="serial">ネオ屋台ID</label>
      <input type="number" id="serial" name="serial" required />
    </div>

    <div class="span3">
      <label for="vendor-name">屋号</label>
      <input type="text" id="vendor-name" name="vendor-name" required />
    </div>

    <div class="span3">
      <label for="vendor-slug">アカウント <small>※ 半角英数字（ハイフン可）</small></label>
      <input type="text" id="vendor-slug" name="vendor-slug" required />
    </div>

    <div class="span3">
      <div class="btn-group pull-right" style="margin-top:25px;">
        <button type="submit" class="btn btn-small" data-mmsf-feemi-submit disabled="disabled"><i class="icon icon-ok"></i> 登録する</button>
        <button type="button" class="btn btn-small" data-mmsf-feemi-cancel><i class="icon icon-remove"></i> 登録キャンセル</button>
      </div>
    </div>

  </div>

</div><!-- /[data-mmsf-feemi-template] -->

<?php
if ( have_posts() ) :
?>
<table class="table table-hover">
  <thead>
    <tr>
      <th>Serial</th>
      <th>Vendor</th>
      <th>Organization</th>
      <th><i class="icon-truck"></i></th>
      <th><i class="icon-food"></i></th>
    </tr>
  </thead>
  <tbody>
<?php
    while ( have_posts() ) : the_post();
        $serial = esc_html( $post->serial );
        $n_kcs = (int) $post->_num_kitchencars;
        $n_items = (int) $post->_num_menuitems;
?>
    <tr>
      <td>
        <?php echo $serial; ?>
      </td>
      <td>
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </td>
      <td>
        <?php echo esc_html( $post->organization ); ?>
      </td>
      <td>
        <?php if ( $n_kcs ) echo $n_kcs; ?>
      </td>
      <td>
        <?php if ( $n_items ) echo $n_items; ?>
      </td>
    </tr>
<?php
    endwhile;
else :
?>
<p class="well">No Vendors.</p>
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