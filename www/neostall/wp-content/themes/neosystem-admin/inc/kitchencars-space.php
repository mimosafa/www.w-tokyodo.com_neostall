<div id="kitchencars" class="tab-pane">

<?php
/**
 * ACF ----------------------------------------------
 *
 *                  list - (Num sub_field)
 *                 _list - acf_meta 'field_41'
 *      list_[i]_dayname -
 *     _list_[i]_dayname - acf_meta 'field_42'
 *  list_[i]_kitchencars -
 * _list_[i]_kitchencars - acf_meta 'field_43'
 *         list_[i]_text -
 *        _list_[i]_text - acf_meta 'field_44'
 *
 * ---------------------------------------------- ACF
 **/

$list_n = get_post_meta( $post->ID, 'list', true );

?>

  <div class="row">

    <div class="span10 pull-right">

      <div class="row" data-acf-lists="<?php echo $list_n ?>">

<?php

wp_nonce_field( 'edit_acf_list_kitchencars', '_neosystem_admin_nonce' );

if ( get_field( 'list' ) ) :

    $i = 0;
    $weeks = array();
    while ( has_sub_field( 'list' ) ) :

        $metakey_str = 'list_' . $i . '_kitchencars';

        $acf_lists_array = get_post_meta( $post->ID, $metakey_str, true );
        $acf_lists_json = json_encode( $acf_lists_array );

        $dayname = get_sub_field( 'dayname' );

        $attr = 'data-acf-list="' . $i . '" ';
        $attr .= "data-acf-kitchencars='" . $acf_lists_json . "'";
/*
        $weeks[$i] = array(
            'dayname' => $dayname,
            'metakey_str' => $metakey_str
        );
*/
?>

        <div class="span2" <?php echo $attr; ?>>

          <h4><?php echo __( ucwords( $dayname ) ); ?></h4>

<?php

        if ( $lists = get_sub_field( 'kitchencars' ) ) :
            foreach ( $lists as $post ) {
                setup_postdata( $post );

                $attr = '';
                $attr .= 'data-acf-kitchencar="' . $id . '"';

?>

          <div <?php echo $attr; ?>>
            <p><a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a></p>
          </div>

<?php

            }
            wp_reset_postdata();
        else :

?>

          <div <?php echo $attr; ?>>
            <p class="muted">No Kitchencars</p>
          </div>

<?php

        endif;

?>

          <a href="#" class="muted" data-edit-toggle><small><i class="icon icon-pencil"></i> リストを編集</small></a>
        </div>

<?php

        $i++;

    endwhile;

endif;

?>

      </div>

    </div><!-- /.span10 -->

    <div class="span2 pull-left">
      <ul class="nav nav-list" style="padding-top:5px;">
        <li class="nav-header">Global Change</li>
        <li><a href="#"><i class="icon icon-pencil"></i> 出店曜日を編集</a></li>
      </ul>
    </div>

  </div><!-- /.row -->

</div><!-- /#kitchencars -->