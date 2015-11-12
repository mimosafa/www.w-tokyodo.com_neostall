</div><!-- /.contaniner -->
<hr>
<div id="footer" class="container neoyataiFooter">
<?php
if ( is_singular() ) {
    if ( current_user_can( 'edit_files', get_the_ID() ) ) {
        edit_post_link( 'edit in dashboard' );
    }
    echo '<pre>';
    var_dump( get_post_custom() );
    var_dump( dirname(__FILE__) );
    echo '</pre>';
}
?>
<p>&copy; Workstore Tokyo Do 2013</p>
</div><!-- /#footer -->
<?php wp_footer(); ?>
</body>
</html>