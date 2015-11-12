<?php
/**
 * write html element
 */

// site description and keywords
function neostall_head_meta() {
    if ( is_home() && !is_paged() ) {
        echo '<meta name="description" content="首都圏各所で展開中のネオ屋台村。ランチ、イベントなどの出店情報を発信しています。">';
        echo "\n";
        echo '<meta name="keywords" content="ネオ屋台村,ネオ屋台,屋台村,屋台,キッチンカー,移動販売車,移動販売,ランチ,イベント,東京,サンケイビル,国際フォーラム">';
        echo "\n";
    }
}
add_action( 'wp_head', 'neostall_head_meta', 5 );

// Google Analyticr Code
function neostall_gae() {
    if ( is_user_logged_in() )
        return;
?>
<script type="text/javascript">
  // w-tokyodo
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-5722525-1']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  // neostall
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-44614414-1', 'w-tokyodo.com');
  ga('send', 'pageview');
</script>
<?php
}
add_action( 'wp_head', 'neostall_gae', 1000 );