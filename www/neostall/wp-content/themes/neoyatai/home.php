<?php

get_header();

?>

<!-- Main hero unit for a primary marketing message or call to action -->
<div id="test-header" class="headerWrapper" style="height:350px;padding-top:120px;box-sizing:border-box;">
<div class="container">
<h1>Hello, world!</h1>
<p>This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
</div>
</div>

<div class="container">

<div class="row" style="text-align:center;">

<div class="span4" style="padding:50px 0;">
  <i class="icon-food icon-4x"></i>
  <h3>Lunch</h3>
  <p style="text-align:left;">This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
</div>

<div class="span4" style="padding:50px 0;">
  <i class="icon-calendar icon-4x"></i>
  <h3>Event</h3>
  <p style="text-align:left;">This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
</div>

<div class="span4" style="padding:50px 0;">
  <i class="icon-truck icon-4x"></i>
  <h3>Kitchencars</h3>
  <p style="text-align:left;">This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
</div>

</div><!-- /.row -->

<hr>

<div class="row">
<!-- news -->
<div class="span6">
<h2>NEWS</h2>
<?php
while (have_posts()):
    the_post();
?>
<dl class="dl-horizontal">
  <dt style="text-align:left;">
    <?php the_time('Y/n/j (D)'); ?>
  </dt>
  <dd>
    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
  </dd>
</dl>
<?php
endwhile;
?>
<p><a href="<?php echo get_post_type_archive_link( 'news' ); ?>">すべてのニュース</a></p>
</div>

<div class="span6">
  <div class="row">
    <div class="span3">
      <p class="well" style="height:220px;">something1</p>
    </div>
    <div class="span3">
      <p class="well" style="height:220px;">something2</p>
    </div>
    <div class="span3">
      <p class="well" style="height:220px;">something3</p>
    </div>
    <div class="span3">
      <p class="well" style="height:220px;">something4</p>
    </div>
  </div>
</div>

</div><!-- /.row -->
</div><!-- /.contaniner -->

<?php

get_footer();

?>