</div><!-- /#neostall-container -->
<div id="neostall-footer">
  <div class="container">
    <ul>
      <li><a href="/terms/">ご利用にあたって</a></li>
      <li><a href="/company/">会社概要</a></li>
      <li><a href="/contact/">お問い合せ</a></li>
<?php if ( is_user_logged_in() ) { ?>
      <li><a id="mmsf-theme-switch" href="#">NeoSystem</a></li>
<?php } ?>
    </ul>
    <p>Copyright &copy; 2010-<?php echo date('Y'); ?> WorkStore Tokyo Do. All Rights Reserved.</p>
  </div>
</div>
<?php wp_footer(); ?>
</body>
</html>