</div><!-- /wrap -->
<div id="foot">
  <div id="footInner">
    <p class="pagetop clear"><a href="#wrap">ページの先頭へ</a></p>
    <ul>
      <li><a href="/terms/">ご利用にあたって</a></li>
      <li><a href="/company/">会社概要</a></li>
      <li><a href="/contact/">お問い合せ</a></li>
<?php
if ( is_user_logged_in() ) {
?>
      <li><a id="mmsf-theme-switch-on" href="#">NeoSystem</a></li>
<?php
}
?>
    </ul>
    <p>
      <img src="/_shared/images/copyright.png" width="254" height="14" alt="Copyright &copy; 2010-<?php echo date('Y'); ?> WorkStore Tokyo Do. All Rights Reserved." />
    </p>
  </div><!-- /footInner -->
</div><!-- /foot -->
<?php wp_footer(); ?>
</body>
</html>