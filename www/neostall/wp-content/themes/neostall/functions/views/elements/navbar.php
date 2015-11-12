<?php
/**
 * write html element
 */

// legacy navbar
function neostall_legacy_navbar() {
?>
<div class="visible-md visible-lg">
  <div id="areaSiteHeader">
    <div class="container">
      <div id="wstdLogo">
        <a href="/">Workstore Tokyo Do</a>
      </div>
      <ul>
        <li><a id="wstdCompany" href="/company/">会社案内</a></li>
        <li><a id="wstdContact" href="/contact/">お問い合わせ</a></li>
      </ul>
    </div>
  </div>
  <div id="header-legacy">
    <div class="container">
      <div id="siteid">
        <?php if ( is_home() ) { ?>
        <h1 id="neoyataiLogo">ネオ屋台村</h1>
        <?php } else { ?>
        <a id="neoyataiLogo" href="<?php echo home_url(); ?>">ネオ屋台村</a>
        <?php } ?>
      </div>
      <ul>
        <li><a id="btn-direct" href="/direct">DIRECT MANAGEMENT</a></li>
        <li><span id="btn-neoyatai">ネオ屋台村</span></li>
        <li><a id="btn-neoponte" href="/neoponte">ネオポンテ</a></li>
        <li><a id="btn-sharyobu" href="/sharyobu">車両部</a></li>
      </ul>
    </div>
  </div>
</div>
<?php
}
add_action( 'get_header', 'neostall_legacy_navbar', 0 );

/**
 * header image
 */
function neostall_header_image() {
    if ( !is_home() || is_paged() )
        return;
?>
<div id="neostall-header-image" style="background-image:url(<?php header_image(); ?>);">
  <div class="container" style="height:300px;">
  </div>
</div>
<?php
}
add_action( 'neostall_before_navbar', 'neostall_header_image' );