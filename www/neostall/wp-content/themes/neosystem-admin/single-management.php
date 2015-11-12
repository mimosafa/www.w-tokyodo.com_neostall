<?php
get_header();
the_post();
?>
<h3><?php the_title(); ?> | 管理情報</h3>

<div class="clearfix">
  <div class="btn-group pull-right">
    <button type="button" class="btn btn-link" data-option-table-toggle><i class="icon icon-pencil"></i> 編集</button>
  </div>
</div>
<div data-option-table-wrapper>
<?php wp_nonce_field( 'edit_management', '_neosystem_admin_nonce' ); ?>
<table class="table table-hover">
  <tbody>
<?php
$type = $post->type;
?>
    <tr>
      <th>種別</th>
      <td data-option-item>
        <span><?php echo esc_html( $post->type ); ?></span>
        <div class="hide" data-option-part>
          <select name="type">
            <option value="off">スペースおやすみ</option>
            <option value="campaign">キャンペーン</option>
          </select>
        </div>
      </td>
    </tr>
<?php
if ( $days = get_post_meta( $id, 'day' ) ) :
    $day_n = count( $days );
    $day_arr = array();
    foreach ( $days as $n => $day ) {
?>
    <tr data-view>
<?php
        if ( 0 == $n ) {
?>
      <th<?php if ( 1 < $day_n ) printf( ' rowspan="%d"', $day_n ); ?>>日付</th>
<?php
        }
?>
      <td>
        <?php echo date( 'Y/n/j D.', strtotime( $day ) ); ?>
      </td>
    </tr>
<?php
        $day_arr[] = $day;
    }
else:
?>
    <tr data-view>
      <th>日付</th>
      <td>日付が指定されていません</td>
    </tr>
<?php
endif;
?>
    <tr class="hide" data-control>
      <th>日付</th>
      <td>
<?php
if ( !empty( $day_arr ) ) {
    foreach ( $day_arr as $day_arg ) {
?>
        <label class="checkbox">
          <input type="checkbox" value="<?php echo esc_attr( $day_arg ); ?>" checked="checked" /> <?php echo date( 'Y/n/j D.', strtotime( $day_arg ) ); ?>
        </label>
<?php
    }
}
?>
        <div class="input-append hide">
          <input type="date" class="span2" id="input-add-day" value="<?php echo date( 'Y-m-d' ); ?>" />
          <button class="btn" type="button">Add</button>
        </div>
        <div class="clearfix">
          <a href="#" data-add-day><i class="icon icon-plus"></i> 日付を追加</a>
        </div>
        <input type="hidden" name="remove-day" value="" />
        <input type="hidden" name="add-day" value="" />
      </td>
    </tr>
<?php
if ( $spaces = get_post_meta( $id, 'space_id' ) ) :
    $space_n = count( $spaces );
    $space_value = implode( ',', $spaces );
    foreach ( $spaces as $n => $space_id ) {
?>
    <tr data-view>
<?php
        if ( 0 == $n ) {
?>
      <th<?php if ( 1 < $space_n ) printf( ' rowspan="%d"', $space_n ); ?>>対象スペース</th>
<?php
        }
?>
      <td data-option-item>
        <a href="<?php echo get_permalink( $space_id ); ?>"><?php echo get_the_title( $space_id ); ?></a>
        <div class="hide" data-option-part>
          <input type="hidden" class="span6" name="target-space" value="<?php echo $target_value; ?>"  />
        </div>
      </td>
    </tr>
<?php
    }
else:
?>
    <tr data-view>
      <th>対象スペース</th>
      <td>スペースは指定されていません</td>
    </tr>
<?php
endif;
?>
    <tr class="hide" data-control>
      <th>対象スペース</th>
      <td>
        <input type="hidden" class="span6" name="target-space" value="<?php echo $space_value; ?>"  />
      </td>
    </tr>
    <tr>
      <th>EXCERPT</th>
      <td data-option-item>
        <span><?php echo $post->post_excerpt; ?></span>
        <div class="hide" data-option-part>
          <textarea class="span6" name="post_excerpt"><?php echo $post->post_excerpt; ?></textarea>
        </div>
      </td>
    </tr>
    <tr>
      <th>CONTENTS</th>
      <td data-option-item>
        <span><?php the_content(); ?></span>
        <div class="hide" data-option-part>
          <textarea class="span6" name="post_content"><?php echo get_the_content(); ?></textarea>
        </div>
      </td>
    </tr>
  </tbody>
</table>
<div class="clearfix hide" data-option-table-toggles>
  <div class="btn-group pull-right">
    <button type="button" class="btn btn-link" data-option-table-cancel><i class="icon icon-remove"></i> キャンセル</button>
    <button type="submit" class="btn btn-link" data-option-table-submit><i class="icon icon-ok"></i> 変更</button>
  </div>
</div>
</div><!-- /[data-option-table-wrapper] -->

<?php

get_footer();

?>