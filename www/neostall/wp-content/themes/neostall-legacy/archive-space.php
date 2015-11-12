<?php
add_action( 'wp_head', function() { ?>
<style>
#neoyatai-space-attention .linkMark { float: right; padding-right: 7px; }
.listPlace { clear: right; }
#neoyatai-space-attention ul { clear: right; display:none; font-size: 12px; color: #888; padding: 16px; }
#neoyatai-space-attention ul li { padding-bottom: 10px; line-height: 1.7; list-style-type: square; }
</style>
<?php
} );
get_header(); ?>
<div id="main">
  <div id="ttl" class="village">
    <h1>所在地と出店スケジュール</h1>
  </div>
  <div id="neoyatai-space-attention" class="clearfix">
    <p class="linkMark"><a href="#">ネオ屋台村のランチ出店について</a></p>
    <ul>
      <li>交通状況などにより、営業時間が遅れることがございます。</li>
      <li>予告なしにお休みとなるネオ屋台もございます。</li>
      <li>出店ネオ屋台・メニューは変更になる可能性がございます。</li>
      <li>みなさまの注文を受けてから目の前で調理いたします。そのため通常のお弁当よりお客さまにお出しできるまで長めにお時間をいただくことになります。</li>
    </ul>
  </div>
  <ul class="listPlace">
<?php
while ( have_posts() ) :
    the_post();
    $region_str = '';
    $region_obj = get_region_tax_obj();
    if ( $pref_id = $region_obj->parent ) {
        $region_str .= esc_html( get_term( $pref_id, 'region' )->name );
    }
    $region_str .= esc_html( $region_obj->name );
?>
    <li>
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      <span style="float:right;padding-right:10px;font-size:.9em;color:#ccc;"><?php echo $region_str; ?></span>
    </li>
<?php
endwhile;
?>
  </ul>
  <p class="linkMark"><a href="<?php home_url(); ?>">トップページに戻る</a></p>
</div><!-- /#main -->
<?php

get_sidebar();

add_action( 'wp_footer', function() { ?>
<script>
  (function($) {
    var a = $('#neoyatai-space-attention a');
    var l = $('#neoyatai-space-attention ul');
    var f = false;
    a.click(function(e) {
      e.preventDefault();
      if(!f) {
        l.slideDown(400);
      } else {
        l.slideUp(400);
      }
      f = !f;
    });
  })(jQuery);
</script>
<?php
} );
get_footer();