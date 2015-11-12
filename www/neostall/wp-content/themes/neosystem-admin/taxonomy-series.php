<?php

get_header();

global $options, $locationData;
$options = get_option( "{$taxonomy}_{$term}" );
$locationData = $options['locationData'];

?>
  <h3><?php single_term_title(); ?></h3>
  <ul class="nav nav-tabs" id="eventTabs">
    <li><a href="#schedule" data-toggle="tab"><i class="icon-calendar"></i> スケジュール</a></li>
    <li><a href="#management" data-toggle="tab"><i class="icon-wrench"></i> 管理情報</a></li>
  </ul>
  <div class="tab-content">
    <div id="schedule" class="tab-pane">
      <div class="clearfix">
        <ul class="inline pull-right">
          <li>
            <button class="btn btn-link" type="button" data-edit-toggle="add">
              <i class="icon-plus"></i> イベントを追加
            </button>
          </li>
        </ul>
      </div>

      <div class="row hide" data-edit-field>
        <?php wp_nonce_field( 'add_new_event', '_neosystem_admin_nonce' ); ?>
        <fieldset class="span3">
          <legend>出店エリア情報</legend>
<?php
if ( 1 < count( $locationData ) ) {
?>
          <label for="multiarea" class="checkbox">
            <input type="checkbox" id="multiarea" name="multiarea" value="1" /> 複数エリアでの出店
          </label>
          <hr>
<?php
}
$area_fieldset_items = '';
foreach ( $locationData as $n => $loc ) {
    $loc_id = "{$term}-location-{$n}";
    $area_fieldset_items .= '<div id="field-' . $loc_id . '" class="span3 hide" data-area-item>';
    $area_fieldset_items .= '<input type="hidden" data-area-name="region" value="' . esc_attr( $loc['region'] ) . '" />';
    $area_fieldset_items .= '<input type="hidden" data-area-name="address" value="' . esc_attr( $loc['address'] ) . '" />';
    $area_fieldset_items .= '<input type="hidden" data-area-name="latlng" value="' . esc_attr( $loc['latlng'] ) . '" />';
    $area_fieldset_items .= '<input type="text" data-area-name="site" value="' . esc_attr( $loc['site'] ) . '" readonly />';
    $area_fieldset_items .= '<input type="text" data-area-name="areaDetail" value="' . esc_attr( $loc['areaDetail'] ) . '" readonly />';
    $area_fieldset_items .= '<label for="input-starting-' . $n . '">営業開始時間</label>';
    $area_fieldset_items .= '<div class="input-append">';
    $area_fieldset_items .= '<input type="datetime-local" id="input-starting-' . $n . '" data-area-name="starting" />';
    $area_fieldset_items .= '<span class="add-on"><label for="input-starting-pending-' . $n . '" class="checkbox">';
    $area_fieldset_items .= '<input type="checkbox" id="input-starting-pending-' . $n . '" data-area-name="startingPending" value="1" /> 予定';
    $area_fieldset_items .= '</label></span></div>';
    $area_fieldset_items .= '<label for="input-ending-' . $n . '">営業終了時間</label>';
    $area_fieldset_items .= '<div class="input-append">';
    $area_fieldset_items .= '<input type="datetime-local" id="input-ending-' . $n . '" data-area-name="ending" />';
    $area_fieldset_items .= '<span class="add-on"><label for="input-ending-pending-' . $n . '" class="checkbox">';
    $area_fieldset_items .= '<input type="checkbox" id="input-ending-pending-' . $n . '" data-area-name="endingPending" value="1" /> 予定';
    $area_fieldset_items .= '</label></span></div><hr></div>';

    $areas_label = esc_html( $loc['site'] );
    if ( !empty( $loc['areaDetail'] ) )
        $areas_label .= ' | ' . esc_html( $loc['areaDetail'] );
?>
          <label class="radio">
            <input type="radio" name="areas" data-area="<?php echo  $loc_id; ?>" /> <?php echo $areas_label; ?>
          </label>
<?php
}
?>
          <legend>イベント公開情報</legend>
          <label for="event-day">イベント開催日</label>
          <input type="date" class="span2" id="event-day" name="event-day" data-required />
          <label for="post_title">イベント名</label>
          <input type="text" class="span3" id="post_title" name="post_title" data-required />
        </fieldset>
        <fieldset id="area-fieldset" class="span9">
          <legend>出店エリア/営業時間</legend>
          <div class="row" data-area-field>
<?php
echo $area_fieldset_items;
?>
          </div>
        </fieldset>
        <div class="span12">
          <div class="btn-group pull-right">
            <button type="submit" class="btn btn-small" data-edit-submit disabled="disabled"><i class="icon icon-ok"></i> 登録</button>
            <button type="button" class="btn btn-small" data-edit-cancel><i class="icon icon-remove"></i> キャンセル</button>
          </div>
        </div>
      </div><!-- /[data-edit-field] -->

<?php
if ( have_posts() ) :
?>
      <table class="table table-hover">
        <thead>
          <tr>
            <th>開催日</th>
            <th>イベント名</th>
            <th>地域</th>
          </tr>
        </thead>
        <tbody>
<?php
    while ( have_posts() ) : the_post();
        $day = date( 'n/j D.', strtotime( esc_html( $post->day ) ) );
        // Region
        $region = esc_html( get_region_tax_obj()->name );
?>
          <tr>
            <td>
              <?php echo $day; ?>
            </td>
            <td>
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </td>
            <td>
              <?php echo $region; ?>
            </td>
          </tr>
<?php
    endwhile;
?>
        </tbody>
      </table>
<?php
else :
?>
      <p class="well">No Events.</p>
<?php
endif;

previous_posts_link();
next_posts_link();

?>
    </div><!-- /#schedule -->
<?php

get_template_part( 'inc/management', 'series' );

?>
  </div><!-- /.tab-content -->
<?php

get_footer();

?>