<?php

get_header();

the_post();

?>

<h3><?php the_title(); ?></h3>

<pre>
<?php
var_dump( $id );
var_dump( $post->post_parent );
?>
</pre>

<?php

get_footer();

?>